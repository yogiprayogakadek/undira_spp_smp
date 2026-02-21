<?php

namespace App\Services;

use App\Models\PembayaranSpp;
use App\Repositories\DetailPembayaranRepository;
use App\Repositories\PembayaranSppRepository;
use App\Repositories\TagihanSppRepository;
use Illuminate\Support\Facades\DB;

class PembayaranSppService
{
    public function __construct(
        protected PembayaranSppRepository  $pembayaranSppRepository,
        protected DetailPembayaranRepository $detailPembayaranRepository,
        protected TagihanSppRepository     $tagihanSppRepository,
    ) {}

    public function getAll(array $fields = ['*'])
    {
        return $this->pembayaranSppRepository->getAll($fields);
    }

    public function findById(array $fields, int $id)
    {
        return $this->pembayaranSppRepository->findById($fields, $id);
    }

    /**
     * Simpan pembayaran + detail tagihan dalam satu database transaction.
     *
     * @param array $data          ['siswa_id', 'user_id', 'tanggal_bayar', 'metode_bayar', 'catatan']
     * @param array $tagihanIds    Array of tagihan_spp.id yang akan dilunasi
     */
    public function bayar(array $data, array $tagihanIds): PembayaranSpp
    {
        return DB::transaction(function () use ($data, $tagihanIds) {
            $tagihan   = $this->tagihanSppRepository->getTagihanBelumLunas($data['siswa_id'])
                             ->whereIn('id', $tagihanIds);
            $totalBayar = $tagihan->sum('nominal');

            $pembayaran = $this->pembayaranSppRepository->create([
                'no_kwitansi'  => PembayaranSpp::generateNoKwitansi(),
                'siswa_id'     => $data['siswa_id'],
                'user_id'      => $data['user_id'],
                'total_bayar'  => $totalBayar,
                'tanggal_bayar' => $data['tanggal_bayar'],
                'metode_bayar' => $data['metode_bayar'],
                'catatan'      => $data['catatan'] ?? null,
            ]);

            $details = $tagihan->map(fn ($t) => [
                'pembayaran_spp_id' => $pembayaran->id,
                'tagihan_spp_id'    => $t->id,
                'nominal_bayar'     => $t->nominal,
                'created_at'        => now(),
                'updated_at'        => now(),
            ])->values()->toArray();

            $this->detailPembayaranRepository->createMany($details);

            foreach ($tagihanIds as $tId) {
                $this->tagihanSppRepository->updateStatus($tId, 'lunas');
            }

            return $pembayaran->fresh(['siswa.kelas', 'user', 'detail.tagihan']);
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $pembayaran = $this->pembayaranSppRepository->findById(['*'], $id);

            foreach ($pembayaran->detail as $detail) {
                $this->tagihanSppRepository->updateStatus($detail->tagihan_spp_id, 'belum_bayar');
            }

            $this->pembayaranSppRepository->delete($id);
        });
    }
}
