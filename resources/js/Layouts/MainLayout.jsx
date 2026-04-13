import { Link, usePage } from '@inertiajs/react';
import { ChevronDown, ChevronRight, Globe, MapPin, Menu as MenuIcon, MessageCircle, Send, Share2, X } from 'lucide-react';
/* global route */
import { useEffect, useMemo, useState } from 'react';
import PageHero from '../Components/PageHero';

const PAGE_HERO_EXCLUDED_ROUTES = new Set(['home', 'news.show']);

const HEADER_ESCUDO_SRC =
    '/assets/img/' + encodeURIComponent('ESCUDO HDAD JESUS DEL PERDON medium png.png');

function resolvePageHeroTitle(props, routeName, locale) {
    const tr = (key, fallback) => props.translations?.[key] ?? fallback;

    if (props.pageHeroTitle) {
        return props.pageHeroTitle;
    }

    if (
        (routeName === 'brotherhood.show' ||
            routeName === 'cultos.show' ||
            routeName === 'patrimonio.show' ||
            routeName === 'obra_social.show') &&
        props.page?.title
    ) {
        return props.page.title;
    }

    const titles = {
        'news.index':
            locale === 'en' ? 'News' : 'Noticias',
        'agenda.index': 'Agenda',
        'history.index': tr(
            'history.title',
            locale === 'en' ? 'History' : 'Historia',
        ),
        'titulares.index': tr(
            'titulares.title',
            locale === 'en'
                ? 'Titular Images and Heritage'
                : 'Titulares y Patrimonio',
        ),
        'contact.index': tr(
            'contact.title',
            locale === 'en' ? 'Contact' : 'Contacto',
        ),
        'member_application.create': tr(
            'member.title',
            locale === 'en' ? 'Become a member' : 'Hazte hermano',
        ),
        'documentation.index': tr(
            'documentation.title',
            locale === 'en' ? 'Documentation' : 'Documentacion',
        ),
        'shop.index': tr(
            'shop.title',
            locale === 'en' ? 'Souvenir shop' : 'Tienda de recuerdos',
        ),
    };

    return titles[routeName] ?? '';
}

function currentRouteName() {
    try {
        if (typeof route !== 'function') {
            return '';
        }
        return route().current() ?? '';
    } catch {
        return '';
    }
}

const defaultItems = [
    { route_name: 'home', label_es: 'Inicio', label_en: 'Home', is_active: true, sort: 1 },
    { route_name: 'news.index', label_es: 'Noticias', label_en: 'News', is_active: true, sort: 2 },
    { route_name: 'agenda.index', label_es: 'Agenda', label_en: 'Agenda', is_active: true, sort: 3 },
    { route_name: 'titulares.index', label_es: 'Titulares', label_en: 'Titular Images', is_active: true, sort: 4 },
    { route_name: 'cultos.nav', label_es: 'Cultos', label_en: 'Worship', is_active: true, sort: 5 },
    { route_name: 'patrimonio.nav', label_es: 'Patrimonio', label_en: 'Heritage', is_active: true, sort: 6 },
    { route_name: 'obra_social.nav', label_es: 'Obra Social', label_en: 'Social outreach', is_active: true, sort: 7 },
    { route_name: 'contact.index', label_es: 'Contacto', label_en: 'Contact', is_active: true, sort: 8 },
];

const defaultBrotherhoodSubmenuItems = [
    { key: 'fines', label_es: 'Fines de la Hermandad', label_en: 'Purposes of the Brotherhood', is_active: true, sort: 1 },
    { key: 'historia', label_es: 'Historia de la hermandad', label_en: 'History of the Brotherhood', is_active: true, sort: 2 },
    { key: 'heraldica-simbolos', label_es: 'Heráldica y símbolos de la Hermandad', label_en: 'Heraldry and symbols', is_active: true, sort: 3 },
    { key: 'reglas-reglamentos', label_es: 'Reglas y Reglamentos', label_en: 'Rules and Regulations', is_active: true, sort: 4 },
    { key: 'junta-gobierno', label_es: 'Junta de Gobierno', label_en: 'Governing Board', is_active: true, sort: 5 },
];

