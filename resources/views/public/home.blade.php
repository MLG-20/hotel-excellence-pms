@extends('layouts.public')

@section('title', 'Accueil')

@push('styles')
<style>
/* ─── Hero Slider ─────────────────────────────────────────── */
.hero-slider { position: relative; width: 100%; min-height: 620px; overflow: hidden; }

.slide {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.9s ease-in-out;
    pointer-events: none;
}
.slide.active { opacity: 1; pointer-events: auto; }

.slide-bg {
    position: absolute; inset: 0;
    background-size: cover; background-position: center;
    transform: scale(1.04);
    transition: transform 7s ease-out;
}
.slide.active .slide-bg { transform: scale(1); }

.slide-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(10,20,40,0.35) 0%,
        rgba(10,20,40,0.65) 60%,
        rgba(10,20,40,0.80) 100%
    );
}

.slide-content {
    position: relative; z-index: 10;
    text-align: center; padding: 0 1.5rem; max-width: 860px;
    transform: translateY(24px);
    transition: transform 0.9s ease-out, opacity 0.9s ease-out;
    opacity: 0;
}
.slide.active .slide-content { transform: translateY(0); opacity: 1; }

/* Fallback gradients si pas d'image uploadée */
.slide-grad-1 { background: linear-gradient(135deg, #0f2442 0%, #1a5276 40%, #c0922a 100%); }
.slide-grad-2 { background: linear-gradient(135deg, #0a2e1a 0%, #1a5c3a 50%, #0e4060 100%); }
.slide-grad-3 { background: linear-gradient(135deg, #1a0a3c 0%, #2e1065 50%, #0e3060 100%); }

/* Navigation dots */
.slider-dots { position: absolute; bottom: 1.75rem; left: 50%; transform: translateX(-50%); display: flex; gap: 0.6rem; z-index: 20; }
.dot {
    width: 10px; height: 10px; border-radius: 9999px;
    background: rgba(255,255,255,0.45); border: 2px solid rgba(255,255,255,0.6);
    cursor: pointer; transition: all 0.3s;
}
.dot.active { background: white; width: 28px; border-color: white; }

/* Nav arrows */
.slider-arrow {
    position: absolute; top: 50%; transform: translateY(-50%);
    z-index: 20; width: 48px; height: 48px; border-radius: 50%;
    background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.35);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.25s; color: white;
}
.slider-arrow:hover { background: rgba(255,255,255,0.30); border-color: white; transform: translateY(-50%) scale(1.08); }
.arrow-prev { left: 1.25rem; }
.arrow-next { right: 1.25rem; }

/* Progress bar */
.slider-progress {
    position: absolute; bottom: 0; left: 0; height: 3px;
    background: linear-gradient(90deg, #f59e0b, #f97316);
    z-index: 20; width: 0%;
    transition: none;
}
.slider-progress.animating { transition: width linear; }

/* ─── Carte chambre ───────────────────────────────────────── */
.chambre-img {
    width: 100%; height: 220px; object-fit: cover;
    transition: transform 0.5s ease;
}
.card-hover:hover .chambre-img { transform: scale(1.05); }
.chambre-img-wrap { overflow: hidden; height: 220px; position: relative; }

.type-badge {
    position: absolute; top: 0.75rem; left: 0.75rem;
    padding: 0.25rem 0.75rem; border-radius: 9999px;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em;
    text-transform: uppercase; backdrop-filter: blur(4px);
}
.badge-simple      { background: rgba(59,130,246,0.85); color: white; }
.badge-double      { background: rgba(99,102,241,0.85); color: white; }
.badge-suite       { background: rgba(245,158,11,0.85); color: white; }
.badge-familiale   { background: rgba(16,185,129,0.85); color: white; }
.badge-presidentielle { background: rgba(220,38,38,0.85); color: white; }

</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════ --}}
{{-- HERO SLIDER                                        --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="hero-slider" id="heroSlider">

    @if($sliders->isEmpty())
    {{-- Slide de secours si aucun slide en base --}}
    <div class="slide active">
        <div class="slide-bg slide-grad-1"></div>
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-3">Hôtel 5 étoiles — Thiès, Sénégal</p>
            <h1 class="text-white text-5xl md:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                Bienvenue à l'Hôtel Excellence
            </h1>
            <p class="text-gray-200 text-xl mb-8 max-w-xl mx-auto leading-relaxed">
                Un séjour d'exception vous attend — élégance, confort et service 5 étoiles au cœur de Thiès.
            </p>
            <a href="{{ route('chambres') }}"
               class="inline-block bg-amber-500 hover:bg-amber-400 text-white font-bold px-8 py-4 rounded-full text-lg shadow-2xl transition-all hover:scale-105">
                Découvrir nos chambres →
            </a>
        </div>
    </div>
    @else
    @foreach($sliders as $i => $slide)
    <div class="slide {{ $i === 0 ? 'active' : '' }}">
        @if($slide->image)
        <div class="slide-bg"
             style="background-image: url('{{ Storage::url($slide->image) }}')">
        </div>
        @else
        <div class="slide-bg slide-grad-{{ ($i % 3) + 1 }}"></div>
        @endif
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-3">
                Hôtel 5 étoiles — Thiès, Sénégal
            </p>
            <h1 class="text-white text-5xl md:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                {{ $slide->titre }}
            </h1>
            @if($slide->sous_titre)
            <p class="text-gray-200 text-xl mb-8 max-w-xl mx-auto leading-relaxed">
                {{ $slide->sous_titre }}
            </p>
            @endif
            @if($slide->bouton_texte)
            <a href="{{ $slide->bouton_lien ?? route('chambres') }}"
               class="inline-block bg-amber-500 hover:bg-amber-400 text-white font-bold px-8 py-4 rounded-full text-lg shadow-2xl transition-all hover:scale-105">
                {{ $slide->bouton_texte }} →
            </a>
            @endif
        </div>
    </div>
    @endforeach
    @endif

    {{-- Barre de progression --}}
    <div class="slider-progress" id="sliderProgress"></div>

    {{-- Navigation arrows --}}
    @if($sliders->count() > 1)
    <button class="slider-arrow arrow-prev" onclick="heroSlider.prev()" aria-label="Slide précédent">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button class="slider-arrow arrow-next" onclick="heroSlider.next()" aria-label="Slide suivant">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
    @endif

    {{-- Dots --}}
    @if($sliders->count() > 1)
    <div class="slider-dots" id="sliderDots">
        @foreach($sliders as $i => $slide)
        <button class="dot {{ $i === 0 ? 'active' : '' }}" onclick="heroSlider.goTo({{ $i }})" aria-label="Slide {{ $i+1 }}"></button>
        @endforeach
    </div>
    @endif


</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- STATISTIQUES                                       --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="bg-white py-12 border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 grid grid-cols-3 gap-6 text-center">
        <div class="group">
            <div class="text-4xl font-black text-amber-500 group-hover:scale-110 transition-transform inline-block">
                {{ $stats['chambres'] }}
            </div>
            <div class="text-gray-500 text-sm mt-1 font-medium">Chambres de prestige</div>
        </div>
        <div class="group">
            <div class="text-4xl font-black text-amber-500 group-hover:scale-110 transition-transform inline-block">
                {{ $stats['types'] }}
            </div>
            <div class="text-gray-500 text-sm mt-1 font-medium">Catégories d'hébergement</div>
        </div>
        <div class="group">
            <div class="text-4xl font-black text-amber-500 group-hover:scale-110 transition-transform inline-block">
                {{ $stats['clients'] }}+
            </div>
            <div class="text-gray-500 text-sm mt-1 font-medium">Clients satisfaits</div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- CHAMBRES DISPONIBLES                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 py-20">
    <div class="flex justify-between items-end mb-10">
        <div>
            <p class="text-amber-500 text-sm font-bold uppercase tracking-widest mb-1">Nos hébergements</p>
            <h2 class="text-3xl font-extrabold text-gray-900">Chambres disponibles</h2>
        </div>
        <a href="{{ route('chambres') }}"
           class="text-amber-500 font-semibold hover:text-amber-600 transition flex items-center gap-1">
            Voir tout
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @if($chambresDisponibles->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="mx-auto mb-4 w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <p class="text-lg font-medium">Aucune chambre disponible pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($chambresDisponibles as $chambre)
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
                        <span class="text-6xl opacity-60">
                            @switch($chambre->type)
                                @case('suite') 🛎 @break
                                @case('presidentielle') 👑 @break
                                @case('familiale') 👨‍👩‍👧 @break
                                @default 🛏
                            @endswitch
                        </span>
                    </div>
                    @endif

                    {{-- Badge type --}}
                    <span class="type-badge badge-{{ $chambre->type }}">{{ ucfirst($chambre->type) }}</span>

                    {{-- Dispo badge --}}
                    <span class="absolute top-0.5rem right-0.75rem px-2 py-1 rounded-full text-xs font-bold"
                          style="position:absolute;top:0.75rem;right:0.75rem;background:rgba(16,185,129,0.9);color:white">
                        ✓ Disponible
                    </span>
                </div>

                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-extrabold text-lg text-gray-900">Chambre {{ $chambre->numero }}</h3>
                        <div class="text-right">
                            <span class="text-xl font-black text-amber-600">{{ number_format($chambre->prix_nuit, 0, ',', ' ') }}</span>
                            <span class="text-gray-400 text-xs"> FCFA/nuit</span>
                        </div>
                    </div>

                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                        {{ $chambre->description ?? 'Chambre confortable et bien équipée pour votre séjour.' }}
                    </p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-5 pb-4 border-b border-gray-100">
                        <span class="flex items-center gap-1">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $chambre->capacite }} pers.
                        </span>
                        <span class="flex items-center gap-1">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Étage {{ $chambre->etage }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('chambre.detail', $chambre) }}"
                           class="flex-1 border-2 border-gray-200 text-gray-700 px-3 py-2.5 rounded-xl text-sm font-semibold text-center hover:border-amber-500 hover:text-amber-600 transition">
                            Voir détails
                        </a>
                        <a href="{{ route('reservation.form', $chambre) }}"
                           class="flex-1 bg-amber-500 hover:bg-amber-600 text-white px-3 py-2.5 rounded-xl text-sm font-bold text-center transition shadow-md hover:shadow-lg">
                            Réserver
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- AVANTAGES                                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="bg-gradient-to-br from-gray-900 to-gray-800 py-20 px-4">
    <div class="max-w-5xl mx-auto">
        <p class="text-amber-400 text-sm font-bold uppercase tracking-widest text-center mb-2">Notre engagement</p>
        <h2 class="text-3xl font-extrabold text-center text-white mb-12">Pourquoi choisir l'Hôtel Excellence ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition">
                <div class="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg">⚡</div>
                <h3 class="font-bold text-white text-lg mb-2">Réservation Instantanée</h3>
                <p class="text-gray-300 text-sm leading-relaxed">Confirmez votre séjour en quelques clics, 24h/24 et 7j/7, sans attente.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition">
                <div class="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg">📧</div>
                <h3 class="font-bold text-white text-lg mb-2">Confirmation par Email</h3>
                <p class="text-gray-300 text-sm leading-relaxed">Recevez un récapitulatif complet de votre réservation instantanément.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition">
                <div class="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg">🛎</div>
                <h3 class="font-bold text-white text-lg mb-2">Service 5 Étoiles</h3>
                <p class="text-gray-300 text-sm leading-relaxed">Notre équipe est entièrement dédiée à votre confort à chaque étape.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- SECTION À PROPOS (résumé)                         --}}
{{-- ═══════════════════════════════════════════════════ --}}
@if($pageAbout)
<section class="bg-amber-50 py-20 px-4 border-t border-amber-100">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-12">
        {{-- Icône décorative --}}
        <div class="flex-shrink-0 w-36 h-36 bg-amber-100 rounded-full flex items-center justify-center text-7xl shadow-inner">
            🏨
        </div>
        <div>
            <p class="text-amber-500 text-sm font-bold uppercase tracking-widest mb-2">À propos</p>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-4">{{ $pageAbout->titre }}</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                {{ Str::limit(str_replace("\n", ' ', $pageAbout->contenu), 220) }}
            </p>
            <a href="{{ route('a-propos') }}"
               class="inline-block border-2 border-amber-500 text-amber-600 hover:bg-amber-500 hover:text-white font-bold px-6 py-3 rounded-full transition">
                En savoir plus →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════ --}}
{{-- SECTION CONTACT (résumé)                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
@if($pageContact)
<section class="bg-gradient-to-br from-gray-900 to-gray-800 py-16 px-4 border-t border-gray-700">
    <div class="max-w-5xl mx-auto">
        <p class="text-amber-400 text-sm font-bold uppercase tracking-widest text-center mb-2">Nous trouver</p>
        <h2 class="text-2xl font-extrabold text-white text-center mb-10">{{ $pageContact->titre }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center mb-8">
            @if($pageContact->telephone)
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                <div class="text-3xl mb-3">📞</div>
                <p class="text-amber-400 text-xs font-bold uppercase tracking-wide mb-1">Téléphone</p>
                <a href="tel:{{ $pageContact->telephone }}" class="text-white font-semibold hover:text-amber-400 transition">
                    {{ $pageContact->telephone }}
                </a>
            </div>
            @endif

            @if($pageContact->email_contact)
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                <div class="text-3xl mb-3">✉️</div>
                <p class="text-amber-400 text-xs font-bold uppercase tracking-wide mb-1">Email</p>
                <a href="mailto:{{ $pageContact->email_contact }}" class="text-white font-semibold hover:text-amber-400 transition break-all">
                    {{ $pageContact->email_contact }}
                </a>
            </div>
            @endif

            @if($pageContact->adresse)
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                <div class="text-3xl mb-3">📍</div>
                <p class="text-amber-400 text-xs font-bold uppercase tracking-wide mb-1">Adresse</p>
                <p class="text-white font-semibold text-sm">{{ $pageContact->adresse }}</p>
            </div>
            @endif
        </div>

        <div class="text-center">
            <a href="{{ route('contact') }}"
               class="inline-block bg-amber-500 hover:bg-amber-400 text-white font-bold px-8 py-3 rounded-full transition shadow-lg">
                Voir toutes les informations →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════ --}}
{{-- AVIS CLIENTS                                       --}}
{{-- ═══════════════════════════════════════════════════ --}}
@if($avis->isNotEmpty())
<section class="bg-white py-20 px-4 border-t border-gray-100">
    <div class="max-w-6xl mx-auto">
        <p class="text-amber-500 text-sm font-bold uppercase tracking-widest text-center mb-2">Témoignages</p>
        <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-12">Ce que disent nos clients</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($avis as $avisItem)
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition">
                {{-- Étoiles --}}
                <div class="flex gap-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $avisItem->note ? 'text-amber-400' : 'text-gray-200' }} text-xl">★</span>
                    @endfor
                </div>

                {{-- Message --}}
                <p class="text-gray-600 text-sm leading-relaxed mb-5 italic">
                    "{{ $avisItem->message }}"
                </p>

                {{-- Auteur --}}
                <div class="flex items-center gap-3 border-t border-gray-200 pt-4">
                    @if($avisItem->photo)
                        <img src="{{ Storage::url($avisItem->photo) }}"
                             alt="{{ $avisItem->nom }}"
                             class="w-10 h-10 rounded-full object-cover border-2 border-amber-200">
                    @else
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-sm border-2 border-amber-200">
                            {{ strtoupper(substr($avisItem->nom, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $avisItem->nom }}</p>
                        <p class="text-gray-400 text-xs">Client vérifié</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
(function() {
    const slider  = document.getElementById('heroSlider');
    if (!slider) return;

    const slides   = slider.querySelectorAll('.slide');
    const dots     = slider.querySelectorAll('.dot');
    const progress = document.getElementById('sliderProgress');
    const DELAY    = 6000;

    if (slides.length <= 1) return;

    let current = 0;
    let timer   = null;

    function goTo(n) {
        slides[current].classList.remove('active');
        if (dots[current]) dots[current].classList.remove('active');

        current = (n + slides.length) % slides.length;

        slides[current].classList.add('active');
        if (dots[current]) dots[current].classList.add('active');

        // Réinitialiser la barre de progression
        if (progress) {
            progress.classList.remove('animating');
            progress.style.width = '0%';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    progress.classList.add('animating');
                    progress.style.transitionDuration = DELAY + 'ms';
                    progress.style.width = '100%';
                });
            });
        }
    }

    function next() { clearTimeout(timer); goTo(current + 1); startAuto(); }
    function prev() { clearTimeout(timer); goTo(current - 1); startAuto(); }

    function startAuto() {
        timer = setTimeout(() => { goTo(current + 1); startAuto(); }, DELAY);
    }

    // Exposer pour les boutons inline
    window.heroSlider = { next, prev, goTo: (n) => { clearTimeout(timer); goTo(n); startAuto(); } };

    // Pause au hover
    slider.addEventListener('mouseenter', () => clearTimeout(timer));
    slider.addEventListener('mouseleave', startAuto);

    goTo(0);
    startAuto();
})();
</script>
@endpush
