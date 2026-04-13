function initialsFromName(name) {
    if (!name || typeof name !== 'string') return '?';
    const parts = name.trim().split(/\s+/).filter(Boolean);
    if (parts.length === 0) return '?';
    if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

export default function BrotherhoodJuntaBoard({ members = [], pastMayors = [], locale = 'es' }) {
    const labels =
        locale === 'en'
            ? {
                  teamTitle: 'Governing board members',
                  pastTitle: 'Past Hermano Mayor office-holders',
                  period: 'Term',
              }
            : {
                  teamTitle: 'Composición de la Junta de Gobierno',
                  pastTitle: 'Hermanos mayores históricos',
                  period: 'Mandato',
              };

    return (
        <div className="not-prose mt-10 space-y-14">
            {members.length > 0 ? (
                <section aria-labelledby="junta-team-heading">
                    <h2 id="junta-team-heading" className="text-xl font-bold text-[#4b1f6f] sm:text-2xl">
                        {labels.teamTitle}
                    </h2>
                    <ul className="mt-8 grid list-none grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        {members.map((m, idx) => (
                            <li key={`${m.name}-${idx}`}>
                                <div className="flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:border-[#4b1f6f]/25 hover:shadow-md">
                                    <div className="relative aspect-[4/5] w-full bg-zinc-100">
                                        {m.photo_url ? (
                                            <img
                                                src={m.photo_url}
                                                alt={m.name}
                                                className="h-full w-full object-cover object-top"
                                                loading="lazy"
                                                decoding="async"
                                            />
                                        ) : (
                                            <div
                                                className="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#4b1f6f]/12 to-[#4b1f6f]/5 text-3xl font-bold tracking-wide text-[#4b1f6f] sm:text-4xl"
                                                aria-hidden
                                            >
                                                {initialsFromName(m.name)}
                                            </div>
                                        )}
                                    </div>
                                    <div className="flex flex-1 flex-col gap-1 p-4 sm:p-5">
                                        {m.role ? (
                                            <p className="text-xs font-semibold uppercase tracking-wide text-[#4b1f6f]">{m.role}</p>
                                        ) : null}
                                        <p className="text-base font-semibold leading-snug text-zinc-900 sm:text-lg">{m.name}</p>
                                    </div>
                                </div>
                            </li>
                        ))}
                    </ul>
                </section>
            ) : null}

            {pastMayors.length > 0 ? (
                <section className="border-t border-zinc-200 pt-12" aria-labelledby="junta-past-heading">
                    <h2 id="junta-past-heading" className="text-xl font-bold text-[#4b1f6f] sm:text-2xl">
                        {labels.pastTitle}
                    </h2>
                    <ul className="mt-6 space-y-3">
                        {pastMayors.map((row, idx) => (
                            <li
                                key={`${row.name}-${row.period}-${idx}`}
                                className="flex flex-col gap-1 rounded-xl border border-zinc-200 bg-zinc-50/80 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4"
                            >
                                <span className="font-semibold text-zinc-900">{row.name}</span>
                                <span className="shrink-0 text-sm font-medium text-zinc-600 sm:text-right">
                                    <span className="sr-only">{labels.period}: </span>
                                    {row.period || '—'}
                                </span>
                            </li>
                        ))}
                    </ul>
                </section>
            ) : null}
        </div>
    );
}
