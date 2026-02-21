@extends('templates.backend.master')

@section('page-title', 'Tarif SPP')
@section('page-link', route('tarif-spp.index'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/sweetalert2.min.css') }}">
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
        <script>toastr.error("{{ session('error') }}", "Error", {showMethod:"slideDown",hideMethod:"slideUp",timeOut:3000});</script>
    @endif

    <div class="row mb-4">
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white"><iconify-icon icon="solar:tag-price-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-primary" id="stat-total">—</div><div class="small text-muted">Total Tarif</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card" style="background:#f0f9ff">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon text-white" style="background:#0ea5e9"><iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0" style="color:#0ea5e9" id="stat-k7">—</div><div class="small text-muted">Tarif Kelas 7</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-success-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white"><iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-success" id="stat-k8">—</div><div class="small text-muted">Tarif Kelas 8</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-warning-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white"><iconify-icon icon="solar:graduation-cap-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-warning" id="stat-k9">—</div><div class="small text-muted">Tarif Kelas 9</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon bg-primary-subtle text-primary" style="width:42px;height:42px;border-radius:10px;font-size:20px;">
                    <iconify-icon icon="solar:tag-price-bold-duotone"></iconify-icon>
                </div>
                <div>
                    <h5 class="card-title mb-0 fw-bold">Daftar Tarif SPP</h5>
                    <p class="card-subtitle mb-0 small text-muted">Manajemen tarif SPP per tingkat dan tahun ajaran</p>
                </div>
            </div>
            <a href="{{ route('tarif-spp.create') }}" class="btn btn-primary hstack gap-2 px-4 shadow-sm">
                <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon>
                <span class="d-none d-sm-inline">Tambah Tarif</span>
            </a>
        </div>
        <div class="card-body px-3 pb-3">
            <div class="table-responsive">
                <table id="table" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold" style="width:50px">No.</th>
                            <th class="fw-semibold">Tingkat</th>
                            <th class="fw-semibold">Tahun Ajaran</th>
                            <th class="fw-semibold">Nominal SPP</th>
                            <th class="fw-semibold">Keterangan</th>
                            <th class="fw-semibold text-center" style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            const tingkatColor = { 7:'primary', 8:'success', 9:'warning' };

            const dt = $('#table').DataTable({
                processing: true, serverSide: true, searchDelay: 500,
                ajax: '{{ route('tarif-spp.index') }}',
                columns: [
                    { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center text-muted small' },
                    {
                        data:'tingkat_label', name:'tingkat',
                        render: function(data, type, row) {
                            const c = tingkatColor[row.tingkat] || 'secondary';
                            return `<span class="badge bg-${c}-subtle text-${c} border border-${c}-subtle fw-semibold px-2">${data}</span>`;
                        }
                    },
                    { data:'tahun_ajaran', name:'tahun_ajaran', render: d => `<span class="fw-semibold">${d}</span>` },
                    { data:'nominal_fmt', name:'nominal', render: d => `<span class="fw-bold text-success">${d}</span>` },
                    { data:'keterangan', name:'keterangan', render: d => d ? d : '<span class="text-muted">—</span>' },
                    { data:'actions', name:'actions', orderable:false, searchable:false, className:'text-center' },
                ],
                language: {
                    search:"_INPUT_", searchPlaceholder:"Cari tarif...",
                    lengthMenu:"Tampilkan _MENU_ data",
                    info:"Menampilkan _START_–_END_ dari _TOTAL_ tarif",
                    infoEmpty:"Tidak ada data", zeroRecords:"Tarif tidak ditemukan",
                    paginate:{ previous:"‹", next:"›" },
                },
                dom:'<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"f><"table-responsive"t><"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2"lip>',
            });

            $('#table').on('xhr.dt', function () {
                const json = dt.ajax.json();
                if (!json || !json.data) return;
                let k7=0, k8=0, k9=0;
                json.data.forEach(r => {
                    if (r.tingkat == 7) k7++;
                    else if (r.tingkat == 8) k8++;
                    else if (r.tingkat == 9) k9++;
                });
                document.getElementById('stat-total').textContent = json.recordsTotal ?? json.data.length;
                document.getElementById('stat-k7').textContent = k7;
                document.getElementById('stat-k8').textContent = k8;
                document.getElementById('stat-k9').textContent = k9;
            });
        });
    </script>
@endpush
