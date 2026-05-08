<?php

namespace App\Filament\Directeur\Resources\SliderHeros\Pages;

use App\Filament\Directeur\Resources\SliderHeros\SliderHeroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSliderHeros extends ListRecords
{
    protected static string $resource = SliderHeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Ajouter un slide'),
        ];
    }
}
