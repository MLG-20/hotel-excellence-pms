<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class AvisClient extends Model
{
    protected $fillable = [
        'nom', 'message', 'note', 'photo', 'ordre', 'actif',
    ];

    protected $casts = [
        'actif'  => 'boolean',
        'note'   => 'integer',
        'ordre'  => 'integer',
    ];

    public static function actifs(): Collection
    {
        return static::where('actif', true)->orderBy('ordre')->get();
    }
}
