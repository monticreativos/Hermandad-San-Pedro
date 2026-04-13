<?php

namespace App\Filament\Resources\CultosPages\Schemas;

use App\Filament\Forms\Components\AdminRichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CultosPageForm
{
    /**
     * Claves alineadas con routes/web.php (cultos.show) para crear/editar páginas sin depender de registros previos.
     *
     * @return array<string, string>
     */
    public static function keyOptions(): array
    {
        return [
            'internos' => 'Cultos internos (resumen / enlace directo)',
            'externos' => 'Cultos externos (general)',
            'culto-misa-corporativa' => 'Internos — Misa corporativa mensual',
            'culto-triduo-cuaresma-titulares' => 'Internos — Triduo de Cuaresma a los Titulares',
            'culto-cristo-rey-protestacion-fe' => 'Internos — Cristo Rey — Función e Instituto',
            'culto-san-pedro-apostol' => 'Internos — San Pedro — 29 de junio',
            'culto-via-crucis-cuaresma' => 'Externos — Vía Crucis en la feligresía',
            'culto-virgen-salud-septiembre' => 'Externos — Virgen de la Salud — Septiembre',
            'culto-virgen-mayo' => 'Externos — Virgen de la Salud — Mes de mayo',
            'estacion-penitencia-cofradia' => 'Externos — Estación de penitencia — Cofradía',
            'estacion-penitencia-horario' => 'Externos — Estación de penitencia — Horario y recorrido',
            'corpus-christi' => 'Externos — Procesión del Corpus Christi',
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('key')
                    ->label('Seccion')
                    ->options(fn (): array => self::keyOptions())
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->searchable(),
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
