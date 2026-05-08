<?php

namespace App\Filament\Directeur\Resources\Chambres\Pages;

use App\Filament\Directeur\Resources\Chambres\ChambreResource;
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
