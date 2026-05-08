<?php

namespace App\Filament\Reception\Resources\Factures\Pages;

use App\Filament\Reception\Resources\Factures\FactureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFacture extends EditRecord
{
    protected static string $resource = FactureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
