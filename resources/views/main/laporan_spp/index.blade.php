@extends('templates.backend.master')

@section('page-title', 'Laporan Pembayaran SPP Bulanan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('laporan-spp.preview') }}" method="POST">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tahun Ajaran</label>
                            <select name="tahun_ajaran" class="form-select border-0 bg-light-subtle shadow-none" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach($tahunAjaran as $ta)
                                    <option value="{{ $ta }}" {{ (isset($filters['tahun_ajaran']) && $filters['tahun_ajaran'] == $ta) ? 'selected' : '' }}>{{ $ta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tingkat</label>
                            <select name="tingkat" class="form-select border-0 bg-light-subtle shadow-none">
                                <option value="">Semua Tingkat</option>
                                <option value="7" {{ (isset($filters['tingkat']) && $filters['tingkat'] == '7') ? 'selected' : '' }}>Tingkat 7</option>
                                <option value="8" {{ (isset($filters['tingkat']) && $filters['tingkat'] == '8') ? 'selected' : '' }}>Tingkat 8</option>
                                <option value="9" {{ (isset($filters['tingkat']) && $filters['tingkat'] == '9') ? 'selected' : '' }}>Tingkat 9</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kelas</label>
                            <select name="kelas_id" class="form-select border-0 bg-light-subtle shadow-none">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ (isset($filters['kelas_id']) && $filters['kelas_id'] == $k->id) ? 'selected' : '' }}>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">Bulan</label>
                            <select name="bulan" class="form-select border-0 bg-light-subtle shadow-none" required>
                                <option value="">Pilih Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ (isset($filters['bulan']) && $filters['bulan'] == $i) ? 'selected' : '' }}>{{ \App\Models\TagihanSpp::namaBulan($i) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                            <select name="status" class="form-select border-0 bg-light-subtle shadow-none">
                                <option value="">Semua Status</option>
                                <option value="lunas" {{ (isset($filters['status']) && $filters['status'] == 'lunas') ? 'selected' : '' }}>Lunas</option>
                                <option value="sebagian" {{ (isset($filters['status']) && $filters['status'] == 'sebagian') ? 'selected' : '' }}>Sebagian</option>
                                <option value="belum_bayar" {{ (isset($filters['status']) && $filters['status'] == 'belum_bayar') ? 'selected' : '' }}>Belum Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 hstack gap-2 justify-content-center">
                                <iconify-icon icon="solar:magnifer-bold-duotone" class="fs-5"></iconify-icon> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(isset($results))
    <div class="col-12 mt-4">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">Preview Data Laporan</h5>
                    <p class="text-muted small mb-0">{{ $bulanLabel }} ({{ $filters['tahun_ajaran'] }})</p>
                </div>
                <a href="{{ route('laporan-spp.print', $filters) }}" target="_blank" class="btn btn-outline-danger hstack gap-2">
                    <iconify-icon icon="solar:printer-bold-duotone" class="fs-5"></iconify-icon> Cetak PDF/Print
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th class="border-0 px-4 py-3" width="50">No</th>
                                <th class="border-0 py-3">Nama Siswa</th>
                                <th class="border-0 py-3">Kelas</th>
                                <th class="border-0 py-3">Tahun Ajaran</th>
                                <th class="border-0 py-3 text-end">Nominal SPP</th>
                                <th class="border-0 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $row)
                            <tr>
                                <td class="px-4">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $row->siswa->nama_lengkap }}</td>
                                <td>{{ $row->kelas?->nama ?? '—' }}</td>
                                <td>{{ $row->tarif->tahun_ajaran }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @php
                                        $map = [
                                            'belum_bayar' => ['danger',  'Belum Lunas'],
                                            'sebagian'    => ['warning', 'Sebagian'],
                                            'lunas'       => ['success', 'Lunas'],
                                        ];
                                        [$color, $label] = $map[$row->status] ?? ['secondary', $row->status];
                                    @endphp
                                    <span class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle px-2 fw-semibold">{{ $label }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan untuk filter ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($results->count() > 0)
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold px-4 py-3">Total Potensi Pemasukan:</td>
                                <td class="text-end fw-bold text-primary py-3">Rp {{ number_format($results->sum('nominal'), 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
