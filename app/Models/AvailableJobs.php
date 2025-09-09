<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableJobs extends Model
{
    protected $table = 'available_jobs';

    protected $fillable = [
        'worker_id',
        'client_id',
        'title',
        'category',
        'message',
    ];
    public function workerProfile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'client_id');
    }
}
