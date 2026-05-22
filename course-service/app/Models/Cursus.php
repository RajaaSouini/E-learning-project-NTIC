<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cursus extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug',
        'thumbnail',
        'level',
        'goal',
    ];
}