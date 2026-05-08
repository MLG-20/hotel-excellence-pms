@extends('layouts.public')

@section('title', 'Mon compte — Connexion')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl">🔑</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Accéder à mon espace client</h1>
            <p class="text-gray-500 mt-2 text-sm">Vos identifiants vous ont été envoyés par email lors de votre réservation.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('client.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                    <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
                </div>

                <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-3.5 rounded-xl font-bold transition shadow-md hover:shadow-lg">
                    Se connecter →
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Pas encore de compte ?
                <a href="{{ route('chambres') }}" class="text-amber-500 hover:text-amber-600 font-semibold">
                    Faites une réservation
                </a>
                et un compte sera créé automatiquement.
            </p>
        </div>
    </div>
</div>
@endsection
