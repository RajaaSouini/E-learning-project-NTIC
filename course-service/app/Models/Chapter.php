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

    
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }


    public function courses()
    {
        return $this->hasMany(Course::class)->orderBy('id');
    }
}