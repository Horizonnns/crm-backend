<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applications extends Model
{
    use HasFactory, Notifiable;

        protected $fillable = [
        'specialist_name',
        'phonenum',
        'topic',
        'account_number',
        'createddate',
        'comment',
        'job_title',
        'status',
    ];
}
