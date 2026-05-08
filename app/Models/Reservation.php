<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    protected $fillable = [
        'client_id', 'chambre_id', 'date_arrivee', 'date_depart',
        'adultes', 'enfants', 'statut', 'source', 'notes', 'montant_total',
    ];

    protected $casts = [
        'date_arrivee' => 'date',
        'date_depart' => 'date',
        'montant_total' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientHotel::class, 'client_id');
    }

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class);
    }

    public function sejour(): HasOne
    {
        return $this->hasOne(Sejour::class);
    }

    public function getNombreNuiteesAttribute(): int
    {
        return $this->date_arrivee->diffInDays($this->date_depart);
    }

    public function getMontantCalculeAttribute(): float
    {
        return $this->nombre_nuitees * ($this->chambre->prix_nuit ?? 0);
    }
}
