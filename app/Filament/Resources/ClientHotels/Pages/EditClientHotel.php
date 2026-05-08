<?php

namespace App\Filament\Resources\ClientHotels\Pages;

use App\Filament\Resources\ClientHotels\ClientHotelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClientHotel extends EditRecord
{
    protected static string $resource = ClientHotelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
