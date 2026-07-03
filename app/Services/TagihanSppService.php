<?php

namespace App\Services;

use App\Models\TagihanSpp;
use App\Repositories\TagihanSppRepository;
use App\Repositories\TarifSppRepository;

class TagihanSppService
{
    public function __construct(
        protected TagihanSppRepository $tagihanSppRepository,
        protected TarifSppRepository   $tarifSppRepository,
    ) {}

    public function getAll(array $fields = ['*'], ?int $siswaId = null, ?int $kelasId = null, ?int $tingkat = null, ?string $tahunAjaran = null, ?string $semester = null)
    {
        return $this->tagihanSppRepository->getAll($fields, $siswaId, $kelasId, $tingkat, $tahunAjaran, $semester);
    }

    public function findById(array $fields, int $id)
    {
        return $this->tagihanSppRepository->findById($fields, $id);
    }

    public function getBySiswa(int $siswaId)
    {
        return $this->tagihanSppRepository->getBySiswa($siswaId);
    }

    public function getTagihanBelumLunas(int $siswaId)
    {
        return $this->tagihanSppRepository->getTagihanBelumLunas($siswaId);
    }

    /**
     * Generate tagihan untuk satu siswa selama setahun (12 bulan dari Juli ke Juni).
     */
    public function generateTahunan(int $siswaId, int $tingkat, int $tahun, string $tahunAjaran): array
    {
        $tarif = $this->tarifSppRepository->findByTingkatTahun($tingkat, $tahunAjaran);

        if (!$tarif) {
            throw new \RuntimeException("Tarif SPP untuk Kelas {$tingkat} tahun ajaran {$tahunAjaran} belum diatur.");
        }

        $parts = explode('/', $tahunAjaran);
        $startYear = (int)$parts[0];
        $endYear = isset($parts[1]) ? (int)$parts[1] : $startYear + 1;

        $months = [
            ['bulan' => 7, 'tahun' => $startYear],
            ['bulan' => 8, 'tahun' => $startYear],
            ['bulan' => 9, 'tahun' => $startYear],
            ['bulan' => 10, 'tahun' => $startYear],
            ['bulan' => 11, 'tahun' => $startYear],
            ['bulan' => 12, 'tahun' => $startYear],
            ['bulan' => 1, 'tahun' => $endYear],
            ['bulan' => 2, 'tahun' => $endYear],
            ['bulan' => 3, 'tahun' => $endYear],
            ['bulan' => 4, 'tahun' => $endYear],
            ['bulan' => 5, 'tahun' => $endYear],
            ['bulan' => 6, 'tahun' => $endYear],
        ];

        $generated = [];
        foreach ($months as $m) {
            $bulan = $m['bulan'];
            $tahunBulan = $m['tahun'];

            if ($this->tagihanSppRepository->existsByBulanTahun($siswaId, $bulan, $tahunBulan)) {
                continue;
            }

            $tagihan = $this->tagihanSppRepository->create([
                'siswa_id'     => $siswaId,
                'tarif_spp_id' => $tarif->id,
                'bulan'        => $bulan,
                'tahun'        => $tahunBulan,
                'nominal'      => $tarif->nominal,
                'status'       => 'belum_bayar',
            ]);

            $generated[] = $tagihan;
        }

        return $generated;
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->tagihanSppRepository->updateStatus($id, $status);
    }

    public function update(int $id, array $data)
    {
        return $this->tagihanSppRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->tagihanSppRepository->delete($id);
    }
}
