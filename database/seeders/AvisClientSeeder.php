<?php

namespace Database\Seeders;

use App\Models\AvisClient;
use Illuminate\Database\Seeder;

class AvisClientSeeder extends Seeder
{
    public function run(): void
    {
        $avis = [
            [
                'nom'     => 'Aminata Diallo',
                'message' => 'Un séjour exceptionnel ! Les chambres sont magnifiques, le personnel attentionné et la vue depuis la suite est à couper le souffle. Je reviendrai sans hésiter.',
                'note'    => 5,
                'ordre'   => 1,
            ],
            [
                'nom'     => 'Mamadou Ndiaye',
                'message' => 'Excellent rapport qualité-prix. J\'ai séjourné 3 nuits pour un voyage d\'affaires. Le Wi-Fi est rapide, le petit-déjeuner délicieux et le check-in très rapide.',
                'note'    => 5,
                'ordre'   => 2,
            ],
            [
                'nom'     => 'Fatou Sarr',
                'message' => 'Le personnel est vraiment aux petits soins. Ma chambre familiale était spacieuse et propre. Les enfants ont adoré ! On se sent comme à la maison.',
                'note'    => 4,
                'ordre'   => 3,
            ],
            [
                'nom'     => 'Ibrahim Touré',
                'message' => 'Je recommande vivement cet hôtel. Le service de room service est rapide et les plats sont savoureux. La piscine est impeccable. Top !',
                'note'    => 5,
                'ordre'   => 4,
            ],
            [
                'nom'     => 'Rokhaya Ba',
                'message' => 'L\'hôtel est bien situé, les chambres confortables et le personnel souriant. Seul petit bémol : l\'attente au restaurant le soir. Mais dans l\'ensemble, très bonne expérience.',
                'note'    => 4,
                'ordre'   => 5,
            ],
        ];

        foreach ($avis as $item) {
            AvisClient::firstOrCreate(['nom' => $item['nom'], 'note' => $item['note']], array_merge($item, ['actif' => true]));
        }
    }
}
