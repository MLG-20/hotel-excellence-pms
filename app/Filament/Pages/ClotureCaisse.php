<?php

namespace App\Filament\Pages;

use App\Models\Facture;
use App\Models\Reservation;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;

class ClotureCaisse extends Page
{
    protected string $view = 'filament.pages.cloture-caisse';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Clôture de caisse';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Clôture de caisse quotidienne';

    public string $date_cloture = '';

    public function mount(): void
    {
        $this->date_cloture = now()->format('Y-m-d');
    }

    public function getCloture(): array
    {
        $date = Carbon::parse($this->date_cloture);

        $facturesJour = Facture::whereDate('date_emission', $date)
            ->with(['sejour.reservation.client', 'sejour.reservation.chambre'])
            ->get();

        $totalEmis = $facturesJour->whereIn('statut', ['emise', 'payee'])->sum('total_ttc');
        $totalEncaisse = $facturesJour->where('statut', 'payee')->sum('total_ttc');
        $totalTva = $facturesJour->whereIn('statut', ['emise', 'payee'])->sum('tva');
        $nbFactures = $facturesJour->whereIn('statut', ['emise', 'payee'])->count();

        $checkins = Reservation::whereDate('date_arrivee', $date)
            ->whereIn('statut', ['checkin', 'checkout'])->count();
        $checkouts = Reservation::whereDate('date_depart', $date)
            ->where('statut', 'checkout')->count();

        return compact(
            'facturesJour', 'totalEmis', 'totalEncaisse',
            'totalTva', 'nbFactures', 'checkins', 'checkouts'
        );
    }
}
