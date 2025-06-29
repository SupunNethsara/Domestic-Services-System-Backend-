<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkersAvailability extends Model
{
    protected $table = 'workers_availability';

    protected $fillable = [
        'worker_id',
        'name',
        'services',
        'availability_type',
        'weekly_availability',
        'locations',
        'coordinates',
        'preferences',
        'expected_rate',
    ];

    protected $casts = [
        'services' => 'array',
        'weekly_availability' => 'array',
        'locations' => 'array',
        'coordinates' => 'array',
        'preferences' => 'array',
        'expected_rate' => 'array',
    ];
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'worker_id', 'user_id');
    }
}
