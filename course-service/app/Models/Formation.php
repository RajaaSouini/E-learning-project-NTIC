<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug',
        'thumbnail',
        'professor_id',
        'status',
    ];

    // Une formation a plusieurs chapitres
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}