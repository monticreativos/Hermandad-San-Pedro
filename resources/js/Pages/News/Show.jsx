import { Head, Link, usePage } from '@inertiajs/react';
import { Link as LinkIcon, MessageCircle, Send, Share2 } from 'lucide-react';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

const formatDate = (value, locale) =>
    new Intl.DateTimeFormat(locale === 'es' ? 'es-ES' : 'en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(new Date(value));

const renderInline = (node, key) => {
    if (!node) return null;

    if (node.type === 'text') {
        let element = node.text ?? '';

        for (const mark of node.marks ?? []) {
            if (mark.type === 'bold') element = <strong key={`${key}-b`}>{element}</strong>;
            if (mark.type === 'italic') element = <em key={`${key}-i`}>{element}</em>;
            if (mark.type === 'link')
                element = (
                    <a
                        key={`${key}-l`}
                        href={mark.attrs?.href}
                        className="text-[#4b1f6f] underline"
                        target="_blank"
                        rel="noreferrer"
                    >
                        {element}
                    </a>
                );
        }

        return <span key={key}>{element}</span>;
    }

    return null;
};

const renderContent = (content) => {
    if (typeof content === 'string') {
        return content.split('\n').filter(Boolean).map((line, i) => (
            <p key={i} className="mb-4 leading-8 text-zinc-700">
                {line}
            </p>
        ));
    }

    if (!content || !Array.isArray(content.content)) {
        return null;
    }

    return content.content.map((block, idx) => {
        const inlineNodes = (block.content ?? []).map((node, i) =>
            renderInline(node, `${idx}-${i}`),
        );

        if (block.type === 'heading') {
            return (
                <h2 key={idx} className="mb-4 mt-6 text-2xl font-bold text-[#1f2b43]">
                    {inlineNodes}
                </h2>
            );
        }

        if (block.type === 'bulletList') {
            return (
                <ul key={idx} className="mb-4 list-disc space-y-1 pl-6 text-zinc-700">
                    {(block.content ?? []).map((li, liIdx) => (
                        <li key={liIdx}>
                            {(li.content ?? []).flatMap((p) =>
                                (p.content ?? []).map((n, nIdx) =>
                                    renderInline(n, `${idx}-${liIdx}-${nIdx}`),
                                ),
                            )}
                        </li>
                    ))}
                </ul>
            );
        }

        if (block.type === 'orderedList') {
            return (
                <ol key={idx} className="mb-4 list-decimal space-y-1 pl-6 text-zinc-700">
                    {(block.content ?? []).map((li, liIdx) => (
                        <li key={liIdx}>
                            {(li.content ?? []).flatMap((p) =>
                                (p.content ?? []).map((n, nIdx) =>
                                    renderInline(n, `${idx}-${liIdx}-${nIdx}`),
                                ),
                            )}
                        </li>
                    ))}
                </ol>
            );
        }

        return (
            <p key={idx} className="mb-4 leading-8 text-zinc-700">
                {inlineNodes}
            </p>
        );
    });
};

export default function NewsShow({ news }) {
    const { locale = 'es' } = usePage().props;
    const currentUrl = typeof window !== 'undefined' ? window.location.href : '';
    const shareText = encodeURIComponent(news.title);
    const encodedUrl = encodeURIComponent(currentUrl);

    const shareLinks = [
        {
            id: 'whatsapp',
            icon: MessageCircle,
            href: `https://wa.me/?text=${shareText}%20${encodedUrl}`,
        },
        {
            id: 'facebook',
            icon: Share2,
            href: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
        },
        {
            id: 'x',
            icon: Send,
            href: `https://twitter.com/intent/tweet?text=${shareText}&url=${encodedUrl}`,
        },
    ];

    const copyLink = async () => {
        if (!currentUrl || !navigator?.clipboard) return;
        await navigator.clipboard.writeText(currentUrl);
    };

    return (
        <MainLayout>
            <Head>
                <title>{news.title}</title>
                <meta name="description" content={news.description ?? ''} />
            </Head>

            <PageTransition>
                <article className="mx-auto w-full max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <img
                        src={
                            news.image_path ||
                            'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1400&q=80'
                        }
                        alt={news.title}
                        className="h-72 w-full rounded-2xl object-cover sm:h-96 lg:h-[34rem]"
                        loading="lazy"
                    />

                    <p className="mt-5 text-sm font-semibold uppercase tracking-wide text-[#4b1f6f]">
                        {formatDate(news.created_at, locale)}
                    </p>

                    <h1 className="mt-3 text-3xl font-bold leading-tight text-[#1f2b43] sm:text-5xl">
                        {news.title}
                    </h1>

                    <div className="mt-8 max-w-4xl text-base">
                        {renderContent(news.content)}
                    </div>

                    <div className="mt-12 flex justify-end gap-2">
                        {shareLinks.map((item) => {
                            const Icon = item.icon;
                            return (
                                <a
                                    key={item.id}
                                    href={item.href}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#3b1758]/25 text-[#3b1758] transition hover:bg-[#3b1758]/10"
                                >
                                    <Icon size={18} />
                                </a>
                            );
                        })}
                        <button
                            type="button"
                            onClick={copyLink}
                            className="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#3b1758]/25 text-[#3b1758] transition hover:bg-[#3b1758]/10"
                        >
                            <LinkIcon size={18} />
                        </button>
                    </div>

                    <div className="mt-12">
                        <p className="text-sm font-semibold text-[#3b1758]">
                            {locale === 'es'
                                ? 'Temas relacionados'
                                : 'Related topics'}
                        </p>
                        <div className="mt-6 flex flex-wrap gap-3">
                            {(news.related_topics ?? []).map((topic) => (
                                <span
                                    key={topic}
                                    className="rounded-full border border-[#3b1758]/25 px-4 py-2 text-xl font-semibold text-[#3b1758]"
                                >
                                    {topic}
                                </span>
                            ))}
                        </div>
                    </div>

                    <Link
                        href={route('news.index', { locale })}
                        className="mt-8 inline-block text-sm font-semibold text-[#4b1f6f] hover:text-[#2f1245]"
                    >
                        {locale === 'es' ? 'Volver a noticias' : 'Back to news'}
                    </Link>
                </article>
            </PageTransition>
        </MainLayout>
    );
}
