<?php

namespace App\Filament\Resources\BrotherhoodPages\Schemas;

use App\Filament\Forms\Components\AdminRichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BrotherhoodPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('key')
                    ->label('Seccion')
                    ->options([
                        'fines' => 'Fines de la Hermandad',
                        'historia' => 'Historia de la Hermandad',
                        'heraldica-simbolos' => 'Heráldica y símbolos',
                        'reglas-reglamentos' => 'Reglas y Reglamentos',
                        'junta-gobierno' => 'Junta de Gobierno',
                    ])
                    ->required()
                    ->live()
                    ->unique(ignoreRecord: true),
                Section::make('Documentos legales (PDF)')
                    ->description('Solo aplica a la seccion Reglas y Reglamentos. Suba aqui los PDF oficiales; la web mostrara enlaces de consulta y descarga.')
                    ->schema([
                        FileUpload::make('legal_documents.estatutos')
                            ->label('Estatutos de la Hermandad')
                            ->disk('public')
                            ->directory('brotherhood/legal')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        FileUpload::make('legal_documents.reglamento_interno')
                            ->label('Regimen interno')
                            ->disk('public')
                            ->directory('brotherhood/legal')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        FileUpload::make('legal_documents.estatuto_base_diocesano')
                            ->label('Estatuto base diocesano')
                            ->disk('public')
                            ->directory('brotherhood/legal')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('key') === 'reglas-reglamentos')
                    ->columnSpanFull(),
                Section::make('Junta de Gobierno (equipo y archivo histórico)')
                    ->description('Solo para la sección Junta de Gobierno: miembros actuales con foto y cargos; hermanos mayores pasados con mandato (ej. 2000-2004).')
                    ->schema([
                        Repeater::make('government_board.members')
                            ->label('Miembros de la Junta actual')
                            ->schema([
                                TextInput::make('role_es')
                                    ->label('Cargo (ES)')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('role_en')
                                    ->label('Cargo (EN)')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('name')
                                    ->label('Nombre completo')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                FileUpload::make('photo')
                                    ->label('Fotografia')
                                    ->image()
                                    ->disk('public')
                                    ->directory('brotherhood/junta')
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->downloadable()
                                    ->openable()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->reorderable()
                            ->addActionLabel('Añadir miembro')
                            ->columnSpanFull(),
                        Repeater::make('government_board.past_mayors')
                            ->label('Hermanos mayores históricos')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre completo')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('period')
                                    ->label('Mandato (ej. 2000-2004)')
                                    ->required()
                                    ->maxLength(64)
                                    ->placeholder('2000-2004'),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->reorderable()
                            ->addActionLabel('Añadir hermano mayor')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('key') === 'junta-gobierno')
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
