<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CookieConsentLog extends Model
{
    protected $fillable = [
        'consent_uuid',
        'user_id',
        'locale',
        'consent_version',
        'source',
        'action',
        'preferences',
        'necessary',
        'analytics',
        'marketing',
        'personalization',
        'ip_hash',
        'user_agent',
        'page_url',
        'consented_at',
    ];

    protected function casts(): array
    {
        return [
            'preferences' => 'array',
            'necessary' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'personalization' => 'boolean',
            'consented_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
