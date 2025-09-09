<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class UserStatus extends Model
{
    protected $fillable = ['user_id', 'status', 'last_seen_at'];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($userStatus) {
            if (empty($userStatus->id)) {
                $userStatus->id = (string) Str::uuid();
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
