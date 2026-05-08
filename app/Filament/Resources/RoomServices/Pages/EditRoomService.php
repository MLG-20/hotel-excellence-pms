<?php

namespace App\Filament\Resources\RoomServices\Pages;

use App\Filament\Resources\RoomServices\RoomServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomService extends EditRecord
{
    protected static string $resource = RoomServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
