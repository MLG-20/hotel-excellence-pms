<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSite extends Model
{
    protected $fillable = [
        'slug', 'titre', 'contenu', 'telephone', 'email_contact',
        'adresse', 'horaires', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public static function bySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('actif', true)->first();
    }
}
