<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaPanen extends Model
{
    use HasFactory;

    protected $fillable = [
        'petani_id',
        'produk_id',
        'estimasi_hasil_panen',
        'estimasi_waktu_panen',
        'status',
    ];
}