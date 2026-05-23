<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
    'title',
    'description',
    'slug',
    'technology',
    'level',
    'duration',
    'video_url',
    'thumbnail',
    'professor_id',
    'status',      
];

    // Un cours appartient à un chapitre
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}