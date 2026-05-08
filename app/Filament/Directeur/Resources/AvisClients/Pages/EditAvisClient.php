<?php

namespace App\Filament\Directeur\Resources\AvisClients\Pages;

use App\Filament\Directeur\Resources\AvisClients\AvisClientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAvisClient extends EditRecord
{
    protected static string $resource = AvisClientResource::class;

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
