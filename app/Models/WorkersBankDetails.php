<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkersBankDetails extends Model
{
    protected $table = 'workers_bank_details';
    protected $fillable = [
        'worker_id',
        'account_holder_name',
        'account_number',
        'bank_name',
        'branch_name',
        'branch_code',
        'account_type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
