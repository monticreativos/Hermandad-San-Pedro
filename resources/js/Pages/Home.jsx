import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowRight, FileDown, ShoppingBag, UserPlus } from 'lucide-react';
import MainLayout from '../Layouts/MainLayout';
import PageTransition from '../Components/PageTransition';
import ChapelMassesPanel from '../Components/ChapelMassesPanel';
import GildingDonationPanel from '../Components/GildingDonationPanel';
import HomeAgendaCalendar from '../Components/HomeAgendaCalendar';
import ImageSliderSection from '../Components/ImageSliderSection';

const copy = {
    es: {
        heroTitle: 'Devocion, historia y vida de hermandad',
        heroText:
            'Una presencia digital solemne para acercar nuestros cultos, noticias y agenda a todos los hermanos.',
        latestNews: 'Ultimas Noticias',
        allNews: 'Ver todas',
    },
    en: {
        heroTitle: 'Devotion, history and brotherhood life',
        heroText:
            'A solemn digital home to bring our worship, news and agenda closer to all members.',
        latestNews: 'Latest News',
        allNews: 'View all',
    },
};

const localized = (value, locale) => {
    if (!value || typeof value !== 'object') return '';
    return value[locale] ?? value.es ?? '';
};

const getNewsImageSrc = (imagePath) => {
    if (!imagePath) return null;
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
    }

    return `/storage/${imagePath}`;
};

const fallbackNewsImage =
    'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1000&q=80';

export default function Home({ latestNews = [], agendaEvents = [] }) {
    const { locale = 'es', translations = {} } = usePage().props;
    const t = copy[locale] ?? copy.es;
    const tr = (key, fallback) => translations[key] ?? fallback;

    return (
        <MainLayout>
            <Head title={t.heroTitle} />

            <PageTransition>
                <ImageSliderSection />

                <section className="mx-auto w-full max-w-[90rem] px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                        <Link
                            href={route('member_application.create', { locale })}
                            className="group flex flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-[#4b1f6f]/35 hover:shadow-md"
                        >
                            <span className="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#4b1f6f]/10 text-[#4b1f6f] transition group-hover:bg-[#4b1f6f]/15">
                                <UserPlus size={22} strokeWidth={2} />
                            </span>
                            <span className="mt-4 text-lg font-bold text-[#4b1f6f]">
                                {tr('home.quick.member.title', 'Hazte hermano')}
                            </span>
                            <span className="mt-2 text-sm leading-relaxed text-zinc-600">
                                {tr('home.quick.member.text', '')}
                            </span>
                            <span className="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#4b1f6f] group-hover:text-[#2f1245]">
                                {tr('home.quick.cta.member', 'Rellenar formulario')}
                                <ArrowRight size={15} />
                            </span>
                        </Link>
                        <Link
                            href={route('documentation.index', { locale })}
                            className="group flex flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-[#4b1f6f]/35 hover:shadow-md"
                        >
                            <span className="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#4b1f6f]/10 text-[#4b1f6f] transition group-hover:bg-[#4b1f6f]/15">
                                <FileDown size={22} strokeWidth={2} />
                            </span>
                            <span className="mt-4 text-lg font-bold text-[#4b1f6f]">
                                {tr('home.quick.docs.title', 'Documentacion')}
                            </span>
                            <span className="mt-2 text-sm leading-relaxed text-zinc-600">
                                {tr('home.quick.docs.text', '')}
                            </span>
                            <span className="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#4b1f6f] group-hover:text-[#2f1245]">
                                {tr('home.quick.cta.docs', 'Ver documentos')}
                                <ArrowRight size={15} />
                            </span>
                        </Link>
                        <Link
                            href={route('shop.index', { locale })}
                            className="group flex flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-[#4b1f6f]/35 hover:shadow-md sm:col-span-2 lg:col-span-1"
                        >
                            <span className="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#4b1f6f]/10 text-[#4b1f6f] transition group-hover:bg-[#4b1f6f]/15">
                                <ShoppingBag size={22} strokeWidth={2} />
                            </span>
                            <span className="mt-4 text-lg font-bold text-[#4b1f6f]">
                                {tr('home.quick.shop.title', 'Tienda de recuerdos')}
                            </span>
                            <span className="mt-2 text-sm leading-relaxed text-zinc-600">
                                {tr('home.quick.shop.text', '')}
                            </span>
                            <span className="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#4b1f6f] group-hover:text-[#2f1245]">
                                {tr('home.quick.cta.shop', 'Visitar tienda')}
                                <ArrowRight size={15} />
                            </span>
                        </Link>
                    </div>
                </section>

                <section className="mx-auto w-full max-w-[90rem] px-4 py-12 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between">
                        <h2 className="text-3xl font-bold text-[#4b1f6f] sm:text-4xl">
                            {t.latestNews}
                        </h2>
                        <Link
                            href={route('news.index', { locale })}
                            className="inline-flex items-center gap-1 text-sm font-semibold text-[#4b1f6f] hover:text-[#2f1245]"
                        >
                            {t.allNews}
                            <ArrowRight size={15} />
                        </Link>
                    </div>

                    <div className="mt-6 grid gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                        {latestNews.map((item) => (
                            <article
                                key={item.id}
                                className="group"
                            >
                                <Link
                                    href={route('news.show', {
                                        locale,
                                        slug: item.slug,
                                    })}
                                >
                                    <img
                                        src={
                                            getNewsImageSrc(item.image_path) ??
                                            fallbackNewsImage
                                        }
                                        alt={localized(item.title, locale)}
                                        className="h-52 w-full rounded-2xl object-cover shadow-sm transition duration-300 group-hover:brightness-95"
                                        loading="lazy"
                                        onError={(event) => {
                                            event.currentTarget.onerror = null;
                                            event.currentTarget.src = fallbackNewsImage;
                                        }}
                                    />
                                </Link>
                                <Link
                                    href={route('news.show', {
                                        locale,
                                        slug: item.slug,
                                    })}
                                    className="mt-4 inline-block text-[1.4em] font-bold leading-[1.6rem] text-[#4b1f6f] hover:text-[#2f1245]"
                                >
                                    {localized(item.title, locale)}
                                </Link>
                            </article>
                        ))}
                    </div>
                </section>

                <section
                    className="w-full border-t border-zinc-200/80 bg-gradient-to-b from-[#faf8fc] to-zinc-50 py-24 sm:py-28"
                    aria-labelledby="home-agenda-chapel-heading"
                >
                    <div className="mx-auto w-full max-w-[90rem] px-4 sm:px-6 lg:px-8">
                        <h2
                            id="home-agenda-chapel-heading"
                            className="sr-only"
                        >
                            {tr('home.triple.sectionTitle', 'Agenda, capilla y colaboracion')}
                        </h2>
                        <div className="grid grid-cols-1 gap-10 lg:grid-cols-3 lg:items-stretch lg:gap-8 xl:gap-10">
                            <div className="flex h-full min-h-[22rem] lg:min-h-0">
                                <HomeAgendaCalendar
                                    events={agendaEvents}
                                    showFullAgendaLink
                                />
                            </div>
                            <div className="flex h-full min-h-0">
                                <ChapelMassesPanel />
                            </div>
                            <div className="flex h-full min-h-0">
                                <GildingDonationPanel />
                            </div>
                        </div>
                    </div>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
