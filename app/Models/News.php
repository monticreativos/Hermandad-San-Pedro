<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'related_topics',
        'image_path',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'content' => 'array',
            'related_topics' => 'array',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (News $news): void {
            if (filled($news->slug)) {
                return;
            }

            $title = $news->title;

            if (is_string($title)) {
                $title = json_decode($title, true);
            }

            $spanishTitle = data_get($title, 'es');

            if (blank($spanishTitle)) {
                return;
            }

            $news->slug = static::generateUniqueSlug($spanishTitle);
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
