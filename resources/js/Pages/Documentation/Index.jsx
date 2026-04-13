import { Head, usePage } from '@inertiajs/react';
import { Download } from 'lucide-react';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

const localized = (value, locale) => {
    if (!value || typeof value !== 'object') return '';
    return value[locale] ?? value.es ?? '';
};

const fileHref = (path) => {
    if (!path) return '#';
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }
    return `/storage/${path}`;
};

export default function DocumentationIndex({ documentCategories = [] }) {
    const { locale = 'es', translations = {} } = usePage().props;
    const t = (key, fallback) => translations[key] ?? fallback;

    const withDocs = documentCategories.filter((c) => (c.documents ?? []).length > 0);
    const isEmpty = withDocs.length === 0;

    return (
        <MainLayout>
            <Head title={t('documentation.title', locale === 'es' ? 'Documentacion' : 'Documentation')} />

            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <p className="max-w-2xl text-zinc-600">
                        {t('documentation.subtitle', '')}
                    </p>

                    {isEmpty ? (
                        <p className="mt-10 rounded-2xl border border-dashed border-zinc-200 bg-zinc-50 px-6 py-10 text-center text-zinc-600">
                            {t('documentation.empty', '')}
                        </p>
                    ) : (
                        <div className="mt-10 space-y-10">
                            {withDocs.map((category) => (
                                <div key={category.id}>
                                    <h2 className="text-xl font-bold text-[#4b1f6f] sm:text-2xl">
                                        {localized(category.name, locale)}
                                    </h2>
                                    <ul className="mt-4 space-y-3">
                                        {(category.documents ?? []).map((doc) => (
                                            <li key={doc.id}>
                                                <a
                                                    href={fileHref(doc.file_path)}
                                                    download
                                                    className="flex items-start gap-3 rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm transition hover:border-[#4b1f6f]/30 hover:shadow"
                                                >
                                                    <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                                                        <Download size={18} />
                                                    </span>
                                                    <span className="font-semibold text-zinc-900">
                                                        {localized(doc.title, locale)}
                                                    </span>
                                                    <span className="ml-auto hidden text-sm font-semibold text-[#4b1f6f] sm:inline">
                                                        {t('documentation.download', 'Descargar')}
                                                    </span>
                                                </a>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            ))}
                        </div>
                    )}
                </section>
            </PageTransition>
        </MainLayout>
    );
}
