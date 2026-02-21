@extends('templates.backend.master')

@section('page-title', 'Tambah Pengguna')
@section('page-link', route('user.index'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-12">




            <div class="card border-0 shadow-sm overflow-hidden">

                {{-- Card top accent --}}
                <div style="height:4px;background:linear-gradient(90deg,#5d87ff,#49beff)"></div>

                <div class="card-header bg-transparent pt-4 pb-3 px-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width:48px;height:48px;border-radius:14px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:user-plus-bold-duotone" class="text-primary"
                                style="font-size:24px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Tambah Pengguna Baru</h5>
                            <p class="text-muted small mb-0">Buat akun pengguna untuk sistem SI SPP</p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-4">

                    {{-- Info notice --}}
                    <div class="d-flex align-items-start gap-3 p-3 rounded-3 mb-4"
                        style="background:#fffbeb;border:1px solid #fde68a;">
                        <div
                            style="width:36px;height:36px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="text-warning"
                                style="font-size:20px"></iconify-icon>
                        </div>
                        <div class="small">
                            <div class="fw-semibold text-warning-emphasis mb-1">Perhatian</div>
                            <span class="text-warning-emphasis">
                                Profil pengguna akan dilengkapi saat login pertama kali.
                                Password default: <code class="bg-warning-subtle px-1 rounded">password</code>
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('user.store') }}" method="POST" id="form">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold small text-uppercase ls-1 text-muted">
                                Alamat Email
                            </label>
                            <div class="input-group shadow-none">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:letter-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <input type="email"
                                    class="form-control border-start-0 ps-0 bg-light @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="nama@smpn1mauponggo.sch.id"
                                    value="{{ old('email') }}" autocomplete="off">
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <iconify-icon icon="solar:danger-circle-bold" style="font-size:14px"></iconify-icon>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold small text-uppercase ls-1 text-muted">
                                Role / Hak Akses
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:shield-keyhole-bold-duotone"
                                        style="font-size:20px"></iconify-icon>
                                </span>
                                <select name="role" id="role"
                                    class="form-select border-start-0 ps-0 bg-light @error('role') is-invalid @enderror">
                                    <option value="">— Pilih Role —</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                            {{ ucwords($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback d-block">
                                        <iconify-icon icon="solar:danger-circle-bold" style="font-size:14px"></iconify-icon>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Role description pills --}}
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <div
                                    class="d-flex align-items-center gap-1 px-2 py-1 rounded-2 bg-warning-subtle small text-warning fw-semibold border border-warning-subtle">
                                    <iconify-icon icon="solar:shield-user-bold-duotone"
                                        style="font-size:14px"></iconify-icon>
                                    Admin — Akses penuh
                                </div>
                                <div
                                    class="d-flex align-items-center gap-1 px-2 py-1 rounded-2 bg-success-subtle small text-success fw-semibold border border-success-subtle">
                                    <iconify-icon icon="solar:wallet-money-bold-duotone"
                                        style="font-size:14px"></iconify-icon>
                                    Bendahara — Keuangan
                                </div>
                                <div
                                    class="d-flex align-items-center gap-1 px-2 py-1 rounded-2 bg-info-subtle small text-info fw-semibold border border-info-subtle">
                                    <iconify-icon icon="solar:graduation-cap-bold-duotone"
                                        style="font-size:14px"></iconify-icon>
                                    Kepsek — View only
                                </div>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-flex justify-content-end gap-2 pt-2 border-top mt-2">
                            <a href="{{ route('user.index') }}" class="btn btn-light px-4 hstack gap-2">
                                <iconify-icon icon="solar:close-circle-line-duotone" class="fs-5"></iconify-icon>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2 shadow-sm">
                                <iconify-icon icon="solar:user-check-rounded-bold-duotone" class="fs-5"></iconify-icon>
                                Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
