@extends('templates.backend.master')

@section('page-title', 'Pembayaran SPP')
@section('page-link', route('pembayaran-spp.index'))

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
        <script>toastr.error("{{ session('error') }}", "Error", {showMethod:"slideDown",hideMethod:"slideUp",timeOut:3500});</script>
    @endif

    <div class="row mb-4">
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white"><iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-primary" id="stat-total">—</div><div class="small text-muted">Total Transaksi</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-success-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white"><iconify-icon icon="solar:banknote-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-success" id="stat-tunai">—</div><div class="small text-muted">Tunai</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card bg-info-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info text-white"><iconify-icon icon="solar:card-transfer-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0 text-info" id="stat-transfer">—</div><div class="small text-muted">Transfer</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3 mb-3">
            <div class="card stat-card" style="background:#f5f3ff">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon text-white" style="background:#8b5cf6"><iconify-icon icon="solar:qr-code-bold-duotone"></iconify-icon></div>
                    <div><div class="fw-bold fs-4 mb-0" style="color:#8b5cf6" id="stat-qris">—</div><div class="small text-muted">QRIS</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon bg-primary-subtle text-primary" style="width:42px;height:42px;border-radius:10px;font-size:20px;">
                    <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                </div>
                <div>
                    <h5 class="card-title mb-0 fw-bold">Riwayat Pembayaran SPP</h5>
                    <p class="card-subtitle mb-0 small text-muted">Semua transaksi pembayaran SPP</p>
                </div>
            </div>
            <a href="{{ route('pembayaran-spp.create') }}" class="btn btn-primary hstack gap-2 px-4 shadow-sm">
                <iconify-icon icon="solar:card-send-bold-duotone" class="fs-5"></iconify-icon>
                <span class="d-none d-sm-inline">Catat Pembayaran</span>
            </a>
        </div>
        <div class="card-body px-3 pb-3">
            <div class="table-responsive">
                <table id="table" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold" style="width:50px">No.</th>
                            <th class="fw-semibold">No. Kwitansi</th>
                            <th class="fw-semibold">Siswa</th>
                            <th class="fw-semibold">Kelas</th>
                            <th class="fw-semibold">Total Bayar</th>
                            <th class="fw-semibold">Tgl Bayar</th>
                            <th class="fw-semibold text-center">Metode</th>
                            <th class="fw-semibold text-center" style="width:170px">Aksi</th>
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
            const dt = $('#table').DataTable({
                processing: true, serverSide: true, searchDelay: 500,
                ajax: '{{ route('pembayaran-spp.index') }}',
                columns: [
                    { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center text-muted small' },
                    { data:'no_kwitansi', name:'no_kwitansi', render: d => `<code class="text-primary small fw-bold">${d}</code>` },
                    { data:'siswa_nama', name:'siswa_nama', render: d => `<span class="fw-semibold">${d}</span>` },
                    { data:'kelas_nama', name:'kelas_nama', render: d => `<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 fw-semibold">${d}</span>` },
                    { data:'total_fmt', name:'total_bayar', render: d => `<span class="fw-bold text-success">${d}</span>` },
                    { data:'tanggal', name:'tanggal_bayar' },
                    { data:'metode_badge', name:'metode_bayar', orderable:false, searchable:false, className:'text-center' },
                    { data:'actions', name:'actions', orderable:false, searchable:false, className:'text-center' },
                ],
                language: {
                    search:"_INPUT_", searchPlaceholder:"Cari siswa / no kwitansi...",
                    lengthMenu:"Tampilkan _MENU_ data", info:"_START_–_END_ dari _TOTAL_",
                    infoEmpty:"Tidak ada data", zeroRecords:"Data tidak ditemukan",
                    paginate:{ previous:"‹", next:"›" },
                },
                dom:'<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"f><"table-responsive"t><"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2"lip>',
            });

            $('#table').on('xhr.dt', function () {
                const json = dt.ajax.json();
                if (!json || !json.data) return;
                let tunai=0, transfer=0, qris=0;
                json.data.forEach(r => {
                    if (r.metode_bayar === 'tunai') tunai++;
                    else if (r.metode_bayar === 'transfer') transfer++;
                    else if (r.metode_bayar === 'qris') qris++;
                });
                document.getElementById('stat-total').textContent    = json.recordsTotal ?? json.data.length;
                document.getElementById('stat-tunai').textContent    = tunai;
                document.getElementById('stat-transfer').textContent = transfer;
                document.getElementById('stat-qris').textContent     = qris;
            });
        });
    </script>
@endpush
