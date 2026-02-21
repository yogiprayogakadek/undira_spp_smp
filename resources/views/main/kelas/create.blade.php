@extends('templates.backend.master')

@section('page-title', 'Tambah Kelas')
@section('page-link', route('kelas.index'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card border-0 shadow-sm overflow-hidden">

                <div style="height:4px;background:linear-gradient(90deg,#5d87ff,#13deb9)"></div>

                <div class="card-header bg-transparent pt-4 pb-3 px-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:14px;background:#eefaf7;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:buildings-2-bold-duotone" class="text-success" style="font-size:24px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Tambah Kelas Baru</h5>
                            <p class="text-muted small mb-0">Daftarkan kelas baru ke dalam sistem</p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-4">
                    <form action="{{ route('kelas.store') }}" method="POST" id="form">
                        @csrf

                        {{-- Grade & Nama row --}}
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <label for="grade" class="form-label fw-semibold small text-uppercase text-muted ls-1">Tingkat</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:sort-by-time-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <select name="grade" id="grade"
                                        class="form-select border-start-0 ps-0 bg-light @error('grade') is-invalid @enderror">
                                        <option value="">Pilih...</option>
                                        @for ($i = 7; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>
                                                Kelas {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('grade')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label for="nama" class="form-label fw-semibold small text-uppercase text-muted ls-1">Nama / Rombel</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:door-open-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" placeholder="Contoh: A, B, Unggul" value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Nama kelas lengkap akan jadi: <strong id="preview-nama" class="text-primary">—</strong></div>
                            </div>
                        </div>

                        {{-- Tingkat khusus --}}
                        <div class="mb-4">
                            <label for="tingkat" class="form-label fw-semibold small text-uppercase text-muted ls-1">Tingkat Numerik</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:graduation-cap-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <select class="form-select border-start-0 ps-0 bg-light @error('tingkat') is-invalid @enderror"
                                    id="tingkat" name="tingkat">
                                    <option value="">Pilih Tingkat...</option>
                                    @foreach ([7, 8, 9] as $t)
                                        <option value="{{ $t }}" {{ old('tingkat') == $t ? 'selected' : '' }}>
                                            Kelas {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tingkat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Pilih sesuai tingkat di atas agar laporan per tingkat akurat.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top mt-2">
                            <a href="{{ route('kelas.index') }}" class="btn btn-light px-4 hstack gap-2">
                                <iconify-icon icon="solar:close-circle-line-duotone" class="fs-5"></iconify-icon>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2 shadow-sm">
                                <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>
                                Simpan Kelas
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
        const gradeEl = document.getElementById('grade');
        const namaEl  = document.getElementById('nama');
        const prev    = document.getElementById('preview-nama');

        function updatePreview() {
            const g = gradeEl.value;
            const n = namaEl.value.trim();
            prev.textContent = g && n ? `${g} ${n}` : '—';
        }

        gradeEl.addEventListener('change', updatePreview);
        namaEl.addEventListener('input', updatePreview);
        updatePreview();
    </script>
@endpush
