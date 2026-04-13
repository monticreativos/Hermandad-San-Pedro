<?php

namespace App\Filament\Resources\WebSettings;

use App\Filament\Resources\WebSettings\Pages\CreateWebSetting;
use App\Filament\Resources\WebSettings\Pages\EditWebSetting;
use App\Filament\Resources\WebSettings\Pages\ListWebSettings;
use App\Filament\Resources\WebSettings\Schemas\WebSettingForm;
use App\Filament\Resources\WebSettings\Tables\WebSettingsTable;
use App\Models\WebSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WebSettingResource extends Resource
{
    protected static ?string $model = WebSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static ?string $navigationLabel = 'Ajustes Web';
    
    protected static ?string $pluralModelLabel = 'Ajustes Web';
    
    protected static ?string $modelLabel = 'Ajuste Web';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return WebSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebSettings::route('/'),
            'create' => CreateWebSetting::route('/create'),
            'edit' => EditWebSetting::route('/{record}/edit'),
        ];
    }
}
