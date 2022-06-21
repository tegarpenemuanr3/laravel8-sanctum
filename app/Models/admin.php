<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'admin_nama',
        'admin_phone',
        'admin_email',
        'admin_foto',
        'password'
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'role'
    ];
}
