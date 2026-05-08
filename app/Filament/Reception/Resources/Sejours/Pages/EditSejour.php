<?php

namespace App\Filament\Reception\Resources\Sejours\Pages;

use App\Filament\Reception\Resources\Sejours\SejourResource;
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
