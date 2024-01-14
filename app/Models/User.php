<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function toArray()
    {
        $array = parent::toArray();
        $array['created_at'] = Carbon::parse($this->created_at)->format('Y-m-d H:i');
        return $array;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'phonenum',
        'role',
        'job_title',
    ];

    protected $hidden = [
        'password'
    ];

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }    
}