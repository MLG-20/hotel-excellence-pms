<?php

namespace Database\Seeders;

use App\Models\PageSite;
use Illuminate\Database\Seeder;

class PageSiteSeeder extends Seeder
{
    public function run(): void
    {
        PageSite::upsert([
            [
                'slug'    => 'about',
                'titre'   => 'À propos de l\'Hôtel Excellence',
                'contenu' => "Fondé au cœur de Thiès, l'Hôtel Excellence est bien plus qu'un lieu de séjour : c'est une expérience. Depuis notre ouverture, nous nous engageons à offrir à chaque client un accueil chaleureux, des chambres raffinées et un service irréprochable digne des établissements 5 étoiles.\n\nNotre équipe passionnée veille 24h/24 à votre confort, qu'il s'agisse d'un voyage d'affaires, d'une escapade romantique ou de vacances en famille. Chacun de nos 24 hébergements — des chambres simples aux suites présidentielles — a été conçu avec soin pour allier élégance et bien-être.\n\nNous croyons que l'hospitalité authentique réside dans les petits détails : un sourire à l'accueil, un lit parfaitement préparé, un service de chambre attentionné. C'est cette philosophie qui guide chacune de nos actions.\n\nBienvenue à l'Hôtel Excellence — bienvenue chez vous.",
                'telephone'      => '+221 33 951 00 00',
                'email_contact'  => 'contact@hotel-excellence.sn',
                'adresse'        => 'Avenue Léopold Sédar Senghor, Thiès, Sénégal',
                'horaires'       => 'Réception ouverte 24h/24, 7j/7',
                'actif'          => true,
            ],
            [
                'slug'    => 'contact',
                'titre'   => 'Nous contacter',
                'contenu' => "Vous avez une question, souhaitez effectuer une réservation de groupe ou simplement obtenir plus d'informations sur nos services ? Notre équipe est à votre disposition.\n\nN'hésitez pas à nous appeler, nous écrire par email ou passer directement à notre réception. Nous nous engageons à vous répondre dans les plus brefs délais.",
                'telephone'      => '+221 33 951 00 00',
                'email_contact'  => 'contact@hotel-excellence.sn',
                'adresse'        => 'Avenue Léopold Sédar Senghor, Thiès, Sénégal',
                'horaires'       => "Réception : 24h/24, 7j/7\nDirection : Lun – Ven : 8h00 – 17h00",
                'actif'          => true,
            ],
        ], ['slug'], ['titre', 'contenu', 'telephone', 'email_contact', 'adresse', 'horaires', 'actif']);
    }
}
