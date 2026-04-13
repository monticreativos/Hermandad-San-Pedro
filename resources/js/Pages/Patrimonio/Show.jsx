import { Head, usePage } from '@inertiajs/react';
import PatrimonioEnseresCatalog from '../../Components/PatrimonioEnseresCatalog';
import PatrimonioPasoGallery from '../../Components/PatrimonioPasoGallery';
import RichHtmlArticle from '../../Components/RichHtmlArticle';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

const CATALOG_PAGE_KEYS = ['enseres', 'insignia-cofradia'];

export default function PatrimonioShow({ page }) {
    const { locale = 'es' } = usePage().props;
    const showCatalog = CATALOG_PAGE_KEYS.includes(page.key) && page.items_catalog;

    return (
        <MainLayout>
            <Head title={page.title} />
            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-8 lg:px-12">
                    <article className={showCatalog ? 'max-w-6xl' : 'max-w-4xl'}>
                        <RichHtmlArticle html={page.content_html} locale={locale} />
                        {showCatalog ? <PatrimonioEnseresCatalog catalog={page.items_catalog} locale={locale} /> : null}
                        {page.gallery?.length > 0 ? (
                            <PatrimonioPasoGallery images={page.gallery} locale={locale} />
                        ) : null}
                    </article>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
