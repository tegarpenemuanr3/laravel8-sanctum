<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'menu_nama',
        'menu_foto',
        'menu_harga',
        'menu_ketersediaan',
        'menu_deskripsi',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
