<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles'; // Database table name

    protected $fillable = [
        'user_id',
        'username',
        'about',
        'first_name',
        'last_name',
        'email',
        'country',
        'address',
        'city',
        'province',
        'profile_image',
        'cover_image'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getIsCompleteAttribute()
    {
        return $this->username &&
            $this->country &&
            $this->address &&
            $this->city &&
            $this->province;
    }
}


