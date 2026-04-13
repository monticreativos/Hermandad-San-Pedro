import { Head, Link, router, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Search } from 'lucide-react';
import { useEffect, useState } from 'react';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

const copy = {
    es: {
        title: 'Noticias',
        searchPlaceholder: 'Buscar por titulo...',
        readMore: 'Leer noticia',
        noResults: 'No se encontraron noticias.',
    },
    en: {
        title: 'News',
        searchPlaceholder: 'Search by title...',
        readMore: 'Read story',
        noResults: 'No news found.',
    },
};

const formatDate = (value, locale) =>
    new Intl.DateTimeFormat(locale === 'es' ? 'es-ES' : 'en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(new Date(value));

export default function NewsIndex({ news = { data: [], links: [] }, filters }) {
    const { locale = 'es' } = usePage().props;
    const [search, setSearch] = useState(filters?.search ?? '');
    const t = copy[locale] ?? copy.es;

    useEffect(() => {
        const timeout = setTimeout(() => {
            router.get(
                route('news.index', { locale }),
                { search },
                { preserveState: true, replace: true },
            );
        }, 350);

        return () => clearTimeout(timeout);
    }, [search, locale]);

    return (
        <MainLayout>
            <Head title={t.title} />
            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-end">
                        <div className="relative w-full sm:max-w-sm sm:shrink-0">
                            <Search
                                size={17}
                                className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400"
                            />
                            <input
                                type="search"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder={t.searchPlaceholder}
                                className="w-full rounded-xl border border-zinc-300 bg-white py-2.5 pl-10 pr-3 text-sm text-zinc-800 outline-none transition focus:border-[#4b1f6f]/60 focus:ring-2 focus:ring-[#4b1f6f]/20"
                            />
                        </div>
                    </div>

                    <div className="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        {news.data.map((item) => (
                            <motion.article
                                key={item.id}
                                whileHover={{ y: -4, scale: 1.01 }}
                                transition={{ duration: 0.2 }}
                                className="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm"
                            >
                                <img
                                    src={item.image_path || 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=900&q=80'}
                                    alt={item.title}
                                    className="h-48 w-full rounded-xl object-cover"
                                    loading="lazy"
                                    onError={(e) => {
                                        e.currentTarget.onerror = null;
                                        e.currentTarget.src =
                                            'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=900&q=80';
                                    }}
                                />
                                <p className="mt-3 text-xs font-medium uppercase tracking-wide text-zinc-500">
                                    {formatDate(item.created_at, locale)}
                                </p>
                                <h2 className="mt-2 text-xl font-bold leading-tight text-[#1f2b43]">
                                    {item.title}
                                </h2>
                                <p className="mt-2 line-clamp-2 text-sm text-zinc-600">
                                    {item.excerpt}
                                </p>
                                <Link
                                    href={route('news.show', {
                                        locale,
                                        slug: item.slug,
                                    })}
                                    className="mt-4 inline-block text-sm font-semibold text-[#4b1f6f] hover:text-[#2f1245]"
                                >
                                    {t.readMore}
                                </Link>
                            </motion.article>
                        ))}
                    </div>

                    {news.data.length === 0 ? (
                        <p className="mt-8 text-sm text-zinc-500">{t.noResults}</p>
                    ) : null}

                    <div className="mt-8 flex flex-wrap gap-2">
                        {news.links.map((link, idx) => (
                            <Link
                                key={`${link.label}-${idx}`}
                                href={link.url || '#'}
                                preserveState
                                className={`rounded-md border px-3 py-1.5 text-sm ${
                                    link.active
                                        ? 'border-[#4b1f6f] bg-[#4b1f6f] text-white'
                                        : 'border-zinc-300 text-zinc-700 hover:bg-zinc-100'
                                } ${!link.url ? 'pointer-events-none opacity-50' : ''}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
