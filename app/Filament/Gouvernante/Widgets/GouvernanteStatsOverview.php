<?php

namespace App\Filament\Gouvernante\Widgets;

use App\Models\Chambre;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GouvernanteStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $aNettoyer = Chambre::where('statut', 'nettoyage')->count();
        $enMaintenance = Chambre::where('statut', 'maintenance')->count();
        $disponibles = Chambre::where('statut', 'disponible')->count();
        $occupees = Chambre::where('statut', 'occupee')->count();

        return [
            Stat::make('À nettoyer', $aNettoyer)
                ->description('Chambres en attente de nettoyage')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color($aNettoyer > 0 ? 'warning' : 'success'),

            Stat::make('En maintenance', $enMaintenance)
                ->description('Chambres hors service')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color($enMaintenance > 0 ? 'danger' : 'success'),

            Stat::make('Chambres disponibles', $disponibles)
                ->description('Prêtes et propres')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Chambres occupées', $occupees)
                ->description('Clients en séjour')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),
        ];
    }
}
