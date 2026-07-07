<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TagihanSpp extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kelas_id) && !empty($model->siswa_id)) {
                $siswa = DB::table('siswa')->where('id', $model->siswa_id)->first();
                if ($siswa) {
                    $model->kelas_id = $siswa->kelas_id;
                }
            }
        });
    }

    protected $table = 'tagihan_spp';
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tarif_spp_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function getSemesterAttribute(): string
    {
        return ($this->bulan >= 7 && $this->bulan <= 12) ? 'Ganjil' : 'Genap';
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function tarif()
    {
        return $this->belongsTo(TarifSpp::class, 'tarif_spp_id', 'id');
    }

    public function detailPembayaran()
    {
        return $this->hasMany(DetailPembayaran::class, 'tagihan_spp_id', 'id');
    }

    public static function namaBulan(int $bulan): string
    {
        $bulanArr = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $bulanArr[$bulan] ?? '';
    }
}
