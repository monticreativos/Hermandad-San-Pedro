import { Head, usePage } from '@inertiajs/react';
import BrotherhoodJuntaBoard from '../../Components/BrotherhoodJuntaBoard';
import BrotherhoodLegalDocuments from '../../Components/BrotherhoodLegalDocuments';
import RichHtmlArticle from '../../Components/RichHtmlArticle';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

export default function BrotherhoodShow({ page }) {
    const { locale = 'es' } = usePage().props;

    return (
        <MainLayout>
            <Head title={page.title} />
            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-8 lg:px-12">
                    <article
                        className={
                            page.key === 'heraldica-simbolos'
                                ? 'max-w-4xl brotherhood-article--heraldica'
                                : page.key === 'junta-gobierno'
                                  ? 'max-w-6xl'
                                  : 'max-w-4xl'
                        }
                    >
                        <RichHtmlArticle html={page.content_html} locale={locale} />
                        {page.key === 'junta-gobierno' && page.government_board ? (
                            <BrotherhoodJuntaBoard
                                members={page.government_board.members ?? []}
                                pastMayors={page.government_board.past_mayors ?? []}
                                locale={locale}
                            />
                        ) : null}
                        {page.key === 'reglas-reglamentos' && page.legal_documents?.length > 0 ? (
                            <BrotherhoodLegalDocuments items={page.legal_documents} locale={locale} />
                        ) : null}
                    </article>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
