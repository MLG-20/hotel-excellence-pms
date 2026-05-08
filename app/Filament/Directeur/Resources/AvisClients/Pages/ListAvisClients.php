<?php

namespace App\Filament\Directeur\Resources\AvisClients\Pages;

use App\Filament\Directeur\Resources\AvisClients\AvisClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvisClients extends ListRecords
{
    protected static string $resource = AvisClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Ajouter un avis'),
        ];
    }
}
