import { Head, usePage } from '@inertiajs/react';
import AgendaCalendar from '../../Components/AgendaCalendar';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

export default function AgendaIndex({ events = [] }) {
    const { locale = 'es' } = usePage().props;

    return (
        <MainLayout>
            <Head title={locale === 'es' ? 'Agenda' : 'Agenda'} />
            <PageTransition>
                <section className="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                    <AgendaCalendar
                        events={events}
                        variant="plain"
                        showHeading={false}
                    />
                </section>
            </PageTransition>
        </MainLayout>
    );
}
