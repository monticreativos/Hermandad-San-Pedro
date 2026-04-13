<?php

namespace App\Filament\Resources\PenitenciaItineraries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenitenciaItinerariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')
                    ->label('Año')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Titulo (ES)')
                    ->formatStateUsing(fn (mixed $state): string => data_get($state, 'es', '—')),
                TextColumn::make('stops')
                    ->label('Paradas')
                    ->formatStateUsing(function (mixed $state): string {
                        if (! is_array($state)) {
                            return '0';
                        }

                        return (string) count($state);
                    }),
            ])
            ->defaultSort('year', 'desc')
            ->filters([])
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
