<?php

namespace App\Filament\Resources\WebSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;

class HomePanelsForm
{
    public static function chapelSection(): Section
    {
        return Section::make('Tarjeta Capilla y misas (portada)')
            ->description('Titulo, bloques de contenido (arrastre las filas para ordenar) y boton inferior.')
            ->schema([
                Tabs::make('Titulo tarjeta')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('chapel_card_title.es')
                                    ->label('Titulo (ES)')
                                    ->maxLength(200),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('chapel_card_title.en')
                                    ->label('Title (EN)')
                                    ->maxLength(200),
                            ]),
                    ])
                    ->columnSpanFull(),
                self::blocksRepeater('chapel_blocks', 'Contenido', 'chapel'),
                Tabs::make('Boton capilla')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('chapel_footer.label.es')
                                    ->label('Texto boton (ES)')
                                    ->maxLength(120),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('chapel_footer.label.en')
                                    ->label('Button text (EN)')
                                    ->maxLength(120),
                            ]),
                    ])
                    ->columnSpanFull(),
                Select::make('chapel_footer.link_type')
                    ->label('Tipo de enlace del boton')
                    ->options([
                        'internal' => 'Ruta de la web',
                        'external' => 'URL externa',
                    ])
                    ->default('internal')
                    ->live(),
                Select::make('chapel_footer.route_name')
                    ->label('Pagina destino')
                    ->options(self::publicRouteOptions())
                    ->default('agenda.index')
                    ->visible(fn (Get $get): bool => ($get('chapel_footer.link_type') ?? 'internal') === 'internal'),
                TextInput::make('chapel_footer.external_url')
                    ->label('URL externa')
                    ->url()
                    ->maxLength(500)
                    ->visible(fn (Get $get): bool => ($get('chapel_footer.link_type') ?? 'internal') === 'external'),
            ])
            ->columns(1);
    }

    public static function donationSection(): Section
    {
        return Section::make('Tarjeta Donacion dorado (portada)')
            ->schema([
                Tabs::make('Titulo tarjeta donacion')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('donation_card_title.es')
                                    ->label('Titulo (ES)')
                                    ->maxLength(240),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('donation_card_title.en')
                                    ->label('Title (EN)')
                                    ->maxLength(240),
                            ]),
                    ])
                    ->columnSpanFull(),
                self::blocksRepeater('donation_blocks', 'Contenido', 'donation'),
                Tabs::make('Boton donacion')
                    ->tabs([
                        Tab::make('Espanol')
                            ->schema([
                                TextInput::make('donation_footer.label.es')
                                    ->label('Texto boton (ES)')
                                    ->maxLength(120),
                            ]),
                        Tab::make('Ingles')
                            ->schema([
                                TextInput::make('donation_footer.label.en')
                                    ->label('Button text (EN)')
                                    ->maxLength(120),
                            ]),
                    ])
                    ->columnSpanFull(),
                Select::make('donation_footer.link_type')
                    ->label('Tipo de enlace del boton')
                    ->options([
                        'internal' => 'Ruta de la web',
                        'external' => 'URL externa',
                    ])
                    ->default('internal')
                    ->live(),
                Select::make('donation_footer.route_name')
                    ->label('Pagina destino')
                    ->options(self::publicRouteOptions())
                    ->default('contact.index')
                    ->visible(fn (Get $get): bool => ($get('donation_footer.link_type') ?? 'internal') === 'internal'),
                TextInput::make('donation_footer.external_url')
                    ->label('URL externa')
                    ->url()
                    ->maxLength(500)
                    ->visible(fn (Get $get): bool => ($get('donation_footer.link_type') ?? 'internal') === 'external'),
            ])
            ->columns(1);
    }

    /**
     * @return array<string, string>
     */
    protected static function publicRouteOptions(): array
    {
        return [
            'home' => 'Inicio',
            'news.index' => 'Noticias',
            'agenda.index' => 'Agenda',
            'history.index' => 'Historia',
            'titulares.index' => 'Titulares',
            'contact.index' => 'Contacto',
            'member_application.create' => 'Hazte hermano',
            'documentation.index' => 'Documentacion',
            'shop.index' => 'Tienda de recuerdos',
        ];
    }

    protected static function blocksRepeater(string $name, string $label, string $requireTypeWhenTab): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->schema([
                Select::make('type')
                    ->label('Tipo de bloque')
                    ->options([
                        'heading' => 'Subtitulo (morado, en negrita)',
                        'paragraph' => 'Parrafo',
                        'callout' => 'Destacado (barra dorada)',
                        'image' => 'Imagen',
                    ])
                    ->required(fn (): bool => WebSettingForm::activeMainTab() === $requireTypeWhenTab)
                    ->live(),
                Textarea::make('body_es')
                    ->label('Texto (ES)')
                    ->rows(fn (Get $get): int => $get('type') === 'paragraph' ? 12 : 4)
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['heading', 'paragraph', 'callout'], true)),
                Textarea::make('body_en')
                    ->label('Text (EN)')
                    ->rows(fn (Get $get): int => $get('type') === 'paragraph' ? 12 : 4)
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['heading', 'paragraph', 'callout'], true)),
                FileUpload::make('image_path')
                    ->label('Imagen')
                    ->image()
                    ->disk('public')
                    ->directory('home-panels')
                    ->nullable()
                    ->visible(fn (Get $get): bool => $get('type') === 'image'),
                TextInput::make('alt_es')
                    ->label('Texto alternativo imagen (ES)')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => $get('type') === 'image'),
                TextInput::make('alt_en')
                    ->label('Image alt (EN)')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => $get('type') === 'image'),
            ])
            ->reorderableWithButtons()
            ->collapsible()
            ->collapsed()
            ->itemLabel(fn (array $state): ?string => match ($state['type'] ?? null) {
                'heading' => 'Subtitulo',
                'paragraph' => 'Parrafo',
                'callout' => 'Destacado',
                'image' => 'Imagen',
                default => 'Bloque',
            })
            ->columnSpanFull()
            ->defaultItems(0);
    }
}
