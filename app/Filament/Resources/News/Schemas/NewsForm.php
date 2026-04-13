<?php

namespace App\Filament\Resources\News\Schemas;

use App\Filament\Forms\Components\AdminRichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Idiomas')
                    ->tabs([
                        Tab::make('Español')
                            ->schema([
                                TextInput::make('title.es')
                                    ->label('Titulo (ES)')
                                    ->required()
                                    ->maxLength(255),
                                ...AdminRichEditor::makeWithPreview('content.es', 'Contenido (ES)', true),
                                TagsInput::make('related_topics.es')
                                    ->label('Temas relacionados (ES)'),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title (EN)')
                                    ->required()
                                    ->maxLength(255),
                                ...AdminRichEditor::makeWithPreview('content.en', 'Content (EN)', true),
                                TagsInput::make('related_topics.en')
                                    ->label('Related topics (EN)'),
                            ]),
                    ])
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('Se genera automaticamente desde el titulo en espanol al crear.')
                    ->disabledOn('create'),
                FileUpload::make('image_path')
                    ->label('Imagen')
                    ->image()
                    ->disk('public')
                    ->directory('news')
                    ->nullable(),
                Toggle::make('is_published')
                    ->label('Publicada')
                    ->default(false),
            ]);
    }
}
