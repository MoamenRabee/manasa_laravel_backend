<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject
{
    use Notifiable;


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'name',
        'phone',
        'parent_phone',
        'password',
        'classroom_id',
        'center_id',
        'image',
        'device_id',
        'activeted',
        'fcm_token',
    ];



    protected $hidden = [
        'password',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }


    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withPivot('activated_with')->withTimestamps();
    }

    public function systems()
    {
        return $this->belongsToMany(System::class)->withPivot('activated_with')->withTimestamps();
    }
}
