@extends('layouts.public')

@section('title', 'Réservation confirmée')

@section('content')

<div class="max-w-2xl mx-auto px-4 py-16 text-center">

    <div class="bg-white rounded-2xl shadow-lg p-10">
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg width="40" height="40" fill="none" stroke="#f59e0b" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Réservation enregistrée !</h1>
        <p class="text-gray-500 mb-8">Un email de confirmation a été envoyé à <strong class="text-gray-700">{{ $reservation->client->email }}</strong>.</p>

        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 text-left space-y-3 mb-6">
            <h2 class="font-bold text-amber-800 mb-3">Récapitulatif de votre réservation</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Client</span>
                    <span class="font-medium text-gray-800">{{ $reservation->client->prenom }} {{ $reservation->client->nom }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Référence</span>
                    <span class="font-mono font-bold text-amber-600">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Chambre</span>
                    <span class="font-medium text-gray-800">{{ $reservation->chambre->numero }} — {{ ucfirst($reservation->chambre->type) }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Statut</span>
                    <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-1 rounded-full">En attente de confirmation</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Arrivée</span>
                    <span class="font-medium text-gray-800">{{ $reservation->date_arrivee->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Départ</span>
                    <span class="font-medium text-gray-800">{{ $reservation->date_depart->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Durée</span>
                    <span class="font-medium text-gray-800">{{ $reservation->nombre_nuitees }} nuit(s)</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-xs mb-0.5">Montant estimé</span>
                    <span class="font-extrabold text-amber-600 text-base">{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-600 mb-8 text-left">
            <strong class="text-gray-800">ℹ Important :</strong> Votre réservation est en attente de confirmation par notre équipe. Vous serez contacté dans les 24h. Le paiement s'effectue à l'arrivée.
        </div>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('home') }}"
               class="border-2 border-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-semibold hover:border-amber-500 hover:text-amber-600 transition">
                Retour à l'accueil
            </a>
            <a href="{{ route('chambres') }}"
               class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl font-semibold transition shadow-md">
                Voir d'autres chambres
            </a>
        </div>
    </div>
</div>

@endsection
