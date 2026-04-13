<?php

namespace App\Models;

use App\Support\RichContentPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'date_time',
        'event_category_id',
        'gallery',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'date_time' => 'datetime',
            'gallery' => 'array',
        ];
    }

    /**
     * @return BelongsTo<EventCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicPayload(string $locale): array
    {
        $gallery = collect($this->gallery ?? [])
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->values()
            ->all();

        $category = $this->category;
        $fallback = [
            'slug' => 'otros',
            'label' => '—',
            'color' => '#a1a1aa',
        ];

        $categoryPayload = $category
            ? [
                'slug' => $category->slug,
                'label' => data_get($category->name, $locale) ?? data_get($category->name, 'es') ?? $category->slug,
                'color' => $category->color ?: '#a1a1aa',
            ]
            : $fallback;

        $rawDescription = data_get($this->description, $locale) ?? data_get($this->description, 'es');

        return [
            'id' => $this->id,
            'name' => data_get($this->name, $locale) ?? data_get($this->name, 'es') ?? '',
            'description' => RichContentPresenter::toHtml($rawDescription),
            'location' => $this->location,
            'date_time' => $this->date_time?->toIso8601String(),
            'category' => $categoryPayload,
            'gallery' => $gallery,
        ];
    }
}
