import { Head, usePage } from '@inertiajs/react';
import ObraSocialCharityContactCard from '../../Components/ObraSocialCharityContactCard';
import RichHtmlArticle from '../../Components/RichHtmlArticle';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

export default function ObraSocialShow({ page }) {
    const { locale = 'es' } = usePage().props;

    return (
        <MainLayout>
            <Head title={page.title} />
            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-8 lg:px-12">
                    <article className="max-w-4xl">
                        {page.key === 'diputacion-caridad' && page.charity_contact ? (
                            <ObraSocialCharityContactCard contact={page.charity_contact} locale={locale} />
                        ) : null}
                        <RichHtmlArticle html={page.content_html} locale={locale} />
                    </article>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
