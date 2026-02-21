<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    protected $table = 'detail_pembayaran';
    protected $fillable = [
        'pembayaran_spp_id',
        'tagihan_spp_id',
        'nominal_bayar',
    ];

    protected $casts = [
        'nominal_bayar' => 'decimal:2',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranSpp::class, 'pembayaran_spp_id', 'id');
    }

    public function tagihan()
    {
        return $this->belongsTo(TagihanSpp::class, 'tagihan_spp_id', 'id');
    }
}
