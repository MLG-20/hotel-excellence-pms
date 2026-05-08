<?php

namespace App\Filament\Directeur\Resources\PageSites\Pages;

use App\Filament\Directeur\Resources\PageSites\PageSiteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPageSite extends EditRecord
{
    protected static string $resource = PageSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
