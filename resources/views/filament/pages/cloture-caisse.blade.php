<x-filament-panels::page>

    {{-- Sélecteur de date --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border p-4 mb-6 flex items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Date de clôture</label>
            <input type="date" wire:model.live="date_cloture" value="{{ $this->date_cloture }}"
                max="{{ now()->format('Y-m-d') }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <span class="text-gray-400 text-sm pb-2">Clôture du {{ \Carbon\Carbon::parse($this->date_cloture)->format('d/m/Y') }}</span>
    </div>

    @php $cloture = $this->getCloture(); @endphp

    {{-- KPIs du jour --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-2xl font-bold text-indigo-600">{{ number_format($cloture['totalEmis'], 0, ',', ' ') }}</div>
            <div class="text-xs text-gray-500 mt-1">Total émis (FCFA)</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-2xl font-bold text-green-600">{{ number_format($cloture['totalEncaisse'], 0, ',', ' ') }}</div>
            <div class="text-xs text-gray-500 mt-1">Encaissé (FCFA)</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-2xl font-bold text-orange-500">{{ number_format($cloture['totalTva'], 0, ',', ' ') }}</div>
            <div class="text-xs text-gray-500 mt-1">TVA collectée (FCFA)</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border p-5 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $cloture['nbFactures'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Factures émises</div>
        </div>
    </div>

    {{-- Mouvements du jour --}}
    <div class="grid md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-green-700">{{ $cloture['checkins'] }}</div>
            <div class="text-sm text-green-600">Check-ins du jour</div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-blue-700">{{ $cloture['checkouts'] }}</div>
            <div class="text-sm text-blue-600">Check-outs du jour</div>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-gray-700">{{ $cloture['facturesJour']->count() }}</div>
            <div class="text-sm text-gray-500">Toutes factures</div>
        </div>
    </div>

    {{-- Détail des factures --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border overflow-hidden">
        <div class="px-5 py-4 border-b">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200">Détail des factures du jour</h3>
        </div>
        @if($cloture['facturesJour']->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <div class="text-4xl mb-2">💰</div>
                <p>Aucune facture émise ce jour.</p>
            </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">N° Facture</th>
                    <th class="px-4 py-3 text-left">Client</th>
                    <th class="px-4 py-3 text-left">Chambre</th>
                    <th class="px-4 py-3 text-right">HT</th>
                    <th class="px-4 py-3 text-right">TVA</th>
                    <th class="px-4 py-3 text-right">TTC</th>
                    <th class="px-4 py-3 text-center">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($cloture['facturesJour'] as $facture)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-4 py-3 font-mono font-semibold text-blue-600">{{ $facture->numero }}</td>
                    <td class="px-4 py-3">{{ $facture->sejour?->reservation?->client?->prenom }} {{ $facture->sejour?->reservation?->client?->nom }}</td>
                    <td class="px-4 py-3">{{ $facture->sejour?->reservation?->chambre?->numero }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format($facture->total_ht, 0, ',', ' ') }}</td>
                    <td class="px-4 py-3 text-right text-orange-500">{{ number_format($facture->tva, 0, ',', ' ') }}</td>
                    <td class="px-4 py-3 text-right font-bold">{{ number_format($facture->total_ttc, 0, ',', ' ') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $facture->statut === 'payee' ? 'bg-green-100 text-green-700' : ($facture->statut === 'emise' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($facture->statut) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-700 font-bold">
                <tr>
                    <td colspan="3" class="px-4 py-3 text-right text-gray-600">TOTAUX :</td>
                    <td class="px-4 py-3 text-right">{{ number_format($cloture['facturesJour']->sum('total_ht'), 0, ',', ' ') }}</td>
                    <td class="px-4 py-3 text-right text-orange-500">{{ number_format($cloture['totalTva'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-3 text-right text-indigo-600">{{ number_format($cloture['totalEmis'], 0, ',', ' ') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @endif
    </div>

</x-filament-panels::page>
