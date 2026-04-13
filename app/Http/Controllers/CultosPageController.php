<?php

namespace App\Http\Controllers;

use App\Models\CultosPage;
use App\Models\PenitenciaItinerary;
use App\Support\RichContentPresenter;
use Inertia\Inertia;
use Inertia\Response;

class CultosPageController extends Controller
{
    public function show(string $locale, string $key): Response
    {
        $page = CultosPage::query()->where('key', $key)->firstOrFail();

        $rawContent = $page->content[$locale] ?? $page->content['es'] ?? null;

        $itinerary = null;
        $itineraryYears = [];
        $itinerarySelectedYear = null;

        if ($key === 'estacion-penitencia-horario') {
            $itineraryYears = PenitenciaItinerary::query()
                ->orderByDesc('year')
                ->pluck('year')
                ->all();

            $requestedYear = (int) request()->query('ano', 0);
            $edition = null;
            if ($requestedYear > 0) {
                $edition = PenitenciaItinerary::query()->where('year', $requestedYear)->first();
            }
            if (! $edition && $itineraryYears !== []) {
                $edition = PenitenciaItinerary::query()->orderByDesc('year')->first();
            }

            if ($edition && is_array($edition->stops) && $edition->stops !== []) {
                $stops = array_values(array_filter(
                    $edition->stops,
                    fn (mixed $row): bool => is_array($row) && filled($row['location_label'] ?? null),
                ));

                if ($stops !== []) {
                    $itinerarySelectedYear = $edition->year;
                    $itinerary = [
                        'year' => $edition->year,
                        'title' => $edition->title[$locale] ?? $edition->title['es'] ?? '',
                        'stops' => $stops,
                    ];
                }
            }
        }

        return Inertia::render('Cultos/Show', [
            'page' => [
                'key' => $page->key,
                'title' => $page->title[$locale] ?? $page->title['es'] ?? '',
                'content_html' => RichContentPresenter::toHtml($rawContent),
            ],
            'itinerary' => $itinerary,
            'itineraryYears' => $itineraryYears,
            'itinerarySelectedYear' => $itinerarySelectedYear,
        ]);
    }
}
