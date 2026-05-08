<?php

namespace App\Filament\Directeur\Resources\SliderHeros\Pages;

use App\Filament\Directeur\Resources\SliderHeros\SliderHeroResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSliderHero extends CreateRecord
{
    protected static string $resource = SliderHeroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
