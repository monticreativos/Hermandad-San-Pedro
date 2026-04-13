<?php

namespace App\Http\Controllers;

use App\Models\ObraSocialPage;
use App\Support\RichContentPresenter;
use Inertia\Inertia;
use Inertia\Response;

class ObraSocialPageController extends Controller
{
    public function show(string $locale, string $key): Response
    {
        $page = ObraSocialPage::query()->where('key', $key)->firstOrFail();

        $rawContent = $page->content[$locale] ?? $page->content['es'] ?? null;

        return Inertia::render('ObraSocial/Show', [
            'page' => [
                'key' => $page->key,
                'title' => $page->title[$locale] ?? $page->title['es'] ?? '',
                'content_html' => RichContentPresenter::toHtml($rawContent),
                'charity_contact' => $this->charityContactForLocale($page, $locale),
            ],
        ]);
    }

    /**
     * @return array{person_name: string, role: string, schedule: string, location: string, phone: string, email: string}|null
     */
    private function charityContactForLocale(ObraSocialPage $page, string $locale): ?array
    {
        if ($page->key !== 'diputacion-caridad') {
            return null;
        }

        $c = $page->charity_contact;
        if (! is_array($c)) {
            return null;
        }

        $role = $c['role'] ?? [];
        $schedule = $c['schedule'] ?? [];
        $location = $c['location'] ?? [];

        return [
            'person_name' => is_string($c['person_name'] ?? null) ? $c['person_name'] : '',
            'role' => is_array($role) ? (string) ($role[$locale] ?? $role['es'] ?? '') : '',
            'schedule' => is_array($schedule) ? (string) ($schedule[$locale] ?? $schedule['es'] ?? '') : '',
            'location' => is_array($location) ? (string) ($location[$locale] ?? $location['es'] ?? '') : '',
            'phone' => is_string($c['phone'] ?? null) ? trim($c['phone']) : '',
            'email' => is_string($c['email'] ?? null) ? trim($c['email']) : '',
        ];
    }
}
