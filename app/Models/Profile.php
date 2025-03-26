<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles'; // Database table name

    protected $fillable = [
        'username', 'about', 'fname', 'lname', 'email',
        'country', 'address', 'city', 'province',
        'profile_image', 'cover_image'
    ];
}
