<?php

namespace App\Filament\Resources\ObraSocialPages\Schemas;

use App\Filament\Forms\Components\AdminRichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ObraSocialPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('key')
                    ->label('Seccion')
                    ->options([
                        'labor-asistencial' => 'Labor Asistencial',
                        'diputacion-caridad' => 'Diputación de Caridad',
                        'obra-asistencial' => 'Obra asistencial',
                    ])
                    ->required()
                    ->live()
                    ->unique(ignoreRecord: true),
                Section::make('Datos de atención de Caridad')
                    ->description('Visible solo en la web en la página Diputación de Caridad: responsable, horario, lugar y contacto.')
                    ->schema([
                        TextInput::make('charity_contact.person_name')
                            ->label('Nombre y apellidos')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('charity_contact.role.es')
                            ->label('Cargo (ES)')
                            ->maxLength(255),
                        TextInput::make('charity_contact.role.en')
                            ->label('Cargo (EN)')
                            ->maxLength(255),
                        Textarea::make('charity_contact.schedule.es')
                            ->label('Horario de atención (ES)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('charity_contact.schedule.en')
                            ->label('Opening hours / schedule (EN)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('charity_contact.location.es')
                            ->label('Lugar de atención (ES)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('charity_contact.location.en')
                            ->label('Location (EN)')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('charity_contact.phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(64),
                        TextInput::make('charity_contact.email')
                            ->label('Correo electrónico')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('key') === 'diputacion-caridad')
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
