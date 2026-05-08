@extends('layouts.public')

@section('title', $page?->titre ?? 'À propos')

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
            {{ $page?->titre ?? 'À propos de nous' }}
        </h1>
        <div class="w-16 h-1 bg-amber-500 mx-auto"></div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- CONTENU PRINCIPAL                                  --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="max-w-4xl mx-auto px-4 py-20">
    @if($page && $page->contenu)
        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed space-y-4">
            @foreach(explode("\n", $page->contenu) as $paragraphe)
                @if(trim($paragraphe))
                    <p>{{ trim($paragraphe) }}</p>
                @endif
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center text-lg">Page en cours de rédaction. Revenez bientôt !</p>
    @endif
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- VALEURS                                            --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="bg-amber-50 py-16 px-4 border-t border-amber-100">
    <div class="max-w-5xl mx-auto">
        <p class="text-amber-500 text-sm font-bold uppercase tracking-widest text-center mb-2">Nos valeurs</p>
        <h2 class="text-2xl font-extrabold text-center text-gray-900 mb-10">Ce qui nous guide au quotidien</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-amber-100">
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">🤝</div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Hospitalité</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Un accueil chaleureux et personnalisé pour chaque client, dès la première minute.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-amber-100">
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">✨</div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Excellence</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Des standards de qualité irréprochables dans chaque détail de votre séjour.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-amber-100">
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">🌍</div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Authenticité</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Une expérience ancrée dans la culture sénégalaise, rayonnante d'authenticité.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- CTA                                                --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-16 px-4 text-center bg-white border-t border-gray-100">
    <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Prêt à vivre l'expérience Excellence ?</h2>
    <p class="text-gray-500 mb-8 max-w-xl mx-auto">Réservez dès maintenant et laissez-vous chouchouter par notre équipe dédiée.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('chambres') }}"
           class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-4 rounded-full text-lg transition shadow-md hover:shadow-lg">
            Voir nos chambres →
        </a>
        <a href="{{ route('contact') }}"
           class="inline-block border-2 border-amber-500 text-amber-600 hover:bg-amber-50 font-bold px-8 py-4 rounded-full text-lg transition">
            Nous contacter
        </a>
    </div>
</section>

@endsection
