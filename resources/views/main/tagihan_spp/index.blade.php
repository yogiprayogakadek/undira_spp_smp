@extends('templates.backend.master')

@section('page-title', 'Tagihan SPP')
@section('page-link', route('tagihan-spp.index'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dataTables.bootstrap5.min.css') }}">
    <style>
        .stat-card { border-radius:12px;border:none;transition:transform .2s,box-shadow .2s; }
        .stat-card:hover { transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.09); }
        .stat-icon { width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px; }
        .table thead th { font-size:12px;text-transform:uppercase;letter-spacing:.5px;padding:11px 13px; }
        .table tbody td { padding:11px 13px;vertical-align:middle; }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <script>toastr.success("{{ session('success') }}", "Berhasil", {showMethod:"slideDown",hideMethod:"slideUp",timeOut:2500});</script>
    @endif
    @if(session('error'))
        <script>toastr.error("{{ session('error') }}", "Error", {showMethod:"slideDown",hideMethod:"slideUp",timeOut:3500});</script>
    @endif

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white"><iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-primary" id="stat-total">—</div><div class="small text-muted">Total Tagihan</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-danger-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger text-white"><iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-danger" id="stat-belum">—</div><div class="small text-muted">Belum Bayar</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-warning-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white"><iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-warning" id="stat-sebagian">—</div><div class="small text-muted">Sebagian</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-success-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white"><iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-success" id="stat-lunas">—</div><div class="small text-muted">Lunas</div></div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role !== 'kepala sekolah')
    {{-- Generate Tagihan --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon bg-info-subtle text-info" style="width:38px;height:38px;border-radius:10px;font-size:18px;">
                    <iconify-icon icon="solar:magic-stick-bold-duotone"></iconify-icon>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Generate Tagihan</h6>
                    <p class="mb-0 small text-muted">Buat 12 tagihan sekaligus untuk satu siswa</p>
                </div>
            </div>
        </div>
        <div class="card-body px-4 py-3">
            <form action="{{ route('tagihan-spp.generate') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label for="tingkat" class="form-label fw-semibold small text-uppercase text-muted">Tingkat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <iconify-icon icon="solar:sort-by-time-bold-duotone" style="font-size:18px"></iconify-icon>
                        </span>
                        <select id="tingkat" class="form-select border-start-0 ps-0 bg-light">
                            <option value="">— Tingkat —</option>
                            <option value="7">Kelas 7</option>
                            <option value="8">Kelas 8</option>
                            <option value="9">Kelas 9</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="kelas_id" class="form-label fw-semibold small text-uppercase text-muted">Kelas</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <iconify-icon icon="solar:users-group-rounded-bold-duotone" style="font-size:18px"></iconify-icon>
                        </span>
                        <select id="kelas_id" class="form-select border-start-0 ps-0 bg-light" disabled>
                            <option value="">— Pilih Kelas —</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="siswa_id" class="form-label fw-semibold small text-uppercase text-muted">Siswa</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <iconify-icon icon="solar:user-bold-duotone" style="font-size:18px"></iconify-icon>
                        </span>
                        <select name="siswa_id" id="siswa_id" class="form-select border-start-0 ps-0 bg-light" disabled>
                            <option value="">— Pilih Siswa —</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="tahun_ajaran" class="form-label fw-semibold small text-uppercase text-muted">Tahun Ajaran</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <iconify-icon icon="solar:calendar-bold-duotone" style="font-size:18px"></iconify-icon>
                        </span>
                        <input type="text" name="tahun_ajaran" id="tahun_ajaran"
                            class="form-control border-start-0 ps-0 bg-light"
                            placeholder="2025/2026" value="{{ date('Y') . '/' . (date('Y')+1) }}">
                    </div>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-info px-5 hstack justify-content-center gap-2 text-white shadow-sm">
                        <iconify-icon icon="solar:magic-stick-bold-duotone" class="fs-5"></iconify-icon>
                        Generate 12 Bulan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($filteredSiswa)
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center justify-content-between mb-4 fade show" role="alert" style="border-radius:12px;background:#eef5ff;">
        <div class="d-flex align-items-center gap-2">
            <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-5 text-info"></iconify-icon>
            <div class="text-dark small">
                Menampilkan hasil generate tagihan untuk siswa: <strong class="text-primary">{{ $filteredSiswa->nama_lengkap }}</strong>
            </div>
        </div>
        <a href="{{ route('tagihan-spp.index') }}" class="btn btn-primary btn-sm px-3 fw-bold hstack gap-1 text-white border-0" style="background:#5d87ff;border-radius:8px;">
            <iconify-icon icon="solar:list-bold" class="fs-5"></iconify-icon>
            Tampilkan Semua Data
        </a>
    </div>
    @endif

    {{-- Tabel Tagihan --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon bg-primary-subtle text-primary" style="width:42px;height:42px;border-radius:10px;font-size:20px;">
                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                </div>
                <div>
                    <h5 class="card-title mb-0 fw-bold">Daftar Tagihan SPP</h5>
                    <p class="card-subtitle mb-0 small text-muted">Rekap tagihan seluruh siswa</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <select id="filter_tingkat" class="form-select form-select-sm border-light-subtle bg-light text-muted fw-semibold" style="width: 150px; border-radius: 8px; font-size: 13px;">
                    <option value="">— Semua Tingkat —</option>
                    <option value="7">Kelas 7</option>
                    <option value="8">Kelas 8</option>
                    <option value="9">Kelas 9</option>
                </select>
                <select id="filter_kelas" class="form-select form-select-sm border-light-subtle bg-light text-muted fw-semibold" style="width: 150px; border-radius: 8px; font-size: 13px;">
                    <option value="">— Semua Kelas —</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" data-tingkat="{{ $kelas->tingkat }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body px-3 pb-3">
            <div class="table-responsive">
                <table id="table" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold" style="width:50px">No.</th>
                            <th class="fw-semibold">Siswa</th>
                            <th class="fw-semibold">Kelas</th>
                            <th class="fw-semibold">Periode</th>
                            <th class="fw-semibold">Nominal</th>
                            <th class="fw-semibold text-center">Status</th>
                            <th class="fw-semibold text-center" style="width:80px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Edit Tagihan --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div style="height:4px;background:linear-gradient(90deg,#5d87ff,#ffae1f); border-radius: 4px 4px 0 0;"></div>
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;border-radius:12px;background:#fff8ec;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-warning" style="font-size:22px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold" id="modalEditLabel">Edit Nominal Tagihan</h5>
                            <p class="text-muted small mb-0">Ubah nominal tagihan SPP bulanan siswa</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-2">
                    <form action="" method="POST" id="formEdit">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Siswa</label>
                            <input type="text" class="form-control bg-light border-0" id="edit_siswa" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Periode</label>
                            <input type="text" class="form-control bg-light border-0" id="edit_periode" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="edit_nominal" class="form-label fw-semibold small text-uppercase text-muted">Nominal Tagihan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">Rp</span>
                                <input type="number" name="nominal" id="edit_nominal" class="form-control border-start-0 ps-0 bg-light" required min="0">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning px-4 shadow-sm text-white fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/backend/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            const dt = $('#table').DataTable({
                processing: true, serverSide: true, searchDelay: 500,
                ajax: {
                    url: '{{ route('tagihan-spp.index') }}',
                    data: function (d) {
                        d.siswa_id = '{{ request('siswa_id') }}';
                        d.kelas_id = $('#filter_kelas').val();
                        d.tingkat = $('#filter_tingkat').val();
                    }
                },
                columns: [
                    { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center text-muted small' },
                    { data:'siswa_nama', name:'siswa_nama', render: d => `<span class="fw-semibold">${d}</span>` },
                    { data:'kelas_nama', name:'kelas_nama', render: d => `<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 fw-semibold">${d}</span>` },
                    { data:'bulan_label', name:'bulan_label', searchable:false },
                    { data:'nominal_fmt', name:'nominal', render: d => `<span class="fw-bold text-success small">${d}</span>` },
                    { data:'status_badge', name:'status', className:'text-center', orderable:false, searchable:false },
                    { data:'actions', name:'actions', className:'text-center', orderable:false, searchable:false },
                ],
                language: {
                    search:"_INPUT_", searchPlaceholder:"Cari siswa / kelas...",
                    lengthMenu:"Tampilkan _MENU_ data", info:"_START_–_END_ dari _TOTAL_",
                    infoEmpty:"Tidak ada data", zeroRecords:"Tagihan tidak ditemukan",
                    paginate:{ previous:"‹", next:"›" },
                },
                dom:'<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"f><"table-responsive"t><"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2"lip>',
            });

            $('#table').on('xhr.dt', function () {
                const json = dt.ajax.json();
                if (!json || !json.data) return;
                let belum=0, sebagian=0, lunas=0;
                json.data.forEach(r => {
                    if (r.status === 'belum_bayar') belum++;
                    else if (r.status === 'sebagian') sebagian++;
                    else if (r.status === 'lunas') lunas++;
                });
                document.getElementById('stat-total').textContent   = json.recordsTotal ?? json.data.length;
                document.getElementById('stat-belum').textContent   = belum;
                document.getElementById('stat-sebagian').textContent = sebagian;
                document.getElementById('stat-lunas').textContent   = lunas;
            });

            // Dependent Dropdown Logic
            const tingkatSelect = document.getElementById('tingkat');
            const kelasSelect   = document.getElementById('kelas_id');
            const siswaSelect   = document.getElementById('siswa_id');

            tingkatSelect.addEventListener('change', function () {
                const tingkat = this.value;
                kelasSelect.innerHTML = '<option value="">— Pilih Kelas —</option>';
                siswaSelect.innerHTML = '<option value="">— Pilih Siswa —</option>';
                kelasSelect.disabled  = true;
                siswaSelect.disabled  = true;

                if (tingkat) {
                    fetch(`{{ route('tagihan-spp.get-kelas') }}?tingkat=${tingkat}`)
                        .then(r => r.json())
                        .then(data => {
                            data.forEach(k => {
                                kelasSelect.innerHTML += `<option value="${k.id}">${k.nama}</option>`;
                            });
                            kelasSelect.disabled = false;
                        });
                }
            });

            kelasSelect.addEventListener('change', function () {
                const kelasId = this.value;
                siswaSelect.innerHTML = '<option value="">— Pilih Siswa —</option>';
                siswaSelect.disabled  = true;

                if (kelasId) {
                    fetch(`{{ route('tagihan-spp.get-siswa') }}?kelas_id=${kelasId}`)
                        .then(r => r.json())
                        .then(data => {
                            data.forEach(s => {
                                siswaSelect.innerHTML += `<option value="${s.id}">${s.nama_lengkap}</option>`;
                            });
                            siswaSelect.disabled = false;
                        });
                }
            });

            const filterTingkatSelect = document.getElementById('filter_tingkat');
            const filterKelasSelect = document.getElementById('filter_kelas');
            const originalFilterKelasOptions = Array.from(filterKelasSelect.options);

            function updateFilterKelasOptions() {
                const selectedTingkat = filterTingkatSelect.value;
                const currentVal = filterKelasSelect.value;

                filterKelasSelect.innerHTML = '';
                filterKelasSelect.appendChild(originalFilterKelasOptions[0].cloneNode(true));

                originalFilterKelasOptions.forEach((option, index) => {
                    if (index === 0) return;
                    const optTingkat = option.getAttribute('data-tingkat');
                    if (!selectedTingkat || optTingkat === selectedTingkat) {
                        const clonedOpt = option.cloneNode(true);
                        if (clonedOpt.value === currentVal) {
                            clonedOpt.selected = true;
                        }
                        filterKelasSelect.appendChild(clonedOpt);
                    }
                });
            }

            // Handle filter change
            $('#filter_tingkat').on('change', function () {
                updateFilterKelasOptions();
                if (filterKelasSelect.value === '' && $('#filter_kelas').val() !== '') {
                    filterKelasSelect.value = '';
                }
                dt.ajax.reload();
            });

            $('#filter_kelas').on('change', function () {
                dt.ajax.reload();
            });

            // Handle Edit Button Click
            $('#table').on('click', '.btn-edit', function () {
                const id = $(this).data('id');
                const nominal = $(this).data('nominal');
                const siswa = $(this).data('siswa');
                const periode = $(this).data('periode');

                $('#formEdit').attr('action', `/tagihan-spp/update/${id}`);
                $('#edit_siswa').val(siswa);
                $('#edit_periode').val(periode);
                $('#edit_nominal').val(nominal);

                $('#modalEdit').modal('show');
            });
        });
    </script>
@endpush
