@extends('templates.backend.master')

@section('page-title', 'Kenaikan Kelas & Kelulusan')
@section('page-link', route('kenaikan-kelas.index'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/sweetalert2.min.css') }}">
    <style>
        .option-card {
            border: 2px solid #eef2f6;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .option-card:hover {
            border-color: #5d87ff;
            background-color: #f6f9ff;
        }
        .option-card.active {
            border-color: #5d87ff;
            background-color: #ecf2ff;
        }
        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 14px;
        }
        .table tbody td {
            padding: 12px 14px;
            vertical-align: middle;
        }
        .panel-disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Berhasil", {
                showMethod: "slideDown",
                hideMethod: "slideUp",
                timeOut: 3000
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                showMethod: "slideDown",
                hideMethod: "slideUp",
                timeOut: 4000
            });
        </script>
    @endif

    <form action="{{ route('kenaikan-kelas.proses') }}" method="POST" id="formProses">
        @csrf
        <div class="row">
            {{-- Bagian Kiri: Form Parameter --}}
            <div class="col-md-5 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center gap-2">
                        <div class="stat-icon bg-primary-subtle text-primary" style="width:36px;height:36px;border-radius:10px;font-size:18px;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Parameter Proses</h5>
                    </div>
                    <div class="card-body p-4">
                        {{-- 1. Pilih Kelas Asal --}}
                        <div class="mb-4">
                            <label for="kelas_asal_id" class="form-label fw-bold text-muted small text-uppercase">1. Kelas Asal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:buildings-bold-duotone" style="font-size:18px"></iconify-icon>
                                </span>
                                <select class="form-select border-start-0 ps-0 bg-light @error('kelas_asal_id') is-invalid @enderror" id="kelas_asal_id" name="kelas_asal_id">
                                    <option value="">Pilih Kelas Asal...</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" data-tingkat="{{ $kelas->tingkat }}" {{ old('kelas_asal_id') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }} (Kelas {{ $kelas->tingkat }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_asal_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 2. Tipe Proses --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase mb-2">2. Tipe Proses</label>
                            <input type="hidden" name="tipe_proses" id="tipe_proses" value="{{ old('tipe_proses', 'naik') }}">
                            <div class="row g-2">
                                <div class="col-6" id="wrapper-btn-naik">
                                    <div class="option-card p-3 text-center active" id="btn-naik" onclick="setTipeProses('naik')">
                                        <iconify-icon icon="solar:round-arrow-right-up-bold-duotone" class="text-primary fs-3 mb-1"></iconify-icon>
                                        <div class="fw-bold small text-dark">Naik Kelas</div>
                                    </div>
                                </div>
                                <div class="col-6" id="wrapper-btn-lulus">
                                    <div class="option-card p-3 text-center" id="btn-lulus" onclick="setTipeProses('lulus')">
                                        <iconify-icon icon="solar:graduation-cap-bold-duotone" class="text-success fs-3 mb-1"></iconify-icon>
                                        <div class="fw-bold small text-dark">Lulus / Alumni</div>
                                    </div>
                                </div>
                            </div>
                            @error('tipe_proses')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. Kelas Tujuan (Hanya untuk Naik Kelas) --}}
                        <div class="mb-4" id="section-kelas-tujuan">
                            <label for="kelas_tujuan_id" class="form-label fw-bold text-muted small text-uppercase">3. Kelas Tujuan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:buildings-2-bold-duotone" style="font-size:18px"></iconify-icon>
                                </span>
                                <select class="form-select border-start-0 ps-0 bg-light @error('kelas_tujuan_id') is-invalid @enderror" id="kelas_tujuan_id" name="kelas_tujuan_id">
                                    <option value="">Pilih Kelas Tujuan...</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" data-tingkat="{{ $kelas->tingkat }}" {{ old('kelas_tujuan_id') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }} (Kelas {{ $kelas->tingkat }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_tujuan_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 4. Tahun Ajaran Baru (Hanya untuk Naik Kelas) --}}
                        <div class="mb-4" id="section-tahun-ajaran">
                            <label for="tahun_ajaran_baru" class="form-label fw-bold text-muted small text-uppercase">4. Tahun Ajaran Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:calendar-bold-duotone" style="font-size:18px"></iconify-icon>
                                </span>
                                <select class="form-select border-start-0 ps-0 bg-light @error('tahun_ajaran_baru') is-invalid @enderror" id="tahun_ajaran_baru" name="tahun_ajaran_baru">
                                    <option value="">Pilih Tahun Ajaran Baru...</option>
                                    @foreach($tahunAjaranList as $ta)
                                        <option value="{{ $ta }}" {{ old('tahun_ajaran_baru', $nextTa) == $ta ? 'selected' : '' }}>
                                            Tahun Ajaran {{ $ta }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun_ajaran_baru')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text mt-2 small text-muted">Tagihan SPP baru selama 12 bulan akan otomatis di-generate berdasarkan tarif di tahun ajaran baru ini.</div>
                        </div>

                        {{-- Tombol Eksekusi --}}
                        <button type="button" class="btn btn-primary w-100 py-2.5 shadow-sm fw-bold hstack gap-2 justify-content-center" id="btnProsesSubmit">
                            <iconify-icon icon="solar:shield-check-bold" class="fs-5"></iconify-icon>
                            Eksekusi Kenaikan Kelas
                        </button>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan: Daftar Siswa --}}
            <div class="col-md-7 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon bg-success-subtle text-success" style="width:36px;height:36px;border-radius:10px;font-size:18px;display:flex;align-items:center;justify-content:center;">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                            </div>
                            <h5 class="card-title mb-0 fw-bold">Daftar Siswa</h5>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3" id="siswa-counter">0 Siswa Terpilih</span>
                    </div>
                    <div class="card-body p-0">
                        <div id="siswa-placeholder" class="text-center py-5">
                            <iconify-icon icon="solar:users-group-rounded-linear" class="text-muted" style="font-size:60px"></iconify-icon>
                            <p class="mt-2 text-muted">Pilih Kelas Asal terlebih dahulu untuk memuat daftar siswa.</p>
                        </div>
                        <div id="siswa-table-wrapper" class="table-responsive d-none">
                            <table class="table table-hover align-middle mb-0 w-100">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="check-all" checked>
                                            </div>
                                        </th>
                                        <th>NIS</th>
                                        <th>Nama Lengkap</th>
                                        <th>L/P</th>
                                    </tr>
                                </thead>
                                <tbody id="siswa-list"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="{{ asset('assets/backend/js/sweetalert2.min.js') }}"></script>
    <script>
        function setTipeProses(tipe) {
            document.getElementById('tipe_proses').value = tipe;
            
            const btnNaik = document.getElementById('btn-naik');
            const btnLulus = document.getElementById('btn-lulus');
            const secKelasTujuan = document.getElementById('section-kelas-tujuan');
            const secTahunAjaran = document.getElementById('section-tahun-ajaran');
            const btnSubmit = document.getElementById('btnProsesSubmit');

            if (tipe === 'naik') {
                btnNaik.classList.add('active');
                btnLulus.classList.remove('active');
                secKelasTujuan.classList.remove('d-none');
                secTahunAjaran.classList.remove('d-none');
                btnSubmit.className = "btn btn-primary w-100 py-2.5 shadow-sm fw-bold hstack gap-2 justify-content-center";
                btnSubmit.innerHTML = `<iconify-icon icon="solar:shield-check-bold" class="fs-5"></iconify-icon> Eksekusi Kenaikan Kelas`;
            } else {
                btnNaik.classList.remove('active');
                btnLulus.classList.add('active');
                secKelasTujuan.classList.add('d-none');
                secTahunAjaran.classList.add('d-none');
                btnSubmit.className = "btn btn-success w-100 py-2.5 shadow-sm fw-bold hstack gap-2 justify-content-center";
                btnSubmit.innerHTML = `<iconify-icon icon="solar:graduation-cap-bold" class="fs-5"></iconify-icon> Eksekusi Kelulusan`;
            }
        }

        $(document).ready(function () {
            // Restore previous inputs if any
            const prevTipe = "{{ old('tipe_proses', 'naik') }}";
            setTipeProses(prevTipe);

            const kelasAsalSelect = document.getElementById('kelas_asal_id');
            const placeholder = document.getElementById('siswa-placeholder');
            const tableWrapper = document.getElementById('siswa-table-wrapper');
            const listContainer = document.getElementById('siswa-list');
            const checkAll = document.getElementById('check-all');
            const counter = document.getElementById('siswa-counter');

            function updateCounter() {
                const totalChecked = listContainer.querySelectorAll('.siswa-check:checked').length;
                counter.textContent = `${totalChecked} Siswa Terpilih`;
            }

            function loadSiswa(kelasId) {
                if (!kelasId) {
                    placeholder.classList.remove('d-none');
                    tableWrapper.classList.add('d-none');
                    listContainer.innerHTML = '';
                    updateCounter();
                    return;
                }

                // Show loading
                listContainer.innerHTML = '<tr><td colspan="4" class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary me-2"></span>Memuat daftar siswa...</td></tr>';
                placeholder.classList.add('d-none');
                tableWrapper.classList.remove('d-none');

                fetch(`{{ route('kenaikan-kelas.get-siswa') }}?kelas_id=${kelasId}`)
                    .then(response => response.json())
                    .then(res => {
                        listContainer.innerHTML = '';
                        if (res.length === 0) {
                            listContainer.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted"><iconify-icon icon="solar:info-square-linear" class="align-middle me-1 fs-5"></iconify-icon> Tidak ada siswa aktif di kelas ini.</td></tr>';
                            return;
                        }

                        res.forEach(siswa => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input siswa-check" type="checkbox" name="siswa_ids[]" value="${siswa.id}" checked>
                                    </div>
                                </td>
                                <td><code class="fw-bold">${siswa.nis}</code></td>
                                <td class="fw-bold text-dark">${siswa.nama_lengkap}</td>
                                <td>
                                    <span class="badge ${siswa.jenis_kelamin === 'laki-laki' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger'} px-2">
                                        ${siswa.jenis_kelamin === 'laki-laki' ? 'L' : 'P'}
                                    </span>
                                </td>
                            `;
                            listContainer.appendChild(row);
                        });

                        // Rebind check event listeners
                        listContainer.querySelectorAll('.siswa-check').forEach(chk => {
                            chk.addEventListener('change', function () {
                                const allCount = listContainer.querySelectorAll('.siswa-check').length;
                                const checkedCount = listContainer.querySelectorAll('.siswa-check:checked').length;
                                checkAll.checked = allCount === checkedCount;
                                updateCounter();
                            });
                        });

                        checkAll.checked = true;
                        updateCounter();
                    })
                    .catch(err => {
                        listContainer.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-danger">Gagal memuat siswa. Silakan coba lagi.</td></tr>';
                    });
            }

            function updateTipeProsesVisibility() {
                const selectedOption = kelasAsalSelect.selectedOptions[0];
                const tingkat = selectedOption && selectedOption.value ? parseInt(selectedOption.getAttribute('data-tingkat')) : null;

                const wrapperBtnNaik = document.getElementById('wrapper-btn-naik');
                const wrapperBtnLulus = document.getElementById('wrapper-btn-lulus');

                if (tingkat === 9) {
                    wrapperBtnLulus.classList.remove('d-none');
                    wrapperBtnNaik.className = "col-6";
                } else {
                    wrapperBtnLulus.classList.add('d-none');
                    wrapperBtnNaik.className = "col-12";
                    setTipeProses('naik');
                }
            }

            const kelasTujuanSelect = document.getElementById('kelas_tujuan_id');
            const originalTujuanOptions = Array.from(kelasTujuanSelect.options);

            function updateKelasTujuanFilter() {
                const selectedAsal = kelasAsalSelect.selectedOptions[0];
                const currentVal = kelasTujuanSelect.value;

                if (!selectedAsal || !selectedAsal.value) {
                    kelasTujuanSelect.innerHTML = '<option value="">Pilih Kelas Tujuan...</option>';
                    return;
                }

                const tingkatAsal = parseInt(selectedAsal.getAttribute('data-tingkat'));
                const tingkatTujuan = tingkatAsal + 1;

                // Clear options
                kelasTujuanSelect.innerHTML = '';
                
                // Append placeholder
                kelasTujuanSelect.appendChild(originalTujuanOptions[0].cloneNode(true));

                // Append matches
                originalTujuanOptions.forEach((option, index) => {
                    if (index === 0) return;
                    const optTingkat = parseInt(option.getAttribute('data-tingkat'));
                    if (optTingkat === tingkatTujuan) {
                        const clonedOpt = option.cloneNode(true);
                        if (clonedOpt.value === currentVal) {
                            clonedOpt.selected = true;
                        }
                        kelasTujuanSelect.appendChild(clonedOpt);
                    }
                });
            }

            kelasAsalSelect.addEventListener('change', function () {
                updateTipeProsesVisibility();
                updateKelasTujuanFilter();
                loadSiswa(this.value);
            });

            // Trigger load if value exists (e.g. from validation error recovery)
            if (kelasAsalSelect.value) {
                updateTipeProsesVisibility();
                updateKelasTujuanFilter();
                loadSiswa(kelasAsalSelect.value);
            } else {
                updateTipeProsesVisibility();
                updateKelasTujuanFilter();
            }

            checkAll.addEventListener('change', function () {
                const isChecked = this.checked;
                listContainer.querySelectorAll('.siswa-check').forEach(chk => {
                    chk.checked = isChecked;
                });
                updateCounter();
            });

            // SweetAlert2 Confirmation Dialog
            document.getElementById('btnProsesSubmit').addEventListener('click', function () {
                const tipe = document.getElementById('tipe_proses').value;
                const totalChecked = listContainer.querySelectorAll('.siswa-check:checked').length;
                const kelasAsal = kelasAsalSelect.selectedOptions[0]?.text || '';

                if (!kelasAsalSelect.value) {
                    Swal.fire('Error', 'Silakan pilih kelas asal terlebih dahulu.', 'error');
                    return;
                }

                if (totalChecked === 0) {
                    Swal.fire('Error', 'Pilih minimal satu siswa yang ingin diproses.', 'error');
                    return;
                }

                let title = '';
                let text = '';
                let confirmBtnColor = '';

                if (tipe === 'naik') {
                    const kelasTujuanSelect = document.getElementById('kelas_tujuan_id');
                    const kelasTujuan = kelasTujuanSelect.selectedOptions[0]?.text || '';
                    const tahunAjaran = document.getElementById('tahun_ajaran_baru').value;

                    if (!kelasTujuanSelect.value) {
                        Swal.fire('Error', 'Silakan pilih kelas tujuan.', 'error');
                        return;
                    }

                    if (!tahunAjaran) {
                        Swal.fire('Error', 'Silakan pilih tahun ajaran baru.', 'error');
                        return;
                    }

                    title = 'Konfirmasi Kenaikan Kelas';
                    text = `Anda akan menaikkan ${totalChecked} siswa dari ${kelasAsal} ke ${kelasTujuan} untuk Tahun Ajaran ${tahunAjaran}. Proses ini juga akan otomatis membuat tagihan SPP selama 12 bulan di tahun ajaran baru. Lanjutkan?`;
                    confirmBtnColor = '#5d87ff';
                } else {
                    title = 'Konfirmasi Kelulusan';
                    text = `Anda akan meluluskan ${totalChecked} siswa dari ${kelasAsal}. Siswa yang diluluskan akan dilepas dari kelas dan statusnya berubah menjadi Alumni. Tindakan ini tidak dapat dibatalkan. Lanjutkan?`;
                    confirmBtnColor = '#13deb9';
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: confirmBtnColor,
                    cancelButtonColor: '#ff4560',
                    confirmButtonText: 'Ya, Proses Sekarang',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('formProses').submit();
                    }
                });
            });
        });
    </script>
@endpush
