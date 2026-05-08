<?php

namespace App\Filament\Reception\Resources\Sejours\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SejourForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reservation_id')
                    ->relationship('reservation', 'id')
                    ->required(),
                DateTimePicker::make('check_in'),
                DateTimePicker::make('check_out'),
                TextInput::make('extras'),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('statut_paiement')
                    ->options(['en_attente' => 'En attente', 'partiel' => 'Partiel', 'paye' => 'Paye'])
                    ->default('en_attente')
                    ->required(),
            ]);
    }
}
