<?php

namespace App\Filament\Resources\PatrimonioItemCategories\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PatrimonioItemCategoryForm
{
    public static function configure(Schema $schema, string $sectionKey = 'enseres'): Schema
    {
        return $schema
            ->components([
                Hidden::make('section_key')
                    ->default($sectionKey),
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
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
