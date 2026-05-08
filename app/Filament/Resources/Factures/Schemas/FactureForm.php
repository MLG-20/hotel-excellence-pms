<?php

namespace App\Filament\Resources\Factures\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FactureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')
                    ->required(),
                Select::make('sejour_id')
                    ->relationship('sejour', 'id')
                    ->required(),
                TextInput::make('nuitees')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('extras')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('remise')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_ht')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tva')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_ttc')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('statut')
                    ->options(['brouillon' => 'Brouillon', 'emise' => 'Emise', 'payee' => 'Payee', 'annulee' => 'Annulee'])
                    ->default('brouillon')
                    ->required(),
                DateTimePicker::make('date_emission'),
            ]);
    }
}
