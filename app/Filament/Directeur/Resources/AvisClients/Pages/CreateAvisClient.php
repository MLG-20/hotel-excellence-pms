<?php

namespace App\Filament\Directeur\Resources\AvisClients\Pages;

use App\Filament\Directeur\Resources\AvisClients\AvisClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAvisClient extends CreateRecord
{
    protected static string $resource = AvisClientResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
