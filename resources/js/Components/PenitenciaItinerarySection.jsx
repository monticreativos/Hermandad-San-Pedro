import { router, usePage } from '@inertiajs/react';
/* global route */

function YearSelect({ locale, cultosKey, years, selectedYear }) {
    if (!years?.length || years.length < 2) {
        return null;
    }

    return (
        <div className="mb-8 flex flex-wrap items-center gap-3">
            <label htmlFor="itinerary-year" className="text-sm font-semibold text-zinc-700">
                {locale === 'en' ? 'Year' : 'Año'}
            </label>
            <select
                id="itinerary-year"
                className="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-[#4b1f6f] focus:outline-none focus:ring-2 focus:ring-[#4b1f6f]/20"
                value={String(selectedYear ?? years[0] ?? '')}
                onChange={(e) => {
                    const y = e.target.value;
                    router.get(
                        route('cultos.show', {
                            locale,
                            key: cultosKey,
                            ...(y ? { ano: Number(y) } : {}),
                        }),
                        {},
                        { preserveScroll: true },
                    );
                }}
            >
                {years.map((y) => (
                    <option key={y} value={y}>
                        {y}
                    </option>
                ))}
            </select>
        </div>
    );
}

export default function PenitenciaItinerarySection({ itinerary, itineraryYears, itinerarySelectedYear, cultosKey }) {
    const page = usePage();
    const locale = page.props.locale ?? 'es';

    const labels =
        locale === 'en'
            ? {
                  cruz: 'Guide cross',
                  misterio: 'Mystery float',
                  palio: 'Palio float',
                  empty: '—',
                  noData: 'The itinerary for this year will be published soon.',
              }
            : {
                  cruz: 'Cruz guía',
                  misterio: 'Paso de misterio',
                  palio: 'Paso de palio',
                  empty: '—',
                  noData: 'El itinerario de este año se publicará próximamente.',
              };

    if (!itinerary?.stops?.length) {
        return (
            <div className="mt-10 border-t border-zinc-200 pt-10">
                <YearSelect
                    locale={locale}
                    cultosKey={cultosKey}
                    years={itineraryYears}
                    selectedYear={itinerarySelectedYear}
                />
                <p className="text-base leading-relaxed text-zinc-600">{labels.noData}</p>
            </div>
        );
    }

    const t = (v) => (v && String(v).trim() !== '' ? v : labels.empty);

    return (
        <div className="mt-10 border-t border-zinc-200 pt-10">
            <YearSelect
                locale={locale}
                cultosKey={cultosKey}
                years={itineraryYears}
                selectedYear={itinerarySelectedYear ?? itinerary.year}
            />

            {itinerary.title ? (
                <h2 className="font-serif text-2xl font-semibold tracking-tight text-[#2f4669] sm:text-3xl">
                    {itinerary.title}
                </h2>
            ) : (
                <h2 className="font-serif text-2xl font-semibold tracking-tight text-[#2f4669] sm:text-3xl">
                    {locale === 'en' ? 'Itinerary' : 'Itinerario'} {itinerary.year}
                </h2>
            )}

            {/* Móvil: tarjetas (sin tabla) */}
            <div className="mt-6 space-y-3 md:hidden">
                {itinerary.stops.map((row, idx) => (
                    <div
                        key={`${row.location_label}-${idx}`}
                        className={`rounded-xl border border-zinc-200 bg-zinc-50/80 p-4 ${
                            row.is_milestone ? 'border-[#4b1f6f]/35 bg-[#4b1f6f]/5' : ''
                        }`}
                    >
                        <p
                            className={`text-base ${row.is_milestone ? 'font-bold text-[#1f2b43]' : 'font-medium text-[#2f4669]'}`}
                        >
                            {row.location_label}
                        </p>
                        <dl className="mt-3 space-y-2 text-sm text-zinc-700">
                            <div className="flex justify-between gap-4">
                                <dt className="text-zinc-500">{labels.cruz}</dt>
                                <dd className="font-medium tabular-nums">{t(row.time_cruz_guia)}</dd>
                            </div>
                            <div className="flex justify-between gap-4">
                                <dt className="text-zinc-500">{labels.misterio}</dt>
                                <dd className="font-medium tabular-nums">{t(row.time_misterio)}</dd>
                            </div>
                            <div className="flex justify-between gap-4">
                                <dt className="text-zinc-500">{labels.palio}</dt>
                                <dd className="font-medium tabular-nums">{t(row.time_palio)}</dd>
                            </div>
                        </dl>
                    </div>
                ))}
            </div>

            {/* Escritorio: tabla */}
            <div className="mt-6 hidden overflow-x-auto rounded-xl border border-zinc-200 md:block">
                <table className="w-full min-w-[640px] border-collapse text-left text-sm">
                    <thead>
                        <tr className="border-b border-zinc-200 bg-zinc-100/90">
                            <th className="px-4 py-3 font-semibold text-[#1f2b43]">
                                {locale === 'en' ? 'Location' : 'Lugar / tramo'}
                            </th>
                            <th className="px-4 py-3 font-semibold text-[#1f2b43]">{labels.cruz}</th>
                            <th className="px-4 py-3 font-semibold text-[#1f2b43]">{labels.misterio}</th>
                            <th className="px-4 py-3 font-semibold text-[#1f2b43]">{labels.palio}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {itinerary.stops.map((row, idx) => (
                            <tr
                                key={`${row.location_label}-${idx}`}
                                className={`border-b border-zinc-100 last:border-0 ${
                                    row.is_milestone ? 'bg-[#4b1f6f]/[0.06]' : 'bg-white'
                                }`}
                            >
                                <td
                                    className={`px-4 py-3 text-zinc-900 ${row.is_milestone ? 'font-bold' : 'font-medium'}`}
                                >
                                    {row.location_label}
                                </td>
                                <td className="px-4 py-3 tabular-nums text-zinc-800">{t(row.time_cruz_guia)}</td>
                                <td className="px-4 py-3 tabular-nums text-zinc-800">{t(row.time_misterio)}</td>
                                <td className="px-4 py-3 tabular-nums text-zinc-800">{t(row.time_palio)}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
