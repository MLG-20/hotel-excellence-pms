<?php

namespace App\Filament\Reception\Resources\RoomServices\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RoomServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sejour_id')
                    ->relationship('sejour', 'id')
                    ->required(),
                TextInput::make('articles')
                    ->required(),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DateTimePicker::make('heure_commande'),
                Select::make('statut')
                    ->options([
            'en_attente' => 'En attente',
            'en_preparation' => 'En preparation',
            'livre' => 'Livre',
            'annule' => 'Annule',
        ])
                    ->default('en_attente')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
