import { Download, ExternalLink, FileText } from 'lucide-react';

export default function BrotherhoodLegalDocuments({ items = [], locale = 'es' }) {
    const labels =
        locale === 'en'
            ? {
                  sectionTitle: 'Official PDF documents',
                  view: 'View',
                  download: 'Download',
                  pending: 'Not yet available',
              }
            : {
                  sectionTitle: 'Documentos oficiales (PDF)',
                  view: 'Ver',
                  download: 'Descargar',
                  pending: 'Documento pendiente de publicación',
              };

    if (!items.length) {
        return null;
    }

    return (
        <div className="not-prose mt-10 border-t border-zinc-200 pt-10">
            <h2 className="text-xl font-bold text-[#4b1f6f] sm:text-2xl">{labels.sectionTitle}</h2>
            <ul className="mt-6 space-y-4">
                {items.map((doc) => (
                    <li key={doc.slug}>
                        <div className="flex flex-col gap-3 rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                            <div className="flex items-start gap-3">
                                <span className="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                                    <FileText size={20} aria-hidden />
                                </span>
                                <div>
                                    <p className="font-semibold text-zinc-900">{doc.title}</p>
                                    {!doc.url && (
                                        <p className="mt-1 text-sm text-zinc-500">{labels.pending}</p>
                                    )}
                                </div>
                            </div>
                            {doc.url ? (
                                <div className="flex flex-wrap gap-2 sm:justify-end">
                                    <a
                                        href={doc.url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="inline-flex items-center gap-2 rounded-full border border-[#4b1f6f]/30 bg-white px-4 py-2 text-sm font-semibold text-[#4b1f6f] transition hover:bg-[#4b1f6f]/5"
                                    >
                                        <ExternalLink size={16} aria-hidden />
                                        {labels.view}
                                    </a>
                                    <a
                                        href={doc.url}
                                        download
                                        className="inline-flex items-center gap-2 rounded-full bg-[#4b1f6f] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#3d1959]"
                                    >
                                        <Download size={16} aria-hidden />
                                        {labels.download}
                                    </a>
                                </div>
                            ) : null}
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}
