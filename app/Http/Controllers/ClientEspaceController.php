<?php

namespace App\Http\Controllers;

use App\Models\ClientHotel;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ClientEspaceController extends Controller
{
    private function getClient(): ?ClientHotel
    {
        return ClientHotel::where('email', Auth::user()->email)->first();
    }

    public function dashboard()
    {
        $client = $this->getClient();

        $prochaine = $client?->reservations()
            ->where('statut', 'confirmee')
            ->whereDate('date_arrivee', '>=', today())
            ->orderBy('date_arrivee')
            ->first();

        $sejoursTotal = $client?->reservations()->where('statut', 'checkout')->count() ?? 0;
        $reservationsTotal = $client?->reservations()->count() ?? 0;

        return view('client.dashboard', compact('client', 'prochaine', 'sejoursTotal', 'reservationsTotal'));
    }

    public function reservations()
    {
        $client = $this->getClient();

        $reservations = $client
            ? $client->reservations()->with('chambre')->orderByDesc('date_arrivee')->get()
            : collect();

        return view('client.reservations', compact('client', 'reservations'));
    }

    public function factures()
    {
        $client = $this->getClient();

        $factures = $client
            ? Facture::whereHas('sejour.reservation', fn ($q) => $q->where('client_id', $client->id))
                ->with(['sejour.reservation.chambre'])
                ->orderByDesc('date_emission')
                ->get()
            : collect();

        return view('client.factures', compact('client', 'factures'));
    }

    public function downloadFacture(Facture $facture)
    {
        $client = $this->getClient();

        abort_unless(
            $client && $facture->sejour?->reservation?->client_id === $client->id,
            403
        );

        $facture->load(['sejour.reservation.client', 'sejour.reservation.chambre']);

        $pdf = Pdf::loadView('pdf.facture', compact('facture'));

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'facture-' . str_pad($facture->id, 6, '0', STR_PAD_LEFT) . '.pdf'
        );
    }
}
