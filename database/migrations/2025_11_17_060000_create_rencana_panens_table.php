<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana_panens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('petanis')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->decimal('estimasi_hasil_panen', 8, 2);
            $table->date('estimasi_waktu_panen');
            $table->string('status')->default('menunggu_verifikasi'); // cth: menunggu_verifikasi, disetujui, ditolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_panens');
    }
};