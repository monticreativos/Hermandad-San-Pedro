import { Head, usePage } from '@inertiajs/react';
import { ExternalLink, ShoppingBag } from 'lucide-react';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

export default function ShopIndex() {
    const { locale = 'es', translations = {}, webSettings } = usePage().props;
    const t = (key, fallback) => translations[key] ?? fallback;
    const rawShop = webSettings?.shop_url;
    const shopUrl =
        typeof rawShop === 'string' && rawShop.trim() !== '' ? rawShop.trim() : '';

    return (
        <MainLayout>
            <Head title={t('shop.title', locale === 'es' ? 'Tienda de recuerdos' : 'Souvenir shop')} />

            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <div className="flex items-start gap-3">
                        <span className="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#4b1f6f]/10 text-[#4b1f6f]">
                            <ShoppingBag size={26} />
                        </span>
                        <p className="max-w-2xl pt-1 text-zinc-600">
                            {t('shop.subtitle', '')}
                        </p>
                    </div>

                    <div className="mt-10 rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm">
                        {shopUrl ? (
                            <a
                                href={shopUrl}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-2 rounded-xl bg-[#4b1f6f] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#3b1758]"
                            >
                                {t('shop.openStore', 'Ir a la tienda')}
                                <ExternalLink size={18} />
                            </a>
                        ) : (
                            <p className="text-zinc-600">{t('shop.comingSoon', '')}</p>
                        )}
                    </div>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
