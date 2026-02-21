@extends('templates.backend.master')

@section('page-title', 'Edit Tarif SPP')
@section('page-link', route('tarif-spp.index'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height:4px;background:linear-gradient(90deg,#ffae1f,#fa896b)"></div>

                <div class="card-header bg-transparent pt-4 pb-3 px-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:14px;background:#fff8f0;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-warning" style="font-size:24px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Edit Tarif SPP</h5>
                            <p class="text-muted small mb-0">Kelas {{ $tarif->tingkat }} — Tahun Ajaran <strong class="text-dark">{{ $tarif->tahun_ajaran }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-4">
                    <form action="{{ route('tarif-spp.update', $tarif->id) }}" method="POST">
                        @method('PUT')
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="tingkat" class="form-label fw-semibold small text-uppercase text-muted">Tingkat Kelas</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:sort-by-time-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <select name="tingkat" id="tingkat" class="form-select border-start-0 ps-0 bg-light @error('tingkat') is-invalid @enderror">
                                        @foreach([7,8,9] as $t)
                                            <option value="{{ $t }}" {{ old('tingkat', $tarif->tingkat) == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                                        @endforeach
                                    </select>
                                    @error('tingkat')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="tahun_ajaran" class="form-label fw-semibold small text-uppercase text-muted">Tahun Ajaran</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:calendar-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 bg-light @error('tahun_ajaran') is-invalid @enderror"
                                        id="tahun_ajaran" name="tahun_ajaran" placeholder="2025/2026"
                                        value="{{ old('tahun_ajaran', $tarif->tahun_ajaran) }}">
                                    @error('tahun_ajaran')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text">Format: <code>2025/2026</code></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nominal" class="form-label fw-semibold small text-uppercase text-muted">Nominal SPP (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted fw-semibold">Rp</span>
                                <input type="number" class="form-control border-start-0 ps-0 bg-light @error('nominal') is-invalid @enderror"
                                    id="nominal" name="nominal" min="1"
                                    value="{{ old('nominal', $tarif->nominal) }}">
                                @error('nominal')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="form-label fw-semibold small text-uppercase text-muted">Keterangan <span class="text-muted fw-normal">(opsional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:document-text-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 bg-light @error('keterangan') is-invalid @enderror"
                                    id="keterangan" name="keterangan"
                                    value="{{ old('keterangan', $tarif->keterangan) }}">
                                @error('keterangan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('tarif-spp.index') }}" class="btn btn-light px-4 hstack gap-2">
                                <iconify-icon icon="solar:close-circle-line-duotone" class="fs-5"></iconify-icon>Batal
                            </a>
                            <button type="submit" class="btn btn-warning px-4 hstack gap-2 shadow-sm text-white">
                                <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
