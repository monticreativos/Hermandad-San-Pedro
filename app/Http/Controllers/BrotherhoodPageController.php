<?php

namespace App\Http\Controllers;

use App\Models\BrotherhoodPage;
use App\Support\RichContentPresenter;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BrotherhoodPageController extends Controller
{
    public function show(string $locale, string $key): Response
    {
        $page = BrotherhoodPage::query()->where('key', $key)->firstOrFail();

        $rawContent = $page->content[$locale] ?? $page->content['es'] ?? null;

        return Inertia::render('Brotherhood/Show', [
            'page' => [
                'key' => $page->key,
                'title' => $page->title[$locale] ?? $page->title['es'] ?? '',
                'content_html' => RichContentPresenter::toHtml($rawContent),
                'legal_documents' => $this->legalDocumentsPayload($page, $locale),
                'government_board' => $this->governmentBoardPayload($page, $locale),
            ],
        ]);
    }

    /**
     * @return array{members: list<array{name: string, role: string, photo_url: string|null}>, past_mayors: list<array{name: string, period: string}>}|null
     */
    private function governmentBoardPayload(BrotherhoodPage $page, string $locale): ?array
    {
        if ($page->key !== 'junta-gobierno') {
            return null;
        }

        $gb = $page->government_board;
        if (! is_array($gb)) {
            return [
                'members' => [],
                'past_mayors' => [],
            ];
        }

        $members = [];
        foreach ($gb['members'] ?? [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $photo = $row['photo'] ?? null;
            $photo = is_string($photo) && $photo !== '' ? $photo : null;
            $roleKey = $locale === 'en' ? 'role_en' : 'role_es';
            $role = trim((string) ($row[$roleKey] ?? $row['role_es'] ?? ''));

            $members[] = [
                'name' => $name,
                'role' => $role,
                'photo_url' => $photo ? Storage::disk('public')->url($photo) : null,
            ];
        }

        $pastMayors = [];
        foreach ($gb['past_mayors'] ?? [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $pastMayors[] = [
                'name' => $name,
                'period' => trim((string) ($row['period'] ?? '')),
            ];
        }

        return [
            'members' => $members,
            'past_mayors' => $pastMayors,
        ];
    }

    /**
     * @return list<array{slug: string, title: string, file_path: string|null, url: string|null}>
     */
    private function legalDocumentsPayload(BrotherhoodPage $page, string $locale): array
    {
        if ($page->key !== 'reglas-reglamentos') {
            return [];
        }

        $paths = $page->legal_documents ?? [];
        $definitions = [
            [
                'slug' => 'estatutos',
                'title_es' => 'Estatutos de la Hermandad',
                'title_en' => 'Brotherhood Statutes',
            ],
            [
                'slug' => 'reglamento_interno',
                'title_es' => 'Régimen interno',
                'title_en' => 'Internal regulations',
            ],
            [
                'slug' => 'estatuto_base_diocesano',
                'title_es' => 'Estatuto base diocesano',
                'title_en' => 'Diocesan base statute',
            ],
        ];

        $out = [];
        foreach ($definitions as $def) {
            $path = $paths[$def['slug']] ?? null;
            $path = is_string($path) && $path !== '' ? $path : null;
            $out[] = [
                'slug' => $def['slug'],
                'title' => $locale === 'en' ? $def['title_en'] : $def['title_es'],
                'file_path' => $path,
                'url' => $path ? Storage::disk('public')->url($path) : null,
            ];
        }

        return $out;
    }
}
