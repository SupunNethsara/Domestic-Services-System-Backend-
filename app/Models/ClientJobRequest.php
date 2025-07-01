<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientJobRequest extends Model
{
    use HasFactory;

    protected $table = 'job_request_client';
    protected $fillable = [
        'client_id',
        'job_titles',
        'custom_job_title',
        'location',
        'salary_range',
        'description',
        'start_date',
        'end_date',
        'job_type',
        'frequency',
        'has_transportation',
        'background_check',
        'interview_required',
        'status'
    ];

    protected $casts = [
        'job_titles' => 'array',
        'has_transportation' => 'boolean',
        'background_check' => 'boolean',
        'interview_required' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
