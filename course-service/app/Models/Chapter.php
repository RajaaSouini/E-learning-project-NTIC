<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'title',
        'order',
        'formation_id',
    ];

    // Un chapitre appartient à une formation
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    // Un chapitre a plusieurs cours
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}