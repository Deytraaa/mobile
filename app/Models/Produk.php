<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    //memberikan akses create &update
    protected $fillable = [
        'nama',
        'stok',
        'harga',
        'deskripsi',
    ];
}
