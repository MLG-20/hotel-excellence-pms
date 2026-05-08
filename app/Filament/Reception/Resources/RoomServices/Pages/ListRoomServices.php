<?php

namespace App\Filament\Reception\Resources\RoomServices\Pages;

use App\Filament\Reception\Resources\RoomServices\RoomServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomServices extends ListRecords
{
    protected static string $resource = RoomServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
