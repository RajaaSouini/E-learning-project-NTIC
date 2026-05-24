<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cursus extends Model
{
    // On force le nom de la table car Laravel cherche "cursuses" par défaut
    protected $table = 'cursus';

    protected $fillable = [
        'title',
        'description',
        'slug',
        'thumbnail',
        'level',
        'goal',
    ];
}