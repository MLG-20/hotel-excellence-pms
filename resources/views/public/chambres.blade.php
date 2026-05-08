@extends('layouts.public')

@section('title', 'Nos Chambres')

@push('styles')
<style>
.chambre-img-wrap { overflow: hidden; height: 240px; position: relative; }
.chambre-img { width: 100%; height: 240px; object-fit: cover; transition: transform 0.5s ease; }
.card-hover:hover .chambre-img { transform: scale(1.05); }
.type-badge {
    position: absolute; top: 0.75rem; left: 0.75rem;
    padding: 0.25rem 0.75rem; border-radius: 9999px;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
    backdrop-filter: blur(4px);
}
.badge-simple      { background: rgba(59,130,246,0.9); color: white; }
.badge-double      { background: rgba(99,102,241,0.9); color: white; }
.badge-suite       { background: rgba(245,158,11,0.9); color: white; }
.badge-familiale   { background: rgba(16,185,129,0.9); color: white; }
.badge-presidentielle { background: rgba(220,38,38,0.9); color: white; }
</style>
@endpush

@section('content')

{{-- Bannière de page --}}
<section class="relative bg-gradient-to-r from-blue-900 to-blue-700 text-white py-16 px-4 overflow-hidden">
    <div class="absolute inset-0 opacity-10"
         style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 0px, transparent 50%); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto relative">
        <p class="text-amber-400 text-sm font-bold uppercase tracking-widest mb-2">Hôtel Excellence</p>
        <h1 class="text-4xl font-extrabold mb-2">Nos Chambres & Suites</h1>
        <p class="text-blue-200 text-lg">Découvrez nos hébergements d'exception et réservez en ligne.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-10">

    {{-- Filtres --}}
    <form action="{{ route('chambres') }}" method="GET"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-10 grid grid-cols-2 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Arrivée</label>
            <input type="date" name="date_arrivee" value="{{ request('date_arrivee') }}" min="{{ now()->format('Y-m-d') }}"
                class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Départ</label>
            <input type="date" name="date_depart" value="{{ request('date_depart') }}" min="{{ now()->addDay()->format('Y-m-d') }}"
                class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Type</label>
            <select name="type" class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 transition">
                <option value="">Tous</option>
                <option value="simple"         @selected(request('type')==='simple')>Simple</option>
                <option value="double"         @selected(request('type')==='double')>Double</option>
                <option value="suite"          @selected(request('type')==='suite')>Suite</option>
                <option value="familiale"      @selected(request('type')==='familiale')>Familiale</option>
                <option value="presidentielle" @selected(request('type')==='presidentielle')>Présidentielle</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Capacité min.</label>
            <select name="capacite" class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 transition">
                <option value="">Toutes</option>
                <option value="1" @selected(request('capacite')==='1')>1 personne</option>
                <option value="2" @selected(request('capacite')==='2')>2 personnes</option>
                <option value="4" @selected(request('capacite')==='4')>4 personnes</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-2.5 px-3 rounded-xl text-sm font-bold transition shadow-md">
                Filtrer
            </button>
            <a href="{{ route('chambres') }}"
               class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition flex items-center">
                ✕
            </a>
        </div>
    </form>

    {{-- Résultats --}}
    @if($chambres->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <div class="text-6xl mb-4">😕</div>
            <p class="text-xl font-semibold text-gray-600 mb-2">Aucune chambre disponible pour ces critères.</p>
            <a href="{{ route('chambres') }}" class="mt-3 inline-block text-amber-500 font-semibold hover:underline">
                Voir toutes les chambres →
            </a>
        </div>
    @else
        <p class="text-gray-500 text-sm mb-6 font-medium">
            <span class="text-amber-600 font-bold">{{ $chambres->total() }}</span> chambre(s) trouvée(s)
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($chambres as $chambre)
            @php
                $gradients = [
                    'simple'         => 'from-blue-200 to-blue-400',
                    'double'         => 'from-indigo-200 to-purple-400',
                    'suite'          => 'from-amber-200 to-orange-400',
                    'familiale'      => 'from-emerald-200 to-teal-400',
                    'presidentielle' => 'from-rose-200 to-red-500',
                ];
                $grad = $gradients[$chambre->type] ?? 'from-gray-200 to-gray-400';
                $firstImage = ($chambre->images && count($chambre->images) > 0) ? $chambre->images[0] : null;
            @endphp

            <div class="bg-white rounded-2xl shadow-md overflow-hidden card-hover group">
                <div class="chambre-img-wrap">
                    @if($firstImage)
                    <img src="{{ Storage::url($firstImage) }}"
                         alt="Chambre {{ $chambre->numero }}"
                         class="chambre-img">
                    @else
                    <div class="chambre-img bg-gradient-to-br {{ $grad }} flex items-center justify-center">
                        <span class="text-7xl opacity-50">
                            @switch($chambre->type)
                                @case('suite') 🛎 @break
                                @case('presidentielle') 👑 @break
                                @case('familiale') 👨‍👩‍👧 @break
                                @default 🛏
                            @endswitch
                        </span>
                    </div>
                    @endif

                    <span class="type-badge badge-{{ $chambre->type }}">{{ ucfirst($chambre->type) }}</span>

                    <span style="position:absolute;top:0.75rem;right:0.75rem;background:rgba(16,185,129,0.9);color:white;padding:0.2rem 0.6rem;border-radius:9999px;font-size:0.7rem;font-weight:700;">
                        ✓ Disponible
                    </span>
                </div>

                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-extrabold text-lg text-gray-900">Chambre {{ $chambre->numero }}</h3>
                        <div class="text-right">
                            <span class="text-xl font-black text-amber-600">{{ number_format($chambre->prix_nuit, 0, ',', ' ') }}</span>
                            <span class="text-gray-400 text-xs block">FCFA/nuit</span>
                        </div>
                    </div>

                    <p class="text-gray-500 text-sm mb-4 leading-relaxed line-clamp-2">
                        {{ $chambre->description ?? 'Chambre confortable et bien équipée pour votre séjour.' }}
                    </p>

                    <div class="flex gap-4 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                        <span>👤 {{ $chambre->capacite }} pers.</span>
                        <span>🏢 Étage {{ $chambre->etage }}</span>
                        @if($chambre->images && count($chambre->images) > 1)
                        <span class="text-blue-500 font-medium">📷 {{ count($chambre->images) }} photos</span>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('chambre.detail', $chambre) }}"
                           class="flex-1 border-2 border-gray-200 text-gray-700 py-2.5 rounded-xl text-sm font-semibold text-center hover:border-amber-500 hover:text-amber-600 transition">
                            Voir détails
                        </a>
                        <a href="{{ route('reservation.form', array_merge(['chambre' => $chambre->id], request()->only(['date_arrivee','date_depart']))) }}"
                           class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-2.5 rounded-xl text-sm font-bold text-center transition shadow-md hover:shadow-lg">
                            Réserver
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $chambres->links() }}
        </div>
    @endif
</section>

@endsection
