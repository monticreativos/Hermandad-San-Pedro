<?php

namespace App\Filament\Resources\MemberApplications;

use App\Filament\Resources\MemberApplications\Pages\ListMemberApplications;
use App\Filament\Resources\MemberApplications\Tables\MemberApplicationsTable;
use App\Models\MemberApplication;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MemberApplicationResource extends Resource
{
    protected static ?string $model = MemberApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static ?string $navigationLabel = 'Solicitudes de hermano';

    protected static ?string $modelLabel = 'Solicitud';

    protected static ?string $pluralModelLabel = 'Solicitudes';

    protected static string|UnitEnum|null $navigationGroup = 'Documentacion';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return MemberApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMemberApplications::route('/'),
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
