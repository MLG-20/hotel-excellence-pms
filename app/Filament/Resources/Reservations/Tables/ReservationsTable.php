<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Models\Sejour;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.nom_complet')
                    ->label('Client')
                    ->searchable(['clients_hotel.nom', 'clients_hotel.prenom'])
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('chambre.numero')
                    ->label('Chambre')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('chambre.type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('date_arrivee')
                    ->label('Arrivée')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('date_depart')
                    ->label('Départ')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('adultes')
                    ->label('Adultes')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en_attente' => 'warning',
                        'confirmee' => 'success',
                        'annulee' => 'danger',
                        'checkin' => 'info',
                        'checkout' => 'gray',
                    }),
                TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('montant_total')
                    ->label('Montant')
                    ->money('XOF')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'confirmee' => 'Confirmée',
                        'annulee' => 'Annulée',
                        'checkin' => 'Check-in',
                        'checkout' => 'Check-out',
                    ]),
                SelectFilter::make('source')
                    ->label('Source')
                    ->options([
                        'direct' => 'Direct',
                        'booking' => 'Booking.com',
                        'airbnb' => 'Airbnb',
                        'telephone' => 'Téléphone',
                        'agence' => 'Agence',
                    ]),
            ])
            ->recordActions([
                Action::make('checkin')
                    ->label('Check-in')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->statut, ['confirmee', 'en_attente']))
                    ->requiresConfirmation()
                    ->modalHeading('Confirmer le Check-in')
                    ->modalDescription(fn ($record) => 'Check-in pour ' . ($record->client->prenom ?? '') . ' ' . ($record->client->nom ?? '') . ' — Chambre ' . ($record->chambre->numero ?? ''))
                    ->action(function ($record) {
                        $record->update(['statut' => 'checkin']);
                        $record->chambre->update(['statut' => 'occupee']);

                        Sejour::firstOrCreate(
                            ['reservation_id' => $record->id],
                            [
                                'check_in' => now(),
                                'total' => $record->montant_total ?? 0,
                                'statut_paiement' => 'en_attente',
                            ]
                        );

                        Notification::make()
                            ->title('Check-in effectué')
                            ->body('Le client est bien enregistré dans la chambre ' . $record->chambre->numero)
                            ->success()
                            ->send();
                    }),

                Action::make('checkout')
                    ->label('Check-out')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->color('warning')
                    ->visible(fn ($record) => $record->statut === 'checkin')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmer le Check-out')
                    ->modalDescription(fn ($record) => 'Check-out pour ' . ($record->client->prenom ?? '') . ' ' . ($record->client->nom ?? ''))
                    ->action(function ($record) {
                        $record->update(['statut' => 'checkout']);
                        $record->chambre->update(['statut' => 'nettoyage']);

                        if ($record->sejour) {
                            $record->sejour->update(['check_out' => now()]);

                            // Générer la facture automatiquement
                            $sejour = $record->sejour;
                            $nuitees = $record->nombre_nuitees;
                            $prixNuit = $record->chambre->prix_nuit ?? 0;
                            $montantNuitees = $nuitees * $prixNuit;
                            $extras = collect($sejour->extras ?? [])->sum('montant');
                            $totalHt = $montantNuitees + $extras;
                            $tva = $totalHt * 0.18;
                            $totalTtc = $totalHt + $tva;

                            \App\Models\Facture::firstOrCreate(
                                ['sejour_id' => $sejour->id],
                                [
                                    'nuitees' => $montantNuitees,
                                    'extras' => $extras,
                                    'remise' => 0,
                                    'total_ht' => $totalHt,
                                    'tva' => $tva,
                                    'total_ttc' => $totalTtc,
                                    'statut' => 'emise',
                                    'date_emission' => now(),
                                ]
                            );
                        }

                        Notification::make()
                            ->title('Check-out effectué')
                            ->body('La facture a été générée automatiquement.')
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date_arrivee', 'desc');
    }
}
