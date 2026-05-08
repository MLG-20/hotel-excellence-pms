@extends('layouts.public')

@section('title', $page?->titre ?? 'Contact')

@section('content')

{{-- ═══════════════════════════════════════════════════ --}}
{{-- HERO                                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="relative bg-gradient-to-br from-gray-900 to-gray-800 py-28 px-4 overflow-hidden">
    <div class="absolute inset-0 opacity-10"
         style="background-image: repeating-linear-gradient(45deg, #f59e0b 0, #f59e0b 1px, transparent 0, transparent 50%); background-size: 20px 20px;">
    </div>
    <div class="relative max-w-4xl mx-auto text-center">
        <p class="text-amber-400 text-sm font-bold uppercase tracking-widest mb-3">Hôtel Excellence</p>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">
            {{ $page?->titre ?? 'Nous contacter' }}
        </h1>
        <div class="w-16 h-1 bg-amber-500 mx-auto"></div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- INTRO + INFOS DE CONTACT                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="max-w-5xl mx-auto px-4 py-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">

        {{-- Texte introductif --}}
        <div>
            @if($page && $page->contenu)
                <div class="text-gray-700 leading-relaxed space-y-4 mb-8">
                    @foreach(explode("\n", $page->contenu) as $paragraphe)
                        @if(trim($paragraphe))
                            <p>{{ trim($paragraphe) }}</p>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Coordonnées --}}
            <div class="space-y-5">
                @if($page && $page->telephone)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 text-amber-600 text-xl">📞</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm uppercase tracking-wide mb-1">Téléphone</p>
                        <a href="tel:{{ $page->telephone }}" class="text-gray-700 hover:text-amber-600 transition font-medium">
                            {{ $page->telephone }}
                        </a>
                    </div>
                </div>
                @endif

                @if($page && $page->email_contact)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 text-amber-600 text-xl">✉️</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm uppercase tracking-wide mb-1">Email</p>
                        <a href="mailto:{{ $page->email_contact }}" class="text-gray-700 hover:text-amber-600 transition font-medium">
                            {{ $page->email_contact }}
                        </a>
                    </div>
                </div>
                @endif

                @if($page && $page->adresse)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 text-amber-600 text-xl">📍</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm uppercase tracking-wide mb-1">Adresse</p>
                        <p class="text-gray-700">{{ $page->adresse }}</p>
                    </div>
                </div>
                @endif

                @if($page && $page->horaires)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 text-amber-600 text-xl">🕐</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm uppercase tracking-wide mb-1">Horaires</p>
                        @foreach(explode("\n", $page->horaires) as $ligne)
                            @if(trim($ligne))
                                <p class="text-gray-700">{{ trim($ligne) }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!$page)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center text-gray-500">
                    Les informations de contact seront disponibles prochainement.
                </div>
                @endif
            </div>
        </div>

        {{-- CTA réservation --}}
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 text-white">
            <p class="text-amber-400 text-sm font-bold uppercase tracking-widest mb-2">Réservation rapide</p>
            <h2 class="text-2xl font-extrabold mb-4">Réservez en ligne</h2>
            <p class="text-gray-300 text-sm leading-relaxed mb-6">
                La façon la plus rapide de garantir votre séjour. Confirmation instantanée par email.
            </p>
            <a href="{{ route('chambres') }}"
               class="inline-block w-full bg-amber-500 hover:bg-amber-400 text-white font-bold py-4 rounded-xl text-center transition text-lg mb-3">
                Voir les chambres disponibles
            </a>
            <p class="text-gray-400 text-xs text-center">Disponible 24h/24, 7j/7 — Sans frais supplémentaires</p>

            <div class="border-t border-white/20 mt-6 pt-6">
                <p class="text-gray-300 text-sm mb-3 font-semibold">Accès espace client</p>
                <a href="{{ route('client.login') }}"
                   class="inline-block w-full border border-white/30 hover:border-amber-400 hover:text-amber-400 text-white font-semibold py-3 rounded-xl text-center transition text-sm">
                    Se connecter à mon espace →
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
