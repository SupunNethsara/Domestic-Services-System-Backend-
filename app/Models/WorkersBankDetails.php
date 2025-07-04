<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
