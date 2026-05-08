<?php

namespace App\Filament\Directeur\Resources\PageSites\Pages;

use App\Filament\Directeur\Resources\PageSites\PageSiteResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePageSite extends CreateRecord
{
    protected static string $resource = PageSiteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
