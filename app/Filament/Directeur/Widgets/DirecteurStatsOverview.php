<?php

namespace App\Filament\Directeur\Widgets;

use App\Models\Chambre;
use App\Models\Facture;
use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DirecteurStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalChambres = Chambre::count();
        $chambresOccupees = Chambre::where('statut', 'occupee')->count();
        $tauxOccupation = $totalChambres > 0 ? round(($chambresOccupees / $totalChambres) * 100) : 0;

        $revenusMois = Facture::whereMonth('date_emission', now()->month)
            ->whereYear('date_emission', now()->year)
            ->where('statut', 'payee')
            ->sum('total_ttc');

        $revenusAnnee = Facture::whereYear('date_emission', now()->year)
            ->where('statut', 'payee')
            ->sum('total_ttc');

        $jours = now()->dayOfYear;
        $revPAR = $totalChambres > 0 ? round($revenusMois / ($totalChambres * now()->daysInMonth), 0) : 0;

        $reservationsMois = Reservation::whereMonth('date_arrivee', now()->month)
            ->whereYear('date_arrivee', now()->year)
            ->count();

        return [
            Stat::make('Taux d\'occupation', $tauxOccupation . '%')
                ->description($chambresOccupees . ' / ' . $totalChambres . ' chambres occupées')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color($tauxOccupation >= 80 ? 'success' : ($tauxOccupation >= 50 ? 'warning' : 'danger')),

            Stat::make('Revenus du mois', number_format($revenusMois, 0, ',', ' ') . ' FCFA')
                ->description('Factures payées — ' . now()->format('F Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('RevPAR', number_format($revPAR, 0, ',', ' ') . ' FCFA')
                ->description('Revenu par chambre disponible / nuit')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make('Réservations ce mois', $reservationsMois)
                ->description('Total réservations — ' . now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
        ];
    }
}
