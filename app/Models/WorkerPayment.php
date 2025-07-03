<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerPayment extends Model
{
    protected $fillable = [
        'client_id',
        'worker_id',
        'amount',
        'status',
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
