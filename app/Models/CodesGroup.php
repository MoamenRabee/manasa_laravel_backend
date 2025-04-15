<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodesGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'isPrinted',
    ];



    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}
