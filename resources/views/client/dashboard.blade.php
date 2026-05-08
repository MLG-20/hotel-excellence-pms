@extends('layouts.public')

@section('title', 'Mon espace client')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Bonjour, {{ $client?->prenom ?? auth()->user()->name }} !
            </h1>
            <p class="text-gray-500 text-sm mt-1">Bienvenue dans votre espace client — Hôtel Excellence</p>
        </div>
        <form action="{{ route('client.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition">Déconnexion</button>
        </form>
    </div>

    {{-- Nav espace client --}}
    <div class="flex gap-2 mb-8 border-b border-gray-200 pb-4">
        <a href="{{ route('client.dashboard') }}"
            class="px-4 py-2 rounded-xl bg-amber-500 text-white text-sm font-semibold">
            Tableau de bord
        </a>
        <a href="{{ route('client.reservations') }}"
            class="px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-amber-600 text-sm font-medium transition">
            Mes réservations
        </a>
        <a href="{{ route('client.factures') }}"
            class="px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-amber-600 text-sm font-medium transition">
            Mes factures
        </a>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="text-3xl font-black text-amber-500">{{ $reservationsTotal }}</div>
            <div class="text-gray-500 text-sm mt-1">Réservation(s) au total</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="text-3xl font-black text-green-500">{{ $sejoursTotal }}</div>
            <div class="text-gray-500 text-sm mt-1">Séjour(s) effectué(s)</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="text-3xl font-black text-amber-500">
                {{ $prochaine ? $prochaine->date_arrivee->format('d/m/Y') : '—' }}
            </div>
            <div class="text-gray-500 text-sm mt-1">Prochaine arrivée</div>
        </div>
    </div>

    {{-- Prochaine réservation --}}
    @if($prochaine)
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 mb-6">
        <h2 class="font-bold text-amber-800 mb-4">Prochaine réservation</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-400 block text-xs mb-1">Chambre</span>
                <span class="font-semibold text-gray-800">{{ $prochaine->chambre->numero }} — {{ ucfirst($prochaine->chambre->type) }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-xs mb-1">Arrivée</span>
                <span class="font-semibold text-gray-800">{{ $prochaine->date_arrivee->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-xs mb-1">Départ</span>
                <span class="font-semibold text-gray-800">{{ $prochaine->date_depart->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-xs mb-1">Montant</span>
                <span class="font-bold text-amber-600">{{ number_format($prochaine->montant_total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gray-50 rounded-2xl p-8 text-center text-gray-400 mb-6">
        <div class="text-4xl mb-3">📅</div>
        <p class="mb-4">Aucune réservation à venir.</p>
        <a href="{{ route('chambres') }}"
           class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold transition shadow-md">
            Réserver une chambre
        </a>
    </div>
    @endif

</div>
@endsection
