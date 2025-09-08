<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableJobs extends Model
{
    protected $table = 'available_jobs';

    protected $fillable = [
        'id',
        'client_id',
        'title',
        'category',
        'message',
    ];
}
