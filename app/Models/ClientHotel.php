<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientHotel extends Model
{
    protected $table = 'clients_hotel';

    protected $fillable = [
        'nom', 'prenom', 'nationalite', 'passeport',
        'email', 'telephone', 'adresse',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'client_id');
    }

    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
