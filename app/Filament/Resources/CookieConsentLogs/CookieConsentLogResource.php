<?php

namespace App\Filament\Resources\CookieConsentLogs;

use App\Filament\Resources\CookieConsentLogs\Pages\ListCookieConsentLogs;
use App\Filament\Resources\CookieConsentLogs\Tables\CookieConsentLogsTable;
use App\Models\CookieConsentLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class CookieConsentLogResource extends Resource
{
    protected static ?string $model = CookieConsentLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Consentimientos cookies';

    protected static ?string $modelLabel = 'Consentimiento';

    protected static ?string $pluralModelLabel = 'Consentimientos cookies';

    protected static string|UnitEnum|null $navigationGroup = 'Cumplimiento';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return CookieConsentLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCookieConsentLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
