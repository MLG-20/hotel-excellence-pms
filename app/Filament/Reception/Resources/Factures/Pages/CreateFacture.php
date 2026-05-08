<?php

namespace App\Filament\Reception\Resources\Factures\Pages;

use App\Filament\Reception\Resources\Factures\FactureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFacture extends CreateRecord
{
    protected static string $resource = FactureResource::class;
}
