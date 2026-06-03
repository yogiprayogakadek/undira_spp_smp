<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->change();
            $table->enum('status', ['aktif', 'lulus', 'mutasi'])->default('aktif')->after('no_telp');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable(false)->change();
            $table->dropColumn('status');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas')->cascadeOnDelete();
        });
    }
};
