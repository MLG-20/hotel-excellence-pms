<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomService extends Model
{
    protected $fillable = [
        'sejour_id', 'articles', 'total',
        'heure_commande', 'statut', 'notes',
    ];

    protected $casts = [
        'articles' => 'array',
        'heure_commande' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function sejour(): BelongsTo
    {
        return $this->belongsTo(Sejour::class);
    }
}
