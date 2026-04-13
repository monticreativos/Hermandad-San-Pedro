<?php

namespace App\Support;

/**
 * Valores por defecto de encuadre del carrusel de portada (JSON hero_slides).
 */
final class HeroSlideNormalizer
{
    private static function legacyFocal(string $raw): string
    {
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
     * @param  array<string, mixed>  $slide
     * @return array<string, mixed>
     */
    public static function normalizeSlide(array $slide): array
    {
        $fx = isset($slide['focus_x']) && is_numeric($slide['focus_x'])
            ? (int) round((float) $slide['focus_x'])
            : null;
        $fy = isset($slide['focus_y']) && is_numeric($slide['focus_y'])
            ? (int) round((float) $slide['focus_y'])
            : null;
        $zoom = isset($slide['focus_zoom']) && is_numeric($slide['focus_zoom'])
            ? (int) round((float) $slide['focus_zoom'])
            : null;

        if ($fx === null) {
            $fx = 50;
        }
        if ($fy === null) {
            $focal = self::legacyFocal((string) ($slide['object_position'] ?? 'center'));
            $fy = match ($focal) {
                'top' => 12,
                'bottom' => 88,
                default => 50,
            };
        }
        if ($zoom === null || $zoom < 100) {
            $zoom = 100;
        }
        $zoom = min(220, max(100, $zoom));

        $slide['focus_x'] = max(0, min(100, $fx));
        $slide['focus_y'] = max(0, min(100, $fy));
        $slide['focus_zoom'] = $zoom;

        return $slide;
    }

    /**
     * @param  array<int, mixed>  $slides
     * @return array<int, mixed>
     */
    public static function normalizeSlides(?array $slides): array
    {
        if ($slides === null || $slides === []) {
            return [];
        }

        return collect($slides)
            ->map(function ($slide) {
                if (! is_array($slide)) {
                    return $slide;
                }

                return self::normalizeSlide($slide);
            })
            ->values()
            ->all();
    }
}
