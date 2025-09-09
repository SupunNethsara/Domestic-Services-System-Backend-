<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Workers extends Model
{
    protected $table = 'workers';

    protected $fillable = [
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
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($worker) {
            if (empty($worker->id)) {
                $worker->id = (string) Str::uuid();
            }
            $worker->password = bcrypt($worker->password);
        });
    }
}
