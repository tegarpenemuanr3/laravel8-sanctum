<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class umkm extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'umkm_nama',
        'umkm_phone',
        'umkm_email',
        'umkm_alamat',
        'umkm_latitude',
        'umkm_longtitude',
        'umkm_deskripsi',
        'umkm_operasional',
        'umkm_buka_sejak',
        'umkm_foto',
        'umkm_pemilik_nama',
        'umkm_pemilik_phone',
        'umkm_status',
        'password',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'role'
    ];
}
