<?php

namespace App\Filament\Resources\MemberApplications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MemberApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefono')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Recibida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 2,
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Nombre completo'),
                        TextInput::make('email')
                            ->label('Email'),
                        TextInput::make('phone')
                            ->label('Telefono'),
                        DatePicker::make('birth_date')
                            ->label('Fecha de nacimiento'),
                        Textarea::make('address')
                            ->label('Direccion')
                            ->rows(8),
                        Textarea::make('message')
                            ->label('Mensaje / motivacion')
                            ->rows(14),
                        TextInput::make('locale')
                            ->label('Idioma del formulario'),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
