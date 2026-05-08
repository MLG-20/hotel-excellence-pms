<x-filament-panels::page>

    @php
        $couleurs = [
            'disponible'  => 'bg-green-50 border-green-300 text-green-800',
            'occupee'     => 'bg-red-50 border-red-300 text-red-800',
            'nettoyage'   => 'bg-blue-50 border-blue-300 text-blue-800',
            'maintenance' => 'bg-yellow-50 border-yellow-300 text-yellow-800',
        ];
        $emojis = [
            'disponible'  => '✅',
            'occupee'     => '🔴',
            'nettoyage'   => '🧹',
            'maintenance' => '🔧',
        ];
        $labels = [
            'disponible'  => 'Disponible',
            'occupee'     => 'Occupée',
            'nettoyage'   => 'Nettoyage',
            'maintenance' => 'Maintenance',
        ];
    @endphp

    {{-- Résumé en haut --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <div class="text-3xl font-bold text-green-700">{{ $stats['disponible'] ?? 0 }}</div>
            <div class="text-sm text-green-600 mt-1">Disponibles</div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <div class="text-3xl font-bold text-red-700">{{ $stats['occupee'] ?? 0 }}</div>
            <div class="text-sm text-red-600 mt-1">Occupées</div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <div class="text-3xl font-bold text-blue-700">{{ $stats['nettoyage'] ?? 0 }}</div>
            <div class="text-sm text-blue-600 mt-1">À nettoyer</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
            <div class="text-3xl font-bold text-yellow-700">{{ $stats['maintenance'] ?? 0 }}</div>
            <div class="text-sm text-yellow-600 mt-1">Maintenance</div>
        </div>
    </div>

    {{-- Légende --}}
    <div class="flex flex-wrap gap-4 mb-6 text-sm text-gray-600">
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-green-500"></span> Disponible</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-red-500"></span> Occupée</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-blue-400"></span> Nettoyage</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-yellow-400"></span> Maintenance</span>
    </div>

    {{-- Grille par étage --}}
    @forelse($parEtage as $etage => $chambresEtage)
    <div class="mb-8">
        <h2 class="text-lg font-bold text-gray-700 mb-3">
            🏢 Étage {{ $etage == 0 ? 'RDC' : $etage }}
            <span class="text-sm font-normal text-gray-400">({{ $chambresEtage->count() }} chambre(s))</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($chambresEtage as $chambre)
            <div class="border-2 rounded-xl p-4 {{ $couleurs[$chambre->statut] ?? 'bg-gray-50 border-gray-200' }}">
                <div class="text-2xl text-center mb-1">{{ $emojis[$chambre->statut] ?? '❓' }}</div>
                <div class="text-center font-bold text-lg">{{ $chambre->numero }}</div>
                <div class="text-center text-xs mb-2">{{ ucfirst($chambre->type) }} · {{ $chambre->capacite }} pers.</div>
                <div class="text-center text-xs font-semibold mb-3">{{ $labels[$chambre->statut] ?? $chambre->statut }}</div>

                <div class="flex flex-col gap-1">
                    @if(in_array($chambre->statut, ['nettoyage', 'maintenance']))
                    <button
                        wire:click="marquerDisponible({{ $chambre->id }})"
                        class="w-full text-xs bg-green-600 text-white py-1 px-2 rounded hover:bg-green-700 transition">
                        ✅ Disponible
                    </button>
                    @endif

                    @if($chambre->statut === 'disponible')
                    <button
                        wire:click="marquerNettoyage({{ $chambre->id }})"
                        class="w-full text-xs bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600 transition">
                        🧹 Nettoyage
                    </button>
                    <button
                        wire:click="marquerMaintenance({{ $chambre->id }})"
                        class="w-full text-xs bg-yellow-500 text-white py-1 px-2 rounded hover:bg-yellow-600 transition">
                        🔧 Maintenance
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="text-center text-gray-400 py-12">Aucune chambre trouvée.</div>
    @endforelse

</x-filament-panels::page>
