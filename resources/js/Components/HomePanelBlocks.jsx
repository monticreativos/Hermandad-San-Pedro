import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';

const bodyForLocale = (block, locale) =>
    block[`body_${locale}`] ?? block.body_es ?? block.body_en ?? '';

const altForLocale = (block, locale) =>
    block[`alt_${locale}`] ?? block.alt_es ?? block.alt_en ?? '';

const imageSrc = (path) => {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }
    return `/storage/${path}`;
};

export function resolvePanelFooter(footer, locale, fallbackLabel, fallbackRouteName) {
    const fromAdmin = (footer?.label?.[locale] ?? footer?.label?.es ?? '').trim();
    const label = fromAdmin || fallbackLabel || '';

    const linkType = footer?.link_type ?? 'internal';

    if (linkType === 'external' && footer?.external_url) {
        return { label, isExternal: true, href: footer.external_url };
    }

    const routeName = footer?.route_name || fallbackRouteName || 'home';
    try {
        return {
            label,
            isExternal: false,
            href: route(routeName, { locale }),
        };
    } catch {
        return {
            label,
            isExternal: false,
            href: route(fallbackRouteName || 'home', { locale }),
        };
    }
}

export default function HomePanelBlocks({ blocks = [], locale }) {
    if (!Array.isArray(blocks) || blocks.length === 0) {
        return null;
    }

    return (
        <div className="space-y-5 text-sm leading-relaxed text-zinc-600 sm:text-base">
            {blocks.map((block, index) => {
                const key = `${block.type}-${index}`;

                if (block.type === 'heading') {
                    const text = bodyForLocale(block, locale);
                    if (!text.trim()) return null;
                    return (
                        <p key={key} className="font-semibold text-[#4b1f6f]">
                            {text}
                        </p>
                    );
                }

                if (block.type === 'paragraph') {
                    const text = bodyForLocale(block, locale);
                    if (!text.trim()) return null;
                    return (
                        <p key={key} className="whitespace-pre-line">
                            {text}
                        </p>
                    );
                }

                if (block.type === 'callout') {
                    const text = bodyForLocale(block, locale);
                    if (!text.trim()) return null;
                    return (
                        <p
                            key={key}
                            className="border-l-[3px] border-[#c9a227] bg-amber-50/50 py-2 pl-4 pr-2 text-zinc-700"
                        >
                            {text}
                        </p>
                    );
                }

                if (block.type === 'image') {
                    const src = imageSrc(block.image_path);
                    if (!src) return null;
                    return (
                        <figure key={key} className="overflow-hidden rounded-xl border border-zinc-100 shadow-sm">
                            <img
                                src={src}
                                alt={altForLocale(block, locale)}
                                className="h-auto w-full object-cover"
                                loading="lazy"
                            />
                        </figure>
                    );
                }

                return null;
            })}
        </div>
    );
}

export function HomePanelFooterButton({ footer, locale, fallbackLabel, fallbackRouteName }) {
    const { label, isExternal, href } = resolvePanelFooter(
        footer,
        locale,
        fallbackLabel,
        fallbackRouteName,
    );

    const className =
        'mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-[#4b1f6f] bg-white px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-[#4b1f6f] transition hover:bg-[#4b1f6f] hover:text-white sm:mt-auto sm:px-6 sm:py-3 sm:text-base';

    if (isExternal) {
        return (
            <a
                href={href}
                target="_blank"
                rel="noopener noreferrer"
                className={className}
            >
                {label}
                <ArrowRight className="h-4 w-4 shrink-0" strokeWidth={2.5} />
            </a>
        );
    }

    return (
        <Link href={href} className={className}>
            {label}
            <ArrowRight className="h-4 w-4 shrink-0" strokeWidth={2.5} />
        </Link>
    );
}
