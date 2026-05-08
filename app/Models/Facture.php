<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    protected $fillable = [
        'numero', 'sejour_id', 'nuitees', 'extras', 'remise',
        'total_ht', 'tva', 'total_ttc', 'statut', 'date_emission',
    ];

    protected $casts = [
        'nuitees' => 'decimal:2',
        'extras' => 'decimal:2',
        'remise' => 'decimal:2',
        'total_ht' => 'decimal:2',
        'tva' => 'decimal:2',
        'total_ttc' => 'decimal:2',
        'date_emission' => 'datetime',
    ];

    public function sejour(): BelongsTo
    {
        return $this->belongsTo(Sejour::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($facture) {
            $facture->numero = 'FAC-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
