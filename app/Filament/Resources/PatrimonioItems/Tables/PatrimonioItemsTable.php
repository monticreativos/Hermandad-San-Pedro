<?php

namespace App\Filament\Resources\PatrimonioItems\Tables;

use App\Models\PatrimonioItemCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PatrimonioItemsTable
{
    public static function configure(Table $table, string $sectionKey = 'enseres'): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->formatStateUsing(fn (mixed $state): string => data_get($state, 'es', '-')),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->formatStateUsing(fn (mixed $state): string => is_array($state) ? data_get($state, 'es', '—') : '—'),
                TextColumn::make('year')
                    ->label('Año')
                    ->placeholder('—'),
                TextColumn::make('author')
                    ->label('Autor')
                    ->limit(30)
                    ->placeholder('—'),
                IconColumn::make('is_published')
                    ->label('Web')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 2,
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('patrimonio_item_category_id')
                    ->label('Categoría')
                    ->options(fn (): array => PatrimonioItemCategory::query()
                        ->where('section_key', $sectionKey)
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->get()
                        ->mapWithKeys(fn (PatrimonioItemCategory $c): array => [
                            $c->id => data_get($c->name, 'es', (string) $c->id),
                        ])
                        ->all()),
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
