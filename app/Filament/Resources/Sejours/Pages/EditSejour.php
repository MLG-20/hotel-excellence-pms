<?php

namespace App\Filament\Resources\Sejours\Pages;

use App\Filament\Resources\Sejours\SejourResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSejour extends EditRecord
{
    protected static string $resource = SejourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
