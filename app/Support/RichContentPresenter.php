<?php

namespace App\Support;

use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Throwable;

final class RichContentPresenter
{
    /**
     * Convierte contenido del RichEditor (JSON TipTap), HTML exportado o texto plano legado a HTML seguro.
     */
    public static function toHtml(mixed $content): string
    {
        if ($content === null || $content === '') {
            return '';
        }

        if (is_array($content)) {
            if (($content['type'] ?? '') === 'doc' && empty($content['content'])) {
                return '';
            }

            return self::renderRichContent($content);
        }

        if (! is_string($content)) {
            return '';
        }

        $trimmed = trim($content);

        if ($trimmed === '') {
            return '';
        }

        $decoded = json_decode($trimmed, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && ($decoded['type'] ?? null) === 'doc') {
            return self::renderRichContent($decoded);
        }

        // Export HTML del RichEditor (getHtml) u otro HTML: parsear con TipTap, no escapar como texto.
        if (str_contains($trimmed, '<') && preg_match('/<\s*[a-z][\s\S]*>/i', $trimmed)) {
            return self::renderRichContent($trimmed);
        }

        $paragraphs = array_filter(
            array_map(trim(...), preg_split('/\r\n|\r|\n/', $trimmed) ?: []),
        );

        if ($paragraphs === []) {
            return '';
        }

        return collect($paragraphs)
            ->map(fn (string $p): string => '<p>'.e($p).'</p>')
            ->implode('');
    }

    /**
     * Texto plano para extractos / meta (sin HTML).
     */
    public static function toPlainText(mixed $content): string
    {
        if ($content === null || $content === '') {
            return '';
        }

        if (is_string($content)) {
            $trimmed = trim($content);
            $decoded = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && ($decoded['type'] ?? null) === 'doc') {
                return self::plainFromTipTap($decoded);
            }

            if (str_contains($trimmed, '<') && preg_match('/<\s*[a-z][\s\S]*>/i', $trimmed)) {
                return self::plainFromTipTap($trimmed);
            }

            return trim(strip_tags($content));
        }

        if (! is_array($content)) {
            return '';
        }

        return self::plainFromTipTap($content);
    }

    /**
     * @param  array<string, mixed>|string  $content
     */
    private static function renderRichContent(array|string $content): string
    {
        try {
            return RichContentRenderer::make($content)
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsVisibility('public')
                ->toHtml();
        } catch (Throwable $e) {
            report($e);

            return '';
        }
    }

    /**
     * @param  array<string, mixed>|string  $content
     */
    private static function plainFromTipTap(array|string $content): string
    {
        try {
            return trim(
                RichContentRenderer::make($content)
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsVisibility('public')
                    ->toText(),
            );
        } catch (Throwable $e) {
            report($e);

            return '';
        }
    }
}
