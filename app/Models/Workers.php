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
        'role',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($worker) {


            $worker->password = bcrypt($worker->password);
        });
    }
}
