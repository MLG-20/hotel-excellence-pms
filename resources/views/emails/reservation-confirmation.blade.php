<x-mail::message>
# Votre réservation est enregistrée !

Bonjour **{{ $reservation->client->prenom }} {{ $reservation->client->nom }}**,

Nous avons bien reçu votre demande de réservation à l'**Hôtel Excellence**. Notre équipe va la traiter dans les plus brefs délais.

---

## Récapitulatif

| | |
|---|---|
| **Référence** | #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }} |
| **Chambre** | {{ $reservation->chambre->numero }} — {{ ucfirst($reservation->chambre->type) }} |
| **Arrivée** | {{ $reservation->date_arrivee->format('d/m/Y') }} |
| **Départ** | {{ $reservation->date_depart->format('d/m/Y') }} |
| **Durée** | {{ $reservation->nombre_nuitees }} nuit(s) |
| **Adultes** | {{ $reservation->adultes }} |
| **Montant estimé** | {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA |

---

> **Important :** Le paiement s'effectue à votre arrivée à la réception. Merci de vous présenter avec une pièce d'identité.

@if ($motDePasse)
---

## Votre espace client

Nous avons créé un compte pour vous permettre de suivre vos réservations et consulter vos factures en ligne.

| | |
|---|---|
| **Email** | {{ $reservation->client->email }} |
| **Mot de passe** | `{{ $motDePasse }}` |

<x-mail::button :url="route('client.login')">
Accéder à mon espace client
</x-mail::button>

> Nous vous recommandons de changer votre mot de passe après votre première connexion.

@else

<x-mail::button :url="route('client.dashboard')">
Accéder à mon espace client
</x-mail::button>

@endif

Merci de votre confiance,

**L'équipe Hôtel Excellence**
📍 Thiès, Sénégal — 📞 +221 33 123 45 67
</x-mail::message>
