@extends('layouts.public')

@section('title', 'Mes factures')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mes factures</h1>
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
            class="px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-amber-600 text-sm font-medium transition">
            Mes réservations
        </a>
        <a href="{{ route('client.factures') }}"
            class="px-4 py-2 rounded-xl bg-amber-500 text-white text-sm font-semibold">
            Mes factures
        </a>
    </div>

    @if($factures->isEmpty())
        <div class="bg-gray-50 rounded-2xl p-12 text-center text-gray-400">
            <div class="text-5xl mb-4">🧾</div>
            <p class="text-gray-500">Aucune facture disponible pour le moment.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($factures as $facture)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="text-xs text-gray-400 font-medium">Facture #{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="font-semibold text-gray-800 mt-1">
                            Chambre {{ $facture->sejour->reservation->chambre->numero }}
                            — Séjour du {{ $facture->sejour->check_in?->format('d/m/Y') }}
                            au {{ $facture->sejour->check_out?->format('d/m/Y') }}
                        </h3>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($facture->statut === 'payee') bg-green-100 text-green-700
                            @elseif($facture->statut === 'emise') bg-amber-100 text-amber-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst($facture->statut) }}
                        </span>
                        <a href="{{ route('client.factures.pdf', $facture) }}"
                           target="_blank"
                           class="flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-4">
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Date émission</span>
                        {{ $facture->date_emission?->format('d/m/Y') }}
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Nuitées HT</span>
                        {{ number_format($facture->nuitees, 0, ',', ' ') }} FCFA
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">TVA (18%)</span>
                        {{ number_format($facture->tva, 0, ',', ' ') }} FCFA
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Total TTC</span>
                        <span class="font-extrabold text-amber-600 text-base">{{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                @if($facture->extras > 0)
                <div class="text-xs text-gray-400 border-t border-gray-100 pt-3">
                    Extras : {{ number_format($facture->extras, 0, ',', ' ') }} FCFA
                </div>
                @endif
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
