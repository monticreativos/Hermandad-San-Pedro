<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Forms\Components\AdminRichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Idiomas')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('name.es')
                                    ->label('Nombre (ES)')
                                    ->required()
                                    ->maxLength(255),
                                ...AdminRichEditor::makeWithPreview('description.es', 'Descripcion (ES)', true),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (EN)')
                                    ->required()
                                    ->maxLength(255),
                                ...AdminRichEditor::makeWithPreview('description.en', 'Description (EN)', true),
                            ]),
                    ])
                    ->columnSpanFull(),
                TextInput::make('location')
                    ->label('Ubicacion')
                    ->required()
                    ->maxLength(255),
                DateTimePicker::make('date_time')
                    ->label('Fecha y hora')
                    ->required(),
                Select::make('event_category_id')
                    ->label('Tipo de acto')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'slug',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('id'),
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => data_get($record->name, 'es', $record->slug),
                    )
                    ->required()
                    ->preload()
                    ->searchable(),
                FileUpload::make('gallery')
                    ->label('Galeria de imagenes')
                    ->helperText('Opcional. Se muestra en la web al abrir el acto.')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->disk('public')
                    ->directory('event-gallery')
                    ->columnSpanFull(),
            ]);
    }
}
