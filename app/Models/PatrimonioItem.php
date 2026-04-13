<?php

namespace App\Models;

use App\Support\RichContentPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PatrimonioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'patrimonio_item_category_id',
        'name',
        'description',
        'year',
        'author',
        'gallery',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'gallery' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<PatrimonioItemCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PatrimonioItemCategory::class, 'patrimonio_item_category_id');
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

        $rawDescription = data_get($this->description, $locale) ?? data_get($this->description, 'es');

        $category = $this->category;

        return [
            'id' => $this->id,
            'name' => (string) (data_get($this->name, $locale) ?? data_get($this->name, 'es') ?? ''),
            'description_html' => RichContentPresenter::toHtml($rawDescription),
            'year' => $this->year ? (string) $this->year : null,
            'author' => $this->author ? (string) $this->author : null,
            'gallery' => $gallery,
            'category' => $category ? [
                'id' => $category->id,
                'label' => (string) (data_get($category->name, $locale) ?? data_get($category->name, 'es') ?? ''),
            ] : null,
        ];
    }
}
