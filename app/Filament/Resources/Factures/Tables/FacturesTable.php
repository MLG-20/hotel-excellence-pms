<?php

namespace App\Filament\Resources\Factures\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FacturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Facture')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('sejour.reservation.client.nom_complet')
                    ->label('Client')
                    ->searchable()
                    ->default('—'),
                TextColumn::make('sejour.reservation.chambre.numero')
                    ->label('Chambre')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('nuitees')
                    ->label('Nuitées')
                    ->money('XOF')
                    ->sortable(),
                TextColumn::make('extras')
                    ->label('Extras')
                    ->money('XOF')
                    ->sortable(),
                TextColumn::make('total_ttc')
                    ->label('Total TTC')
                    ->money('XOF')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'payee' => 'success',
                        'emise' => 'info',
                        'annulee' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('date_emission')
                    ->label('Date émission')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'brouillon' => 'Brouillon',
                        'emise' => 'Émise',
                        'payee' => 'Payée',
                        'annulee' => 'Annulée',
                    ]),
            ])
            ->recordActions([
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.facture', ['facture' => $record->load([
                            'sejour.reservation.client',
                            'sejour.reservation.chambre',
                        ])]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'facture-' . $record->numero . '.pdf'
                        );
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date_emission', 'desc');
    }
}
