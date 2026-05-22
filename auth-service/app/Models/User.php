<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];


    protected $hidden = [
        'password',
    ];

    // JWT : retourne l'id de l'utilisateur
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // JWT : ajoute le rôle dans le token
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'name' => $this->name,
        ];
    }

    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProfesseur()
    {
        return $this->role === 'professeur';
    }
}