/** Claves de cultos internos sustituidas por el Capítulo IV del Estatuto (migración 2026_04_12). */
const legacyInternosCultoKeys = new Set([
    'culto-misa-hermandad',
    'culto-triduo-cristo-perdon',
    'culto-triduo-virgen-salud',
    'culto-cuaresma-preparatorios',
    'culto-besamanos-titulares',
    'culto-funcion-principal',
    'culto-difuntos-fieles',
    'culto-via-crucis-ejercicios',
]);

/** En el menú actual estos cultos van bajo «externos»; si siguen bajo «internos», se regenera el submenú. */
const internosMisplacedCultoKeys = new Set(['culto-via-crucis-cuaresma', 'culto-virgen-salud-septiembre']);

/** Deben aparecer en la rama «externos» (cualquier nivel). */
const externosRequiredCultoKeys = [
    'culto-via-crucis-cuaresma',
    'culto-virgen-salud-septiembre',
    'culto-virgen-mayo',
    'corpus-christi',
];

function collectCultosSubmenuKeys(nodes, out = []) {
    if (!Array.isArray(nodes)) {
        return out;
    }
    for (const n of nodes) {
        const k = n?.key != null ? String(n.key).trim() : '';
        if (k) {
            out.push(k);
        }
        if (Array.isArray(n.children)) {
            collectCultosSubmenuKeys(n.children, out);
        }
    }
    return out;
}

const defaultCultosSubmenuItems = [
    {
        key: 'internos',
        label_es: 'Cultos internos',
        label_en: 'Internal worship',
        is_active: true,
        sort: 1,
        children: [
            {
                key: 'culto-misa-corporativa',
                label_es: 'Misa corporativa mensual',
                label_en: 'Monthly corporate Mass',
                is_active: true,
                sort: 1,
            },
            {
                key: 'culto-triduo-cuaresma-titulares',
                label_es: 'Triduo de Cuaresma a los Titulares',
                label_en: 'Lenten triduum to Titular Images',
                is_active: true,
                sort: 2,
            },
            {
                key: 'culto-cristo-rey-protestacion-fe',
                label_es: 'Cristo Rey — Función e Instituto',
                label_en: 'Christ the King — Principal function',
                is_active: true,
                sort: 3,
            },
            {
                key: 'culto-san-pedro-apostol',
                label_es: 'San Pedro Apóstol — 29 de junio',
                label_en: 'Saint Peter — 29 June',
                is_active: true,
                sort: 4,
            },
        ],
    },
    {
        key: 'externos',
        label_es: 'Cultos externos',
        label_en: 'External worship',
        is_active: true,
        sort: 2,
        children: [
            {
                key: '',
                label_es: 'Estación de Penitencia',
                label_en: 'Penitential station',
                is_active: true,
                sort: 1,
                children: [
                    {
                        key: 'estacion-penitencia-cofradia',
                        label_es: 'Cofradía',
                        label_en: 'Brotherhood',
                        is_active: true,
                        sort: 1,
                    },
                    {
                        key: 'estacion-penitencia-horario',
                        label_es: 'Horario y Recorrido',
                        label_en: 'Schedule and route',
                        is_active: true,
                        sort: 2,
                    },
                ],
            },
            {
                key: 'culto-via-crucis-cuaresma',
                label_es: 'Vía Crucis en la feligresía',
                label_en: 'Stations of the Cross in the parish',
                is_active: true,
                sort: 2,
                children: [],
            },
            {
                key: 'culto-virgen-salud-septiembre',
                label_es: 'Virgen de la Salud — Septiembre',
                label_en: 'Our Lady of Health — September',
                is_active: true,
                sort: 3,
                children: [],
            },
            {
                key: 'culto-virgen-mayo',
                label_es: 'Virgen de la Salud — Mes de mayo',
                label_en: 'Our Lady of Health — Month of May',
                is_active: true,
                sort: 4,
                children: [],
            },
            {
                key: 'corpus-christi',
                label_es: 'Procesión del Corpus Christi',
                label_en: 'Corpus Christi procession',
                is_active: true,
                sort: 5,
                children: [],
            },
        ],
    },
];

