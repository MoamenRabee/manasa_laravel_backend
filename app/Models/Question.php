<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question',
        'image',
        'type',
        'exam_id',
        'options',
        'correct_answer',
    ];



    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }


    protected $casts = [
        'options' => 'array',
    ];


    protected static function booted()
    {
        static::saving(function ($question) {
            if ($question->type !== 'mcq') {
                $question->options = null;
                $question->correct_answer = null;
            }
        });
    }


}
