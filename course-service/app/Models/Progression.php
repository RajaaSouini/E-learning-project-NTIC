<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'formation_id',
        'completed',
        'watch_time',
    ];
}