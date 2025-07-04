<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerRating extends Model
{
    protected $fillable = [
        'worker_id',
        'user_id',
        'rating',
        'review'
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
