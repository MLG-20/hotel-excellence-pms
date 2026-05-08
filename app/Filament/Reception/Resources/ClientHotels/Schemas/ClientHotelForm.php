<?php

namespace App\Filament\Reception\Resources\ClientHotels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ClientHotelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required(),
                TextInput::make('prenom')
                    ->label('Prénom')
                    ->required(),
                TextInput::make('email')
                    ->label('Adresse email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('telephone')
                    ->label('Téléphone')
                    ->tel(),
                TextInput::make('nationalite')
                    ->label('Nationalité'),
                TextInput::make('passeport')
                    ->label('N° Passeport / CNI'),
                Textarea::make('adresse')
                    ->label('Adresse')
                    ->columnSpanFull(),
            ]);
    }
}
