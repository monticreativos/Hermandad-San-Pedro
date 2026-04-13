import { Head, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

const blocks = [
    {
        image: 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1400&q=80',
        titleKey: 'history.section1.title',
        textKey: 'history.section1.text',
    },
    {
        image: 'https://images.unsplash.com/photo-1504198266285-165a1d59fdda?auto=format&fit=crop&w=1400&q=80',
        titleKey: 'history.section2.title',
        textKey: 'history.section2.text',
    },
    {
        image: 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1400&q=80',
        titleKey: 'history.section3.title',
        textKey: 'history.section3.text',
    },
];

export default function HistoryIndex() {
    const { locale = 'es', translations = {} } = usePage().props;
    const t = (key, fallback) => translations[key] ?? fallback;

    return (
        <MainLayout>
            <Head title={t('history.title', locale === 'es' ? 'Historia' : 'History')} />
            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <p className="max-w-3xl text-base text-zinc-600">
                        {t('history.intro', '')}
                    </p>

                    <div className="mt-10 space-y-12">
                        {blocks.map((block, idx) => (
                            <div
                                key={block.titleKey}
                                className={`grid items-center gap-6 md:grid-cols-2 ${idx % 2 ? 'md:[&>*:first-child]:order-2' : ''}`}
                            >
                                <img
                                    src={block.image}
                                    alt={t(block.titleKey, '')}
                                    className="h-72 w-full rounded-2xl object-cover shadow-sm sm:h-96"
                                    loading="lazy"
                                />
                                <motion.div
                                    initial={{ opacity: 0, y: 28 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, amount: 0.35 }}
                                    transition={{ duration: 0.6 }}
                                >
                                    <h2 className="text-3xl font-bold text-[#1f2b43]">
                                        {t(block.titleKey, '')}
                                    </h2>
                                    <p className="mt-3 leading-8 text-zinc-700">
                                        {t(block.textKey, '')}
                                    </p>
                                </motion.div>
                            </div>
                        ))}
                    </div>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
