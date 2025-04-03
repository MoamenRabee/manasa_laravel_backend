<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name',
        'image',
        'path',
        'lesson_id',
        'classroom_id',
    ];



    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    
}
