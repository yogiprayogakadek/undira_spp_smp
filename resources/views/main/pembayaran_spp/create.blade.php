@extends('templates.backend.master')

@section('page-title', 'Catat Pembayaran SPP')
@section('page-link', route('pembayaran-spp.index'))

@push('css')
    <style>
        .tagihan-item { cursor:pointer; transition: border-color .15s, background .15s; }
        .tagihan-item:hover { border-color: #5d87ff !important; background: #f0f4ff !important; }
        .tagihan-item.selected { border-color: #5d87ff !important; background: #eef2ff !important; }
        .tagihan-item .check-icon { display:none; }
        .tagihan-item.selected .check-icon { display:inline-flex; }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height:4px;background:linear-gradient(90deg,#5d87ff,#13deb9)"></div>

                <div class="card-header bg-transparent pt-4 pb-3 px-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:14px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:card-send-bold-duotone" class="text-primary" style="font-size:24px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Catat Pembayaran SPP</h5>
                            <p class="text-muted small mb-0">Pilih siswa dan tagihan yang akan dilunasi</p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-4">
                    <form action="{{ route('pembayaran-spp.store') }}" method="POST" id="form-bayar">
                        @csrf

                        {{-- Section: Siswa --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:28px;height:28px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:user-bold-duotone" class="text-primary" style="font-size:15px"></iconify-icon>
                            </div>
                            <span class="fw-bold text-dark small text-uppercase">Data Siswa</span>
                            <div class="flex-grow-1" style="height:1px;background:#e9ecef"></div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label for="siswa_id" class="form-label fw-semibold small text-uppercase text-muted">Pilih Siswa</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <select name="siswa_id" id="siswa_id"
                                        class="form-select border-start-0 ps-0 bg-light @error('siswa_id') is-invalid @enderror">
                                        <option value="">— Pilih Siswa —</option>
                                        @foreach($siswa as $s)
                                            <option value="{{ $s->id }}" {{ old('siswa_id', request('siswa_id')) == $s->id ? 'selected' : '' }}>
                                                {{ $s->nama_lengkap }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('siswa_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" id="btn-load-tagihan"
                                    class="btn btn-outline-primary w-100 hstack justify-content-center gap-2">
                                    <iconify-icon icon="solar:refresh-bold-duotone" class="fs-5"></iconify-icon>
                                    Muat Tagihan
                                </button>
                            </div>
                        </div>

                        {{-- Section: Tagihan --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:28px;height:28px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:bill-list-bold-duotone" class="text-primary" style="font-size:15px"></iconify-icon>
                            </div>
                            <span class="fw-bold text-dark small text-uppercase">Pilih Tagihan</span>
                            <div class="flex-grow-1" style="height:1px;background:#e9ecef"></div>
                        </div>

                        <div id="tagihan-container" class="mb-4">
                            @if($tagihan->isEmpty())
                                <div id="tagihan-empty" class="text-center py-4 text-muted">
                                    <iconify-icon icon="solar:bill-list-bold-duotone" style="font-size:40px;opacity:.3"></iconify-icon>
                                    <p class="mt-2 small">Pilih siswa dan klik "Muat Tagihan"</p>
                                </div>
                            @else
                                <div class="row g-2" id="tagihan-list">
                                    @foreach($tagihan as $t)
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <div class="tagihan-item border rounded-3 p-3 d-flex flex-column align-items-center gap-1" data-id="{{ $t->id }}" data-nominal="{{ $t->nominal }}">
                                                <div class="check-icon" style="width:20px;height:20px;border-radius:50%;background:#5d87ff;display:flex;align-items:center;justify-content:center;margin-bottom:4px">
                                                    <iconify-icon icon="solar:check-bold" style="font-size:12px;color:#fff"></iconify-icon>
                                                </div>
                                                <span class="fw-bold text-dark small">{{ \App\Models\TagihanSpp::namaBulan($t->bulan) }}</span>
                                                <span class="text-muted" style="font-size:11px">{{ $t->tahun }}</span>
                                                <span class="fw-semibold text-success mt-1" style="font-size:12px">Rp {{ number_format($t->nominal, 0, ',', '.') }}</span>
                                                <input type="checkbox" name="tagihan_ids[]" value="{{ $t->id }}" class="tagihan-check d-none" >
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @error('tagihan_ids')
                            <div class="alert alert-danger py-2 small mb-4">{{ $message }}</div>
                        @enderror

                        {{-- Section: Informasi Pembayaran --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:28px;height:28px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:wallet-money-bold-duotone" class="text-primary" style="font-size:15px"></iconify-icon>
                            </div>
                            <span class="fw-bold text-dark small text-uppercase">Informasi Pembayaran</span>
                            <div class="flex-grow-1" style="height:1px;background:#e9ecef"></div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="tanggal_bayar" class="form-label fw-semibold small text-uppercase text-muted">Tanggal Bayar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:calendar-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="date" name="tanggal_bayar" id="tanggal_bayar"
                                        class="form-control border-start-0 ps-0 bg-light @error('tanggal_bayar') is-invalid @enderror"
                                        value="{{ old('tanggal_bayar', date('Y-m-d')) }}">
                                    @error('tanggal_bayar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="metode_bayar" class="form-label fw-semibold small text-uppercase text-muted">Metode Bayar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:card-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <select name="metode_bayar" id="metode_bayar"
                                        class="form-select border-start-0 ps-0 bg-light @error('metode_bayar') is-invalid @enderror">
                                        <option value="tunai" {{ old('metode_bayar') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="transfer" {{ old('metode_bayar') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                        <option value="qris" {{ old('metode_bayar') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    </select>
                                    @error('metode_bayar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Total Bayar</label>
                                <div class="p-3 rounded-3 text-center fw-bold fs-5 text-success" style="background:#f0fdf4;border:1px solid #bbf7d0" id="total-display">
                                    Rp 0
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="catatan" class="form-label fw-semibold small text-uppercase text-muted">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:document-text-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text" name="catatan" id="catatan"
                                        class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="Misal: Bayar bulan Januari - Maret"
                                        value="{{ old('catatan') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('pembayaran-spp.index') }}" class="btn btn-light px-4 hstack gap-2">
                                <iconify-icon icon="solar:close-circle-line-duotone" class="fs-5"></iconify-icon>Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2 shadow-sm">
                                <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function formatRupiah(n) {
            return 'Rp ' + parseInt(n).toLocaleString('id-ID');
        }

        function hitungTotal() {
            let total = 0;
            document.querySelectorAll('.tagihan-item.selected').forEach(el => {
                total += parseFloat(el.dataset.nominal || 0);
            });
            document.getElementById('total-display').textContent = formatRupiah(total);
        }

        document.querySelectorAll('.tagihan-item').forEach(el => {
            el.addEventListener('click', function () {
                this.classList.toggle('selected');
                const cb = this.querySelector('.tagihan-check');
                if (cb) cb.checked = !cb.checked;
                hitungTotal();
            });
        });

        document.getElementById('btn-load-tagihan').addEventListener('click', function () {
            const siswaId = document.getElementById('siswa_id').value;
            if (!siswaId) { alert('Pilih siswa terlebih dahulu.'); return; }

            const url = '{{ route('pembayaran-spp.tagihan-siswa') }}?siswa_id=' + siswaId;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memuat...';

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    const container = document.getElementById('tagihan-container');
                    if (!data.length) {
                        container.innerHTML = `<div class="text-center py-4 text-muted">
                            <iconify-icon icon="solar:check-circle-bold-duotone" style="font-size:40px;color:#22c55e"></iconify-icon>
                            <p class="mt-2 small fw-semibold text-success">Semua tagihan sudah lunas!</p>
                        </div>`;
                        return;
                    }
                    let html = '<div class="row g-2" id="tagihan-list">';
                    data.forEach(t => {
                        html += `<div class="col-6 col-md-4 col-lg-3">
                            <div class="tagihan-item border rounded-3 p-3 d-flex flex-column align-items-center gap-1"
                                data-id="${t.id}" data-nominal="${t.nominal}">
                                <div class="check-icon" style="width:20px;height:20px;border-radius:50%;background:#5d87ff;display:flex;align-items:center;justify-content:center;margin-bottom:4px">
                                    <iconify-icon icon="solar:check-bold" style="font-size:12px;color:#fff"></iconify-icon>
                                </div>
                                <span class="fw-bold text-dark small">${t.label.split(' ')[0]}</span>
                                <span class="text-muted" style="font-size:11px">${t.label.split(' ')[1] || ''}</span>
                                <span class="fw-semibold text-success mt-1" style="font-size:12px">Rp ${parseInt(t.nominal).toLocaleString('id-ID')}</span>
                                <input type="checkbox" name="tagihan_ids[]" value="${t.id}" class="tagihan-check d-none">
                            </div>
                        </div>`;
                    });
                    html += '</div>';
                    container.innerHTML = html;

                    document.querySelectorAll('.tagihan-item').forEach(el => {
                        el.addEventListener('click', function () {
                            this.classList.toggle('selected');
                            this.querySelector('.tagihan-check').checked = !this.querySelector('.tagihan-check').checked;
                            hitungTotal();
                        });
                    });
                    hitungTotal();
                })
                .catch(() => alert('Gagal memuat tagihan.'))
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = '<iconify-icon icon="solar:refresh-bold-duotone" class="fs-5"></iconify-icon> Muat Tagihan';
                });
        });

        hitungTotal();
    </script>
@endpush
