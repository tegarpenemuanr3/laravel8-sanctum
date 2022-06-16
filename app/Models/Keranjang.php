<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'menu_id',
        'menu_nama',
        'menu_foto',
        'menu_harga',
        'produk_jumlah',
        'total'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
