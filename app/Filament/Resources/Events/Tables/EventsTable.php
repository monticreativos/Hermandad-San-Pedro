<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->formatStateUsing(fn (mixed $state): string => data_get($state, 'es', '-'))
                    ->searchable(),
                TextColumn::make('location')
                    ->label('Ubicacion')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Tipo')
                    ->formatStateUsing(fn ($state) => data_get($state, 'es', '-')),
                TextColumn::make('date_time')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
