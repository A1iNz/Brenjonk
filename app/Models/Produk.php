<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'kode',
        'harga',
        'stock',
        'kategori_id',
        'shelf_life_days',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Kategori.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function rencanaPanens(): HasMany
    {
        return $this->hasMany(RencanaPanen::class);
    }


}
