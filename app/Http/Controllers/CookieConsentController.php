<?php

namespace App\Http\Controllers;

use App\Models\CookieConsentLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CookieConsentController extends Controller
{
    public function store(Request $request, string $locale): JsonResponse
    {
        $data = $request->validate([
            'consent_uuid' => ['nullable', 'uuid'],
            'action' => ['required', 'in:accept_all,reject_all,save_preferences,withdraw'],
            'source' => ['nullable', 'in:banner,settings,footer,modal'],
            'consent_version' => ['nullable', 'string', 'max:32'],
            'preferences' => ['required', 'array'],
            'preferences.necessary' => ['required', 'boolean'],
            'preferences.analytics' => ['required', 'boolean'],
            'preferences.marketing' => ['required', 'boolean'],
            'preferences.personalization' => ['required', 'boolean'],
            'page_url' => ['nullable', 'url', 'max:1024'],
        ]);

        $preferences = $data['preferences'];
        $consentUuid = $data['consent_uuid'] ?? (string) Str::uuid();
        $appKey = config('app.key', 'app-key');
        $ipHash = $request->ip() ? hash_hmac('sha256', (string) $request->ip(), $appKey) : null;

        CookieConsentLog::query()->create([
            'consent_uuid' => $consentUuid,
            'user_id' => $request->user()?->id,
            'locale' => $locale,
            'consent_version' => $data['consent_version'] ?? (string) config('cookie-consent.version', '2026-04'),
            'source' => $data['source'] ?? 'banner',
            'action' => $data['action'],
            'preferences' => $preferences,
            'necessary' => true,
            'analytics' => (bool) $preferences['analytics'],
            'marketing' => (bool) $preferences['marketing'],
            'personalization' => (bool) $preferences['personalization'],
            'ip_hash' => $ipHash,
            'user_agent' => $request->userAgent(),
            'page_url' => $data['page_url'] ?? $request->fullUrl(),
            'consented_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'consent_uuid' => $consentUuid,
        ]);
    }
}
