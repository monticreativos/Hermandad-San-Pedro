<?php

namespace App\Filament\Resources\WebSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class WebSettingForm
{
    /**
     * Pestaña principal activa (query ?seccion=). La validación «required» solo debe aplicar aquí.
     */
    public static function activeMainTab(): string
    {
        $tab = request()->query('seccion');
        if (! is_string($tab) || $tab === '') {
            return 'general';
        }

        $allowed = ['general', 'menu', 'slider', 'chapel', 'donation'];

        return in_array($tab, $allowed, true) ? $tab : 'general';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ajustes web')
                    ->id('web-settings-main-tabs')
                    ->persistTabInQueryString('seccion')
                    ->tabs([
                        Tab::make('General')
                            ->id('general')
                            ->schema([
                                Section::make('Cabecera Web')
                                    ->schema([
                                        Tabs::make('Idiomas')
                                            ->tabs([
                                                Tab::make('Espanol')
                                                    ->schema([
                                                        TextInput::make('brand_name.es')
                                                            ->label('Nombre marca (ES)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'general')
                                                            ->maxLength(100),
                                                        TextInput::make('cta_label.es')
                                                            ->label('Boton CTA (ES)')
                                                            ->maxLength(40),
                                                    ]),
                                                Tab::make('Ingles')
                                                    ->schema([
                                                        TextInput::make('brand_name.en')
                                                            ->label('Brand name (EN)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'general')
                                                            ->maxLength(100),
                                                        TextInput::make('cta_label.en')
                                                            ->label('CTA button (EN)')
                                                            ->maxLength(40),
                                                    ]),
                                            ])
                                            ->columnSpanFull(),
                                        TextInput::make('cta_url')
                                            ->label('URL del boton CTA')
                                            ->placeholder('/login')
                                            ->maxLength(255),
                                        TextInput::make('shop_url')
                                            ->label('URL tienda de recuerdos (externa)')
                                            ->placeholder('https://...')
                                            ->url()
                                            ->maxLength(500)
                                            ->helperText('Si se rellena, la pagina Tienda mostrara un boton para abrirla en nueva pestana.'),
                                    ]),
                            ]),
                        Tab::make('Menu principal')
                            ->id('menu')
                            ->schema([
                                Section::make('Menu principal')
                                    ->description('Arrastre las filas para cambiar el orden. Cada fila empieza plegada para ver la lista de un vistazo.')
                                    ->schema([
                                        Repeater::make('menu_items')
                                            ->label('Elementos del menu')
                                            ->schema([
                                                TextInput::make('route_name')
                                                    ->label('Nombre de ruta')
                                                    ->placeholder('news.index')
                                                    ->helperText('Desplegables: cultos.nav, patrimonio.nav y obra_social.nav (no son rutas Laravel).')
                                                    ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                TextInput::make('label_es')
                                                    ->label('Texto (ES)')
                                                    ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                TextInput::make('label_en')
                                                    ->label('Texto (EN)')
                                                    ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                TextInput::make('sort')
                                                    ->label('Orden')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                Toggle::make('is_active')
                                                    ->label('Activo')
                                                    ->default(true),
                                            ])
                                            ->defaultItems(0)
                                            ->reorderableWithButtons()
                                            ->columnSpanFull()
                                            ->collapsed(),
                                        Section::make('Submenu Hermandad (menu hamburguesa)')
                                            ->description('Enlaces a paginas internas (ruta brotherhood.show). La clave debe coincidir con la clave de la pagina en Hermandad.')
                                            ->schema([
                                                Repeater::make('brotherhood_submenu_items')
                                                    ->label('Enlaces')
                                                    ->schema([
                                                        TextInput::make('key')
                                                            ->label('Clave (slug)')
                                                            ->placeholder('fines')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu')
                                                            ->maxLength(120),
                                                        TextInput::make('label_es')
                                                            ->label('Texto (ES)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('label_en')
                                                            ->label('Texto (EN)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('sort')
                                                            ->label('Orden')
                                                            ->numeric()
                                                            ->default(1)
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        Toggle::make('is_active')
                                                            ->label('Activo')
                                                            ->default(true),
                                                    ])
                                                    ->defaultItems(0)
                                                    ->reorderableWithButtons()
                                                    ->columnSpanFull()
                                                    ->collapsed(),
                                            ])
                                            ->columnSpanFull(),
                                        Section::make('Submenu Cultos (cabecera y hamburguesa)')
                                            ->description('Clave = segmento URL en cultos.show (debe existir la pagina en Cultos). Deje la clave vacia en un elemento para usarlo solo como titulo de agrupacion con subenlaces. Si «Cultos internos» o «Cultos externos» tienen subenlaces, en la web no mostraran enlace propio (solo desplegable).')
                                            ->schema([
                                                Repeater::make('cultos_submenu_items')
                                                    ->label('Enlaces (nivel 1)')
                                                    ->schema([
                                                        TextInput::make('key')
                                                            ->label('Clave (opcional si solo agrupa)')
                                                            ->placeholder('internos')
                                                            ->maxLength(120),
                                                        TextInput::make('label_es')
                                                            ->label('Texto (ES)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('label_en')
                                                            ->label('Texto (EN)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('sort')
                                                            ->label('Orden')
                                                            ->numeric()
                                                            ->default(1)
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        Toggle::make('is_active')
                                                            ->label('Activo')
                                                            ->default(true),
                                                        Repeater::make('children')
                                                            ->label('Submenu (nivel 2)')
                                                            ->schema([
                                                                TextInput::make('key')
                                                                    ->label('Clave (vacío = agrupador)')
                                                                    ->maxLength(120),
                                                                TextInput::make('label_es')
                                                                    ->label('Texto (ES)')
                                                                    ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                                TextInput::make('label_en')
                                                                    ->label('Texto (EN)')
                                                                    ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                                TextInput::make('sort')
                                                                    ->label('Orden')
                                                                    ->numeric()
                                                                    ->default(1)
                                                                    ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                                Toggle::make('is_active')
                                                                    ->label('Activo')
                                                                    ->default(true),
                                                                Repeater::make('children')
                                                                    ->label('Subenlaces (nivel 3)')
                                                                    ->schema([
                                                                        TextInput::make('key')
                                                                            ->label('Clave')
                                                                            ->required(fn (): bool => self::activeMainTab() === 'menu')
                                                                            ->maxLength(120),
                                                                        TextInput::make('label_es')
                                                                            ->label('Texto (ES)')
                                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                                        TextInput::make('label_en')
                                                                            ->label('Texto (EN)')
                                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                                        TextInput::make('sort')
                                                                            ->label('Orden')
                                                                            ->numeric()
                                                                            ->default(1)
                                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                                        Toggle::make('is_active')
                                                                            ->label('Activo')
                                                                            ->default(true),
                                                                    ])
                                                                    ->defaultItems(0)
                                                                    ->reorderableWithButtons()
                                                                    ->collapsible()
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->defaultItems(0)
                                                            ->reorderableWithButtons()
                                                            ->collapsible()
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->defaultItems(0)
                                                    ->reorderableWithButtons()
                                                    ->columnSpanFull()
                                                    ->collapsed(),
                                            ])
                                            ->columnSpanFull(),
                                        Section::make('Submenu Patrimonio (cabecera y hamburguesa)')
                                            ->description('Enlaces bajo Patrimonio. Claves deben coincidir con las paginas en Patrimonio (ruta patrimonio.show).')
                                            ->schema([
                                                Repeater::make('patrimonio_submenu_items')
                                                    ->label('Enlaces')
                                                    ->schema([
                                                        TextInput::make('key')
                                                            ->label('Clave')
                                                            ->placeholder('enseres')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu')
                                                            ->maxLength(120),
                                                        TextInput::make('label_es')
                                                            ->label('Texto (ES)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('label_en')
                                                            ->label('Texto (EN)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('sort')
                                                            ->label('Orden')
                                                            ->numeric()
                                                            ->default(1)
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        Toggle::make('is_active')
                                                            ->label('Activo')
                                                            ->default(true),
                                                    ])
                                                    ->defaultItems(0)
                                                    ->reorderableWithButtons()
                                                    ->columnSpanFull()
                                                    ->collapsed(),
                                            ])
                                            ->columnSpanFull(),
                                        Section::make('Submenu Obra Social (cabecera y hamburguesa)')
                                            ->description('Enlaces bajo Obra Social (ruta obra_social.show). Sin carteles ni centro infantil.')
                                            ->schema([
                                                Repeater::make('obra_social_submenu_items')
                                                    ->label('Enlaces')
                                                    ->schema([
                                                        TextInput::make('key')
                                                            ->label('Clave')
                                                            ->placeholder('labor-asistencial')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu')
                                                            ->maxLength(120),
                                                        TextInput::make('label_es')
                                                            ->label('Texto (ES)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('label_en')
                                                            ->label('Texto (EN)')
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        TextInput::make('sort')
                                                            ->label('Orden')
                                                            ->numeric()
                                                            ->default(1)
                                                            ->required(fn (): bool => self::activeMainTab() === 'menu'),
                                                        Toggle::make('is_active')
                                                            ->label('Activo')
                                                            ->default(true),
                                                    ])
                                                    ->defaultItems(0)
                                                    ->reorderableWithButtons()
                                                    ->columnSpanFull()
                                                    ->collapsed(),
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Slider de portada')
                            ->id('slider')
                            ->schema([
                                Section::make('Slider de portada')
                                    ->description('Al subir la imagen se abre el editor (estilo recorte de avatar): puede arrastrar, hacer zoom y elegir proporcion 21:9, 16:9 o libre. La vista previa debajo refleja encuadre y zoom como en la web; ajuste fino con los tres controles.')
                                    ->schema([
                                        Repeater::make('hero_slides')
                                            ->label('Imagenes del slider')
                                            ->schema([
                                                FileUpload::make('image')
                                                    ->label('Imagen')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('hero-slides')
                                                    ->imageEditor()
                                                    ->imageEditorAspectRatioOptions([
                                                        '21/9',
                                                        '16/9',
                                                        null,
                                                    ])
                                                    ->imageEditorViewportWidth(1920)
                                                    ->imageEditorViewportHeight(640)
                                                    ->required(fn (): bool => self::activeMainTab() === 'slider')
                                                    ->live(debounce: 400)
                                                    ->partiallyRenderComponentsAfterStateUpdated(['heroSlidePreview']),
                                                View::make('filament.forms.components.hero-slide-preview')
                                                    ->key('heroSlidePreview')
                                                    ->columnSpanFull(),
                                                Slider::make('focus_x')
                                                    ->label('Encuadre horizontal (%)')
                                                    ->helperText('0 = izquierda, 50 = centro, 100 = derecha.')
                                                    ->range(0, 100)
                                                    ->default(50)
                                                    ->step(1)
                                                    ->live()
                                                    ->partiallyRenderComponentsAfterStateUpdated(['heroSlidePreview']),
                                                Slider::make('focus_y')
                                                    ->label('Encuadre vertical (%)')
                                                    ->helperText('0 = arriba, 50 = centro, 100 = abajo.')
                                                    ->range(0, 100)
                                                    ->default(50)
                                                    ->step(1)
                                                    ->live()
                                                    ->partiallyRenderComponentsAfterStateUpdated(['heroSlidePreview']),
                                                Slider::make('focus_zoom')
                                                    ->label('Zoom (%)')
                                                    ->helperText('100 = sin zoom; subir acerca el encuadre (desde el punto elegido arriba).')
                                                    ->range(100, 220)
                                                    ->default(100)
                                                    ->step(5)
                                                    ->live()
                                                    ->partiallyRenderComponentsAfterStateUpdated(['heroSlidePreview']),
                                                TextInput::make('alt_es')
                                                    ->label('Texto alternativo (ES)')
                                                    ->maxLength(255),
                                                TextInput::make('alt_en')
                                                    ->label('Alt text (EN)')
                                                    ->maxLength(255),
                                                TextInput::make('sort')
                                                    ->label('Orden')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required(fn (): bool => self::activeMainTab() === 'slider'),
                                                Toggle::make('is_active')
                                                    ->label('Activo')
                                                    ->default(true),
                                            ])
                                            ->defaultItems(0)
                                            ->reorderableWithButtons()
                                            ->columnSpanFull()
                                            ->collapsed(),
                                    ]),
                            ]),
                        Tab::make('Portada: Capilla')
                            ->id('chapel')
                            ->schema([
                                HomePanelsForm::chapelSection(),
                            ]),
                        Tab::make('Portada: Donacion')
                            ->id('donation')
                            ->schema([
                                HomePanelsForm::donationSection(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
