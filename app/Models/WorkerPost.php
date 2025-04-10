<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPost extends Model
{
    use HasFactory;

    protected $table = 'workerposts';
    protected $fillable = [
        'user_id',
        'content',
        'image'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
