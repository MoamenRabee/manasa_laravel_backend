<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'sort_number',
        'is_active',
        'classroom_id',
        'require_exam_id',
        'is_free',
        'price',
    ];


    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }


    public function exams()
    {
        return $this->hasMany(Exam::class);
    }


    
    public function required_exam()
    {
        return $this->belongsTo(Exam::class, 'require_exam_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->is_free == 1) {
                $model->price = null;
            }
        });
    }


    public function systems()
    {
        return $this->belongsToMany(System::class, 'lesson_system');
    }


    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

}
