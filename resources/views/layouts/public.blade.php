<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Hôtelière') — Hôtel Excellence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">

{{-- Navigation --}}
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-gray-900">
                🏨 <span>Hôtel <span class="text-amber-500">Excellence</span></span>
            </a>
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-amber-500 font-medium transition">Accueil</a>
                <a href="{{ route('chambres') }}" class="text-gray-600 hover:text-amber-500 font-medium transition">Nos Chambres</a>
                <a href="{{ route('a-propos') }}" class="text-gray-600 hover:text-amber-500 font-medium transition">À propos</a>
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-amber-500 font-medium transition">Contact</a>
                @auth
                    @if(auth()->user()->role?->value === 'client')
                        <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-amber-500 font-medium transition">Mon compte</a>
                        <form action="{{ route('client.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 text-sm transition">Déconnexion</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('client.login') }}" class="text-gray-600 hover:text-amber-500 font-medium transition">Mon compte</a>
                @endauth
                <a href="{{ route('chambres') }}" class="bg-amber-500 text-white px-5 py-2 rounded-full font-semibold hover:bg-amber-600 transition shadow-sm">
                    Réserver maintenant
                </a>
            </div>
            {{-- Mobile menu button --}}
            <button class="md:hidden p-2 rounded text-gray-500" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                ☰
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="hidden md:hidden px-4 pb-4 space-y-2 border-t border-gray-100">
        <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-amber-500 transition">Accueil</a>
        <a href="{{ route('chambres') }}" class="block py-2 text-gray-700 hover:text-amber-500 transition">Nos Chambres</a>
        <a href="{{ route('a-propos') }}" class="block py-2 text-gray-700 hover:text-amber-500 transition">À propos</a>
        <a href="{{ route('contact') }}" class="block py-2 text-gray-700 hover:text-amber-500 transition">Contact</a>
        @auth
            @if(auth()->user()->role?->value === 'client')
                <a href="{{ route('client.dashboard') }}" class="block py-2 text-gray-700">Mon compte</a>
            @endif
        @else
            <a href="{{ route('client.login') }}" class="block py-2 text-gray-700">Mon compte</a>
        @endauth
        <a href="{{ route('chambres') }}" class="block py-2 text-amber-500 font-semibold">Réserver →</a>
    </div>
</nav>

{{-- Flash messages --}}
@if(session('success'))
<div class="bg-amber-50 border-l-4 border-amber-400 text-amber-800 px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center gap-2">
        <span class="text-amber-500">✓</span> {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4">
    <div class="max-w-7xl mx-auto">⚠ {{ session('error') }}</div>
</div>
@endif

@yield('content')

{{-- Footer --}}
<footer class="bg-gray-900 text-gray-300 mt-16 py-12">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <h3 class="text-white font-bold text-lg mb-3">🏨 Hôtel <span class="text-amber-400">Excellence</span></h3>
            <p class="text-sm text-gray-400 leading-relaxed">Votre établissement de référence pour un séjour inoubliable. Confort, élégance et service personnalisé.</p>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-3">Navigation</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition">Accueil</a></li>
                <li><a href="{{ route('chambres') }}" class="hover:text-amber-400 transition">Nos chambres</a></li>
                <li><a href="{{ route('a-propos') }}" class="hover:text-amber-400 transition">À propos</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-amber-400 transition">Contact</a></li>
                <li><a href="{{ route('client.login') }}" class="hover:text-amber-400 transition">Espace client</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-3">Contact</h3>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2">📍 <span>Thiès, Sénégal</span></li>
                <li class="flex items-center gap-2">📞 <span>+221 33 000 00 00</span></li>
                <li class="flex items-center gap-2">✉ <span>contact@hotel-excellence.sn</span></li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-gray-800 flex items-center justify-between text-xs text-gray-500">
        <span>© {{ date('Y') }} Hôtel Excellence — Tous droits réservés</span>
        <span class="text-amber-500 font-semibold">★★★★★</span>
    </div>
</footer>

@stack('scripts')
</body>
</html>
