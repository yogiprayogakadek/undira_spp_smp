@extends('templates.backend.master')

@section('page-title', 'Edit Data Siswa')
@section('page-link', route('siswa.index'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card border-0 shadow-sm overflow-hidden">

                <div style="height:4px;background:linear-gradient(90deg,#ffae1f,#fa896b)"></div>

                <div class="card-header bg-transparent pt-4 pb-3 px-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:14px;background:#fff8f0;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-warning" style="font-size:24px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Edit Data Siswa</h5>
                            <p class="text-muted small mb-0">Perbarui informasi <strong class="text-dark">{{ $siswa->nama_lengkap }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-4">
                    <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" id="form">
                        @method('PUT')
                        @csrf

                        {{-- Section: Identitas --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:28px;height:28px;border-radius:8px;background:#fff8f0;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:user-id-bold-duotone" class="text-warning" style="font-size:15px"></iconify-icon>
                            </div>
                            <span class="fw-bold text-dark small text-uppercase">Identitas Siswa</span>
                            <div class="flex-grow-1" style="height:1px;background:#e9ecef"></div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label for="nama_lengkap" class="form-label fw-semibold small text-uppercase text-muted">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:user-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('nama_lengkap') is-invalid @enderror"
                                        id="nama_lengkap" name="nama_lengkap"
                                        value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}">
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="nis" class="form-label fw-semibold small text-uppercase text-muted">NIS</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:hashtag-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('nis') is-invalid @enderror"
                                        id="nis" name="nis"
                                        value="{{ old('nis', $siswa->nis) }}">
                                    @error('nis')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="jenis_kelamin" class="form-label fw-semibold small text-uppercase text-muted">Jenis Kelamin</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:men-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <select name="jenis_kelamin" id="jenis_kelamin"
                                        class="form-select border-start-0 ps-0 bg-light @error('jenis_kelamin') is-invalid @enderror">
                                        <option value="laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="agama" class="form-label fw-semibold small text-uppercase text-muted">Agama</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:star-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <select name="agama" id="agama"
                                        class="form-select border-start-0 ps-0 bg-light @error('agama') is-invalid @enderror">
                                        @foreach (['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $ag)
                                            <option value="{{ $ag }}" {{ old('agama', $siswa->agama) == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                        @endforeach
                                    </select>
                                    @error('agama')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="tempat_lahir" class="form-label fw-semibold small text-uppercase text-muted">Tempat Lahir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:map-point-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('tempat_lahir') is-invalid @enderror"
                                        id="tempat_lahir" name="tempat_lahir"
                                        value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label fw-semibold small text-uppercase text-muted">Tanggal Lahir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:calendar-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="date"
                                        class="form-control border-start-0 ps-0 bg-light @error('tanggal_lahir') is-invalid @enderror"
                                        id="tanggal_lahir" name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')) }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Kelas & Kontak --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:28px;height:28px;border-radius:8px;background:#fff8f0;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:buildings-bold-duotone" class="text-warning" style="font-size:15px"></iconify-icon>
                            </div>
                            <span class="fw-bold text-dark small text-uppercase">Kelas & Kontak</span>
                            <div class="flex-grow-1" style="height:1px;background:#e9ecef"></div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="kelas_id" class="form-label fw-semibold small text-uppercase text-muted">Kelas</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:door-open-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <select name="kelas_id" id="kelas_id"
                                        class="form-select border-start-0 ps-0 bg-light @error('kelas_id') is-invalid @enderror">
                                        <option value="">— Pilih Kelas —</option>
                                        @foreach ($kelas->sortBy('tingkat') as $k)
                                            <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="no_telp" class="form-label fw-semibold small text-uppercase text-muted">No. Telepon / WA</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:phone-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('no_telp') is-invalid @enderror"
                                        id="no_telp" name="no_telp"
                                        value="{{ old('no_telp', $siswa->no_telp) }}">
                                    @error('no_telp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label fw-semibold small text-uppercase text-muted">Email <span class="text-muted fw-normal">(opsional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:letter-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="email"
                                        class="form-control border-start-0 ps-0 bg-light @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        value="{{ old('email', $siswa->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="alamat" class="form-label fw-semibold small text-uppercase text-muted">Alamat</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:home-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('alamat') is-invalid @enderror"
                                        id="alamat" name="alamat"
                                        value="{{ old('alamat', $siswa->alamat) }}">
                                    @error('alamat')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('siswa.index') }}" class="btn btn-light px-4 hstack gap-2">
                                <iconify-icon icon="solar:close-circle-line-duotone" class="fs-5"></iconify-icon>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-warning px-4 hstack gap-2 shadow-sm text-white">
                                <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