function defaultExternosSubmenuChildren() {
    const externos = defaultCultosSubmenuItems.find((row) => row.key === 'externos');
    return Array.isArray(externos?.children) ? externos.children : [];
}

function defaultInternosSubmenuChildren() {
    const internos = defaultCultosSubmenuItems.find((row) => row.key === 'internos');
    return Array.isArray(internos?.children) ? internos.children : [];
}

/** Si en BD «externos» sigue plano (sin children), se rellenan los subniveles por defecto. */
function mergeExternosChildren(items) {
    return items.map((item) => {
        if (item?.key !== 'externos') {
            return item;
        }
        const existing = Array.isArray(item.children) ? item.children : [];
        const keysFlat = collectCultosSubmenuKeys(existing);
        const missingRequired = externosRequiredCultoKeys.some((req) => !keysFlat.includes(req));
        if (existing.length === 0 || missingRequired) {
            return { ...item, children: defaultExternosSubmenuChildren() };
        }
        return item;
    });
}

/** «internos»: hijos por defecto si faltan o si persisten claves de menú pre-Estatuto (enlaces rotos). */
function mergeInternosChildren(items) {
    return items.map((item) => {
        if (item?.key !== 'internos') {
            return item;
        }
        const existing = Array.isArray(item.children) ? item.children : [];
        const hasLegacyKey = existing.some((c) =>
            legacyInternosCultoKeys.has(String(c?.key ?? '').trim()),
        );
        const hasMisplacedKey = existing.some((c) =>
            internosMisplacedCultoKeys.has(String(c?.key ?? '').trim()),
        );
        if (existing.length === 0 || hasLegacyKey || hasMisplacedKey) {
            return { ...item, children: defaultInternosSubmenuChildren() };
        }
        return item;
    });
}

function normalizeCultosBranch(item, locale, path) {
    if (item?.is_active === false) {
        return null;
    }
    const label = locale === 'en' ? item.label_en : item.label_es;
    if (!label) {
        return null;
    }
    const keyStr = item.key != null ? String(item.key).trim() : '';
    const rawChildren = Array.isArray(item.children) ? [...item.children] : [];
    rawChildren.sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999));
    const children = rawChildren
        .map((child, i) => normalizeCultosBranch(child, locale, `${path}-${i}`))
        .filter(Boolean);

    let href = null;
    if (keyStr && typeof route === 'function') {
        try {
            href = route('cultos.show', { locale, key: keyStr });
        } catch {
            href = null;
        }
    }

    if ((keyStr === 'externos' || keyStr === 'internos') && children.length > 0) {
        href = null;
    }

    if (children.length === 0 && !href) {
        return null;
    }

    const reactKey = keyStr || `cultos-${path}`;
    return { reactKey, label, href, children };
}

