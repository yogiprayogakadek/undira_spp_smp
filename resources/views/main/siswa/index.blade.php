@extends('templates.backend.master')

@section('page-title', 'Manajemen Siswa')
@section('page-link', route('siswa.index'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/sweetalert2.min.css') }}">
    <style>
        .stat-card { border-radius: 12px; border: none; transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.09); }
        .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
        .badge-gender { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .table thead th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 11px 13px; }
        .table tbody td { padding: 11px 13px; vertical-align: middle; }
        .student-avatar { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }
    </style>
@endpush

@section('content')
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Berhasil", {
                showMethod: "slideDown", hideMethod: "slideUp", timeOut: 2500
            });
        </script>
    @endif

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-primary" id="stat-total">—</div>
                        <div class="small text-muted">Total Siswa</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card" style="background:#f0f9ff">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon text-white" style="background:#0ea5e9">
                        <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0" style="color:#0ea5e9" id="stat-k7">—</div>
                        <div class="small text-muted">Kelas 7</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-success-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white">
                        <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-success" id="stat-k8">—</div>
                        <div class="small text-muted">Kelas 8</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-warning-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white">
                        <iconify-icon icon="solar:graduation-cap-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-warning" id="stat-k9">—</div>
                        <div class="small text-muted">Kelas 9</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon bg-primary-subtle text-primary" style="width:42px;height:42px;border-radius:10px;font-size:20px;">
                            <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold">Daftar Siswa</h5>
                            <p class="card-subtitle mb-0 small text-muted">Manajemen data siswa seluruh kelas</p>
                        </div>
                    </div>
                    <a href="{{ route('siswa.create') }}" class="btn btn-primary hstack gap-2 px-4 shadow-sm">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon>
                        <span class="d-none d-sm-inline">Tambah Siswa</span>
                    </a>
                </div>
                <div class="card-body px-3 pb-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold" style="width:50px">No.</th>
                                    <th class="fw-semibold">Nama Siswa</th>
                                    <th class="fw-semibold">NIS</th>
                                    <th class="fw-semibold">Kelas</th>
                                    <th class="fw-semibold text-center">L/P</th>
                                    <th class="fw-semibold text-center" style="width:160px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/sweetalert2.min.js') }}"></script>

    <script>
        const avatarColors = ['#5d87ff', '#13deb9', '#ffae1f', '#fa896b', '#49beff', '#6f42c1'];
        function getInitial(nama) { return nama ? nama[0].toUpperCase() : '?'; }
        function getColor(nama) {
            let s = 0;
            for (let c of (nama || '')) s += c.charCodeAt(0);
            return avatarColors[s % avatarColors.length];
        }

        $(document).ready(function () {
            const dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{{ route('siswa.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-muted small' },
                    {
                        data: 'nama_lengkap', name: 'nama_lengkap',
                        render: function (data) {
                            const color = getColor(data);
                            return `<div class="d-flex align-items-center gap-2">
                                <div class="student-avatar" style="background:${color}">${getInitial(data)}</div>
                                <span class="fw-semibold text-dark">${data}</span>
                            </div>`;
                        }
                    },
                    { data: 'nis', name: 'nis', render: data => `<code class="text-muted small">${data}</code>` },
                    {
                        data: 'kelas', name: 'kelas',
                        render: data => `<span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2">${data}</span>`
                    },
                    {
                        data: 'jenis_kelamin', name: 'jenis_kelamin',
                        className: 'text-center',
                        render: function (data) {
                            const lk = (data || '').toLowerCase().trim() === 'laki-laki';
                            return lk
                                ? `<span class="badge-gender bg-info-subtle text-info border border-info-subtle"><iconify-icon icon="solar:men-bold-duotone" style="font-size:13px"></iconify-icon> L</span>`
                                : `<span class="badge-gender bg-danger-subtle text-danger border border-danger-subtle"><iconify-icon icon="solar:women-bold-duotone" style="font-size:13px"></iconify-icon> P</span>`;
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari nama / NIS...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_–_END_ dari _TOTAL_ siswa",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Siswa tidak ditemukan",
                    paginate: { previous: "‹", next: "›" },
                },
                dom: '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"f><"table-responsive"t><"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2"lip>',
            });

            $('#table').on('xhr.dt', function () {
                const json = dt.ajax.json();
                if (!json || !json.data) return;

                let k7 = 0, k8 = 0, k9 = 0;
                json.data.forEach(function (r) {
                    const kelas = (r.kelas || '').trim();
                    if (kelas.startsWith('VII')) k7++;
                    else if (kelas.startsWith('VIII')) k8++;
                    else if (kelas.startsWith('IX')) k9++;
                });

                document.getElementById('stat-total').textContent = json.recordsTotal ?? json.data.length;
                document.getElementById('stat-k7').textContent    = k7;
                document.getElementById('stat-k8').textContent    = k8;
                document.getElementById('stat-k9').textContent    = k9;
            });
        });
    </script>
@endpush
