<?php

namespace App\Filament\Directeur\Resources\PageSites\Pages;

use App\Filament\Directeur\Resources\PageSites\PageSiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPageSites extends ListRecords
{
    protected static string $resource = PageSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Ajouter une page'),
        ];
    }
}
