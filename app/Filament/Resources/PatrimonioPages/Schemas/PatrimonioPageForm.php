<?php

namespace App\Filament\Resources\PatrimonioPages\Schemas;

use App\Filament\Forms\Components\AdminRichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PatrimonioPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('key')
                    ->label('Seccion')
                    ->options([
                        'enseres' => 'Enseres',
                        'insignia-cofradia' => 'Insignia de la Cofradía',
                        'paso-cristo-perdon' => 'Paso Stmo. Cristo del Perdón',
                        'paso-virgen-salud' => 'Paso Ntra. Sra. de la Salud',
                    ])
                    ->required()
                    ->live()
                    ->unique(ignoreRecord: true),
                Section::make('Galería del paso (imágenes al pie de la página)')
                    ->description('Solo para las páginas de pasos (Cristo del Perdón y Virgen de la Salud). Reordene arrastrando; se muestran al final del artículo en la web.')
                    ->schema([
                        FileUpload::make('gallery')
                            ->label('Imágenes')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->disk('public')
                            ->directory('patrimonio/pasos')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => in_array($get('key'), ['paso-cristo-perdon', 'paso-virgen-salud'], true))
                    ->columnSpanFull(),
                Tabs::make('Idiomas')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('title.es')
                                    ->label('Titulo (ES)')
                                    ->required(),
                                ...AdminRichEditor::makeWithPreview('content.es', 'Contenido (ES)', true),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title (EN)')
                                    ->required(),
                                ...AdminRichEditor::makeWithPreview('content.en', 'Content (EN)', true),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
