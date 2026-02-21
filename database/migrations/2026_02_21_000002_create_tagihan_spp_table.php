<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->references('id')->on('siswa')->cascadeOnDelete();
            $table->foreignId('tarif_spp_id')->references('id')->on('tarif_spp')->restrictOnDelete();
            $table->tinyInteger('bulan')->comment('1-12');
            $table->smallInteger('tahun');
            $table->decimal('nominal', 12, 2)->comment('Snapshot nominal saat tagihan dibuat');
            $table->enum('status', ['belum_bayar', 'lunas', 'sebagian'])->default('belum_bayar');
            $table->timestamps();

            $table->unique(['siswa_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_spp');
    }
};
