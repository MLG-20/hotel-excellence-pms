<?php

namespace App\Filament\Reception\Resources\Reservations\Pages;

use App\Filament\Reception\Resources\Reservations\ReservationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
