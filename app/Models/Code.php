<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'codes_group_id',
        'lesson_id',
        'system_id',
        'student_id',
        'code',
        'price',
        'is_used',
        'used_at',
    ];



    public function codesGroup()
    {
        return $this->belongsTo(CodesGroup::class);
    }


    public function student()
    {
        return $this->belongsTo(Student::class);
    }


}
