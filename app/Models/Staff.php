<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    //memberikan akses create &update
    protected $fillable = [
        'nama',
        'jabatan',
        'shift',
        'alamat',
        'telepon',
        'email',
    ];
    
}
