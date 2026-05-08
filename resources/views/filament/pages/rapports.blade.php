<x-filament-panels::page>

    {{-- Filtres de période --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Début de période</label>
            <input type="date" wire:model.live="date_debut" value="{{ $this->date_debut }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Fin de période</label>
            <input type="date" wire:model.live="date_fin" value="{{ $this->date_fin }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex gap-2">
            <button wire:click="$set('date_debut', '{{ now()->startOfMonth()->format('Y-m-d') }}')" wire:then="$set('date_fin', '{{ now()->endOfMonth()->format('Y-m-d') }}')"
                class="px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">Ce mois</button>
            <button wire:click="$set('date_debut', '{{ now()->startOfWeek()->format('Y-m-d') }}')" wire:then="$set('date_fin', '{{ now()->endOfWeek()->format('Y-m-d') }}')"
                class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Cette semaine</button>
        </div>
    </div>

    @php $stats = $this->getStats(); @endphp

    {{-- KPIs principaux --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['tauxOccupation'] }}%</div>
            <div class="text-sm text-gray-500 mt-1">Taux d'occupation</div>
            <div class="text-xs text-gray-400 mt-1">{{ $stats['chambresOccupees'] }}/{{ $stats['totalChambres'] }} chambres</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['revPAR'], 0, ',', ' ') }}</div>
            <div class="text-sm text-gray-500 mt-1">RevPAR (FCFA)</div>
            <div class="text-xs text-gray-400 mt-1">Revenu par chambre disponible</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-3xl font-bold text-indigo-600">{{ number_format($stats['revenus'], 0, ',', ' ') }}</div>
            <div class="text-sm text-gray-500 mt-1">Recettes période (FCFA)</div>
            <div class="text-xs text-gray-400 mt-1">Factures émises + payées</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">{{ number_format($stats['facturesPayees'], 0, ',', ' ') }}</div>
            <div class="text-sm text-gray-500 mt-1">Encaissé (FCFA)</div>
            <div class="text-xs text-gray-400 mt-1">Factures payées uniquement</div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-6">

        {{-- Recettes journalières --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">📈 Recettes journalières</h3>
            @php $revenusJour = $this->getRevenusParJour(); @endphp
            @if(empty($revenusJour))
                <p class="text-gray-400 text-sm text-center py-6">Aucune donnée sur cette période.</p>
            @else
                @php $max = max(array_column($revenusJour, 'total')); @endphp
                <div class="space-y-2">
                    @foreach($revenusJour as $jour)
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-10">{{ $jour['jour'] }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-4 overflow-hidden">
                            <div class="bg-blue-500 h-4 rounded-full transition-all"
                                style="width: {{ $max > 0 ? round(($jour['total']/$max)*100) : 0 }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-600 w-28 text-right">
                            {{ number_format($jour['total'], 0, ',', ' ') }} F
                        </span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Réservations par source --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">📊 Réservations par source</h3>
            @php $sources = $this->getReservationsParSource(); @endphp
            @if(empty($sources))
                <p class="text-gray-400 text-sm text-center py-6">Aucune réservation sur cette période.</p>
            @else
                @php $maxS = max(array_column($sources, 'total')); @endphp
                <div class="space-y-2">
                    @foreach($sources as $src)
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-20">{{ $src['source'] }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-4 overflow-hidden">
                            <div class="bg-indigo-500 h-4 rounded-full"
                                style="width: {{ $maxS > 0 ? round(($src['total']/$maxS)*100) : 0 }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-600 w-8 text-right">{{ $src['total'] }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Top chambres + résumé réservations --}}
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">🏆 Top chambres</h3>
            @php $top = $this->getTopChambres(); @endphp
            @forelse($top as $i => $item)
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <span class="text-sm text-gray-600">{{ $i+1 }}. {{ $item['chambre'] }}</span>
                <span class="text-sm font-bold text-blue-600">{{ $item['nb'] }} rés.</span>
            </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Aucune donnée.</p>
            @endforelse
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">📋 Résumé réservations</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Total réservations</span>
                    <span class="font-semibold">{{ $stats['nbReservations'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Check-outs effectués</span>
                    <span class="font-semibold text-green-600">{{ $stats['nbCheckouts'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">En cours (check-in)</span>
                    <span class="font-semibold text-blue-600">{{ $stats['chambresOccupees'] }}</span>
                </div>
                <hr>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Recettes totales</span>
                    <span class="font-bold text-indigo-600">{{ number_format($stats['revenus'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Encaissé</span>
                    <span class="font-bold text-green-600">{{ number_format($stats['facturesPayees'], 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
    </div>

</x-filament-panels::page>
