@extends('templates.backend.master')

@section('page-title', 'Detail Pembayaran')
@section('page-link', route('pembayaran-spp.index'))

@push('css')
    <style>
        @media print {
            .no-print { display: none !important; }
            .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
        }
        .kwitansi-header { background: linear-gradient(135deg, #5d87ff 0%, #13deb9 100%); }
        .detail-bulan { display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;background:#eef2ff;color:#5d87ff;border:1px solid #c7d2fe; }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">

            {{-- Action Bar --}}
            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <a href="{{ route('pembayaran-spp.index') }}" class="btn btn-light hstack gap-2">
                    <iconify-icon icon="solar:arrow-left-bold-duotone" class="fs-5"></iconify-icon>Kembali
                </a>
                <button onclick="window.print()" class="btn btn-outline-primary hstack gap-2">
                    <iconify-icon icon="solar:printer-bold-duotone" class="fs-5"></iconify-icon>Cetak Kwitansi
                </button>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden" id="kwitansi">
                {{-- Header Kwitansi --}}
                <div class="kwitansi-header text-white px-4 py-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <div class="fw-bold" style="font-size:18px;letter-spacing:.5px">KWITANSI PEMBAYARAN SPP</div>
                            <div class="opacity-75 small mt-1">SMPN 1 Mauponggo Satap</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" style="font-size:20px;letter-spacing:1px">{{ $pembayaran->no_kwitansi }}</div>
                            <div class="opacity-75 small mt-1">{{ $pembayaran->tanggal_bayar?->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 py-4">

                    {{-- Info Siswa & Pembayar --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <p class="fw-bold small text-uppercase text-muted mb-2">Informasi Siswa</p>
                            <table class="w-100" style="font-size:14px">
                                <tr>
                                    <td class="text-muted" style="width:110px">Nama</td>
                                    <td class="fw-semibold">: {{ $pembayaran->siswa?->nama_lengkap ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">NIS</td>
                                    <td>: <code>{{ $pembayaran->siswa?->nis ?? '—' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kelas</td>
                                    <td>: <span class="fw-semibold text-primary">{{ $pembayaran->siswa?->kelas?->nama ?? '—' }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-bold small text-uppercase text-muted mb-2">Informasi Transaksi</p>
                            <table class="w-100" style="font-size:14px">
                                <tr>
                                    <td class="text-muted" style="width:120px">Petugas</td>
                                    <td class="fw-semibold">: {{ $pembayaran->user?->profile?->nama_lengkap ?? $pembayaran->user?->email ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Metode</td>
                                    <td>: <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold px-2">{{ ucfirst($pembayaran->metode_bayar) }}</span></td>
                                </tr>
                                @if($pembayaran->catatan)
                                <tr>
                                    <td class="text-muted">Catatan</td>
                                    <td>: {{ $pembayaran->catatan }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div style="height:1px;background:#e9ecef" class="mb-4"></div>

                    {{-- Detail Tagihan --}}
                    <p class="fw-bold small text-uppercase text-muted mb-3">Rincian Tagihan yang Dilunasi</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($pembayaran->detail as $d)
                            <span class="detail-bulan">
                                <iconify-icon icon="solar:calendar-bold-duotone" style="font-size:13px"></iconify-icon>
                                {{ \App\Models\TagihanSpp::namaBulan($d->tagihan?->bulan) }} {{ $d->tagihan?->tahun }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Total --}}
                    <div class="d-flex justify-content-end">
                        <div class="p-4 rounded-3 text-end" style="background:#f0fdf4;border:2px solid #bbf7d0;min-width:260px">
                            <div class="text-muted small mb-1">Total Pembayaran</div>
                            <div class="fw-bold text-success" style="font-size:26px">
                                Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
                            </div>
                            <div class="text-muted small mt-1">{{ $pembayaran->detail->count() }} bulan dilunasi</div>
                        </div>
                    </div>

                    {{-- Tanda Tangan --}}
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-6 text-center">
                            <p class="small text-muted mb-5">Orang Tua / Wali Siswa</p>
                            <div class="border-top pt-2 small fw-semibold">( ................................ )</div>
                        </div>
                        <div class="col-6 text-center">
                            <p class="small text-muted mb-5">Bendahara</p>
                            <div class="border-top pt-2 small fw-semibold">( {{ $pembayaran->user?->profile?->nama_lengkap ?? '...' }} )</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
