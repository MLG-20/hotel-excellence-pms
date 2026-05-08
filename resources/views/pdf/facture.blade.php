<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #1a56db; padding-bottom: 15px; }
        .hotel-name { font-size: 22px; font-weight: bold; color: #1a56db; }
        .hotel-info { font-size: 11px; color: #666; }
        .facture-title { text-align: right; }
        .facture-title h2 { font-size: 20px; color: #1a56db; margin: 0; }
        .facture-meta { font-size: 11px; color: #444; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #1a56db; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 8px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a56db; color: white; padding: 8px; text-align: left; font-size: 11px; }
        td { padding: 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .total-table { margin-top: 15px; width: 50%; margin-left: auto; }
        .total-table td { border: none; padding: 5px 8px; }
        .total-row td { font-weight: bold; font-size: 14px; background: #1a56db; color: white; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #888; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <div class="hotel-name">🏨 Gestion Hôtelière</div>
        <div class="hotel-info">
            Système PMS — Panel Administratif<br>
            Email : admin@hotel.com
        </div>
    </div>
    <div class="facture-title">
        <h2>FACTURE</h2>
        <div class="facture-meta">
            <strong>N° :</strong> {{ $facture->numero }}<br>
            <strong>Date :</strong> {{ $facture->date_emission?->format('d/m/Y H:i') ?? now()->format('d/m/Y') }}<br>
            <strong>Statut :</strong>
            <span class="badge {{ $facture->statut === 'payee' ? 'badge-success' : 'badge-warning' }}">
                {{ ucfirst($facture->statut) }}
            </span>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Informations Client</div>
    @php $client = $facture->sejour->reservation->client; @endphp
    <strong>{{ $client->prenom }} {{ $client->nom }}</strong><br>
    Email : {{ $client->email }}<br>
    Tél : {{ $client->telephone ?? '—' }}<br>
    Nationalité : {{ $client->nationalite ?? '—' }}<br>
    N° Passeport/CNI : {{ $client->passeport ?? '—' }}
</div>

<div class="section">
    <div class="section-title">Détails du Séjour</div>
    @php
        $reservation = $facture->sejour->reservation;
        $chambre = $reservation->chambre;
        $sejour = $facture->sejour;
    @endphp
    <table>
        <tr>
            <th>Chambre</th>
            <th>Type</th>
            <th>Arrivée</th>
            <th>Départ</th>
            <th>Nuitées</th>
            <th>Prix/nuit</th>
        </tr>
        <tr>
            <td>{{ $chambre->numero }}</td>
            <td>{{ ucfirst($chambre->type) }}</td>
            <td>{{ $reservation->date_arrivee->format('d/m/Y') }}</td>
            <td>{{ $reservation->date_depart->format('d/m/Y') }}</td>
            <td>{{ $reservation->nombre_nuitees }}</td>
            <td>{{ number_format($chambre->prix_nuit, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>
</div>

@if(!empty($sejour->extras))
<div class="section">
    <div class="section-title">Extras & Services</div>
    <table>
        <tr>
            <th>Description</th>
            <th>Montant</th>
        </tr>
        @foreach($sejour->extras as $extra)
        <tr>
            <td>{{ $extra['description'] ?? 'Extra' }}</td>
            <td>{{ number_format($extra['montant'] ?? 0, 0, ',', ' ') }} FCFA</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

<div class="section">
    <div class="section-title">Récapitulatif</div>
    <table class="total-table">
        <tr>
            <td>Nuitées :</td>
            <td align="right">{{ number_format($facture->nuitees, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td>Extras :</td>
            <td align="right">{{ number_format($facture->extras, 0, ',', ' ') }} FCFA</td>
        </tr>
        @if($facture->remise > 0)
        <tr>
            <td>Remise :</td>
            <td align="right">- {{ number_format($facture->remise, 0, ',', ' ') }} FCFA</td>
        </tr>
        @endif
        <tr>
            <td>Total HT :</td>
            <td align="right">{{ number_format($facture->total_ht, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td>TVA (18%) :</td>
            <td align="right">{{ number_format($facture->tva, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL TTC :</td>
            <td align="right">{{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>
</div>

<div class="footer">
    Merci de votre confiance — Gestion Hôtelière PMS<br>
    Document généré le {{ now()->format('d/m/Y à H:i') }}
</div>

</body>
</html>
