<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    
    protected $fillable = [
        'name',
        'sort_number',
    ];



    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }


    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function systems()
    {
        return $this->hasMany(System::class);
    }

}
