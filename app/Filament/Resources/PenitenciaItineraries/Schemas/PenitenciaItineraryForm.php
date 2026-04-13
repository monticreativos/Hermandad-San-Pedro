<?php

namespace App\Filament\Resources\PenitenciaItineraries\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PenitenciaItineraryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->label('Año')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->unique(ignoreRecord: true),
                Tabs::make('Titulo del documento')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('title.es')
                                    ->label('Titulo (ES)')
                                    ->placeholder('ej. Itinerario para la estación de penitencia de 2026')
                                    ->maxLength(500),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title (EN)')
                                    ->placeholder('e.g. Itinerary for the 2026 penitential station')
                                    ->maxLength(500),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Puntos del recorrido')
                    ->description('Orden arrastrando filas. Marque «Hito» para resaltar filas tipo SALIDA, CARRERA OFICIAL, etc. Horarios en formato HH:MM.')
                    ->schema([
                        Repeater::make('stops')
                            ->label('Paradas')
                            ->schema([
                                TextInput::make('location_label')
                                    ->label('Lugar / tramo')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                TextInput::make('time_cruz_guia')
                                    ->label('Cruz guía')
                                    ->placeholder('18:30')
                                    ->maxLength(20),
                                TextInput::make('time_misterio')
                                    ->label('Paso de misterio')
                                    ->placeholder('18:40')
                                    ->maxLength(20),
                                TextInput::make('time_palio')
                                    ->label('Paso de palio')
                                    ->placeholder('18:55')
                                    ->maxLength(20),
                                Toggle::make('is_milestone')
                                    ->label('Hito (texto destacado)')
                                    ->default(false)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(
                                fn (array $state): ?string => $state['location_label'] ?? null,
                            )
                            ->defaultItems(0)
                            ->addActionLabel('Añadir parada')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
