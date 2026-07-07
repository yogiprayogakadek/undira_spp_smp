<?php

namespace App\Repositories;

use App\Models\TagihanSpp;

class TagihanSppRepository
{
    public function __construct(protected TagihanSpp $model) {}

    public function getAll(array $fields = ['*'], ?int $siswaId = null, ?int $kelasId = null, ?int $tingkat = null, ?string $tahunAjaran = null, ?string $semester = null)
    {
        $query = $this->model::select('tagihan_spp.*')
            ->with(['siswa.kelas', 'tarif', 'kelas']);

        if ($siswaId) {
            $query->where('tagihan_spp.siswa_id', $siswaId);
        }

        if ($kelasId) {
            $query->where('tagihan_spp.kelas_id', $kelasId);
        } elseif ($tingkat) {
            $query->whereHas('kelas', function ($q) use ($tingkat) {
                $q->where('tingkat', $tingkat);
            });
        }

        if ($tahunAjaran) {
            $query->whereHas('tarif', function ($q) use ($tahunAjaran) {
                $q->where('tahun_ajaran', $tahunAjaran);
            });
        }

        if ($semester) {
            if ($semester === 'ganjil') {
                $query->whereBetween('tagihan_spp.bulan', [7, 12]);
            } elseif ($semester === 'genap') {
                $query->whereBetween('tagihan_spp.bulan', [1, 6]);
            }
        }

        $query->join('siswa', 'tagihan_spp.siswa_id', '=', 'siswa.id')
            ->leftJoin('kelas', 'tagihan_spp.kelas_id', '=', 'kelas.id')
            ->orderBy('kelas.tingkat', 'asc')
            ->orderBy('kelas.nama', 'asc')
            ->orderBy('siswa.nama_lengkap', 'asc')
            ->orderBy('tagihan_spp.tahun', 'desc')
            ->orderBy('tagihan_spp.bulan', 'desc');

        return $query->get();
    }

    public function findById(array $fields, int $id)
    {
        return $this->model::select($fields)->with(['siswa.kelas', 'tarif'])->where('id', $id)->firstOrFail();
    }

    public function getBySiswa(int $siswaId)
    {
        return $this->model::where('siswa_id', $siswaId)
            ->with('tarif')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
    }

    public function getTagihanBelumLunas(int $siswaId)
    {
        return $this->model::where('siswa_id', $siswaId)
            ->whereIn('status', ['belum_bayar', 'sebagian'])
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->model::where('id', $id)->update(['status' => $status]);
    }

    public function update(int $id, array $data)
    {
        return $this->model::where('id', $id)->update($data);
    }

    public function delete(int $id)
    {
        $tagihan = $this->model::find($id);
        return $tagihan?->delete();
    }

    public function existsByBulanTahun(int $siswaId, int $bulan, int $tahun): bool
    {
        return $this->model::where('siswa_id', $siswaId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();
    }
}
