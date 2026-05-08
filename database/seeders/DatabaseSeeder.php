<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Chambre;
use App\Models\ClientHotel;
use App\Models\Facture;
use App\Models\Reservation;
use App\Models\RoomService;
use App\Models\Sejour;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Démarrage du seeder...');

        // Utilisateurs par rôle
        User::updateOrCreate(
            ['email' => 'directeur@hotel.com'],
            ['name' => 'Ibrahima Sow', 'password' => Hash::make('password'), 'role' => Role::Directeur]
        );

        User::updateOrCreate(
            ['email' => 'reception@hotel.com'],
            ['name' => 'Aminata Fall', 'password' => Hash::make('password'), 'role' => Role::Receptionniste]
        );

        User::updateOrCreate(
            ['email' => 'gouvernante@hotel.com'],
            ['name' => 'Marie Gomis', 'password' => Hash::make('password'), 'role' => Role::Gouvernante]
        );

        User::updateOrCreate(
            ['email' => 'client@hotel.com'],
            ['name' => 'Pierre Martin', 'password' => Hash::make('password'), 'role' => Role::Client]
        );

        // Chambres
        $chambres = [
            // ── Étage 1 : Simples (6 chambres) ──────────────────────────────────
            ['numero' => '101', 'type' => 'simple', 'etage' => 1, 'capacite' => 1, 'prix_nuit' => 25000, 'statut' => 'disponible',  'description' => 'Chambre simple vue jardin'],
            ['numero' => '102', 'type' => 'simple', 'etage' => 1, 'capacite' => 1, 'prix_nuit' => 25000, 'statut' => 'disponible',  'description' => 'Chambre simple vue jardin'],
            ['numero' => '103', 'type' => 'simple', 'etage' => 1, 'capacite' => 1, 'prix_nuit' => 25000, 'statut' => 'nettoyage',   'description' => 'Chambre simple côté rue'],
            ['numero' => '104', 'type' => 'simple', 'etage' => 1, 'capacite' => 1, 'prix_nuit' => 27000, 'statut' => 'occupee',     'description' => 'Chambre simple avec douche'],
            ['numero' => '105', 'type' => 'simple', 'etage' => 1, 'capacite' => 1, 'prix_nuit' => 27000, 'statut' => 'disponible',  'description' => 'Chambre simple avec douche'],
            ['numero' => '106', 'type' => 'simple', 'etage' => 1, 'capacite' => 1, 'prix_nuit' => 25000, 'statut' => 'maintenance', 'description' => 'Chambre simple en cours de rénovation'],

            // ── Étage 2 : Doubles (6 chambres) ──────────────────────────────────
            ['numero' => '201', 'type' => 'double', 'etage' => 2, 'capacite' => 2, 'prix_nuit' => 45000, 'statut' => 'occupee',    'description' => 'Chambre double avec terrasse'],
            ['numero' => '202', 'type' => 'double', 'etage' => 2, 'capacite' => 2, 'prix_nuit' => 45000, 'statut' => 'disponible', 'description' => 'Chambre double vue piscine'],
            ['numero' => '203', 'type' => 'double', 'etage' => 2, 'capacite' => 2, 'prix_nuit' => 50000, 'statut' => 'nettoyage',  'description' => 'Chambre double supérieure'],
            ['numero' => '204', 'type' => 'double', 'etage' => 2, 'capacite' => 2, 'prix_nuit' => 45000, 'statut' => 'disponible', 'description' => 'Chambre double standard'],
            ['numero' => '205', 'type' => 'double', 'etage' => 2, 'capacite' => 2, 'prix_nuit' => 48000, 'statut' => 'occupee',    'description' => 'Chambre double vue jardin'],
            ['numero' => '206', 'type' => 'double', 'etage' => 2, 'capacite' => 2, 'prix_nuit' => 50000, 'statut' => 'disponible', 'description' => 'Chambre double balcon'],

            // ── Étage 3 : Familiales (5 chambres) ───────────────────────────────
            ['numero' => '301', 'type' => 'familiale', 'etage' => 3, 'capacite' => 4, 'prix_nuit' => 75000, 'statut' => 'disponible',  'description' => 'Suite familiale 2 pièces'],
            ['numero' => '302', 'type' => 'familiale', 'etage' => 3, 'capacite' => 4, 'prix_nuit' => 75000, 'statut' => 'occupee',     'description' => 'Suite familiale vue piscine'],
            ['numero' => '303', 'type' => 'familiale', 'etage' => 3, 'capacite' => 5, 'prix_nuit' => 85000, 'statut' => 'nettoyage',   'description' => 'Suite familiale grande capacité'],
            ['numero' => '304', 'type' => 'familiale', 'etage' => 3, 'capacite' => 4, 'prix_nuit' => 80000, 'statut' => 'disponible',  'description' => 'Suite familiale avec coin cuisine'],
            ['numero' => '305', 'type' => 'familiale', 'etage' => 3, 'capacite' => 4, 'prix_nuit' => 75000, 'statut' => 'maintenance', 'description' => 'Suite familiale en rénovation'],

            // ── Étage 4 : Suites (4 chambres) ───────────────────────────────────
            ['numero' => '401', 'type' => 'suite', 'etage' => 4, 'capacite' => 2, 'prix_nuit' => 120000, 'statut' => 'disponible',  'description' => 'Suite junior avec salon'],
            ['numero' => '402', 'type' => 'suite', 'etage' => 4, 'capacite' => 2, 'prix_nuit' => 130000, 'statut' => 'occupee',     'description' => 'Suite supérieure avec jacuzzi'],
            ['numero' => '403', 'type' => 'suite', 'etage' => 4, 'capacite' => 3, 'prix_nuit' => 150000, 'statut' => 'nettoyage',   'description' => 'Suite deluxe panoramique'],
            ['numero' => '404', 'type' => 'suite', 'etage' => 4, 'capacite' => 2, 'prix_nuit' => 125000, 'statut' => 'disponible',  'description' => 'Suite avec terrasse privée'],

            // ── Étage 5 : Présidentielles (3 chambres) ──────────────────────────
            ['numero' => '501', 'type' => 'presidentielle', 'etage' => 5, 'capacite' => 4, 'prix_nuit' => 250000, 'statut' => 'maintenance', 'description' => 'Suite présidentielle panoramique'],
            ['numero' => '502', 'type' => 'presidentielle', 'etage' => 5, 'capacite' => 4, 'prix_nuit' => 280000, 'statut' => 'disponible',  'description' => 'Suite présidentielle avec piscine privée'],
            ['numero' => '503', 'type' => 'presidentielle', 'etage' => 5, 'capacite' => 6, 'prix_nuit' => 350000, 'statut' => 'disponible',  'description' => 'Suite royale avec salon et terrasse'],
        ];

        foreach ($chambres as $chambre) {
            Chambre::firstOrCreate(['numero' => $chambre['numero']], $chambre);
        }

        // Clients hôtel
        $clients = [
            ['nom' => 'Diallo',   'prenom' => 'Mamadou',      'email' => 'mamadou.diallo@email.com',  'telephone' => '+221 77 123 45 67', 'nationalite' => 'Sénégalaise', 'passeport' => 'SN123456'],
            ['nom' => 'Traoré',   'prenom' => 'Fatou',        'email' => 'fatou.traore@email.com',    'telephone' => '+221 76 234 56 78', 'nationalite' => 'Malienne',    'passeport' => 'ML234567'],
            ['nom' => 'Martin',   'prenom' => 'Pierre',       'email' => 'pierre.martin@email.fr',    'telephone' => '+33 6 12 34 56 78', 'nationalite' => 'Française',   'passeport' => 'FR345678'],
            ['nom' => 'Ndiaye',   'prenom' => 'Aïssatou',    'email' => 'aissatou.ndiaye@email.com', 'telephone' => '+221 70 345 67 89', 'nationalite' => 'Sénégalaise', 'passeport' => 'SN456789'],
            ['nom' => 'Kouassi',  'prenom' => 'Jean-Baptiste','email' => 'jb.kouassi@email.ci',       'telephone' => '+225 07 456 78 90', 'nationalite' => 'Ivoirienne',  'passeport' => 'CI567890'],
        ];

        foreach ($clients as $client) {
            ClientHotel::firstOrCreate(['email' => $client['email']], $client);
        }

        $this->command->info('Utilisateurs et chambres créés.');

        // Réservations et séjours
        $client1  = ClientHotel::where('email', 'mamadou.diallo@email.com')->first();
        $client2  = ClientHotel::where('email', 'fatou.traore@email.com')->first();
        $client3  = ClientHotel::where('email', 'pierre.martin@email.fr')->first();
        $chambre201 = Chambre::where('numero', '201')->first();
        $chambre101 = Chambre::where('numero', '101')->first();
        $chambre301 = Chambre::where('numero', '301')->first();

        if (! $client1 || ! $client2 || ! $client3 || ! $chambre201 || ! $chambre101 || ! $chambre301) {
            $this->command->warn('Clients ou chambres manquants — réservations ignorées.');
            $this->call(SliderHeroSeeder::class);
            $this->call(PageSiteSeeder::class);
            $this->call(AvisClientSeeder::class);
            return;
        }

        // Réservation active (check-in en cours)
        $res1 = Reservation::firstOrCreate(
            ['client_id' => $client1->id, 'chambre_id' => $chambre201->id, 'date_arrivee' => today()],
            [
                'date_depart'    => today()->addDays(3),
                'adultes'        => 2,
                'enfants'        => 0,
                'statut'         => 'checkin',
                'source'         => 'direct',
                'montant_total'  => 45000 * 3,
            ]
        );
        $sejour1 = Sejour::firstOrCreate(
            ['reservation_id' => $res1->id],
            ['check_in' => now(), 'total' => 45000 * 3, 'statut_paiement' => 'en_attente']
        );

        // Réservation à venir
        Reservation::firstOrCreate(
            ['client_id' => $client2->id, 'chambre_id' => $chambre301->id, 'date_arrivee' => today()->addDays(2)],
            [
                'date_depart'   => today()->addDays(7),
                'adultes'       => 2,
                'enfants'       => 2,
                'statut'        => 'confirmee',
                'source'        => 'booking',
                'montant_total' => 75000 * 5,
            ]
        );

        // Réservation terminée avec facture (liée au compte client)
        $res3 = Reservation::firstOrCreate(
            ['client_id' => $client3->id, 'chambre_id' => $chambre101->id, 'date_arrivee' => today()->subDays(5)],
            [
                'date_depart'   => today()->subDays(2),
                'adultes'       => 1,
                'enfants'       => 0,
                'statut'        => 'checkout',
                'source'        => 'direct',
                'montant_total' => 25000 * 3,
            ]
        );
        $sejour3 = Sejour::firstOrCreate(
            ['reservation_id' => $res3->id],
            [
                'check_in'          => today()->subDays(5),
                'check_out'         => today()->subDays(2),
                'total'             => 25000 * 3,
                'statut_paiement'   => 'paye',
            ]
        );

        $totalHt = 25000 * 3;
        $tva     = $totalHt * 0.18;
        Facture::firstOrCreate(
            ['sejour_id' => $sejour3->id],
            [
                'nuitees'       => $totalHt,
                'extras'        => 0,
                'remise'        => 0,
                'total_ht'      => $totalHt,
                'tva'           => $tva,
                'total_ttc'     => $totalHt + $tva,
                'statut'        => 'payee',
                'date_emission' => today()->subDays(2),
            ]
        );

        // Slides Hero
        $this->call(SliderHeroSeeder::class);

        // Pages du site (À propos, Contact)
        $this->call(PageSiteSeeder::class);

        // Avis clients
        $this->call(AvisClientSeeder::class);

        // Room service pour le séjour actif
        RoomService::firstOrCreate(
            ['sejour_id' => $sejour1->id],
            [
                'articles' => [
                    ['nom' => 'Petit déjeuner continental', 'quantite' => 2, 'prix' => 5000],
                    ['nom' => 'Jus d\'orange frais',        'quantite' => 2, 'prix' => 1500],
                ],
                'total'         => 13000,
                'heure_commande'=> now(),
                'statut'        => 'livre',
            ]
        );

        $this->command->info('Seeder terminé avec succès.');
    }
}
