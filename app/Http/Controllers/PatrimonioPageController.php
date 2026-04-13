<?php

namespace App\Http\Controllers;

use App\Models\PatrimonioItem;
use App\Models\PatrimonioItemCategory;
use App\Models\PatrimonioPage;
use App\Support\RichContentPresenter;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PatrimonioPageController extends Controller
{
    public function show(string $locale, string $key): Response
    {
        $page = PatrimonioPage::query()->where('key', $key)->firstOrFail();

        $rawContent = $page->content[$locale] ?? $page->content['es'] ?? null;

        return Inertia::render('Patrimonio/Show', [
            'page' => [
                'key' => $page->key,
                'title' => $page->title[$locale] ?? $page->title['es'] ?? '',
                'content_html' => RichContentPresenter::toHtml($rawContent),
                'items_catalog' => in_array($key, ['enseres', 'insignia-cofradia'], true)
                    ? $this->patrimonioCatalogPayload($key, $locale)
                    : null,
                'gallery' => $this->patrimonioGalleryPayload($page),
            ],
        ]);
    }

    /**
     * @return list<string>
     */
    private function patrimonioGalleryPayload(PatrimonioPage $page): array
    {
        if (! in_array($page->key, ['paso-cristo-perdon', 'paso-virgen-salud'], true)) {
            return [];
        }

        return collect($page->gallery ?? [])
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->values()
            ->all();
    }

    /**
     * @return array{categories: list<array{id: int, label: string}>, items: list<array<string, mixed>>}
     */
    private function patrimonioCatalogPayload(string $sectionKey, string $locale): array
    {
        $categories = PatrimonioItemCategory::query()
            ->where('section_key', $sectionKey)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $items = PatrimonioItem::query()
            ->where('section_key', $sectionKey)
            ->where('is_published', true)
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return [
            'categories' => $categories
                ->map(fn (PatrimonioItemCategory $c): array => [
                    'id' => $c->id,
                    'label' => (string) (data_get($c->name, $locale) ?? data_get($c->name, 'es') ?? ''),
                ])
                ->values()
                ->all(),
            'items' => $items
                ->map(fn (PatrimonioItem $item): array => $item->toPublicPayload($locale))
                ->values()
                ->all(),
        ];
    }
}
