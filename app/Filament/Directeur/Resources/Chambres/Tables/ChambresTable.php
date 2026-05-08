<?php

namespace App\Filament\Directeur\Resources\Chambres\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ChambresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Chambre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'suite' => 'warning',
                        'presidentielle' => 'danger',
                        'familiale' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('etage')
                    ->label('Étage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('capacite')
                    ->label('Capacité')
                    ->numeric()
                    ->sortable()
                    ->suffix(' pers.'),
                TextColumn::make('prix_nuit')
                    ->label('Prix/nuit')
                    ->numeric()
                    ->sortable()
                    ->money('XOF'),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disponible' => 'success',
                        'occupee' => 'danger',
                        'maintenance' => 'warning',
                        'nettoyage' => 'info',
                    }),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'disponible' => 'Disponible',
                        'occupee' => 'Occupée',
                        'maintenance' => 'Maintenance',
                        'nettoyage' => 'Nettoyage',
                    ]),
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'simple' => 'Simple',
                        'double' => 'Double',
                        'suite' => 'Suite',
                        'familiale' => 'Familiale',
                        'presidentielle' => 'Présidentielle',
                    ]),
            ])
            ->recordActions([
                Action::make('marquer_disponible')
                    ->label('Disponible')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->statut, ['nettoyage', 'maintenance']))
                    ->action(function ($record) {
                        $record->update(['statut' => 'disponible']);
                        Notification::make()->title('Chambre marquée disponible')->success()->send();
                    }),
                Action::make('signaler_maintenance')
                    ->label('Maintenance')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('warning')
                    ->visible(fn ($record) => $record->statut === 'disponible')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['statut' => 'maintenance']);
                        Notification::make()->title('Signalement maintenance enregistré')->warning()->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
