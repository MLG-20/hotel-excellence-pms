<?php

namespace App\Filament\Reception\Resources\Reservations\Schemas;

use App\Models\Chambre;
use App\Models\ClientHotel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'nom')
                    ->getOptionLabelFromRecordUsing(fn (ClientHotel $record) => $record->prenom . ' ' . $record->nom)
                    ->searchable(['nom', 'prenom', 'email'])
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('nom')->required(),
                        TextInput::make('prenom')->required(),
                        TextInput::make('email')->email()->required()->unique('clients_hotel', 'email'),
                        TextInput::make('telephone')->tel(),
                        TextInput::make('nationalite'),
                    ]),
                Select::make('chambre_id')
                    ->label('Chambre')
                    ->relationship('chambre', 'numero')
                    ->getOptionLabelFromRecordUsing(fn (Chambre $record) => 'Chambre ' . $record->numero . ' - ' . ucfirst($record->type) . ' (' . number_format($record->prix_nuit, 0, ',', ' ') . ' FCFA/nuit)')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date_arrivee')
                    ->label("Date d'arrivée")
                    ->required()
                    ->native(false)
                    ->minDate(now()),
                DatePicker::make('date_depart')
                    ->label('Date de départ')
                    ->required()
                    ->native(false)
                    ->after('date_arrivee'),
                TextInput::make('adultes')
                    ->label('Adultes')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                TextInput::make('enfants')
                    ->label('Enfants')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Select::make('source')
                    ->label('Source de réservation')
                    ->options([
                        'direct' => 'Direct',
                        'booking' => 'Booking.com',
                        'airbnb' => 'Airbnb',
                        'telephone' => 'Téléphone',
                        'agence' => 'Agence',
                    ])
                    ->default('direct')
                    ->required()
                    ->native(false),
                Select::make('statut')
                    ->label('Statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'confirmee' => 'Confirmée',
                        'annulee' => 'Annulée',
                        'checkin' => 'Check-in',
                        'checkout' => 'Check-out',
                    ])
                    ->default('en_attente')
                    ->required()
                    ->native(false),
                TextInput::make('montant_total')
                    ->label('Montant total (FCFA)')
                    ->numeric()
                    ->prefix('FCFA'),
                Textarea::make('notes')
                    ->label('Notes')
                    ->columnSpanFull(),
            ]);
    }
}
