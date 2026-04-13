<?php

namespace App\Support;

/**
 * Limpia claves heredadas del carrusel (encuadre/zoom) que ya no se usan en la web.
 *
 * @phpstan-type HeroSlide array<string, mixed>
 */
final class HeroSlideNormalizer
{
    private const LEGACY_KEYS = ['focus_x', 'focus_y', 'focus_zoom', 'object_position'];

    /**
     * @param  HeroSlide  $slide
     * @return HeroSlide
     */
    public static function normalizeSlide(array $slide): array
    {
        foreach (self::LEGACY_KEYS as $key) {
            unset($slide[$key]);
        }

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
