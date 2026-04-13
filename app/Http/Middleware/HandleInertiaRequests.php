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
            'webSettings' => fn () => WebSetting::query()->find(1),
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
