import { Link, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { ArrowRight, ChevronLeft, ChevronRight, X } from 'lucide-react';
import { useMemo, useState } from 'react';
import Swal from 'sweetalert2';
import Modal from './Modal';

const mondayIndex = (date) => (date.getDay() + 6) % 7;

const startOfWeekMonday = (d) => {
    const x = new Date(d);
    x.setHours(0, 0, 0, 0);
    x.setDate(x.getDate() - mondayIndex(x));
    return x;
};

const addDays = (d, n) => {
    const x = new Date(d);
    x.setDate(x.getDate() + n);
    return x;
};

const sameDay = (a, b) =>
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate();

function hexToRgba(hex, alpha) {
    const h = (hex || '').replace('#', '');
    if (h.length !== 6 && h.length !== 3) {
        return `rgba(161,161,170,${alpha})`;
    }
    const full =
        h.length === 3
            ? h
                  .split('')
                  .map((c) => c + c)
                  .join('')
            : h;
    const n = parseInt(full, 16);
    const r = (n >> 16) & 255;
    const g = (n >> 8) & 255;
    const b = n & 255;
    return `rgba(${r},${g},${b},${alpha})`;
}

/** Categoría enviada desde el back (Filament: Tipos de acto). */
function normalizeCategoryPayload(raw) {
    if (raw && typeof raw.color === 'string' && raw.color.startsWith('#')) {
        return {
            slug: raw.slug ?? 'otros',
            label: raw.label ?? '—',
            color: raw.color,
        };
    }
    return { slug: 'otros', label: '—', color: '#a1a1aa' };
}

function parseEventDate(iso) {
    if (!iso) return null;
    const d = new Date(iso);
    return Number.isNaN(d.getTime()) ? null : d;
}

function openMapPrompt(location, locale) {
    if (!location) return Promise.resolve();

    return Swal.fire({
        title:
            locale === 'es'
                ? 'Abrir ubicación'
                : 'Open location',
        text:
            locale === 'es'
                ? '¿Quieres abrir esta dirección en Google Maps?'
                : 'Do you want to open this address in Google Maps?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: locale === 'es' ? 'Sí, abrir' : 'Yes, open',
        cancelButtonText: locale === 'es' ? 'Cancelar' : 'Cancel',
        confirmButtonColor: '#4b1f6f',
    }).then((result) => {
        if (result.isConfirmed) {
            const query = encodeURIComponent(location);
            window.open(
                `https://www.google.com/maps/search/?api=1&query=${query}`,
                '_blank',
                'noopener,noreferrer',
            );
        }
    });
}

export default function AgendaCalendar({
    events = [],
    showFullAgendaLink = false,
    variant = 'card',
    showHeading = true,
}) {
    const { locale = 'es', translations = {} } = usePage().props;
    const tr = (key, fallback) => translations[key] ?? fallback;
    const [view, setView] = useState('month');
    const [cursor, setCursor] = useState(() => new Date());
    const [sheet, setSheet] = useState(null);
    const [lightboxSrc, setLightboxSrc] = useState(null);

    const intlLocale = locale === 'es' ? 'es-ES' : 'en-GB';

    const parsedEvents = useMemo(
        () =>
            events
                .map((e) => ({
                    ...e,
                    category: normalizeCategoryPayload(e.category),
                    gallery: Array.isArray(e.gallery) ? e.gallery : [],
                    at: parseEventDate(e.date_time),
                }))
                .filter((e) => e.at),
        [events],
    );

    const weekdayLabels = useMemo(() => {
        const base = new Date(2024, 0, 1);
        while (base.getDay() !== 1) {
            base.setDate(base.getDate() + 1);
        }
        const fmt = new Intl.DateTimeFormat(intlLocale, { weekday: 'short' });
        return Array.from({ length: 7 }, (_, i) => fmt.format(addDays(base, i)));
    }, [intlLocale]);

    const eventsForDay = (day) =>
        parsedEvents.filter((e) => e.at && sameDay(e.at, day));

    const monthTitle = new Intl.DateTimeFormat(intlLocale, {
        month: 'long',
        year: 'numeric',
    }).format(cursor);

    const monthCells = useMemo(() => {
        const y = cursor.getFullYear();
        const m = cursor.getMonth();
        const first = new Date(y, m, 1);
        const daysInMonth = new Date(y, m + 1, 0).getDate();
        const pad = mondayIndex(first);
        const cells = [];
        for (let i = 0; i < pad; i += 1) {
            cells.push({ date: null, key: `head-${i}` });
        }
        for (let d = 1; d <= daysInMonth; d += 1) {
            cells.push({ date: new Date(y, m, d), key: `day-${y}-${m}-${d}` });
        }
        while (cells.length % 7 !== 0) {
            cells.push({ date: null, key: `tail-${cells.length}` });
        }
        return cells;
    }, [cursor]);

    const weekStart = startOfWeekMonday(cursor);
    const weekDays = useMemo(
        () => Array.from({ length: 7 }, (_, i) => addDays(weekStart, i)),
        [weekStart],
    );

    const weekRangeLabel = `${new Intl.DateTimeFormat(intlLocale, {
        day: 'numeric',
        month: 'short',
    }).format(weekStart)} – ${new Intl.DateTimeFormat(intlLocale, {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    }).format(addDays(weekStart, 6))}`;

    const timeFmt = new Intl.DateTimeFormat(intlLocale, {
        hour: '2-digit',
        minute: '2-digit',
    });

    const dateTimeLongFmt = useMemo(
        () =>
            new Intl.DateTimeFormat(intlLocale, {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            }),
        [intlLocale],
    );

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const goPrev = () => {
        if (view === 'month') {
            setCursor(new Date(cursor.getFullYear(), cursor.getMonth() - 1, 1));
        } else {
            setCursor(addDays(weekStart, -7));
        }
    };

    const goNext = () => {
        if (view === 'month') {
            setCursor(new Date(cursor.getFullYear(), cursor.getMonth() + 1, 1));
        } else {
            setCursor(addDays(weekStart, 7));
        }
    };

    const goToday = () => {
        setCursor(new Date());
    };

    const openDetail = (event, returnTo = null) => {
        setLightboxSrc(null);
        setSheet({ kind: 'detail', event, returnTo });
    };

    const closeSheet = () => {
        setLightboxSrc(null);
        setSheet(null);
    };

    const dayTitle = (day) =>
        new Intl.DateTimeFormat(intlLocale, {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
        }).format(day);

    const outerClass =
        variant === 'card'
            ? 'flex h-full w-full flex-col rounded-2xl border border-zinc-200/90 bg-white p-6 shadow-sm sm:p-7 lg:p-8'
            : 'flex w-full flex-col';

    const HeadingTag = variant === 'plain' ? 'h1' : 'h2';
    const headingClass =
        variant === 'plain'
            ? 'shrink-0 text-3xl font-bold uppercase tracking-[0.12em] text-[#4b1f6f] sm:text-4xl'
            : 'shrink-0 text-lg font-bold uppercase tracking-[0.12em] text-[#4b1f6f] sm:text-xl';

    return (
        <div className={outerClass}>
            {showHeading ? (
                <HeadingTag className={headingClass}>
                    {tr('home.agenda.title', 'Agenda')}
                </HeadingTag>
            ) : null}

            <div
                className={`flex flex-wrap items-center gap-2 ${showHeading ? 'mt-6' : 'mt-0'}`}
            >
                <div className="inline-flex gap-1 rounded-full bg-zinc-50/90 p-0.5">
                    <button
                        type="button"
                        onClick={() => setView('month')}
                        className={`rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-wide transition sm:px-4 sm:text-sm ${
                            view === 'month'
                                ? 'bg-[#c9a227] text-white shadow-sm'
                                : 'border border-[#4b1f6f]/35 bg-white text-[#4b1f6f] hover:border-[#4b1f6f]/55'
                        }`}
                    >
                        {tr('home.agenda.month', 'Mes')}
                    </button>
                    <button
                        type="button"
                        onClick={() => setView('week')}
                        className={`rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-wide transition sm:px-4 sm:text-sm ${
                            view === 'week'
                                ? 'bg-[#c9a227] text-white shadow-sm'
                                : 'border border-[#4b1f6f]/35 bg-white text-[#4b1f6f] hover:border-[#4b1f6f]/55'
                        }`}
                    >
                        {tr('home.agenda.week', 'Semana')}
                    </button>
                </div>
                <button
                    type="button"
                    onClick={goToday}
                    className="ml-auto text-xs font-semibold text-[#4b1f6f] underline-offset-4 hover:text-[#2f1245] hover:underline sm:text-sm"
                >
                    {tr('home.agenda.today', 'Hoy')}
                </button>
            </div>

            <div className="mt-4 flex shrink-0 items-center justify-between gap-2 border-b border-zinc-200 pb-3">
                <button
                    type="button"
                    onClick={goPrev}
                    className="rounded-full p-2 text-zinc-500 transition hover:bg-[#4b1f6f]/10 hover:text-[#4b1f6f]"
                    aria-label={tr('home.agenda.prev', 'Anterior')}
                >
                    <ChevronLeft className="h-5 w-5" />
                </button>
                <p className="min-w-0 flex-1 text-center text-sm font-semibold capitalize text-zinc-900 sm:text-base">
                    {view === 'month' ? monthTitle : weekRangeLabel}
                </p>
                <button
                    type="button"
                    onClick={goNext}
                    className="rounded-full p-2 text-zinc-500 transition hover:bg-[#4b1f6f]/10 hover:text-[#4b1f6f]"
                    aria-label={tr('home.agenda.next', 'Siguiente')}
                >
                    <ChevronRight className="h-5 w-5" />
                </button>
            </div>

            <div className="mt-3 min-h-0 flex-1">
                <AnimatePresence mode="wait">
                    {view === 'month' ? (
                        <motion.div
                            key="month"
                            initial={{ opacity: 0, y: 6 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -6 }}
                            transition={{ duration: 0.2 }}
                            className="mt-3"
                        >
                            <div className="grid grid-cols-7 gap-0.5 text-center text-[10px] font-semibold uppercase tracking-wide text-[#4b1f6f]/75 sm:text-xs">
                                {weekdayLabels.map((w) => (
                                    <div key={w} className="py-1">
                                        {w}
                                    </div>
                                ))}
                            </div>
                            <div className="mt-1 grid grid-cols-7 gap-0.5 sm:gap-1">
                                {monthCells.map(({ date, key }) => {
                                    if (!date) {
                                        return (
                                            <div
                                                key={key}
                                                className="aspect-square min-h-[2.25rem] sm:min-h-[2.5rem]"
                                            />
                                        );
                                    }
                                    const isToday = sameDay(date, today);
                                    const evs = eventsForDay(date);
                                    const visible = evs.slice(0, 4);
                                    const overflow = evs.length - visible.length;

                                    return (
                                        <div
                                            key={key}
                                            className={`flex aspect-square min-h-[2.25rem] flex-col items-center rounded-lg border p-0.5 sm:min-h-[2.5rem] sm:p-1 ${
                                                isToday
                                                    ? 'border-[#c9a227] bg-amber-50/90 shadow-[inset_0_0_0_1px_rgba(201,162,39,0.25)]'
                                                    : 'border-transparent hover:bg-zinc-100'
                                            }`}
                                        >
                                            <span
                                                className={`text-[11px] font-semibold sm:text-xs ${
                                                    isToday
                                                        ? 'text-[#8a6d12]'
                                                        : 'text-zinc-800'
                                                }`}
                                            >
                                                {date.getDate()}
                                            </span>
                                            <div className="mt-auto flex max-w-full flex-wrap justify-center gap-0.5">
                                                {visible.map((e) => (
                                                    <button
                                                        key={e.id}
                                                        type="button"
                                                        title={e.name}
                                                        onClick={() =>
                                                            openDetail(e, null)
                                                        }
                                                        className="h-2 w-2 shrink-0 rounded-full ring-offset-1 ring-offset-white transition hover:scale-125 sm:h-2.5 sm:w-2.5"
                                                        style={{
                                                            backgroundColor:
                                                                e.category
                                                                    .color,
                                                            boxShadow: `0 0 0 1px ${hexToRgba(e.category.color, 0.42)}`,
                                                        }}
                                                    />
                                                ))}
                                                {overflow > 0 ? (
                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            setSheet({
                                                                kind: 'day',
                                                                day: date,
                                                                events: evs,
                                                                dayTitle:
                                                                    dayTitle(
                                                                        date,
                                                                    ),
                                                            })
                                                        }
                                                        className="min-h-[18px] min-w-[18px] rounded px-0.5 text-[8px] font-bold text-[#4b1f6f] hover:underline sm:text-[9px]"
                                                    >
                                                        +{overflow}
                                                    </button>
                                                ) : null}
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </motion.div>
                    ) : (
                        <motion.div
                            key="week"
                            initial={{ opacity: 0, y: 6 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -6 }}
                            transition={{ duration: 0.2 }}
                            className="mt-3 space-y-2"
                        >
                            {weekDays.map((day) => {
                                const evs = eventsForDay(day);
                                const isToday = sameDay(day, today);
                                return (
                                    <div
                                        key={day.toISOString()}
                                        className={`rounded-xl border px-2 py-2 sm:px-3 ${
                                            isToday
                                                ? 'border-[#c9a227]/55 bg-amber-50/70'
                                                : 'border-zinc-200 bg-zinc-50/60'
                                        }`}
                                    >
                                        <div className="flex items-baseline justify-between gap-2">
                                            <span className="text-xs font-semibold uppercase tracking-wide text-[#4b1f6f]">
                                                {new Intl.DateTimeFormat(
                                                    intlLocale,
                                                    {
                                                        weekday: 'short',
                                                        day: 'numeric',
                                                        month: 'short',
                                                    },
                                                ).format(day)}
                                            </span>
                                            {evs.length === 0 ? (
                                                <span className="text-[10px] text-zinc-400 sm:text-xs">
                                                    {tr(
                                                        'home.agenda.noEvents',
                                                        '—',
                                                    )}
                                                </span>
                                            ) : null}
                                        </div>
                                        <ul className="mt-2 space-y-1.5">
                                            {evs.map((e) => (
                                                <li key={e.id}>
                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            openDetail(e, null)
                                                        }
                                                        className="flex w-full flex-wrap items-baseline gap-x-2 gap-y-0.5 rounded-lg text-left text-xs text-zinc-700 transition hover:bg-white/80 hover:ring-1 hover:ring-[#4b1f6f]/15 sm:text-sm"
                                                    >
                                                        <span
                                                            className="mt-0.5 h-2 w-2 shrink-0 rounded-full"
                                                            style={{
                                                                backgroundColor:
                                                                    e.category
                                                                        .color,
                                                            }}
                                                            aria-hidden
                                                        />
                                                        <span className="shrink-0 font-mono text-sm font-semibold text-[#4b1f6f]">
                                                            {timeFmt.format(
                                                                e.at,
                                                            )}
                                                        </span>
                                                        <span className="font-medium leading-snug text-zinc-800">
                                                            {e.name}
                                                        </span>
                                                    </button>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                );
                            })}
                        </motion.div>
                    )}
                </AnimatePresence>
            </div>

            {showFullAgendaLink ? (
                <Link
                    href={route('agenda.index', { locale })}
                    className="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-[#4b1f6f] bg-white px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-[#4b1f6f] transition hover:bg-[#4b1f6f] hover:text-white sm:mt-auto sm:px-6 sm:py-3 sm:text-base"
                >
                    {tr('home.agenda.fullAgenda', 'Ver agenda completa')}
                    <ArrowRight
                        className="h-4 w-4 shrink-0"
                        strokeWidth={2.5}
                    />
                </Link>
            ) : null}

            <Modal
                show={sheet !== null}
                onClose={closeSheet}
                maxWidth="2xl"
            >
                {sheet?.kind === 'day' ? (
                    <div className="relative border-b-4 border-[#c9a227] bg-gradient-to-br from-[#4b1f6f] to-[#2f1245] px-5 pb-4 pt-5 text-white sm:px-8 sm:pt-6">
                        <button
                            type="button"
                            onClick={closeSheet}
                            className="absolute right-3 top-3 rounded-full p-2 text-white/80 transition hover:bg-white/10 hover:text-white"
                            aria-label={tr('agenda.modalClose', 'Cerrar')}
                        >
                            <X className="h-5 w-5" />
                        </button>
                        <p className="text-xs font-semibold uppercase tracking-[0.2em] text-[#c9a227]">
                            {tr('agenda.eventsThisDay', 'Actos del día')}
                        </p>
                        <h3 className="mt-1 text-xl font-bold capitalize sm:text-2xl">
                            {sheet.dayTitle}
                        </h3>
                        <ul className="mt-5 space-y-2">
                            {sheet.events.map((e) => (
                                <li key={e.id}>
                                    <button
                                        type="button"
                                        onClick={() =>
                                            openDetail(e, {
                                                day: sheet.day,
                                                events: sheet.events,
                                                dayTitle: sheet.dayTitle,
                                            })
                                        }
                                        className="flex w-full items-center gap-3 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-left transition hover:bg-white/15"
                                    >
                                        <span
                                            className="h-2.5 w-2.5 shrink-0 rounded-full"
                                            style={{
                                                backgroundColor:
                                                    e.category.color,
                                            }}
                                        />
                                        <span className="font-mono text-sm font-semibold text-[#c9a227]">
                                            {timeFmt.format(e.at)}
                                        </span>
                                        <span className="font-medium">
                                            {e.name}
                                        </span>
                                    </button>
                                </li>
                            ))}
                        </ul>
                    </div>
                ) : null}

                {sheet?.kind === 'detail' ? (
                    <div className="relative max-h-[85vh] overflow-y-auto">
                        <button
                            type="button"
                            onClick={closeSheet}
                            className="absolute right-3 top-3 z-10 rounded-full bg-zinc-100 p-2 text-zinc-600 transition hover:bg-zinc-200 hover:text-zinc-900"
                            aria-label={tr('agenda.modalClose', 'Cerrar')}
                        >
                            <X className="h-5 w-5" />
                        </button>

                        {sheet.returnTo ? (
                            <button
                                type="button"
                                onClick={() => {
                                    setLightboxSrc(null);
                                    setSheet({
                                        kind: 'day',
                                        day: sheet.returnTo.day,
                                        events: sheet.returnTo.events,
                                        dayTitle: sheet.returnTo.dayTitle,
                                    });
                                }}
                                className="absolute left-3 top-3 z-10 rounded-full border border-zinc-200 bg-white px-3 py-1.5 text-xs font-semibold text-[#4b1f6f] shadow-sm hover:bg-zinc-50"
                            >
                                {tr('agenda.backToDay', 'Volver al día')}
                            </button>
                        ) : null}

                        <div className="border-b-4 border-[#c9a227] bg-gradient-to-br from-[#4b1f6f] to-[#2f1245] px-5 pb-5 pt-6 sm:px-8 sm:pb-6 sm:pt-8">
                            <div className="flex flex-wrap items-center gap-2">
                                <span
                                    className="rounded-full border px-3 py-1 text-xs font-semibold"
                                    style={{
                                        backgroundColor: hexToRgba(
                                            sheet.event.category.color,
                                            0.2,
                                        ),
                                        borderColor: hexToRgba(
                                            sheet.event.category.color,
                                            0.5,
                                        ),
                                        color: sheet.event.category.color,
                                    }}
                                >
                                    {sheet.event.category.label}
                                </span>
                            </div>
                            <h3 className="mt-3 text-2xl font-bold leading-tight text-white sm:text-3xl">
                                {sheet.event.name}
                            </h3>
                            <p className="mt-3 text-sm text-white/85 sm:text-base">
                                {dateTimeLongFmt.format(sheet.event.at)}
                            </p>
                            {sheet.event.location ? (
                                <button
                                    type="button"
                                    onClick={() =>
                                        openMapPrompt(
                                            sheet.event.location,
                                            locale,
                                        )
                                    }
                                    className="mt-3 text-left text-sm font-semibold text-[#c9a227] underline-offset-2 hover:underline"
                                >
                                    {sheet.event.location}
                                </button>
                            ) : null}
                        </div>

                        <div className="space-y-5 px-5 py-6 sm:px-8">
                            {sheet.event.description ? (
                                <div
                                    className="agenda-event-html text-sm leading-relaxed text-zinc-700 [&_a]:text-[#4b1f6f] [&_a]:underline [&_li]:my-0.5 [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:mb-3 [&_p:last-child]:mb-0 [&_strong]:font-semibold [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5"
                                    // eslint-disable-next-line react/no-danger
                                    dangerouslySetInnerHTML={{
                                        __html: sheet.event.description,
                                    }}
                                />
                            ) : (
                                <p className="text-sm italic text-zinc-400">
                                    {tr(
                                        'agenda.noDescription',
                                        'Sin descripción.',
                                    )}
                                </p>
                            )}

                            {sheet.event.gallery?.length ? (
                                <div>
                                    <p className="mb-3 text-xs font-semibold uppercase tracking-[0.15em] text-[#4b1f6f]">
                                        {tr('agenda.gallery', 'Galería')}
                                    </p>
                                    <div className="grid grid-cols-2 gap-2 sm:grid-cols-3">
                                        {sheet.event.gallery.map((src) => (
                                            <button
                                                key={src}
                                                type="button"
                                                onClick={() =>
                                                    setLightboxSrc(src)
                                                }
                                                className="group relative aspect-[4/3] overflow-hidden rounded-xl border border-zinc-200 bg-zinc-100 shadow-sm"
                                            >
                                                <img
                                                    src={src}
                                                    alt=""
                                                    loading="lazy"
                                                    className="h-full w-full object-cover transition group-hover:scale-[1.03]"
                                                />
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            ) : null}
                        </div>
                    </div>
                ) : null}
            </Modal>

            {lightboxSrc ? (
                <button
                    type="button"
                    className="fixed inset-0 z-[60] flex items-center justify-center bg-black/85 p-4"
                    onClick={() => setLightboxSrc(null)}
                >
                    <span className="sr-only">
                        {tr('agenda.closeLightbox', 'Cerrar imagen')}
                    </span>
                    <img
                        src={lightboxSrc}
                        alt=""
                        className="max-h-full max-w-full rounded-lg object-contain shadow-2xl"
                        onClick={(ev) => ev.stopPropagation()}
                    />
                </button>
            ) : null}
        </div>
    );
}
