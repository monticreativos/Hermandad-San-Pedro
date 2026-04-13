<?php

namespace App\Http\Middleware;

use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * Foco vertical del slider (Filament Select o valores legibles en español).
     *
     * @return 'top'|'center'|'bottom'
     */
    private static function normalizeHeroSlideFocal(mixed $raw): string
    {
        if ($raw === null || $raw === '') {
            return 'center';
        }

        if (! is_string($raw)) {
            return 'center';
        }

        $v = strtolower(trim($raw));
        if ($v === 'top' || str_contains($v, 'arriba')) {
            return 'top';
        }
        if ($v === 'bottom' || str_contains($v, 'abajo')) {
            return 'bottom';
        }

        return 'center';
    }

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'locale' => app()->getLocale(),
            'locales' => ['es', 'en'],
            'webSettings' => function (): ?array {
                $setting = WebSetting::query()->find(1);
                if ($setting === null) {
                    return null;
                }

                $data = $setting->toArray();

                if (! empty($data['hero_slides']) && is_array($data['hero_slides'])) {
                    $data['hero_slides'] = collect($data['hero_slides'])
                        ->map(function ($slide) {
                            if (! is_array($slide)) {
                                return $slide;
                            }
                            $raw = $slide['object_position'] ?? $slide['objectPosition'] ?? null;
                            $slide['object_position'] = self::normalizeHeroSlideFocal($raw);

                            return $slide;
                        })
                        ->values()
                        ->all();
                }

                return $data;
            },
            'translations' => function (): array {
                $locale = app()->getLocale();
                $path = lang_path("{$locale}.json");

                if (! File::exists($path)) {
                    return [];
                }

                return json_decode(File::get($path), true) ?? [];
            },
        ];
    }
}
