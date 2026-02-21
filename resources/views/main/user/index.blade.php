@extends('templates.backend.master')

@section('page-title', 'Manajemen Pengguna')
@section('page-link', route('user.index'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/sweetalert2.min.css') }}">
    <style>
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .stat-card {
            border-radius: 12px;
            padding: 16px 20px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
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
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s;
            cursor: pointer;
        }
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
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-primary" id="stat-total">—</div>
                        <div class="small text-muted">Total Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card bg-warning-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white">
                        <iconify-icon icon="solar:shield-user-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-warning" id="stat-admin">—</div>
                        <div class="small text-muted">Admin</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card bg-success-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-success" id="stat-bendahara">—</div>
                        <div class="small text-muted">Bendahara</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card bg-info-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info text-white">
                        <iconify-icon icon="solar:graduation-cap-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-info" id="stat-kepsek">—</div>
                        <div class="small text-muted">Kepala Sekolah</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon bg-primary-subtle text-primary" style="width:42px;height:42px;border-radius:10px;font-size:20px;">
                            <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold">Manajemen Pengguna</h5>
                            <p class="card-subtitle mb-0 small text-muted">Kelola akun & hak akses pengguna sistem</p>
                        </div>
                    </div>
                    <a href="{{ route('user.create') }}" class="btn btn-primary hstack gap-2 px-4 shadow-sm">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon>
                        <span class="d-none d-sm-inline">Tambah Pengguna</span>
                    </a>
                </div>
                <div class="card-body px-3 pb-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold" style="width:50px">No.</th>
                                    <th class="fw-semibold">Pengguna</th>
                                    <th class="fw-semibold">Role</th>
                                    <th class="fw-semibold text-center">Status</th>
                                    <th class="fw-semibold text-center" style="width:120px">Aksi</th>
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
        const roleConfig = {
            'admin': { color: 'warning', icon: 'solar:shield-user-bold-duotone', label: 'Admin' },
            'bendahara': { color: 'success', icon: 'solar:wallet-money-bold-duotone', label: 'Bendahara' },
            'kepala sekolah': { color: 'info', icon: 'solar:graduation-cap-bold-duotone', label: 'Kepala Sekolah' },
        };

        const avatarColors = ['#5d87ff', '#49beff', '#13deb9', '#ffae1f', '#fa896b', '#6f42c1'];

        function getInitial(email) {
            return email ? email[0].toUpperCase() : '?';
        }

        function getAvatarColor(email) {
            let sum = 0;
            for (let c of (email || '')) sum += c.charCodeAt(0);
            return avatarColors[sum % avatarColors.length];
        }

        $(document).ready(function () {
            const dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{{ route('user.index') }}',
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center text-muted small',
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function (data) {
                            const color = getAvatarColor(data);
                            const initial = getInitial(data);
                            return `<div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="background:${color}">${initial}</div>
                                <span class="fw-semibold text-dark">${data}</span>
                            </div>`;
                        }
                    },
                    {
                        data: 'role',
                        name: 'role',
                        render: function (data) {
                            const key = (data || '').toLowerCase().trim();
                            const cfg = roleConfig[key] || { color: 'secondary', icon: 'solar:user-bold-duotone', label: data };
                            return `<span class="badge-role bg-${cfg.color}-subtle text-${cfg.color}">
                                <iconify-icon icon="${cfg.icon}" style="font-size:14px"></iconify-icon>
                                ${cfg.label}
                            </span>`;
                        }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        className: 'text-center',
                        render: function (data) {
                            return data
                                ? `<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                    <i class="ti ti-circle-filled me-1" style="font-size:8px"></i>Aktif
                                   </span>`
                                : `<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                    <i class="ti ti-circle-filled me-1" style="font-size:8px"></i>Nonaktif
                                   </span>`;
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                    },
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari pengguna...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_–_END_ dari _TOTAL_ pengguna",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Pengguna tidak ditemukan",
                    paginate: { previous: "‹", next: "›" },
                },
                dom: '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"f><"table-responsive"t><"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2"lip>',
            });

            // Hitung stat langsung dari raw JSON response server
            // (drawCallback tidak bisa dipakai karena role sudah dirender sebagai HTML di server)
            $('#table').on('xhr.dt', function () {
                const json = dt.ajax.json();
                if (!json || !json.data) return;

                let admin = 0, bendahara = 0, kepsek = 0;
                json.data.forEach(function (r) {
                    const role = (r.role || '').toLowerCase().trim();
                    if (role === 'admin') admin++;
                    else if (role === 'bendahara') bendahara++;
                    else if (role === 'kepala sekolah') kepsek++;
                });

                document.getElementById('stat-total').textContent    = json.recordsTotal ?? json.data.length;
                document.getElementById('stat-admin').textContent     = admin;
                document.getElementById('stat-bendahara').textContent = bendahara;
                document.getElementById('stat-kepsek').textContent    = kepsek;
            });
        });
    </script>
@endpush
