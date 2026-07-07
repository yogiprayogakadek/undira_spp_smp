<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tagihan_spp', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->after('siswa_id')->constrained('kelas')->nullOnDelete();
        });

        // Populate existing data
        $siswas = DB::table('siswa')->get();
        $classes = DB::table('kelas')->get();
        $classesByTingkat = $classes->groupBy('tingkat');

        echo "Processing " . count($siswas) . " students...\n";

        $updates = [];
        $count = 0;
        foreach ($siswas as $siswa) {
            if (!$siswa->kelas_id) {
                continue;
            }

            $currentKelas = $classes->firstWhere('id', $siswa->kelas_id);
            if (!$currentKelas) {
                continue;
            }

            // Extract section/suffix (e.g. "A" from "VII A")
            $parts = explode(' ', $currentKelas->nama);
            $suffix = end($parts);

            // Fetch all bills for this student with their grade level
            $bills = DB::table('tagihan_spp')
                ->join('tarif_spp', 'tagihan_spp.tarif_spp_id', '=', 'tarif_spp.id')
                ->select('tagihan_spp.id', 'tarif_spp.tingkat')
                ->where('tagihan_spp.siswa_id', $siswa->id)
                ->get();

            foreach ($bills as $bill) {
                $targetKelasId = null;
                if (isset($classesByTingkat[$bill->tingkat])) {
                    foreach ($classesByTingkat[$bill->tingkat] as $k) {
                        $kParts = explode(' ', $k->nama);
                        if (end($kParts) === $suffix) {
                            $targetKelasId = $k->id;
                            break;
                        }
                    }
                    if (!$targetKelasId && count($classesByTingkat[$bill->tingkat]) > 0) {
                        $targetKelasId = $classesByTingkat[$bill->tingkat]->first()->id;
                    }
                }

                // If still not found, fallback to the student's current class
                if (!$targetKelasId) {
                    $targetKelasId = $siswa->kelas_id;
                }

                $updates[$targetKelasId][] = $bill->id;
                $count++;
            }
        }

        foreach ($updates as $kelasId => $billIds) {
            DB::table('tagihan_spp')->whereIn('id', $billIds)->update(['kelas_id' => $kelasId]);
        }

        echo "Updated $count bills in " . count($updates) . " batch queries.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihan_spp', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};
