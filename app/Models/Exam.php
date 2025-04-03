<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'name',
        'minutes',
        'status',
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

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

}