function CultosNavBranch({ nodes, onNavigate, depth = 0 }) {
    if (!nodes?.length) {
        return null;
    }

    const linkClass =
        depth === 0
            ? 'text-3xl font-medium text-[#2f4669] hover:text-[#3b1758]'
            : depth === 1
              ? 'text-2xl font-medium text-[#2f4669] hover:text-[#3b1758]'
              : 'text-xl font-medium text-[#2f4669] hover:text-[#3b1758]';

    return (
        <ul className={depth ? 'mt-2 list-none space-y-3 border-l border-zinc-200 pl-4' : 'list-none space-y-4'}>
            {nodes.map((node) => (
                <li key={node.reactKey}>
                    {node.children.length > 0 ? (
                        <>
                            {node.href ? (
                                <Link
                                    href={node.href}
                                    className={`flex w-full items-center justify-between gap-2 ${linkClass}`}
                                    onClick={onNavigate}
                                >
                                    <span>{node.label}</span>
                                    <ChevronRight aria-hidden className="shrink-0 text-zinc-400" size={18} />
                                </Link>
                            ) : (
                                <div
                                    className={`flex w-full items-center justify-between gap-2 font-semibold text-[#1f2b43] ${
                                        depth === 0 ? 'text-2xl sm:text-3xl' : 'text-xl sm:text-2xl'
                                    }`}
                                >
                                    <span>{node.label}</span>
                                    <ChevronRight aria-hidden className="shrink-0 text-zinc-400" size={18} />
                                </div>
                            )}
                            <CultosNavBranch nodes={node.children} onNavigate={onNavigate} depth={depth + 1} />
                        </>
                    ) : node.href ? (
                        <Link href={node.href} className={`block ${linkClass}`} onClick={onNavigate}>
                            {node.label}
                        </Link>
                    ) : null}
                </li>
            ))}
        </ul>
    );
}

const defaultPatrimonioSubmenuItems = [
    { key: 'enseres', label_es: 'Enseres', label_en: 'Furnishings', is_active: true, sort: 1 },
    { key: 'insignia-cofradia', label_es: 'Insignia de la Cofradía', label_en: 'Brotherhood insignia', is_active: true, sort: 2 },
    {
        key: 'paso-cristo-perdon',
        label_es: 'Paso Stmo. Cristo del Perdón',
        label_en: 'Float of the Holy Christ of Mercy',
        is_active: true,
        sort: 3,
    },
    {
        key: 'paso-virgen-salud',
        label_es: 'Paso Ntra. Sra. de la Salud',
        label_en: 'Float of Our Lady of Health',
        is_active: true,
        sort: 4,
    },
];

const defaultObraSocialSubmenuItems = [
    { key: 'labor-asistencial', label_es: 'Labor Asistencial', label_en: 'Assistance work', is_active: true, sort: 1 },
    { key: 'diputacion-caridad', label_es: 'Diputación de Caridad', label_en: 'Charity board', is_active: true, sort: 2 },
    { key: 'obra-asistencial', label_es: 'Obra asistencial', label_en: 'Charitable work', is_active: true, sort: 3 },
];

