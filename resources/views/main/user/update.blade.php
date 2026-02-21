@extends('templates.backend.master')

@section('page-title', 'Edit Pengguna')
@section('page-link', route('user.index'))

@section('content')
    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                showMethod: "slideDown", hideMethod: "slideUp", timeOut: 2500
            });
        </script>
    @endif

    <form action="{{ route('user.update', $user->id) }}" method="POST" id="form" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="row g-4">

            {{-- ===== KIRI: Security ===== --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden h-100">
                    <div style="height:4px;background:linear-gradient(90deg,#5d87ff,#49beff)"></div>

                    <div class="card-header bg-transparent pt-4 pb-3 px-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;border-radius:12px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:shield-keyhole-bold-duotone" class="text-primary" style="font-size:22px"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Informasi Akun</h6>
                                <p class="text-muted small mb-0">Email, role, status &amp; sandi</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 pb-4">

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold small text-uppercase text-muted">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:letter-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <input type="email"
                                    class="form-control border-start-0 ps-0 bg-light @error('email') is-invalid @enderror"
                                    id="email" name="email"
                                    placeholder="user@smpn1mauponggo.sch.id"
                                    value="{{ old('email', $user->email ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold small text-uppercase text-muted">Role</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:shield-user-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <select name="role" id="role"
                                    class="form-select border-start-0 ps-0 bg-light @error('role') is-invalid @enderror">
                                    <option value="">— Pilih Role —</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                                            {{ ucwords($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Status Akun --}}
                        <div class="mb-4">
                            <label for="isActive" class="form-label fw-semibold small text-uppercase text-muted">Status Akun</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:verified-check-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <select name="is_active" id="isActive"
                                    class="form-select border-start-0 ps-0 bg-light @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>✅ Aktif</option>
                                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>🔴 Nonaktif</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 p-3 rounded-3 mb-4"
                            style="background:#eff6ff;border:1px solid #bfdbfe;">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="text-primary flex-shrink-0 mt-1" style="font-size:18px"></iconify-icon>
                            <span class="small text-primary-emphasis">
                                Kosongkan field sandi jika tidak ingin mengubah password pengguna.
                            </span>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small text-uppercase text-muted">Sandi Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:lock-password-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <input type="password" name="password" id="password"
                                    class="form-control border-start-0 ps-0 bg-light border-end-0 @error('password') is-invalid @enderror"
                                    placeholder="••••••••">
                                <button class="btn bg-light border border-start-0 password-toggle" type="button" data-target="password">
                                    <iconify-icon icon="solar:eye-bold-duotone" class="fs-5 text-muted"></iconify-icon>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-1">
                            <label for="passwordConfirmation" class="form-label fw-semibold small text-uppercase text-muted">Konfirmasi Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:lock-keyhole-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <input type="password" name="password_confirmation" id="passwordConfirmation"
                                    class="form-control border-start-0 ps-0 bg-light border-end-0 @error('password_confirmation') is-invalid @enderror"
                                    placeholder="••••••••">
                                <button class="btn bg-light border border-start-0 password-toggle" type="button" data-target="passwordConfirmation">
                                    <iconify-icon icon="solar:eye-bold-duotone" class="fs-5 text-muted"></iconify-icon>
                                </button>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ===== KANAN: Profile ===== --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden h-100">
                    <div style="height:4px;background:linear-gradient(90deg,#13deb9,#02b5a0)"></div>

                    <div class="card-header bg-transparent pt-4 pb-3 px-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;border-radius:12px;background:#eafaf7;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:user-id-bold-duotone" class="text-success" style="font-size:22px"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Profil Pengguna</h6>
                                <p class="text-muted small mb-0">Nama lengkap, alamat, no. telp &amp; NIP</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 pb-4">

                        {{-- Avatar --}}
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->profile && $user->profile->image ? asset('storage/' . $user->profile->image) : asset('assets/images/profile/user-1.jpg') }}"
                                    alt="avatar" id="preview-image"
                                    class="rounded-circle border border-3 border-white shadow"
                                    style="width:110px;height:110px;object-fit:cover;">
                                <label for="image"
                                    class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2 cursor-pointer shadow-sm"
                                    style="width:36px;height:36px;">
                                    <iconify-icon icon="solar:camera-add-bold-duotone" class="fs-5"></iconify-icon>
                                    <input type="file" id="image" name="image" class="d-none" accept="image/*" onchange="previewFile()">
                                </label>
                            </div>
                            <div class="mt-2">
                                <div class="fw-semibold">{{ $user->profile->nama_lengkap ?? '—' }}</div>
                                <span class="badge bg-primary-subtle text-primary small">{{ ucwords($user->role) }}</span>
                            </div>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="mb-4">
                            <label for="namaLengkap" class="form-label fw-semibold small text-uppercase text-muted">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:user-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <input type="text"
                                    class="form-control border-start-0 ps-0 bg-light @error('nama_lengkap') is-invalid @enderror"
                                    id="namaLengkap" name="nama_lengkap" placeholder="Nama lengkap pengguna"
                                    value="{{ old('nama_lengkap', $user->profile->nama_lengkap ?? '') }}">
                                @error('nama_lengkap')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-4">
                            <label for="alamat" class="form-label fw-semibold small text-uppercase text-muted">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:map-point-bold-duotone" style="font-size:20px"></iconify-icon>
                                </span>
                                <input type="text"
                                    class="form-control border-start-0 ps-0 bg-light @error('alamat') is-invalid @enderror"
                                    id="alamat" name="alamat" placeholder="Desa / Kelurahan, Kecamatan"
                                    value="{{ old('alamat', $user->profile->alamat ?? '') }}">
                                @error('alamat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- No. Telp & NIP --}}
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="noTelp" class="form-label fw-semibold small text-uppercase text-muted">No. Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:phone-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('no_telp') is-invalid @enderror"
                                        id="noTelp" name="no_telp" placeholder="08xx..."
                                        value="{{ old('no_telp', $user->profile->no_telp ?? '') }}">
                                    @error('no_telp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="nip" class="form-label fw-semibold small text-uppercase text-muted">NIP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:card-2-bold-duotone" style="font-size:20px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('nip') is-invalid @enderror"
                                        id="nip" name="nip" inputmode="numeric" placeholder="199001..."
                                        value="{{ old('nip', $user->profile->nip ?? '') }}">
                                    @error('nip')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('user.index') }}" class="btn btn-light px-4 hstack gap-2">
                        <iconify-icon icon="solar:close-circle-line-duotone" class="fs-5"></iconify-icon>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 hstack gap-2 shadow-sm">
                        <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </div>
    </form>
@endsection

@push('script')
    <script>
        function previewFile() {
            const preview = document.querySelector('#preview-image');
            const file    = document.querySelector('#image').files[0];
            const reader  = new FileReader();
            reader.addEventListener('load', () => { preview.src = reader.result; });
            if (file) reader.readAsDataURL(file);
        }

        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.getAttribute('data-target'));
                const icon  = this.querySelector('iconify-icon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.setAttribute('icon', 'solar:eye-closed-bold-duotone');
                } else {
                    input.type = 'password';
                    icon.setAttribute('icon', 'solar:eye-bold-duotone');
                }
            });
        });
    </script>
@endpush
