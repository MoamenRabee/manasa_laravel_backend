<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'sort_number',
        'type',
        'is_free',
        'price',
        'is_active',
        'classroom_id',
    ];


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


    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_system');
    }
}
