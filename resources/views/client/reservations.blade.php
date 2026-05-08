@extends('layouts.public')

@section('title', 'Mes réservations')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mes réservations</h1>
        <form action="{{ route('client.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition">Déconnexion</button>
        </form>
    </div>

    {{-- Nav --}}
    <div class="flex gap-2 mb-8 border-b border-gray-200 pb-4">
        <a href="{{ route('client.dashboard') }}"
            class="px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-amber-600 text-sm font-medium transition">
            Tableau de bord
        </a>
        <a href="{{ route('client.reservations') }}"
            class="px-4 py-2 rounded-xl bg-amber-500 text-white text-sm font-semibold">
            Mes réservations
        </a>
        <a href="{{ route('client.factures') }}"
            class="px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-amber-600 text-sm font-medium transition">
            Mes factures
        </a>
    </div>

    @if($reservations->isEmpty())
        <div class="bg-gray-50 rounded-2xl p-12 text-center text-gray-400">
            <div class="text-5xl mb-4">📋</div>
            <p class="mb-4 text-gray-500">Vous n'avez aucune réservation.</p>
            <a href="{{ route('chambres') }}"
               class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold transition shadow-md">
                Réserver une chambre
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reservations as $reservation)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="text-xs text-gray-400 font-medium">Réf. #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="font-semibold text-gray-800 mt-1">
                            Chambre {{ $reservation->chambre->numero }} — {{ ucfirst($reservation->chambre->type) }}
                        </h3>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($reservation->statut === 'confirmee') bg-green-100 text-green-700
                        @elseif($reservation->statut === 'checkin') bg-amber-100 text-amber-700
                        @elseif($reservation->statut === 'checkout') bg-gray-100 text-gray-600
                        @elseif($reservation->statut === 'annulee') bg-red-100 text-red-600
                        @else bg-amber-50 text-amber-600 @endif">
                        {{ match($reservation->statut) {
                            'en_attente' => 'En attente',
                            'confirmee'  => 'Confirmée',
                            'checkin'    => 'En séjour',
                            'checkout'   => 'Terminée',
                            'annulee'    => 'Annulée',
                            default      => ucfirst($reservation->statut),
                        } }}
                    </span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Arrivée</span>
                        {{ $reservation->date_arrivee->format('d/m/Y') }}
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Départ</span>
                        {{ $reservation->date_depart->format('d/m/Y') }}
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Nuitées</span>
                        {{ $reservation->nombre_nuitees }} nuit(s)
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Montant</span>
                        <span class="font-bold text-amber-600">{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
