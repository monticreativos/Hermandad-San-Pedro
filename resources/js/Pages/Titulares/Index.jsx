import { Head, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

const images = [
    'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80',
    'https://images.unsplash.com/photo-1504198266285-165a1d59fdda?auto=format&fit=crop&w=1600&q=80',
    'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1600&q=80',
];

export default function TitularesIndex() {
    const { locale = 'es', translations = {} } = usePage().props;
    const t = (key, fallback) => translations[key] ?? fallback;

    return (
        <MainLayout>
            <Head title={t('titulares.title', locale === 'es' ? 'Titulares' : 'Titular Images')} />
            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <p className="max-w-3xl text-zinc-600">
                        {t('titulares.subtitle', '')}
                    </p>

                    <div className="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        {images.map((src, idx) => (
                            <div
                                key={idx}
                                className="group relative overflow-hidden rounded-2xl"
                            >
                                <motion.img
                                    src={src}
                                    alt={t('titulares.title', 'Titulares')}
                                    className="h-96 w-full object-cover"
                                    initial={{ scale: 1.05 }}
                                    whileInView={{ scale: 1.15 }}
                                    viewport={{ once: true, amount: 0.3 }}
                                    transition={{ duration: 6, ease: 'easeOut' }}
                                    loading="lazy"
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-black/45 to-transparent" />
                            </div>
                        ))}
                    </div>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
