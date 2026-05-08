<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sejour extends Model
{
    protected $fillable = [
        'reservation_id', 'check_in', 'check_out',
        'extras', 'total', 'statut_paiement',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'extras' => 'array',
        'total' => 'decimal:2',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function roomServices(): HasMany
    {
        return $this->hasMany(RoomService::class);
    }

    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class);
    }
}
