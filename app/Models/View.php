<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'student_id',
        'video_id',
        'duration',
        'count',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }


    public function video()
    {
        return $this->belongsTo(Video::class,'video_id');
    }

    


}
