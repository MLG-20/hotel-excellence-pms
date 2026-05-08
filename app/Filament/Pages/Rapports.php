<?php

namespace App\Filament\Pages;

use App\Models\Chambre;
use App\Models\Facture;
use App\Models\Reservation;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;

class Rapports extends Page
{
    protected string $view = 'filament.pages.rapports';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Rapports';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Rapports & Revenus';

    public string $date_debut = '';
    public string $date_fin = '';

    public function mount(): void
    {
        $this->date_debut = now()->startOfMonth()->format('Y-m-d');
        $this->date_fin = now()->endOfMonth()->format('Y-m-d');
    }

    public function appliquerFiltres(): void
    {
        // Livewire re-render automatique via les propriétés publiques
    }

    public function getStats(): array
    {
        $debut = Carbon::parse($this->date_debut)->startOfDay();
        $fin = Carbon::parse($this->date_fin)->endOfDay();

        $totalChambres = Chambre::count();
        $chambresOccupees = Chambre::where('statut', 'occupee')->count();
        $tauxOccupation = $totalChambres > 0 ? round(($chambresOccupees / $totalChambres) * 100, 1) : 0;

        $revenus = Facture::whereBetween('date_emission', [$debut, $fin])
            ->whereIn('statut', ['emise', 'payee'])
            ->sum('total_ttc');

        $facturesPayees = Facture::whereBetween('date_emission', [$debut, $fin])
            ->where('statut', 'payee')
            ->sum('total_ttc');

        $nbReservations = Reservation::whereBetween('date_arrivee', [$debut, $fin])->count();
        $nbCheckouts = Reservation::whereBetween('date_arrivee', [$debut, $fin])
            ->where('statut', 'checkout')->count();

        $jours = max(1, $debut->diffInDays($fin) + 1);
        $revPAR = $totalChambres > 0 ? round($revenus / ($totalChambres * $jours), 0) : 0;

        return compact(
            'tauxOccupation', 'chambresOccupees', 'totalChambres',
            'revenus', 'facturesPayees', 'nbReservations', 'nbCheckouts', 'revPAR'
        );
    }

    public function getRevenusParJour(): array
    {
        $debut = Carbon::parse($this->date_debut)->startOfDay();
        $fin = Carbon::parse($this->date_fin)->endOfDay();

        return Facture::selectRaw('DATE(date_emission) as jour, SUM(total_ttc) as total')
            ->whereBetween('date_emission', [$debut, $fin])
            ->whereIn('statut', ['emise', 'payee'])
            ->groupBy('jour')
            ->orderBy('jour')
            ->get()
            ->map(fn ($row) => [
                'jour' => Carbon::parse($row->jour)->format('d/m'),
                'total' => (float) $row->total,
            ])
            ->toArray();
    }

    public function getReservationsParSource(): array
    {
        $debut = Carbon::parse($this->date_debut)->startOfDay();
        $fin = Carbon::parse($this->date_fin)->endOfDay();

        return Reservation::selectRaw('source, COUNT(*) as total')
            ->whereBetween('date_arrivee', [$debut, $fin])
            ->groupBy('source')
            ->get()
            ->map(fn ($row) => ['source' => ucfirst($row->source), 'total' => $row->total])
            ->toArray();
    }

    public function getTopChambres(): array
    {
        $debut = Carbon::parse($this->date_debut)->startOfDay();
        $fin = Carbon::parse($this->date_fin)->endOfDay();

        return Reservation::selectRaw('chambre_id, COUNT(*) as nb')
            ->whereBetween('date_arrivee', [$debut, $fin])
            ->with('chambre')
            ->groupBy('chambre_id')
            ->orderByDesc('nb')
            ->take(5)
            ->get()
            ->map(fn ($row) => [
                'chambre' => 'Ch. ' . ($row->chambre->numero ?? '?') . ' — ' . ucfirst($row->chambre->type ?? ''),
                'nb' => $row->nb,
            ])
            ->toArray();
    }
}
