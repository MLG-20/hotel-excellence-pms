<?php

namespace Database\Seeders;

use App\Models\SliderHero;
use Illuminate\Database\Seeder;

class SliderHeroSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'titre'       => 'Bienvenue à l\'Hôtel Excellence',
                'sous_titre'  => 'Un séjour d\'exception vous attend — élégance, confort et service 5 étoiles au cœur de Thiès, Sénégal.',
                'bouton_texte' => 'Découvrir nos chambres',
                'bouton_lien' => '/chambres',
                'ordre'       => 1,
                'actif'       => true,
            ],
            [
                'titre'       => 'Des Suites d\'Exception',
                'sous_titre'  => 'Plongez dans un univers de luxe et de raffinement. Nos suites présidentielles redéfinissent le confort.',
                'bouton_texte' => 'Voir les suites',
                'bouton_lien' => '/chambres?type=presidentielle',
                'ordre'       => 2,
                'actif'       => true,
            ],
            [
                'titre'       => 'Réservez en Toute Sérénité',
                'sous_titre'  => 'Confirmation instantanée, service personnalisé, et une équipe dédiée à votre satisfaction 24h/24.',
                'bouton_texte' => 'Réserver maintenant',
                'bouton_lien' => '/chambres',
                'ordre'       => 3,
                'actif'       => true,
            ],
        ];

        foreach ($slides as $slide) {
            SliderHero::firstOrCreate(['titre' => $slide['titre']], $slide);
        }
    }
}
