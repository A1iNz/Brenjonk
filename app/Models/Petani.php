<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Petani extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'kode',
    ];

    /**
     * Get the user that owns the petani.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
