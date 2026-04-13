<?php

namespace App\Filament\Resources\EventCategories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class EventCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->label('Clave interna')
                    ->required()
                    ->maxLength(80)
                    ->alphaDash()
                    ->unique(ignoreRecord: true)
                    ->helperText('Solo minusculas, numeros y guiones. No la cambie si ya hay eventos clasificados asi.'),
                Tabs::make('Idiomas')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('name.es')
                                    ->label('Nombre (ES)')
                                    ->required()
                                    ->maxLength(120),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (EN)')
                                    ->required()
                                    ->maxLength(120),
                            ]),
                    ])
                    ->columnSpanFull(),
                ColorPicker::make('color')
                    ->label('Color en calendario')
                    ->hex()
                    ->required()
                    ->helperText('Se usa en los puntos del calendario y en la etiqueta del acto en la web.'),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->helperText('Si esta desactivado, no aparecera al crear nuevos eventos (los ya existentes no cambian).')
                    ->default(true),
            ]);
    }
}
