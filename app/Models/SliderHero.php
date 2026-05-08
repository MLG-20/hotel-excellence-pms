<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class SliderHero extends Model
{
    protected $table = 'slider_heros';

    protected $fillable = [
        'titre', 'sous_titre', 'image', 'bouton_texte', 'bouton_lien', 'ordre', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    public static function actifs(): Collection
    {
        return static::where('actif', true)->orderBy('ordre')->get();
    }
}
