<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranSpp extends Model
{
    protected $table = 'pembayaran_spp';
    protected $fillable = [
        'no_kwitansi',
        'siswa_id',
        'user_id',
        'total_bayar',
        'tanggal_bayar',
        'metode_bayar',
        'catatan',
    ];

    protected $casts = [
        'total_bayar'   => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPembayaran::class, 'pembayaran_spp_id', 'id');
    }

    public static function generateNoKwitansi(): string
    {
        $year  = now()->year;
        $last  = static::whereYear('created_at', $year)->max('id') ?? 0;
        $seq   = str_pad($last + 1, 5, '0', STR_PAD_LEFT);
        return "SPP-{$year}-{$seq}";
    }
}
