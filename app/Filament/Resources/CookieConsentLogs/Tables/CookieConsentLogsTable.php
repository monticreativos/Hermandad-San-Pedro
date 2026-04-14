<?php

namespace App\Filament\Resources\CookieConsentLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CookieConsentLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('consented_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('locale')
                    ->label('Idioma')
                    ->badge(),
                TextColumn::make('action')
                    ->label('Accion')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accept_all' => 'Acepta todo',
                        'reject_all' => 'Rechaza opcionales',
                        'save_preferences' => 'Preferencias personalizadas',
                        'withdraw' => 'Retirada',
                        default => $state,
                    }),
                TextColumn::make('consent_version')
                    ->label('Version')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Origen')
                    ->badge(),
                IconColumn::make('analytics')
                    ->label('Analiticas')
                    ->boolean(),
                IconColumn::make('personalization')
                    ->label('Personalizacion')
                    ->boolean(),
                IconColumn::make('marketing')
                    ->label('Marketing')
                    ->boolean(),
                TextColumn::make('user.email')
                    ->label('Usuario')
                    ->placeholder('Anonimo')
                    ->toggleable(),
                TextColumn::make('consent_uuid')
                    ->label('UUID')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('ip_hash')
                    ->label('Hash IP')
                    ->limit(14)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->label('Accion')
                    ->options([
                        'accept_all' => 'Acepta todo',
                        'reject_all' => 'Rechaza opcionales',
                        'save_preferences' => 'Preferencias personalizadas',
                        'withdraw' => 'Retirada',
                    ]),
                SelectFilter::make('locale')
                    ->label('Idioma')
                    ->options([
                        'es' => 'es',
                        'en' => 'en',
                    ]),
            ])
            ->defaultSort('consented_at', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->schema([
                        TextInput::make('consent_uuid')->label('Consent UUID'),
                        TextInput::make('consent_version')->label('Version'),
                        TextInput::make('action')->label('Accion'),
                        TextInput::make('source')->label('Origen'),
                        TextInput::make('locale')->label('Idioma'),
                        TextInput::make('user.email')->label('Email usuario'),
                        TextInput::make('page_url')->label('URL'),
                        TextInput::make('ip_hash')->label('Hash IP'),
                        TextInput::make('user_agent')->label('User agent'),
                    ]),
            ]);
    }
}
