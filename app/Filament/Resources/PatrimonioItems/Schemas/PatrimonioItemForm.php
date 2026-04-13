<?php

namespace App\Filament\Resources\PatrimonioItems\Schemas;

use App\Filament\Forms\Components\AdminRichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PatrimonioItemForm
{
    public static function configure(Schema $schema, string $sectionKey = 'enseres'): Schema
    {
        $galleryDirectory = 'patrimonio/'.$sectionKey;

        return $schema
            ->components([
                Hidden::make('section_key')
                    ->default($sectionKey),
                Select::make('patrimonio_item_category_id')
                    ->label('Categoría')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('section_key', $sectionKey)
                            ->orderBy('sort_order')
                            ->orderBy('id'),
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => data_get($record->name, 'es', (string) $record->id),
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('Sin categoría'),
                Tabs::make('Idiomas')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('name.es')
                                    ->label('Nombre (ES)')
                                    ->required()
                                    ->maxLength(255),
                                ...AdminRichEditor::makeWithPreview('description.es', 'Descripcion larga (ES)', true),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (EN)')
                                    ->required()
                                    ->maxLength(255),
                                ...AdminRichEditor::makeWithPreview('description.en', 'Long description (EN)', true),
                            ]),
                    ])
                    ->columnSpanFull(),
                TextInput::make('year')
                    ->label('Año / época')
                    ->maxLength(64)
                    ->placeholder('ej. 1990 o s. XVIII'),
                TextInput::make('author')
                    ->label('Autor o taller')
                    ->maxLength(255),
                FileUpload::make('gallery')
                    ->label('Galería de imágenes')
                    ->helperText('Una o varias imágenes; en la web se muestran como galería si hay más de una.')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->disk('public')
                    ->directory($galleryDirectory)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Orden en listado')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_published')
                    ->label('Publicado en la web')
                    ->default(true),
            ]);
    }
}
