<?php

namespace App\Filament\Directeur\Resources\Chambres\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReservationsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservations';

    protected static ?string $title = 'Historique des occupants';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('client.nom_complet')
                    ->label('Client')
                    ->searchable(['clients_hotel.nom', 'clients_hotel.prenom'])
                    ->weight('bold'),
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
                TextColumn::make('montant_total')
                    ->label('Montant')
                    ->money('XOF')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('date_arrivee', 'desc');
    }
}
