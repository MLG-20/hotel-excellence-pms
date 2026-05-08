<?php

namespace App\Filament\Directeur\Resources\Chambres\Pages;

use App\Filament\Directeur\Resources\Chambres\ChambreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChambre extends EditRecord
{
    protected static string $resource = ChambreResource::class;

    private array $originalImages = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->originalImages = $data['images'] ?? [];
        $data['images'] = []; // Ne pas pré-charger dans FilePond
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['images'])) {
            $data['images'] = $this->originalImages; // Garder les images existantes
        }
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
