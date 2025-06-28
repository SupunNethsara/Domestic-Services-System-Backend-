<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class UserStatus extends Model
{
    protected $fillable = ['user_id', 'status', 'last_seen_at'];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
