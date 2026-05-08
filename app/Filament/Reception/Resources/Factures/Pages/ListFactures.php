<?php

namespace App\Filament\Reception\Resources\Factures\Pages;

use App\Filament\Reception\Resources\Factures\FactureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactures extends ListRecords
{
    protected static string $resource = FactureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
