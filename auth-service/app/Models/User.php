<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Champs que l'on peut remplir
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',       // visiteur | membre | professeur | admin
        'avatar',
    ];

    // Champs cachés dans les réponses JSON
    protected $hidden = [
        'password',
    ];

    // Requis par JWT — retourne l'identifiant unique
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Requis par JWT — données supplémentaires dans le token
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'name' => $this->name,
        ];
    }

    // Helpers pour vérifier le rôle
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProfesseur()
    {
        return $this->role === 'professeur';
    }
}