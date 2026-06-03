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

    public function getAll(array $fields = ['*'], ?int $siswaId = null, ?int $kelasId = null, ?int $tingkat = null)
    {
        return $this->tagihanSppRepository->getAll($fields, $siswaId, $kelasId, $tingkat);
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
     * Generate tagihan untuk satu siswa selama setahun (12 bulan).
     */
    public function generateTahunan(int $siswaId, int $tingkat, int $tahun, string $tahunAjaran): array
    {
        $tarif = $this->tarifSppRepository->findByTingkatTahun($tingkat, $tahunAjaran);

        if (!$tarif) {
            throw new \RuntimeException("Tarif SPP untuk Kelas {$tingkat} tahun ajaran {$tahunAjaran} belum diatur.");
        }

        $generated = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            if ($this->tagihanSppRepository->existsByBulanTahun($siswaId, $bulan, $tahun)) {
                continue;
            }

            $tagihan = $this->tagihanSppRepository->create([
                'siswa_id'     => $siswaId,
                'tarif_spp_id' => $tarif->id,
                'bulan'        => $bulan,
                'tahun'        => $tahun,
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
