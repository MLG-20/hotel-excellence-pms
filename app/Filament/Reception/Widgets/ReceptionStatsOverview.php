<?php

namespace App\Filament\Reception\Widgets;

use App\Models\Chambre;
use App\Models\Reservation;
use App\Models\Sejour;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReceptionStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $arrivéesAujourdhui = Reservation::whereDate('date_arrivee', today())
            ->whereIn('statut', ['confirmee', 'checkin'])
            ->count();

        $departsAujourdhui = Reservation::whereDate('date_depart', today())
            ->where('statut', 'checkin')
            ->count();

        $checkinsActifs = Reservation::where('statut', 'checkin')->count();

        $chambresDisponibles = Chambre::where('statut', 'disponible')->count();

        return [
            Stat::make('Arrivées aujourd\'hui', $arrivéesAujourdhui)
                ->description('Clients à accueillir')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->color('success'),

            Stat::make('Départs aujourd\'hui', $departsAujourdhui)
                ->description('Check-outs à effectuer')
                ->descriptionIcon('heroicon-m-arrow-left-circle')
                ->color('warning'),

            Stat::make('Clients en séjour', $checkinsActifs)
                ->description('Chambres actuellement occupées')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Chambres disponibles', $chambresDisponibles)
                ->description('Prêtes à l\'attribution')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),
        ];
    }
}
