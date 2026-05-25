<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $fillable = [
        'user_id',
        'plan',
        'prix',
        'statut',
        'debut_le',
        'expire_le',
    ];

    protected $casts = [
        'debut_le'  => 'datetime',
        'expire_le' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActif()
    {
        return $this->statut === 'actif' && $this->expire_le > now();
    }
}