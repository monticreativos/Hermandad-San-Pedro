<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Schemas\Components\View;

final class AdminRichEditor
{
    /**
     * Editor enriquecido del panel: barra ampliada, imágenes, columnas (grid), color y altura mayor.
     */
    public static function make(string $name): RichEditor
    {
        $cofradePalette = [
            'cofrade_morado' => TextColor::make('Morado cofrade', '#3b0764', '#e9d5ff'),
            'cofrade_dorado' => TextColor::make('Dorado', '#854d0e', '#fde68a'),
        ];

        return RichEditor::make($name)
            // Documento JSON en estado: coherente con la vista previa y con el editor (evita getHtml + escape).
            ->json()
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link', 'textColor'],
                ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'paragraph'],
                ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                ['blockquote', 'code', 'codeBlock', 'bulletList', 'orderedList', 'horizontalRule', 'highlight', 'lead', 'small', 'details'],
                ['table', 'attachFiles', 'grid', 'gridDelete'],
                ['undo', 'redo', 'clearFormatting'],
            ])
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('rich-content')
            ->fileAttachmentsVisibility('public')
            ->fileAttachmentsMaxSize(20480)
            ->resizableImages(true)
            ->customTextColors(true)
            ->textColors(array_merge(TextColor::getDefaults(), $cofradePalette))
            ->extraInputAttributes([
                'style' => 'min-height: 28rem;',
            ])
            ->helperText(
                'Imagen: sube con el clip; al seleccionarla debería mostrarse en el editor y podrás redimensionarla con las asas. '.
                'Filament solo alinea párrafos y títulos: pon el cursor en el párrafo que contiene la imagen (o en la línea de la imagen) y usa los botones Alinear de la barra.',
            )
            ->debounce(750);
    }

    /**
     * @return array<int, RichEditor|View>
     */
    public static function makeWithPreview(string $name, ?string $label = null, bool $required = false): array
    {
        $editor = self::make($name);

        if ($label !== null) {
            $editor->label($label);
        }

        if ($required) {
            $editor->required();
        }

        return [
            $editor,
            View::make('filament.forms.components.rich-content-preview')
                ->viewData(['previewField' => $name])
                ->columnSpanFull(),
        ];
    }
}
