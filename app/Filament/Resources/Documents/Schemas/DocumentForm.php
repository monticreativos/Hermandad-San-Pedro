<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Document;
use App\Models\DocumentCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('document_category_id')
                    ->label('Categoria')
                    ->options(fn (): array => DocumentCategory::query()
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->get()
                        ->mapWithKeys(fn (DocumentCategory $c): array => [
                            $c->id => data_get($c->name, 'es', 'Sin nombre'),
                        ])
                        ->all())
                    ->required()
                    ->searchable(),
                Tabs::make('Idiomas')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('title.es')
                                    ->label('Titulo (ES)')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title (EN)')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
                FileUpload::make('file_path')
                    ->label('Archivo')
                    ->disk('public')
                    ->directory('documents')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->downloadable()
                    ->openable()
                    ->maxSize(20480)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(fn (): int => (int) Document::query()->max('sort_order') + 1)
                    ->required(),
                Toggle::make('is_published')
                    ->label('Publicado')
                    ->default(true),
            ]);
    }
}
