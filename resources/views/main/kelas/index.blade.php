@extends('templates.backend.master')

@section('page-title', 'Manajemen Kelas')
@section('page-link', route('kelas.index'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/sweetalert2.min.css') }}">
    <style>
        .stat-card {
            border-radius: 12px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
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

        .badge-tingkat {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
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
    </style>
@endpush

@section('content')
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Berhasil", {
                showMethod: "slideDown",
                hideMethod: "slideUp",
                timeOut: 2500
            });
        </script>
    @endif

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white">
                        <iconify-icon icon="solar:buildings-2-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 mb-0 text-primary" id="stat-total">—</div>
                        <div class="small text-muted">Total Kelas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card" style="background:#f0f9ff">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon text-white" style="background:#0ea5e9">
                        <iconify-icon icon="solar:sort-by-time-bold-duotone"></iconify-icon>
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
                        <iconify-icon icon="solar:sort-by-time-bold-duotone"></iconify-icon>
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
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon bg-primary-subtle text-primary"
                            style="width:42px;height:42px;border-radius:10px;font-size:20px;">
                            <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold">Daftar Kelas</h5>
                            <p class="card-subtitle mb-0 small text-muted">Manajemen data kelas dan tingkat SMP</p>
                        </div>
                    </div>
                    @if (auth()->user()->role !== 'kepala sekolah')
                        <a href="{{ route('kelas.create') }}" class="btn btn-primary hstack gap-2 px-4 shadow-sm">
                            <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon>
                            <span class="d-none d-sm-inline">Tambah Kelas</span>
                        </a>
                    @endif
                </div>
                <div class="card-body px-3 pb-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold" style="width:50px">No.</th>
                                    <th class="fw-semibold">Nama Kelas</th>
                                    <th class="fw-semibold">Tingkat</th>
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
        const tingkatColor = {
            7: 'primary',
            8: 'success',
            9: 'warning'
        };

        $(document).ready(function() {
            const dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{{ route('kelas.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center text-muted small',
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        render: function(data) {
                            return `<span class="fw-bold text-dark">
                                <iconify-icon icon="solar:door-open-bold-duotone" class="text-primary me-1"></iconify-icon>
                                ${data}
                            </span>`;
                        }
                    },
                    {
                        data: 'tingkat',
                        name: 'tingkat',
                        render: function(data) {
                            const raw = data.replace(/\D/g, '');
                            const color = tingkatColor[parseInt(raw)] || 'secondary';
                            return `<span class="badge-tingkat bg-${color}-subtle text-${color} border border-${color}-subtle">
                                <iconify-icon icon="solar:sort-by-time-bold-duotone" style="font-size:13px"></iconify-icon>
                                Kelas ${raw}
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
                    searchPlaceholder: "Cari kelas...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_–_END_ dari _TOTAL_ kelas",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Kelas tidak ditemukan",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    },
                },
                dom: '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"f><"table-responsive"t><"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2"lip>',
            });

            $('#table').on('xhr.dt', function() {
                const json = dt.ajax.json();
                if (!json || !json.data) return;

                let k7 = 0,
                    k8 = 0,
                    k9 = 0;
                json.data.forEach(function(r) {
                    const t = parseInt((r.tingkat || '').toString().replace(/\D/g, ''));
                    if (t === 7) k7++;
                    else if (t === 8) k8++;
                    else if (t === 9) k9++;
                });

                document.getElementById('stat-total').textContent = json.recordsTotal ?? json.data.length;
                document.getElementById('stat-k7').textContent = k7;
                document.getElementById('stat-k8').textContent = k8;
                document.getElementById('stat-k9').textContent = k9;
            });
        });
    </script>
@endpush
