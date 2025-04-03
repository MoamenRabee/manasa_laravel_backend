<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'name',
        'url',
        'lesson_id',
        'classroom_id',
        'sort_number',
        'duration',
        'is_active',
        'description',
        'link_type',
        'views_count',
        'is_free',
        'price',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }


    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->is_free == 1) {
                $model->price = null;
            }
        });
    }

    
}
