<?php

namespace App\Filament\Resources\Chambres\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ChambreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('numero')
                    ->label('Numéro de chambre')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'simple' => 'Simple',
                        'double' => 'Double',
                        'suite' => 'Suite',
                        'familiale' => 'Familiale',
                        'presidentielle' => 'Présidentielle',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('etage')
                    ->label('Étage')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                TextInput::make('capacite')
                    ->label('Capacité (personnes)')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                TextInput::make('prix_nuit')
                    ->label('Prix / nuit (FCFA)')
                    ->required()
                    ->numeric()
                    ->prefix('FCFA'),
                Select::make('statut')
                    ->label('Statut')
                    ->options([
                        'disponible' => 'Disponible',
                        'occupee' => 'Occupée',
                        'maintenance' => 'Maintenance',
                        'nettoyage' => 'Nettoyage',
                    ])
                    ->default('disponible')
                    ->required()
                    ->native(false),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
            ]);
    }
}
