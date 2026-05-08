<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chambre extends Model
{
    protected $fillable = [
        'numero', 'type', 'etage', 'capacite',
        'prix_nuit', 'statut', 'description', 'images',
    ];

    protected $casts = [
        'prix_nuit' => 'decimal:2',
        'images' => 'array',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function isDisponible(): bool
    {
        return $this->statut === 'disponible';
    }
}
