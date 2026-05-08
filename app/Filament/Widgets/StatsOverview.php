<?php

namespace App\Filament\Widgets;

use App\Models\Chambre;
use App\Models\Facture;
use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalChambres = Chambre::count();
        $chambresOccupees = Chambre::where('statut', 'occupee')->count();
        $tauxOccupation = $totalChambres > 0 ? round(($chambresOccupees / $totalChambres) * 100) : 0;

        $reservationsAujourdhui = Reservation::whereDate('date_arrivee', today())->count();

        $revenusMois = Facture::whereMonth('date_emission', now()->month)
            ->whereYear('date_emission', now()->year)
            ->where('statut', 'payee')
            ->sum('total_ttc');

        $checkinsEnCours = Reservation::where('statut', 'checkin')->count();

        return [
            Stat::make('Taux d\'occupation', $tauxOccupation . '%')
                ->description($chambresOccupees . ' / ' . $totalChambres . ' chambres occupées')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color($tauxOccupation >= 80 ? 'success' : ($tauxOccupation >= 50 ? 'warning' : 'danger')),

            Stat::make('Clients en séjour', $checkinsEnCours)
                ->description('Check-ins actifs en ce moment')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Arrivées aujourd\'hui', $reservationsAujourdhui)
                ->description('Réservations pour ce jour')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),

            Stat::make('Revenus du mois', number_format($revenusMois, 0, ',', ' ') . ' FCFA')
                ->description('Factures payées — ' . now()->format('F Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
