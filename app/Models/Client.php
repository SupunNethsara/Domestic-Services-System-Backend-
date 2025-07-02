<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected  $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'role',
    ];
public function user()
{
    return $this->belongsTo(User::class);
}
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            $client->password = bcrypt($client->password);
        });
    }
}

