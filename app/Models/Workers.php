<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workers extends Model
{
    protected $table = 'workers';

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            $client->password = bcrypt($client->password);
        });
    }
}
