<?php

namespace App\Filament\Gouvernante\Pages;

use App\Models\Chambre;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Housekeeping extends Page
{
    protected string $view = 'filament.pages.housekeeping';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $navigationLabel = 'Housekeeping';

    protected static string|\UnitEnum|null $navigationGroup = 'Hôtel';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Planning Housekeeping';

    protected function getViewData(): array
    {
        $chambres = Chambre::orderBy('etage')->orderBy('numero')->get();

        return [
            'chambres'  => $chambres,
            'parEtage'  => $chambres->groupBy('etage'),
            'stats'     => $chambres->groupBy('statut')->map->count(),
        ];
    }

    public function marquerDisponible(int $chambreId): void
    {
        $chambre = Chambre::findOrFail($chambreId);
        $chambre->update(['statut' => 'disponible']);
        Notification::make()->title('Chambre ' . $chambre->numero . ' → Disponible')->success()->send();
    }

    public function marquerNettoyage(int $chambreId): void
    {
        $chambre = Chambre::findOrFail($chambreId);
        $chambre->update(['statut' => 'nettoyage']);
        Notification::make()->title('Chambre ' . $chambre->numero . ' → En nettoyage')->info()->send();
    }

    public function marquerMaintenance(int $chambreId): void
    {
        $chambre = Chambre::findOrFail($chambreId);
        $chambre->update(['statut' => 'maintenance']);
        Notification::make()->title('Chambre ' . $chambre->numero . ' → Maintenance')->warning()->send();
    }
}
