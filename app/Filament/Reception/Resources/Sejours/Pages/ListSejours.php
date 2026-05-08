<?php

namespace App\Filament\Reception\Resources\Sejours\Pages;

use App\Filament\Reception\Resources\Sejours\SejourResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSejours extends ListRecords
{
    protected static string $resource = SejourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
