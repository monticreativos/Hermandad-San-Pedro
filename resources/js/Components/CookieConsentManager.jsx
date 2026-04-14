import { Link, usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';

const STORAGE_KEY = 'hsanpedro-cookie-consent';

function defaultPreferences() {
    return {
        necessary: true,
        analytics: false,
        marketing: false,
        personalization: false,
    };
}

function CookieToggle({ checked, disabled = false, onChange }) {
    return (
        <button
            type="button"
            role="switch"
            aria-checked={checked}
            disabled={disabled}
            onClick={() => {
                if (!disabled) onChange(!checked);
            }}
            className={`relative inline-flex h-6 w-11 items-center rounded-full transition ${
                checked ? 'bg-[#4b1f6f]' : 'bg-zinc-300'
            } ${disabled ? 'opacity-70' : 'cursor-pointer'}`}
        >
            <span
                className={`inline-block h-5 w-5 transform rounded-full bg-white shadow transition ${
                    checked ? 'translate-x-5' : 'translate-x-1'
                }`}
            />
        </button>
    );
}

export default function CookieConsentManager() {
    const { locale = 'es', cookieConsent } = usePage().props;
    const version = String(cookieConsent?.version ?? '2026-04');
    const [isOpen, setIsOpen] = useState(false);
    const [isVisible, setIsVisible] = useState(false);
    const [preferences, setPreferences] = useState(defaultPreferences);

    const legalUrls = useMemo(
        () => ({
            legal: route('brotherhood.show', { locale, key: 'aviso-legal' }),
            privacy: route('brotherhood.show', { locale, key: 'politica-privacidad' }),
            cookies: route('brotherhood.show', { locale, key: 'politica-cookies' }),
        }),
        [locale],
    );

    useEffect(() => {
        try {
            const raw = window.localStorage.getItem(STORAGE_KEY);
            if (!raw) {
                setIsVisible(true);
                return;
            }
            const parsed = JSON.parse(raw);
            if (parsed?.version !== version) {
                setIsVisible(true);
                return;
            }
            if (parsed?.preferences) {
                setPreferences({
                    necessary: true,
                    analytics: !!parsed.preferences.analytics,
                    marketing: !!parsed.preferences.marketing,
                    personalization: !!parsed.preferences.personalization,
                });
            }
        } catch {
            setIsVisible(true);
        }
    }, [version]);

    useEffect(() => {
        function openCookieSettings() {
            setIsOpen(true);
        }

        window.addEventListener('open-cookie-settings', openCookieSettings);
        return () => window.removeEventListener('open-cookie-settings', openCookieSettings);
    }, []);

    async function persistConsent(nextPreferences, action, source = 'banner') {
        const existing = window.localStorage.getItem(STORAGE_KEY);
        let existingParsed = null;
        if (existing) {
            try {
                existingParsed = JSON.parse(existing);
            } catch {
                existingParsed = null;
            }
        }
        const consentUuid =
            existingParsed?.consent_uuid ??
            (crypto?.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`);
        const payload = {
            consent_uuid: consentUuid,
            consent_version: version,
            action,
            source,
            preferences: {
                necessary: true,
                analytics: !!nextPreferences.analytics,
                marketing: !!nextPreferences.marketing,
                personalization: !!nextPreferences.personalization,
            },
            page_url: window.location.href,
        };

        window.localStorage.setItem(
            STORAGE_KEY,
            JSON.stringify({
                version,
                consent_uuid: consentUuid,
                action,
                preferences: payload.preferences,
                updated_at: new Date().toISOString(),
            }),
        );

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const endpoint =
            typeof route === 'function'
                ? route('cookie-consent.store', { locale })
                : `/${locale}/cookie-consent`;

        try {
            await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': token ?? '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });
        } catch {
            // La preferencia queda en localStorage aunque falle el log remoto.
        }
    }

    async function acceptAll() {
        const next = {
            necessary: true,
            analytics: true,
            marketing: true,
            personalization: true,
        };
        setPreferences(next);
        await persistConsent(next, 'accept_all');
        setIsVisible(false);
        setIsOpen(false);
    }

    async function rejectOptional() {
        const next = defaultPreferences();
        setPreferences(next);
        await persistConsent(next, 'reject_all');
        setIsVisible(false);
        setIsOpen(false);
    }

    async function saveCustom() {
        await persistConsent(preferences, 'save_preferences', 'modal');
        setIsVisible(false);
        setIsOpen(false);
    }

    return (
        <>
            {isVisible ? (
                <>
                    <div className="fixed inset-0 z-[59] bg-zinc-900/45 backdrop-blur-[1px]" />
                    <div className="fixed inset-x-0 bottom-0 z-[60] border-t border-zinc-200 bg-white px-4 py-4 shadow-[0_-12px_30px_rgba(0,0,0,0.16)] sm:px-6">
                        <div className="mx-auto flex w-full max-w-[90rem] flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div className="max-w-4xl">
                                <p className="text-lg font-bold text-[#1f2b43]">
                                    {locale === 'en' ? 'Cookie usage' : 'Uso de cookies'}
                                </p>
                                <p className="mt-1.5 text-xs leading-relaxed text-zinc-600 sm:text-sm">
                                    {locale === 'en'
                                        ? 'We use first-party and third-party cookies to ensure website functionality and, with your consent, for analytics, personalization and marketing purposes.'
                                        : 'Utilizamos cookies propias y de terceros para garantizar la funcionalidad de la web y, con su consentimiento, para fines analiticos, de personalizacion y marketing.'}{' '}
                                    <Link href={legalUrls.cookies} className="font-semibold text-[#4b1f6f] underline">
                                        {locale === 'en' ? 'View cookies policy' : 'Ver politica de cookies'}
                                    </Link>
                                </p>
                            </div>

                            <div className="flex flex-wrap items-center gap-2 sm:gap-3">
                                <button
                                    type="button"
                                    onClick={acceptAll}
                                    className="rounded-md bg-gradient-to-b from-[#6a2ca1] to-[#4b1f6f] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:brightness-110 sm:px-5"
                                >
                                    {locale === 'en' ? 'Accept all' : 'Aceptar todas'}
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setIsOpen(true)}
                                    className="rounded-md border border-[#4b1f6f]/40 bg-white px-4 py-2.5 text-xs font-semibold text-[#4b1f6f] transition hover:bg-[#4b1f6f]/5 sm:px-5"
                                >
                                    {locale === 'en' ? 'Configure' : 'Configurar'}
                                </button>
                                <button
                                    type="button"
                                    onClick={rejectOptional}
                                    className="rounded-md border border-zinc-300 bg-white px-4 py-2.5 text-xs font-semibold text-zinc-700 transition hover:bg-zinc-50 sm:px-5"
                                >
                                    {locale === 'en' ? 'Reject' : 'Rechazar'}
                                </button>
                            </div>
                        </div>
                    </div>
                </>
            ) : null}

            {isOpen ? (
                <div className="fixed inset-0 z-[70] flex items-center justify-center bg-black/50 p-4">
                    <div className="w-full max-w-2xl rounded-3xl border border-zinc-200 bg-white p-5 shadow-2xl sm:p-6">
                        <div className="mb-4 flex items-center gap-2">
                            <button
                                type="button"
                                onClick={() => setIsOpen(false)}
                                className="inline-flex h-8 w-8 items-center justify-center rounded-full text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700"
                                aria-label={locale === 'en' ? 'Back' : 'Volver'}
                            >
                                ←
                            </button>
                            <h3 className="text-lg font-semibold text-[#1f2b43]">
                                {locale === 'en' ? 'Cookie preferences' : 'Preferencias de cookies'}
                            </h3>
                        </div>

                        <div className="max-h-[58vh] space-y-3 overflow-y-auto pr-1">
                            <div className="rounded-lg bg-zinc-50 p-3.5">
                                <div className="flex items-center justify-between gap-4">
                                    <p className="text-sm font-semibold text-zinc-800">
                                        {locale === 'en'
                                            ? 'Strictly necessary cookies'
                                            : 'Cookies estrictamente necesarias'}
                                    </p>
                                    <span className="text-xs font-semibold text-zinc-500">
                                        {locale === 'en' ? 'Always active' : 'Activas siempre'}
                                    </span>
                                </div>
                            </div>

                            <div className="rounded-lg bg-zinc-50 p-3.5">
                                <div className="flex items-center justify-between gap-4">
                                    <p className="text-sm font-semibold text-zinc-800">
                                        {locale === 'en' ? 'Functionality cookies' : 'Cookies de funcionalidad'}
                                    </p>
                                    <CookieToggle
                                        checked={preferences.personalization}
                                        onChange={(value) =>
                                            setPreferences((prev) => ({
                                                ...prev,
                                                personalization: value,
                                            }))
                                        }
                                    />
                                </div>
                                <p className="mt-2 text-xs leading-relaxed text-zinc-600">
                                    {locale === 'en'
                                        ? 'These cookies allow the website to provide improved functionality and personalization. They may be set by us or by third-party providers whose services we have added to our pages.'
                                        : 'Estas cookies permiten que el sitio ofrezca una mejor funcionalidad y personalizacion. Pueden ser establecidas por nosotros o por terceros cuyos servicios hemos agregado a nuestras paginas.'}
                                </p>
                            </div>

                            <div className="rounded-lg bg-zinc-50 p-3.5">
                                <div className="flex items-center justify-between gap-4">
                                    <p className="text-sm font-semibold text-zinc-800">
                                        {locale === 'en'
                                            ? 'Performance and analytics cookies'
                                            : 'Cookies de rendimiento o analiticas'}
                                    </p>
                                    <CookieToggle
                                        checked={preferences.analytics}
                                        onChange={(value) =>
                                            setPreferences((prev) => ({
                                                ...prev,
                                                analytics: value,
                                            }))
                                        }
                                    />
                                </div>
                                <p className="mt-2 text-xs leading-relaxed text-zinc-600">
                                    {locale === 'en'
                                        ? 'These cookies help us count visits and traffic sources so we can measure and improve the performance of our site. They help us know which pages are the most and least popular and see how visitors move around the site.'
                                        : 'Estas cookies nos permiten contar las visitas y fuentes de trafico para medir y mejorar el rendimiento de nuestro sitio. Nos ayudan a saber que paginas son las mas y menos populares y como se mueven los visitantes por la web.'}
                                </p>
                            </div>

                            <div className="rounded-lg bg-zinc-50 p-3.5">
                                <div className="flex items-center justify-between gap-4">
                                    <p className="text-sm font-semibold text-zinc-800">
                                        {locale === 'en' ? 'Targeting or advertising cookies' : 'Cookies dirigidas o publicidad'}
                                    </p>
                                    <CookieToggle
                                        checked={preferences.marketing}
                                        onChange={(value) =>
                                            setPreferences((prev) => ({
                                                ...prev,
                                                marketing: value,
                                            }))
                                        }
                                    />
                                </div>
                                <p className="mt-2 text-xs leading-relaxed text-zinc-600">
                                    {locale === 'en'
                                        ? 'These cookies may be set through our site by advertising partners. They may be used to build a profile of your interests and show you relevant ads on other sites.'
                                        : 'Estas cookies pueden ser establecidas en nuestro sitio por socios publicitarios. Pueden utilizarse para crear un perfil de sus intereses y mostrarle anuncios relevantes en otros sitios.'}
                                </p>
                            </div>
                        </div>

                        <p className="mt-4 text-xs text-zinc-500">
                            <Link href={legalUrls.legal} className="underline">
                                {locale === 'en' ? 'Legal notice' : 'Aviso legal'}
                            </Link>{' '}
                            ·{' '}
                            <Link href={legalUrls.privacy} className="underline">
                                {locale === 'en' ? 'Privacy policy' : 'Politica de privacidad'}
                            </Link>{' '}
                            ·{' '}
                            <Link href={legalUrls.cookies} className="underline">
                                {locale === 'en' ? 'Cookies policy' : 'Politica de cookies'}
                            </Link>
                        </p>

                        <div className="mt-5 flex flex-wrap items-center justify-between gap-2">
                            <button
                                type="button"
                                onClick={saveCustom}
                                className="rounded-md bg-gradient-to-b from-[#6a2ca1] to-[#4b1f6f] px-5 py-2.5 text-xs font-semibold text-white hover:brightness-110"
                            >
                                {locale === 'en' ? 'Save my settings' : 'Guardar mi configuracion'}
                            </button>
                            <button
                                type="button"
                                onClick={rejectOptional}
                                className="rounded-md border border-zinc-300 px-4 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-zinc-50"
                            >
                                {locale === 'en' ? 'Reject all optional' : 'Rechazar opcionales'}
                            </button>
                        </div>
                    </div>
                </div>
            ) : null}
        </>
    );
}