export default function MainLayout({ children }) {
    const page = usePage();
    const { locale = 'es', webSettings, translations = {} } = page.props;

    const routeName = currentRouteName();
    const pageHeroTitle =
        routeName && !PAGE_HERO_EXCLUDED_ROUTES.has(routeName)
            ? resolvePageHeroTitle(page.props, routeName, locale)
            : '';
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [isSolid, setIsSolid] = useState(false);
    const [activePanel, setActivePanel] = useState('hermandad');

    useEffect(() => {
        const onScroll = () => setIsSolid(window.scrollY > 24);
        onScroll();
        window.addEventListener('scroll', onScroll);

        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    const sortedMenuRows = useMemo(
        () =>
            [...(webSettings?.menu_items ?? defaultItems)]
                .filter((item) => item?.is_active !== false)
                .sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999)),
        [webSettings?.menu_items],
    );

    const hermandadSubmenu = useMemo(() => {
        const source =
            Array.isArray(webSettings?.brotherhood_submenu_items) && webSettings.brotherhood_submenu_items.length > 0
                ? webSettings.brotherhood_submenu_items
                : defaultBrotherhoodSubmenuItems;

        return [...source]
            .filter((item) => item?.is_active !== false && item?.key)
            .sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999))
            .map((item) => ({
                key: item.key,
                label: locale === 'en' ? item.label_en : item.label_es,
            }));
    }, [locale, webSettings?.brotherhood_submenu_items]);

    const cultosSubmenuTree = useMemo(() => {
        const source =
            Array.isArray(webSettings?.cultos_submenu_items) && webSettings.cultos_submenu_items.length > 0
                ? webSettings.cultos_submenu_items
                : defaultCultosSubmenuItems;

        const merged = mergeInternosChildren(mergeExternosChildren([...source]));

        return merged
            .filter((item) => item?.is_active !== false && String(item?.key ?? '').trim())
            .sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999))
            .map((item, i) => normalizeCultosBranch(item, locale, String(i)))
            .filter(Boolean);
    }, [locale, webSettings?.cultos_submenu_items]);

    const patrimonioSubmenu = useMemo(() => {
        const source =
            Array.isArray(webSettings?.patrimonio_submenu_items) && webSettings.patrimonio_submenu_items.length > 0
                ? webSettings.patrimonio_submenu_items
                : defaultPatrimonioSubmenuItems;

        return [...source]
            .filter((item) => item?.is_active !== false && item?.key)
            .sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999))
            .map((item) => ({
                key: item.key,
                label: locale === 'en' ? item.label_en : item.label_es,
            }));
    }, [locale, webSettings?.patrimonio_submenu_items]);

    const obraSocialSubmenu = useMemo(() => {
        const source =
            Array.isArray(webSettings?.obra_social_submenu_items) && webSettings.obra_social_submenu_items.length > 0
                ? webSettings.obra_social_submenu_items
                : defaultObraSocialSubmenuItems;

        return [...source]
            .filter((item) => item?.is_active !== false && item?.key)
            .sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999))
            .map((item) => ({
                key: item.key,
                label: locale === 'en' ? item.label_en : item.label_es,
            }));
    }, [locale, webSettings?.obra_social_submenu_items]);

    const burgerMainItems = [
        { id: 'hermandad', kind: 'panel', label: locale === 'en' ? 'Brotherhood' : 'Hermandad' },
        ...sortedMenuRows
            .filter((row) => row.route_name !== 'home')
            .map((row) => {
                const label = locale === 'en' ? row.label_en : row.label_es;
                if (row.route_name === 'cultos.nav') {
                    return { id: 'cultos', kind: 'panel', label };
                }
                if (row.route_name === 'patrimonio.nav') {
                    return { id: 'patrimonio', kind: 'panel', label };
                }
                if (row.route_name === 'obra_social.nav') {
                    return { id: 'obra_social', kind: 'panel', label };
                }
                return { id: row.route_name, kind: 'link', label };
            }),
    ];

    const switchLocale = locale === 'es' ? 'en' : 'es';
    const brandName = (locale === 'en' ? webSettings?.brand_name?.en : webSettings?.brand_name?.es) ?? 'Hermandad';
    const ctaLabel = (locale === 'en' ? webSettings?.cta_label?.en : webSettings?.cta_label?.es) ?? (locale === 'en' ? 'Login' : 'Acceder');
    const ctaUrl = webSettings?.cta_url ?? '/login';
    const t = (key, fallback) => translations[key] ?? fallback;
    return (
        <div className="min-h-screen bg-white font-sans text-zinc-900">
            <header
                className={`fixed inset-x-0 top-0 z-50 border-b border-zinc-200 transition-all duration-300 ${
                    isSolid
                        ? 'bg-white shadow-sm md:bg-white/95 md:backdrop-blur'
                        : 'bg-white md:bg-white/80 md:backdrop-blur'
                }`}
            >
                <nav className="flex w-full items-center justify-between px-3 py-3 sm:px-6 lg:px-8">
                    <div className="flex items-center gap-3.5">
                        <button
                            type="button"
                            className="inline-flex items-center rounded-md p-2 text-[#4b1f6f] hover:bg-[#4b1f6f]/10"
                            onClick={() => setIsMenuOpen((prev) => !prev)}
                            aria-label="Toggle menu"
                        >
                            {isMenuOpen ? <X size={22} /> : <MenuIcon size={22} />}
                        </button>

                        <div className="inline-flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-full border border-[#4b1f6f]/30 bg-white">
                            <img
                                src={HEADER_ESCUDO_SRC}
                                alt={locale === 'en' ? 'Brotherhood coat of arms' : 'Escudo de la Hermandad'}
                                className="h-full w-full object-contain p-0.5"
                                width={36}
                                height={36}
                                decoding="async"
                            />
                        </div>

                        <Link
                            href={route('home', { locale })}
                            className="font-serif text-xl font-semibold tracking-wide text-[#4b1f6f]"
                        >
                            {brandName}
                        </Link>
                    </div>

                    <div className="hidden items-center gap-4 md:flex">
                        <Link
                            href={route('home', { locale: switchLocale })}
                            className="inline-flex items-center gap-1.5 text-base font-medium text-[#4b1f6f] hover:text-[#2f1245]"
                        >
                            <Globe size={18} />
                            {switchLocale.toUpperCase()}
                            <ChevronDown size={15} />
                        </Link>
                        <a
                            href={ctaUrl}
                            className="rounded-md border border-[#4b1f6f]/35 bg-white px-3.5 py-2 text-base font-semibold text-[#4b1f6f] transition hover:border-[#4b1f6f]/60 hover:bg-[#4b1f6f]/5"
                        >
                            {ctaLabel}
                        </a>
                    </div>
                </nav>

                {isMenuOpen ? (
                    <div className="max-h-[85vh] overflow-y-auto border-t border-zinc-200 bg-white md:max-h-none md:overflow-visible">
                        <div className="grid min-h-0 grid-cols-1 md:min-h-[70vh] md:grid-cols-[320px_1fr]">
                            <div className="space-y-1 border-r border-zinc-200 p-4">
                                {burgerMainItems.map((item) => {
                                    const isActive = activePanel === item.id;

                                    if (item.kind === 'panel') {
                                        return (
                                            <button
                                                key={item.id}
                                                type="button"
                                                className={`flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-2xl font-semibold transition ${
                                                    isActive
                                                        ? 'bg-[#3b1758]/10 text-[#3b1758]'
                                                        : 'text-[#1f2b43] hover:bg-zinc-100'
                                                }`}
                                                onClick={() => setActivePanel(item.id)}
                                            >
                                                {item.label}
                                                <ChevronRight size={18} />
                                            </button>
                                        );
                                    }

                                    return (
                                        <Link
                                            key={item.id}
                                            href={route(item.id, { locale })}
                                            className="flex items-center justify-between rounded-xl px-4 py-3 text-2xl font-semibold text-[#1f2b43] transition hover:bg-zinc-100"
                                            onClick={() => setIsMenuOpen(false)}
                                        >
                                            {item.label}
                                            <ChevronRight
                                                size={18}
                                                className="text-zinc-400"
                                            />
                                        </Link>
                                    );
                                })}

                                <Link
                                    href={route('home', { locale: switchLocale })}
                                    className="mt-2 block rounded-xl px-4 py-3 text-lg font-semibold text-[#3b1758] hover:bg-zinc-100"
                                    onClick={() => setIsMenuOpen(false)}
                                >
                                    {switchLocale.toUpperCase()}
                                </Link>
                            </div>

                            <div className="p-6">
                                {activePanel === 'hermandad' ? (
                                    <div className="grid gap-4">
                                        {hermandadSubmenu.map((subItem) => (
                                            <Link
                                                key={subItem.key}
                                                href={route('brotherhood.show', {
                                                    locale,
                                                    key: subItem.key,
                                                })}
                                                className="text-3xl font-medium text-[#2f4669] hover:text-[#3b1758]"
                                                onClick={() => setIsMenuOpen(false)}
                                            >
                                                {subItem.label}
                                            </Link>
                                        ))}
                                    </div>
                                ) : null}
                                {activePanel === 'cultos' ? (
                                    <div className="grid gap-4">
                                        <CultosNavBranch
                                            nodes={cultosSubmenuTree}
                                            onNavigate={() => setIsMenuOpen(false)}
                                        />
                                    </div>
                                ) : null}
                                {activePanel === 'patrimonio' ? (
                                    <div className="grid gap-4">
                                        {patrimonioSubmenu.map((subItem) => (
                                            <Link
                                                key={subItem.key}
                                                href={route('patrimonio.show', {
                                                    locale,
                                                    key: subItem.key,
                                                })}
                                                className="text-2xl font-medium leading-snug text-[#2f4669] hover:text-[#3b1758] sm:text-3xl"
                                                onClick={() => setIsMenuOpen(false)}
                                            >
                                                {subItem.label}
                                            </Link>
                                        ))}
                                    </div>
                                ) : null}
                                {activePanel === 'obra_social' ? (
                                    <div className="grid gap-4">
                                        {obraSocialSubmenu.map((subItem) => (
                                            <Link
                                                key={subItem.key}
                                                href={route('obra_social.show', {
                                                    locale,
                                                    key: subItem.key,
                                                })}
                                                className="text-2xl font-medium leading-snug text-[#2f4669] hover:text-[#3b1758] sm:text-3xl"
                                                onClick={() => setIsMenuOpen(false)}
                                            >
                                                {subItem.label}
                                            </Link>
                                        ))}
                                    </div>
                                ) : null}
                            </div>
                        </div>
                    </div>
                ) : null}
            </header>

            <main className="pt-14">
                {pageHeroTitle ? <PageHero title={pageHeroTitle} /> : null}
                {children}
            </main>

            <footer className="mt-16 border-t border-zinc-200 bg-zinc-50">
                <div className="mx-auto grid w-full max-w-[90rem] gap-8 px-4 py-10 sm:px-6 md:grid-cols-3 lg:px-8">
                    <div>
                        <h3 className="text-lg font-bold text-[#4b1f6f]">{t('footer.follow', 'Siguenos')}</h3>
                        <div className="mt-4 flex items-center gap-3">
                            <a
                                href="https://instagram.com"
                                target="_blank"
                                rel="noreferrer"
                                className="rounded-xl border border-zinc-300 p-2 text-[#4b1f6f] hover:bg-[#4b1f6f]/10"
                            >
                                <MessageCircle size={18} />
                            </a>
                            <a
                                href="https://facebook.com"
                                target="_blank"
                                rel="noreferrer"
                                className="rounded-xl border border-zinc-300 p-2 text-[#4b1f6f] hover:bg-[#4b1f6f]/10"
                            >
                                <Share2 size={18} />
                            </a>
                            <a
                                href="https://youtube.com"
                                target="_blank"
                                rel="noreferrer"
                                className="rounded-xl border border-zinc-300 p-2 text-[#4b1f6f] hover:bg-[#4b1f6f]/10"
                            >
                                <Send size={18} />
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 className="text-lg font-bold text-[#4b1f6f]">{t('footer.address', 'Direccion')}</h3>
                        <a
                            href="https://maps.google.com/?q=Plaza+de+la+Constitucion+Sevilla"
                            target="_blank"
                            rel="noreferrer"
                            className="mt-4 inline-flex items-center gap-2 text-sm text-zinc-700 hover:text-[#4b1f6f]"
                        >
                            <MapPin size={16} />
                            Plaza de la Constitucion, Sevilla
                        </a>
                    </div>

                    <div>
                        <h3 className="text-lg font-bold text-[#4b1f6f]">{t('footer.hours', 'Horario de secretaria')}</h3>
                        <p className="mt-4 text-sm text-zinc-700">{t('footer.hours.value', 'Lunes a viernes, 19:30 - 21:30')}</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
