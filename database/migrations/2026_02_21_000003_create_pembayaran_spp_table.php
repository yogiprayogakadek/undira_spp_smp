<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();
            $table->string('no_kwitansi', 30)->unique();
            $table->foreignId('siswa_id')->references('id')->on('siswa')->restrictOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->decimal('total_bayar', 12, 2);
            $table->date('tanggal_bayar');
            $table->enum('metode_bayar', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
