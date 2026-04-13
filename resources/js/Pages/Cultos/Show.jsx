import { Head, usePage } from '@inertiajs/react';
import PenitenciaItinerarySection from '../../Components/PenitenciaItinerarySection';
import RichHtmlArticle from '../../Components/RichHtmlArticle';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

export default function CultosShow({ page, itinerary, itineraryYears, itinerarySelectedYear }) {
    const { locale = 'es' } = usePage().props;
    const showItineraryBlock = page.key === 'estacion-penitencia-horario';

    return (
        <MainLayout>
            <Head title={page.title} />
            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-8 lg:px-12">
                    <article className="max-w-5xl">
                        <RichHtmlArticle html={page.content_html} locale={locale} />
                        {showItineraryBlock ? (
                            <PenitenciaItinerarySection
                                itinerary={itinerary}
                                itineraryYears={itineraryYears ?? []}
                                itinerarySelectedYear={itinerarySelectedYear}
                                cultosKey={page.key}
                            />
                        ) : null}
                    </article>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
