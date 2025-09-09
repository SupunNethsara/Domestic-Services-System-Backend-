<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->id)) {
                $client->id = (string) Str::uuid();
            }
            $client->password = bcrypt($client->password);
        });
    }
}

