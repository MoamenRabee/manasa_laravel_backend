<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'android_build_number',
        'ios_build_number',
        'android_link',
        'ios_link',
        'is_closed',
        'closed_message',
        'whatsapp',
        'facebook',
        'telegram',
        'website',
        'phone_number',
    ];
}
