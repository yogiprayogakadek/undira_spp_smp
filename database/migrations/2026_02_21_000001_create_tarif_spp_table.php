<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_spp', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tingkat')->comment('7, 8, atau 9');
            $table->char('tahun_ajaran', 9)->comment('Format: 2025/2026');
            $table->decimal('nominal', 12, 2);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->unique(['tingkat', 'tahun_ajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_spp');
    }
};
