<?php

namespace App\Filament\Directeur\Resources\SliderHeros\Pages;

use App\Filament\Directeur\Resources\SliderHeros\SliderHeroResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSliderHero extends EditRecord
{
    protected static string $resource = SliderHeroResource::class;

    private ?string $originalImage = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->originalImage = $data['image'] ?? null;
        $data['image'] = null; // Ne pas pré-charger dans FilePond
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['image'])) {
            $data['image'] = $this->originalImage; // Garder l'image existante si rien uploadé
        }
        return $data;
    }

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
