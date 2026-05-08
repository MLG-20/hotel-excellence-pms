<?php

namespace App\Filament\Resources\Chambres\Pages;

use App\Filament\Resources\Chambres\ChambreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChambres extends ListRecords
{
    protected static string $resource = ChambreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
