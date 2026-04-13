<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * URL de imagen y focal para la vista previa del repeater hero_slides en Filament.
 */
final class HeroSlidePreview
{
    public static function resolveImageSrc(mixed $raw): ?string
    {
        if ($raw instanceof TemporaryUploadedFile) {
            try {
                return $raw->temporaryUrl();
            } catch (\Throwable) {
                return null;
            }
        }

        if (is_array($raw)) {
            foreach ($raw as $item) {
                $url = self::resolveImageSrc($item);
                if ($url !== null) {
                    return $url;
                }
            }

            return null;
        }

        if (! is_string($raw)) {
            return null;
        }

        $path = trim($raw);
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return url($path);
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @return array{fx: int, fy: int, zoom: float}
     */
    public static function focalFromGet(callable $get): array
    {
        $rawFx = $get('focus_x');
        $rawFy = $get('focus_y');
        $rawZoom = $get('focus_zoom');

        $fx = is_numeric($rawFx) ? (int) round((float) $rawFx) : 50;
        $fy = is_numeric($rawFy) ? (int) round((float) $rawFy) : 50;
        $zoomPct = is_numeric($rawZoom) ? (int) round((float) $rawZoom) : 100;

        $fx = max(0, min(100, $fx));
        $fy = max(0, min(100, $fy));
        $zoomPct = max(100, min(220, $zoomPct));

        return [
            'fx' => $fx,
            'fy' => $fy,
            'zoom' => $zoomPct / 100,
        ];
    }
}
