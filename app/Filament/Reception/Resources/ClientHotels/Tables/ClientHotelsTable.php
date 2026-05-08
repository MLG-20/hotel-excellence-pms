<?php

namespace App\Filament\Reception\Resources\ClientHotels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientHotelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom_complet')
                    ->label('Nom complet')
                    ->searchable(['nom', 'prenom'])
                    ->sortable(['nom'])
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('nationalite')
                    ->label('Nationalité')
                    ->searchable(),
                TextColumn::make('passeport')
                    ->label('Passeport / CNI')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('reservations_count')
                    ->label('Réservations')
                    ->counts('reservations')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
