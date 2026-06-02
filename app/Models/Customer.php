<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importing the Authenticatable trait
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'customers';

    protected $fillable = [
        'cstatus',
        'fname',
        'mname',
        'lname',
        'email',
        'password',
        'role',
        'gender',
        'bday',
        'mobileno',
        'hnum',
        'brgy',
        'city',
        'province',
        'region',
        'zcode',
        'address',
        'avatar',
    ];

    protected $hidden = [
        'password',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
