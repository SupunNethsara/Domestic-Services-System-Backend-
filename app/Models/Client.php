<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected  $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'role',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {


            $client->password = bcrypt($client->password);
        });
    }
}

