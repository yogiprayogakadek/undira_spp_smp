<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_spp_id')->references('id')->on('pembayaran_spp')->cascadeOnDelete();
            $table->foreignId('tagihan_spp_id')->references('id')->on('tagihan_spp')->restrictOnDelete();
            $table->decimal('nominal_bayar', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembayaran');
    }
};
