# Gestion Hôtelière — Hôtel Excellence

[![Laravel](https://img.shields.io/badge/Laravel-13.8-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-4.x-FDAE4B?style=for-the-badge&logo=filament&logoColor=white)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/Licence-MIT-green?style=for-the-badge)](LICENSE)

Système de gestion hôtelière (PMS) complet développé avec **Laravel 13.8** et **Filament 4**, couvrant l'ensemble du cycle de vie d'un séjour : réservation en ligne, check-in, room service, facturation avec TVA, check-out, et téléchargement de factures PDF.

> Projet réalisé dans le cadre du cours L3 MIO — Université de Thiès, Sénégal — Pr SOW

---

## Table des matières

1. [Aperçu du projet](#1-aperçu-du-projet)
2. [Stack technique](#2-stack-technique)
3. [Architecture multi-acteurs](#3-architecture-multi-acteurs)
4. [Modèles et base de données](#4-modèles-et-base-de-données)
5. [Chambres disponibles](#5-chambres-disponibles)
6. [Routes publiques](#6-routes-publiques)
7. [Fonctionnalités par panel](#7-fonctionnalités-par-panel)
   - [Panel Directeur](#71-panel-directeur--directeur)
   - [Panel Réceptionniste](#72-panel-réceptionniste--reception)
   - [Panel Gouvernante](#73-panel-gouvernante--gouvernante)
   - [Espace Client](#74-espace-client--mon-compte)
8. [Flux métier principal](#8-flux-métier-principal)
9. [Structure des dossiers](#9-structure-des-dossiers)
10. [Installation locale](#10-installation-locale)
11. [Variables d'environnement](#11-variables-denvironnement)
12. [Comptes de test](#12-comptes-de-test)
13. [Déploiement Railway](#13-déploiement-railway)
14. [Captures d'écran](#14-captures-décran)
15. [Licence](#15-licence)

---

## 1. Aperçu du projet

**Hôtel Excellence** est un PMS (Property Management System) conçu pour répondre aux besoins opérationnels d'un établissement hôtelier de taille moyenne. Il expose :

- Un **site vitrine public** dynamique (slider, liste de chambres, formulaire de réservation en ligne, pages À propos et Contact) entièrement géré depuis le back-office.
- Quatre **panels d'administration séparés** avec guards et routes distincts, chacun adapté au métier de son utilisateur (Directeur, Réceptionniste, Gouvernante, Client).
- Une **facturation automatique** avec TVA à 18 %, génération de PDF via DomPDF, et espace de téléchargement sécurisé pour le client.
- Un **flux métier complet** : réservation → confirmation e-mail → check-in → room service → check-out → facture.

---

## 2. Stack technique

| Technologie | Version | Rôle |
|---|---|---|
| PHP | 8.2+ | Langage serveur |
| Laravel | 13.8 | Framework principal |
| Filament | 4.x | Panels d'administration |
| MySQL | 8.x | Base de données relationnelle |
| DomPDF (`barryvdh/laravel-dompdf`) | latest | Génération de factures PDF |
| Tailwind CSS | CDN | Style des vues publiques Blade |
| Inter (Google Fonts) | — | Police d'interface |

---

## 3. Architecture multi-acteurs

Le projet repose sur **quatre panels totalement indépendants**, chacun avec son propre guard Laravel, ses propres routes et ses propres ressources Filament.

| Acteur | URL d'accès | Guard | E-mail de connexion |
|---|---|---|---|
| Directeur | `/directeur` | `directeur` | directeur@hotel.com |
| Réceptionniste | `/reception` | `reception` | reception@hotel.com |
| Gouvernante | `/gouvernante` | `gouvernante` | gouvernante@hotel.com |
| Client | `/mon-compte` | `web` (Blade custom) | créé automatiquement à la réservation |

Chaque panel est défini dans `app/Filament/{NomPanel}/` et déclaré dans `app/Providers/Filament/{NomPanel}PanelProvider.php`. Le mot de passe par défaut pour tous les comptes de test est `password`.

> **Remarque de sécurité :** en production, modifier impérativement tous les mots de passe et configurer les variables d'environnement correspondantes.

---

## 4. Modèles et base de données

### `User` — table `users`

| Colonne | Type | Notes |
|---|---|---|
| name | string | — |
| email | string | unique |
| password | string | bcrypt |
| role | enum | `directeur`, `receptionniste`, `gouvernante`, `client` |

La logique de rôle est encapsulée dans `app/Enums/Role.php`.

---

### `Chambre` — table `chambres`

| Colonne | Type | Notes |
|---|---|---|
| numero | string | — |
| type | enum | `simple`, `double`, `familiale`, `suite`, `presidentielle` |
| etage | integer | — |
| capacite | integer | Nombre de personnes |
| prix_nuit | decimal | En FCFA |
| statut | enum | `disponible`, `occupee`, `nettoyage`, `maintenance` |
| description | text | — |
| images | JSON | Tableau de chemins (max 8 photos) |

---

### `ClientHotel` — table `clients_hotel`

| Colonne | Type | Notes |
|---|---|---|
| nom | string | — |
| prenom | string | — |
| email | string | — |
| telephone | string | — |
| nationalite | string | — |
| passeport | string | Optionnel |

---

### `Reservation` — table `reservations`

| Colonne | Type | Notes |
|---|---|---|
| client_id | FK → clients_hotel | — |
| chambre_id | FK → chambres | — |
| date_arrivee | date | — |
| date_depart | date | — |
| adultes | integer | — |
| enfants | integer | — |
| statut | enum | `en_attente`, `confirmee`, `checkin`, `checkout`, `annulee` |
| source | string | Ex. : site web, téléphone |
| notes | text | Optionnel |
| montant_total | decimal | Calculé automatiquement |

---

### `Sejour` — table `sejours`

| Colonne | Type | Notes |
|---|---|---|
| reservation_id | FK → reservations | — |
| check_in | datetime | — |
| check_out | datetime | — |
| total | decimal | — |
| statut_paiement | enum | `en_attente`, `paye` |
| extras | JSON | Prestations supplémentaires |

---

### `RoomService` — table `room_services`

| Colonne | Type | Notes |
|---|---|---|
| sejour_id | FK → sejours | — |
| articles | JSON | Liste des articles commandés |
| total | decimal | — |
| heure_commande | datetime | — |
| statut | string | — |

---

### `Facture` — table `factures`

| Colonne | Type | Notes |
|---|---|---|
| sejour_id | FK → sejours | — |
| numero | string | Généré automatiquement |
| nuitees | decimal | Montant des nuits |
| extras | decimal | Montant des extras |
| remise | decimal | — |
| total_ht | decimal | — |
| tva | decimal | 18 % fixe |
| total_ttc | decimal | — |
| statut | enum | `brouillon`, `emise`, `payee`, `annulee` |
| date_emission | date | — |

---

### `SliderHero` — table `slider_heros`

| Colonne | Type | Notes |
|---|---|---|
| titre | string | — |
| sous_titre | string | — |
| image | string | Chemin fichier |
| bouton_texte | string | — |
| bouton_lien | string | — |
| ordre | integer | Ordre d'affichage |
| actif | boolean | — |

---

### `PageSite` — table `page_sites`

| Colonne | Type | Notes |
|---|---|---|
| slug | string | unique : `about` ou `contact` |
| titre | string | — |
| contenu | longtext | — |
| telephone | string | — |
| email_contact | string | — |
| adresse | string | — |
| horaires | string | — |
| actif | boolean | — |

---

### `AvisClient` — table `avis_clients`

| Colonne | Type | Notes |
|---|---|---|
| nom | string | — |
| message | text | — |
| note | integer | Entre 1 et 5 |
| photo | string | Optionnel |
| ordre | integer | Ordre d'affichage |
| actif | boolean | — |

---

## 5. Chambres disponibles

L'hôtel dispose de **24 chambres** réparties sur 5 étages, seedées automatiquement à l'installation.

| Étage | Type | Numéros | Prix / nuit |
|---|---|---|---|
| 1 | Simples | 101 → 106 | 25 000 – 27 000 FCFA |
| 2 | Doubles | 201 → 206 | 45 000 – 50 000 FCFA |
| 3 | Familiales | 301 → 305 | 75 000 – 85 000 FCFA |
| 4 | Suites | 401 → 404 | 120 000 – 150 000 FCFA |
| 5 | Présidentielles | 501 → 503 | 250 000 – 350 000 FCFA |

---

## 6. Routes publiques

| Méthode | URI | Description |
|---|---|---|
| GET | `/` | Page d'accueil (slider hero, chambres disponibles, avantages, témoignages, contact) |
| GET | `/chambres` | Liste de toutes les chambres avec filtres (type, capacité, prix, dates) |
| GET | `/chambres/{chambre}` | Détail d'une chambre avec galerie photos |
| GET | `/reserver/{chambre}` | Formulaire de réservation pour une chambre |
| POST | `/reserver` | Traitement : crée `ClientHotel` + `User` + `Reservation`, envoie e-mail de confirmation |
| GET | `/reservation/confirmation/{reservation}` | Page de confirmation de réservation |
| GET | `/a-propos` | Page À propos (contenu géré par le Directeur) |
| GET | `/contact` | Page Contact (contenu géré par le Directeur) |
| GET | `/mon-compte` | Dashboard client (nécessite authentification) |
| GET | `/mon-compte/factures/{facture}/pdf` | Téléchargement PDF de facture (auth client requise) |

---

## 7. Fonctionnalités par panel

### 7.1 Panel Directeur — `/directeur`

Le Directeur dispose d'un accès complet à la configuration du site et aux fonctions stratégiques.

**Dashboard**
- Statistiques globales : taux d'occupation, revenus, nombre de réservations, chambres disponibles.

**Gestion des chambres**
- CRUD complet avec upload de photos multiples (jusqu'à 8 images par chambre).
- Gestion des statuts (`disponible`, `occupee`, `nettoyage`, `maintenance`).
- Définition du type, de l'étage, de la capacité et du prix par nuit.

**Sliders Hero**
- CRUD des slides de la page d'accueil.
- Upload d'image, texte, sous-titre, texte et lien du bouton d'appel à l'action.
- Aperçu de l'image en mode édition.
- Ordre d'affichage personnalisable.

**Pages du site**
- Édition des pages **À propos** et **Contact** (titre, contenu rich text, téléphone, e-mail, adresse, horaires).

**Avis clients**
- CRUD des témoignages affichés sur la page d'accueil.
- Note de 1 à 5, photo optionnelle, ordre d'affichage.

**Rapports**
- Consultation des données agrégées de l'activité hôtelière.

**Clôture de caisse**
- Fonctionnalité de réconciliation journalière.

---

### 7.2 Panel Réceptionniste — `/reception`

Le Réceptionniste gère les opérations quotidiennes à la réception.

**Gestion des réservations**
- Visualisation de toutes les réservations avec leurs statuts.
- **Check-in** : confirme l'arrivée du client, crée automatiquement un `Sejour` et passe la chambre en statut `Occupée`.
- **Check-out** : enregistre le départ, génère automatiquement une `Facture` avec calcul de la TVA à 18 %, passe la chambre en statut `Nettoyage`.

**Gestion des clients hôtel**
- CRUD des fiches `ClientHotel` (nom, prénom, e-mail, téléphone, nationalité, numéro de passeport).

**Gestion des séjours**
- Visualisation et suivi des séjours en cours, avec gestion des extras.

**Gestion des room services**
- Enregistrement des commandes en chambre (articles en JSON, total, heure, statut).

**Gestion des factures**
- Consultation et téléchargement PDF de chaque facture.

---

### 7.3 Panel Gouvernante — `/gouvernante`

La Gouvernante dispose d'une interface dédiée au housekeeping.

**Gestion des statuts des chambres**
- Passage des chambres entre les statuts : `disponible`, `nettoyage`, `maintenance`.
- Vue dédiée permettant de savoir quelles chambres nécessitent une intervention.

---

### 7.4 Espace Client — `/mon-compte`

Interface Blade sur mesure (hors Filament) protégée par le middleware `EnsureClientRole`.

**Tableau de bord**
- Affichage de la prochaine réservation à venir.
- Statistiques personnelles du client (nombre de séjours, montant total).

**Mes réservations**
- Liste de toutes les réservations du client avec statuts et détails.

**Mes factures**
- Liste des factures disponibles avec bouton de téléchargement PDF.

---

## 8. Flux métier principal

```
┌─────────────────────────────────────────────────────────────────┐
│  1. CLIENT réserve en ligne                                     │
│     → Création ClientHotel + User + Reservation (en_attente)   │
│     → E-mail de confirmation automatique envoyé                 │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│  2. RÉCEPTIONNISTE effectue le Check-in                         │
│     → Séjour créé (check_in = maintenant)                       │
│     → Statut Reservation → checkin                              │
│     → Statut Chambre     → occupee                              │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│  3. (Optionnel) RÉCEPTIONNISTE enregistre des Room Services     │
│     → Articles ajoutés au Séjour (extras JSON)                  │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│  4. RÉCEPTIONNISTE effectue le Check-out                        │
│     → Facture générée automatiquement                           │
│       (nuitées + extras + TVA 18% → total TTC)                  │
│     → Statut Reservation → checkout                             │
│     → Statut Chambre     → nettoyage                            │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│  5. GOUVERNANTE nettoie la chambre                              │
│     → Statut Chambre : nettoyage → disponible                   │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│  6. CLIENT télécharge sa facture PDF                            │
│     → Accès via /mon-compte/factures/{facture}/pdf              │
│     → Authentification client requise                           │
└─────────────────────────────────────────────────────────────────┘
```

---

## 9. Structure des dossiers

```
gestion_hoteliere/
│
├── app/
│   ├── Enums/
│   │   └── Role.php                        # Enum des rôles utilisateurs
│   │
│   ├── Filament/
│   │   ├── Directeur/                      # Ressources Filament du panel Directeur
│   │   │   ├── Pages/
│   │   │   └── Resources/
│   │   ├── Reception/                      # Ressources Filament du panel Réception
│   │   │   ├── Pages/
│   │   │   └── Resources/
│   │   ├── Gouvernante/                    # Ressources Filament du panel Gouvernante
│   │   │   ├── Pages/
│   │   │   └── Resources/
│   │   └── Resources/                      # (Ressources génériques, legacy)
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ReservationPubliqueController.php  # Flux de réservation public
│   │   │   ├── ClientAuthController.php           # Authentification client custom
│   │   │   └── ClientEspaceController.php         # Dashboard / factures client
│   │   └── Middleware/
│   │       └── EnsureClientRole.php               # Protection espace client
│   │
│   ├── Mail/
│   │   └── ReservationConfirmation.php            # Mailable confirmation réservation
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Chambre.php
│   │   ├── ClientHotel.php
│   │   ├── Reservation.php
│   │   ├── Sejour.php
│   │   ├── RoomService.php
│   │   ├── Facture.php
│   │   ├── SliderHero.php
│   │   ├── PageSite.php
│   │   └── AvisClient.php
│   │
│   └── Providers/
│       └── Filament/
│           ├── DirecteurPanelProvider.php
│           ├── ReceptionPanelProvider.php
│           └── GouvernantePanelProvider.php
│
├── database/
│   ├── migrations/                         # Une migration par table
│   └── seeders/
│       ├── DatabaseSeeder.php              # Orchestrateur principal
│       ├── SliderHeroSeeder.php
│       ├── PageSiteSeeder.php
│       └── AvisClientSeeder.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── public.blade.php            # Layout principal vues publiques
│       ├── public/
│       │   ├── home.blade.php              # Page d'accueil
│       │   ├── chambres.blade.php          # Liste chambres avec filtres
│       │   ├── chambre-detail.blade.php    # Détail chambre + galerie
│       │   ├── reservation-form.blade.php  # Formulaire de réservation
│       │   ├── confirmation.blade.php      # Page de confirmation
│       │   ├── a-propos.blade.php          # Page À propos
│       │   └── contact.blade.php           # Page Contact
│       ├── client/
│       │   ├── login.blade.php             # Connexion espace client
│       │   ├── dashboard.blade.php         # Dashboard client
│       │   ├── reservations.blade.php      # Mes réservations
│       │   └── factures.blade.php          # Mes factures
│       ├── emails/
│       │   └── reservation-confirmation.blade.php
│       └── pdf/
│           └── facture.blade.php           # Template PDF facture
│
├── routes/
│   └── web.php                             # Toutes les routes de l'application
│
├── storage/
│   └── app/public/                         # Images uploadées (chambres, sliders…)
│
├── .env.example
├── composer.json
└── README.md
```

---

## 10. Installation locale

### Prérequis

- PHP 8.2 ou supérieur
- Composer 2.x
- MySQL 8.x (ou MariaDB compatible)
- Node.js (optionnel, Tailwind est chargé via CDN)
- Extension PHP `gd` ou `imagick` (pour le traitement d'images)

### Étapes

**1. Cloner le dépôt**

```bash
git clone https://github.com/<votre-organisation>/gestion_hoteliere.git
cd gestion_hoteliere
```

**2. Installer les dépendances PHP**

```bash
composer install
```

**3. Configurer l'environnement**

```bash
cp .env.example .env
php artisan key:generate
```

Ouvrir `.env` et renseigner les valeurs de connexion à la base de données ainsi que les paramètres SMTP (voir section [Variables d'environnement](#11-variables-denvironnement)).

**4. Créer la base de données**

```sql
CREATE DATABASE gestion_hoteliere CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**5. Exécuter les migrations et les seeders**

```bash
php artisan migrate --seed
```

Cette commande crée toutes les tables et insère :
- Les 24 chambres de l'hôtel
- Les slides hero par défaut
- Les pages À propos et Contact
- Les avis clients de démonstration
- Les comptes utilisateurs de test (voir section [Comptes de test](#12-comptes-de-test))

**6. Créer le lien symbolique pour le stockage**

```bash
php artisan storage:link
```

**7. Lancer le serveur de développement**

```bash
php artisan serve
```

L'application est accessible à l'adresse : [http://localhost:8000](http://localhost:8000)

---

### Commandes utiles

```bash
# Réinitialiser complètement la base de données et rejouer tous les seeders
php artisan migrate:fresh --seed

# Vider tous les caches
php artisan optimize:clear

# Lister toutes les routes enregistrées
php artisan route:list

# Lister tous les panels Filament
php artisan filament:list-panels
```

---

## 11. Variables d'environnement

Voici le contenu de référence du fichier `.env`. Copier dans `.env` et adapter les valeurs à votre contexte.

```dotenv
APP_NAME="Hôtel Excellence"
APP_ENV=local
APP_KEY=                        # Généré par php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_hoteliere
DB_USERNAME=root
DB_PASSWORD=

# Configuration e-mail (exemple avec Mailtrap pour le développement)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@hotel-excellence.sn"
MAIL_FROM_NAME="Hôtel Excellence"

# Stockage
FILESYSTEM_DISK=public
```

> En développement, [Mailtrap](https://mailtrap.io) permet d'intercepter les e-mails envoyés par l'application sans les livrer réellement. Renseigner `MAIL_USERNAME` et `MAIL_PASSWORD` avec vos identifiants Mailtrap.

---

## 12. Comptes de test

Ces comptes sont créés automatiquement par `DatabaseSeeder` lors de l'exécution de `php artisan migrate --seed`.

| Rôle | E-mail | Mot de passe | URL de connexion |
|---|---|---|---|
| Directeur | directeur@hotel.com | password | [/directeur](/directeur) |
| Réceptionniste | reception@hotel.com | password | [/reception](/reception) |
| Gouvernante | gouvernante@hotel.com | password | [/gouvernante](/gouvernante) |
| Client | client@hotel.com | password | [/mon-compte](/mon-compte) |

> **Important :** Ces comptes sont destinés au développement et aux tests uniquement. En production, supprimer ces comptes ou modifier leurs mots de passe avant tout déploiement accessible publiquement.

---

## 13. Déploiement Railway

[Railway](https://railway.app) est la plateforme cible pour le déploiement de cette application.

### Variables d'environnement à configurer dans Railway

Depuis le tableau de bord Railway → votre projet → onglet **Variables**, définir les variables suivantes :

| Variable | Description |
|---|---|
| `APP_KEY` | Clé de chiffrement Laravel (générer avec `php artisan key:generate --show`) |
| `APP_URL` | URL publique attribuée par Railway |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `DB_HOST` | Hôte du service MySQL Railway |
| `DB_PORT` | Port MySQL (généralement `3306`) |
| `DB_DATABASE` | Nom de la base de données |
| `DB_USERNAME` | Utilisateur MySQL |
| `DB_PASSWORD` | Mot de passe MySQL |
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | Hôte SMTP de production |
| `MAIL_PORT` | Port SMTP |
| `MAIL_USERNAME` | Identifiant SMTP |
| `MAIL_PASSWORD` | Mot de passe SMTP |
| `MAIL_FROM_ADDRESS` | `noreply@hotel-excellence.sn` |
| `MAIL_FROM_NAME` | `Hôtel Excellence` |

### Commandes post-déploiement

À exécuter dans le terminal Railway après chaque déploiement initial (ou en cas de réinitialisation) :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan optimize
```

### Fichier `Procfile`

Le fichier `Procfile` présent à la racine du projet indique à Railway la commande de démarrage :

```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

> Pour un déploiement via Nginx + PHP-FPM (recommandé en production), utiliser la configuration standard Laravel avec `public/` comme répertoire racine du serveur web.

---

## 14. Captures d'écran

> Les captures d'écran ci-dessous sont à placer dans le dossier `docs/screenshots/` du dépôt.

### Page d'accueil publique

<!-- Remplacer par : docs/screenshots/home.png -->
*Capture : slider hero, liste des chambres disponibles, section témoignages et contact.*

---

### Liste des chambres avec filtres

<!-- Remplacer par : docs/screenshots/chambres.png -->
*Capture : grille de chambres avec filtres par type, capacité, fourchette de prix et dates de disponibilité.*

---

### Formulaire de réservation

<!-- Remplacer par : docs/screenshots/reservation-form.png -->
*Capture : formulaire client (coordonnées, dates, nombre d'occupants) avec récapitulatif du montant estimé.*

---

### Page de confirmation de réservation

<!-- Remplacer par : docs/screenshots/confirmation.png -->
*Capture : récapitulatif de la réservation et indication de l'e-mail de confirmation envoyé.*

---

### Panel Directeur — Dashboard

<!-- Remplacer par : docs/screenshots/directeur-dashboard.png -->
*Capture : indicateurs clés (taux d'occupation, revenus, réservations du jour).*

---

### Panel Directeur — Gestion des chambres

<!-- Remplacer par : docs/screenshots/directeur-chambres.png -->
*Capture : liste des chambres avec statuts colorés et actions rapides.*

---

### Panel Directeur — Sliders Hero

<!-- Remplacer par : docs/screenshots/directeur-sliders.png -->
*Capture : interface CRUD des slides avec aperçu de l'image en édition.*

---

### Panel Réceptionniste — Réservations

<!-- Remplacer par : docs/screenshots/reception-reservations.png -->
*Capture : tableau des réservations avec boutons Check-in et Check-out contextuels.*

---

### Panel Réceptionniste — Facture générée

<!-- Remplacer par : docs/screenshots/reception-facture.png -->
*Capture : détail d'une facture avec décomposition nuitées, extras, TVA 18 % et total TTC.*

---

### Panel Gouvernante — Housekeeping

<!-- Remplacer par : docs/screenshots/gouvernante-housekeeping.png -->
*Capture : liste des chambres à nettoyer avec bouton de changement de statut.*

---

### Espace Client — Mes réservations

<!-- Remplacer par : docs/screenshots/client-reservations.png -->
*Capture : tableau de bord client avec statuts des réservations et lien vers les factures PDF.*

---

### Facture PDF générée

<!-- Remplacer par : docs/screenshots/facture-pdf.png -->
*Capture : aperçu du PDF généré par DomPDF avec entête hôtel, détail des prestations et signature.*

---

## 15. Licence

Ce projet est distribué sous licence **MIT**.

```
MIT License

Copyright (c) 2025 — Cours L3 MIO, Université de Thiès, Sénégal

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

*Projet académique — L3 MIO — Université de Thiès, Sénégal — Pr SOW — 2025*
