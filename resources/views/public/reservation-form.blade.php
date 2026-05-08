@extends('layouts.public')

@section('title', 'Réserver — Chambre '.$chambre->numero)

@section('content')

<div class="max-w-4xl mx-auto px-4 py-10">

    <a href="{{ route('chambre.detail', $chambre) }}"
       class="inline-flex items-center gap-1.5 text-amber-500 hover:text-amber-600 text-sm font-medium mb-6 transition">
        ← Retour à la chambre
    </a>

    <div class="grid md:grid-cols-3 gap-8">

        {{-- Formulaire --}}
        <div class="md:col-span-2">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Finaliser votre réservation</h1>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <ul class="text-red-600 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('reservation.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-amber-500 text-white rounded-full text-xs flex items-center justify-center font-bold">1</span>
                        Dates du séjour
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée <span class="text-red-500">*</span></label>
                            <input type="date" name="date_arrivee" value="{{ old('date_arrivee', $date_arrivee) }}"
                                min="{{ now()->format('Y-m-d') }}" required
                                class="w-full border-2 @error('date_arrivee') border-red-400 @else border-gray-100 @enderror rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ <span class="text-red-500">*</span></label>
                            <input type="date" name="date_depart" value="{{ old('date_depart', $date_depart) }}"
                                min="{{ now()->addDay()->format('Y-m-d') }}" required
                                class="w-full border-2 @error('date_depart') border-red-400 @else border-gray-100 @enderror rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adultes <span class="text-red-500">*</span></label>
                            <input type="number" name="adultes" value="{{ old('adultes', 1) }}" min="1" max="10" required
                                class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Enfants</label>
                            <input type="number" name="enfants" value="{{ old('enfants', 0) }}" min="0" max="10"
                                class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-amber-500 text-white rounded-full text-xs flex items-center justify-center font-bold">2</span>
                        Vos informations
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required placeholder="DIALLO"
                                class="w-full border-2 @error('nom') border-red-400 @else border-gray-100 @enderror rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required placeholder="Mamadou"
                                class="w-full border-2 @error('prenom') border-red-400 @else border-gray-100 @enderror rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="exemple@email.com"
                                class="w-full border-2 @error('email') border-red-400 @else border-gray-100 @enderror rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="+221 77 000 00 00"
                                class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                        <input type="text" name="nationalite" value="{{ old('nationalite') }}" placeholder="Sénégalaise"
                            class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Demandes spéciales</label>
                        <textarea name="notes" rows="3" placeholder="Chambre haute, lit bébé, arrivée tardive..."
                            class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-4 rounded-2xl text-lg font-bold transition shadow-lg hover:shadow-xl hover:scale-[1.01]">
                    Confirmer ma réservation →
                </button>
                <p class="text-xs text-gray-400 text-center">Vous recevrez une confirmation par email après validation.</p>
            </form>
        </div>

        {{-- Récapitulatif --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-20">
                <h3 class="font-bold text-gray-800 mb-4">Récapitulatif</h3>

                @php $firstImage = ($chambre->images && count($chambre->images) > 0) ? $chambre->images[0] : null; @endphp
                @if($firstImage)
                <img src="{{ Storage::url($firstImage) }}" alt="Chambre {{ $chambre->numero }}"
                     class="w-full h-32 object-cover rounded-xl mb-4">
                @else
                <div class="h-28 rounded-xl mb-4 flex items-center justify-center
                    @switch($chambre->type)
                        @case('presidentielle') bg-gradient-to-br from-rose-100 to-red-300 @break
                        @case('suite') bg-gradient-to-br from-amber-100 to-orange-300 @break
                        @case('familiale') bg-gradient-to-br from-emerald-100 to-teal-300 @break
                        @default bg-gradient-to-br from-blue-100 to-indigo-300
                    @endswitch">
                    <span class="text-4xl">
                        @switch($chambre->type)
                            @case('suite') 🛎 @break
                            @case('presidentielle') 👑 @break
                            @case('familiale') 👨‍👩‍👧 @break
                            @default 🛏
                        @endswitch
                    </span>
                </div>
                @endif

                <div class="font-semibold text-gray-800">Chambre {{ $chambre->numero }}</div>
                <div class="text-gray-500 text-sm mb-4">{{ ucfirst($chambre->type) }} — Étage {{ $chambre->etage }}</div>

                <div class="border-t border-gray-100 pt-3 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Prix/nuit</span>
                        <span>{{ number_format($chambre->prix_nuit, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 text-base border-t border-gray-100 pt-2 mt-2">
                        <span>Total estimé</span>
                        <span id="total-price" class="text-amber-600">—</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Le montant exact sera confirmé à la réception.</p>
            </div>
        </div>
    </div>
</div>

<script>
    const prixNuit = {{ $chambre->prix_nuit }};
    function updateTotal() {
        const arrivee = document.querySelector('[name="date_arrivee"]').value;
        const depart  = document.querySelector('[name="date_depart"]').value;
        if (arrivee && depart) {
            const nuits = Math.max(0, (new Date(depart) - new Date(arrivee)) / 86400000);
            document.getElementById('total-price').textContent =
                nuits > 0 ? new Intl.NumberFormat('fr-SN').format(nuits * prixNuit) + ' FCFA (' + nuits + ' nuit' + (nuits > 1 ? 's' : '') + ')' : '—';
        }
    }
    document.querySelectorAll('[name="date_arrivee"], [name="date_depart"]').forEach(el => el.addEventListener('change', updateTotal));
    updateTotal();
</script>

@endsection
