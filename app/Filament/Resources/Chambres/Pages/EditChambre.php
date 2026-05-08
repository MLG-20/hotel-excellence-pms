<?php

namespace App\Filament\Resources\Chambres\Pages;

use App\Filament\Resources\Chambres\ChambreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChambre extends EditRecord
{
    protected static string $resource = ChambreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
