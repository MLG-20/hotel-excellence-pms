@extends('layouts.public')

@section('title', 'Chambre '.$chambre->numero.' — '.ucfirst($chambre->type))

@push('styles')
<style>
/* Galerie principale */
.gallery-main { width: 100%; height: 420px; object-fit: cover; border-radius: 1rem 1rem 0 0; }
.gallery-main-wrap { overflow: hidden; border-radius: 1rem 1rem 0 0; height: 420px; position: relative; }

/* Vignettes */
.thumb-strip { display: flex; gap: 0.5rem; padding: 0.5rem; background: #f9fafb; border-top: 1px solid #e5e7eb; }
.thumb {
    width: 80px; height: 60px; object-fit: cover; border-radius: 0.5rem;
    cursor: pointer; border: 2px solid transparent;
    transition: all 0.2s; opacity: 0.65; flex-shrink: 0;
}
.thumb:hover, .thumb.active { opacity: 1; border-color: #f59e0b; }

/* Gradient placeholder */
.grad-simple         { background: linear-gradient(135deg, #bfdbfe 0%, #60a5fa 100%); }
.grad-double         { background: linear-gradient(135deg, #c7d2fe 0%, #818cf8 100%); }
.grad-suite          { background: linear-gradient(135deg, #fde68a 0%, #fb923c 100%); }
.grad-familiale      { background: linear-gradient(135deg, #a7f3d0 0%, #2dd4bf 100%); }
.grad-presidentielle { background: linear-gradient(135deg, #fecdd3 0%, #ef4444 100%); }

.type-badge-lg {
    display: inline-block; padding: 0.35rem 1rem; border-radius: 9999px;
    font-size: 0.75rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
}
.badge-simple      { background: #dbeafe; color: #1d4ed8; }
.badge-double      { background: #e0e7ff; color: #4338ca; }
.badge-suite       { background: #fef3c7; color: #92400e; }
.badge-familiale   { background: #d1fae5; color: #065f46; }
.badge-presidentielle { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')

<div class="max-w-5xl mx-auto px-4 py-10">

    <a href="{{ route('chambres') }}"
       class="inline-flex items-center gap-1.5 text-blue-600 hover:text-amber-500 text-sm font-medium mb-6 transition">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour aux chambres
    </a>

    @php
        $images = $chambre->images ?? [];
        $hasImages = count($images) > 0;
        $gradClass = 'grad-' . $chambre->type;
    @endphp

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        {{-- ── Galerie ─────────────────────────────────── --}}
        @if($hasImages)
        <div class="gallery-main-wrap" id="galleryMain">
            <img src="{{ Storage::url($images[0]) }}"
                 alt="Chambre {{ $chambre->numero }}"
                 class="gallery-main"
                 id="mainImage">
        </div>
        @if(count($images) > 1)
        <div class="thumb-strip" id="thumbStrip">
            @foreach($images as $i => $img)
            <img src="{{ Storage::url($img) }}"
                 alt="Photo {{ $i+1 }}"
                 class="thumb {{ $i === 0 ? 'active' : '' }}"
                 onclick="switchImage('{{ Storage::url($img) }}', this)">
            @endforeach
        </div>
        @endif
        @else
        {{-- Pas d'image : gradient de qualité --}}
        <div class="gallery-main-wrap {{ $gradClass }} flex items-center justify-center">
            <span class="text-9xl opacity-40">
                @switch($chambre->type)
                    @case('suite') 🛎 @break
                    @case('presidentielle') 👑 @break
                    @case('familiale') 👨‍👩‍👧 @break
                    @default 🛏
                @endswitch
            </span>
        </div>
        @endif

        {{-- ── Contenu ─────────────────────────────────── --}}
        <div class="p-8 grid md:grid-cols-2 gap-10">

            {{-- Infos --}}
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <h1 class="text-2xl font-extrabold text-gray-900">Chambre {{ $chambre->numero }}</h1>
                    <span class="type-badge-lg badge-{{ $chambre->type }}">{{ ucfirst($chambre->type) }}</span>
                    @if($chambre->statut === 'disponible')
                        <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                            ✓ Disponible
                        </span>
                    @else
                        <span class="inline-block bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">
                            Indisponible
                        </span>
                    @endif
                </div>

                <p class="text-gray-600 mb-8 leading-relaxed">
                    {{ $chambre->description ?? 'Chambre confortable et bien équipée pour votre séjour. Profitez d\'un environnement soigné et d\'un service attentionné.' }}
                </p>

                {{-- Caractéristiques --}}
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 rounded-xl p-4 flex items-start gap-3">
                        <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg width="18" height="18" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-400 text-xs mb-0.5">Capacité</div>
                            <div class="font-bold text-gray-800">{{ $chambre->capacite }} personne(s)</div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 flex items-start gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-400 text-xs mb-0.5">Étage</div>
                            <div class="font-bold text-gray-800">Étage {{ $chambre->etage }}</div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 flex items-start gap-3">
                        <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg width="18" height="18" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-400 text-xs mb-0.5">Catégorie</div>
                            <div class="font-bold text-gray-800">{{ ucfirst($chambre->type) }}</div>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-xl p-4 flex items-start gap-3 border border-amber-100">
                        <div class="w-9 h-9 bg-amber-400 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-amber-600 text-xs mb-0.5">Prix / nuit</div>
                            <div class="font-black text-amber-700 text-lg">{{ number_format($chambre->prix_nuit, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulaire de réservation --}}
            <div>
                <div class="bg-gradient-to-br from-blue-900 to-blue-700 rounded-2xl p-6 text-white">
                    <h2 class="font-extrabold text-xl mb-1">Réserver cette chambre</h2>
                    <p class="text-blue-200 text-sm mb-6">Confirmez votre séjour en quelques secondes</p>

                    @if($chambre->statut === 'disponible')
                    <form action="{{ route('reservation.form', $chambre) }}" method="GET" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-blue-200 mb-1.5 uppercase tracking-wide">Date d'arrivée</label>
                            <input type="date" name="date_arrivee"
                                   value="{{ now()->addDay()->format('Y-m-d') }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="w-full border-2 border-blue-600 bg-blue-800/50 text-white rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-blue-200 mb-1.5 uppercase tracking-wide">Date de départ</label>
                            <input type="date" name="date_depart"
                                   value="{{ now()->addDays(2)->format('Y-m-d') }}"
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   class="w-full border-2 border-blue-600 bg-blue-800/50 text-white rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>

                        <div class="bg-blue-800/40 rounded-xl px-4 py-3 text-sm">
                            <div class="flex justify-between text-blue-200">
                                <span>Prix par nuit</span>
                                <span class="font-bold text-white">{{ number_format($chambre->prix_nuit, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-amber-500 hover:bg-amber-400 text-white py-3.5 rounded-xl font-extrabold transition shadow-lg hover:shadow-xl hover:scale-[1.02] text-base">
                            Continuer la réservation →
                        </button>
                    </form>
                    @else
                        <div class="bg-red-500/20 border border-red-400/40 rounded-xl p-4 text-center">
                            <p class="text-red-200 font-semibold mb-3">Cette chambre est actuellement indisponible.</p>
                            <a href="{{ route('chambres') }}"
                               class="inline-block bg-white text-blue-900 px-5 py-2 rounded-xl font-bold text-sm hover:bg-blue-50 transition">
                                Voir d'autres chambres
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Avantages rapides --}}
                <div class="mt-5 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg width="16" height="16" fill="none" stroke="#10b981" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirmation instantanée par email
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg width="16" height="16" fill="none" stroke="#10b981" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Réservation sans frais cachés
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg width="16" height="16" fill="none" stroke="#10b981" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Service disponible 24h/24
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function switchImage(src, thumb) {
    const main = document.getElementById('mainImage');
    if (main) main.src = src;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>
@endpush
