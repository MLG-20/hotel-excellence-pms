<?php

namespace App\Filament\Resources\ClientHotels\Pages;

use App\Filament\Resources\ClientHotels\ClientHotelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClientHotels extends ListRecords
{
    protected static string $resource = ClientHotelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
