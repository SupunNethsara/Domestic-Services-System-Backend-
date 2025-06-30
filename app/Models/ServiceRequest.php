<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'client_id',
        'worker_id',
        'message',
        'status',
        'requested_date',
        'special_requirements'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function worker()
    {
        return $this->belongsTo(WorkersAvailability::class, 'worker_id');
    }
}
