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
                        <button type="button" class="btn btn-primary hstack gap-2 px-4 shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">
                            <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon>
                            <span class="d-none d-sm-inline">Tambah Kelas</span>
                        </button>
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

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div style="height:4px;background:linear-gradient(90deg,#5d87ff,#13deb9); border-radius: 4px 4px 0 0;"></div>
                <div class="modal-header border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width:42px;height:42px;border-radius:12px;background:#eefaf7;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:buildings-2-bold-duotone" class="text-success"
                                style="font-size:22px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Kelas Baru</h5>
                            <p class="text-muted small mb-0">Daftarkan kelas baru ke dalam sistem</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <form action="{{ route('kelas.store') }}" method="POST" id="formTambah">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <label for="grade"
                                    class="form-label fw-semibold small text-uppercase text-muted ls-1">Tingkat</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:sort-by-time-bold-duotone"
                                            style="font-size:18px"></iconify-icon>
                                    </span>
                                    <select name="grade" id="grade"
                                        class="form-select border-start-0 ps-0 bg-light @error('grade') is-invalid @enderror">
                                        <option value="">Pilih...</option>
                                        @for ($i = 7; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>
                                                Kelas {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('grade')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-7">
                                <label for="nama"
                                    class="form-label fw-semibold small text-uppercase text-muted ls-1">Nama /
                                    Rombel</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:door-open-bold-duotone"
                                            style="font-size:18px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" placeholder="Contoh: A, B, Unggul"
                                        value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="tingkat"
                                class="form-label fw-semibold small text-uppercase text-muted ls-1">Tingkat
                                Numerik</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:graduation-cap-bold-duotone"
                                        style="font-size:18px"></iconify-icon>
                                </span>
                                <select class="form-select border-start-0 ps-0 bg-light @error('tingkat') is-invalid @enderror"
                                    id="tingkat" name="tingkat">
                                    <option value="">Pilih Tingkat...</option>
                                    @foreach ([7, 8, 9] as $t)
                                        <option value="{{ $t }}" {{ old('tingkat') == $t ? 'selected' : '' }}>
                                            Kelas {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tingkat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text mt-2 small text-muted">Nama kelas lengkap: <strong id="preview-nama"
                                    class="text-primary">—</strong></div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm hstack gap-2">
                                <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>
                                Simpan Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div style="height:4px;background:linear-gradient(90deg,#5d87ff,#ffae1f); border-radius: 4px 4px 0 0;"></div>
                <div class="modal-header border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width:42px;height:42px;border-radius:12px;background:#fff8ec;display:flex;align-items:center;justify-content:center;">
                            <iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-warning"
                                style="font-size:22px"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold" id="modalEditLabel">Perbarui Data Kelas</h5>
                            <p class="text-muted small mb-0">Ubah informasi kelas yang sudah terdaftar</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <form action="" method="POST" id="formEdit">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id" value="{{ old('id') }}">

                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <label for="edit_grade"
                                    class="form-label fw-semibold small text-uppercase text-muted ls-1">Tingkat</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:sort-by-time-bold-duotone"
                                            style="font-size:18px"></iconify-icon>
                                    </span>
                                    <select name="grade" id="edit_grade"
                                        class="form-select border-start-0 ps-0 bg-light @error('grade') is-invalid @enderror">
                                        <option value="">Pilih...</option>
                                        @for ($i = 7; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>
                                                Kelas {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('grade')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-7">
                                <label for="edit_nama"
                                    class="form-label fw-semibold small text-uppercase text-muted ls-1">Nama /
                                    Rombel</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <iconify-icon icon="solar:door-open-bold-duotone"
                                            style="font-size:18px"></iconify-icon>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 bg-light @error('nama') is-invalid @enderror"
                                        id="edit_nama" name="nama" placeholder="Contoh: A, B, Unggul"
                                        value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="edit_tingkat"
                                class="form-label fw-semibold small text-uppercase text-muted ls-1">Tingkat
                                Numerik</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <iconify-icon icon="solar:graduation-cap-bold-duotone"
                                        style="font-size:18px"></iconify-icon>
                                </span>
                                <select class="form-select border-start-0 ps-0 bg-light @error('tingkat') is-invalid @enderror"
                                    id="edit_tingkat" name="tingkat">
                                    <option value="">Pilih Tingkat...</option>
                                    @foreach ([7, 8, 9] as $t)
                                        <option value="{{ $t }}" {{ old('tingkat') == $t ? 'selected' : '' }}>
                                            Kelas {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tingkat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text mt-2 small text-muted">Nama kelas lengkap: <strong id="edit-preview-nama"
                                    class="text-primary">—</strong></div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning px-4 shadow-sm hstack gap-2 text-white">
                                <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>
                                Perbarui Kelas
                            </button>
                        </div>
                    </form>
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
            // Auto open modal on error
            @if ($errors->any())
                @if (old('_method') == 'PUT')
                    const editId = "{{ old('id') }}";
                    $('#formEdit').attr('action', `/kelas/update/${editId}`);
                    $('#modalEdit').modal('show');
                @else
                    $('#modalTambah').modal('show');
                @endif
            @endif

            // Create Preview logic
            const gradeEl = document.getElementById('grade');
            const namaEl = document.getElementById('nama');
            const prev = document.getElementById('preview-nama');

            function updatePreview() {
                const g = gradeEl.value;
                const n = namaEl.value.trim();
                prev.textContent = g && n ? `${g} ${n}` : '—';
            }

            if (gradeEl && namaEl) {
                gradeEl.addEventListener('change', updatePreview);
                namaEl.addEventListener('input', updatePreview);
                updatePreview();
            }

            // Edit Preview logic
            const editGradeEl = document.getElementById('edit_grade');
            const editNamaEl = document.getElementById('edit_nama');
            const editPrev = document.getElementById('edit-preview-nama');

            function updateEditPreview() {
                const g = editGradeEl.value;
                const n = editNamaEl.value.trim();
                editPrev.textContent = g && n ? `${g} ${n}` : '—';
            }

            if (editGradeEl && editNamaEl) {
                editGradeEl.addEventListener('change', updateEditPreview);
                editNamaEl.addEventListener('input', updateEditPreview);
                updateEditPreview();
            }

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

            // Handle Edit button click
            $('#table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const grade = $(this).data('grade');
                const nama = $(this).data('nama');
                const tingkat = $(this).data('tingkat');

                $('#formEdit').attr('action', `/kelas/update/${id}`);
                $('#edit_id').val(id);
                $('#edit_grade').val(grade);
                $('#edit_nama').val(nama);
                $('#edit_tingkat').val(tingkat);

                updateEditPreview();
                $('#modalEdit').modal('show');
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
