@extends('templates.backend.master')

@section('page-title', 'Dashboard')
@section('page-link', route('dashboard'))

@push('css')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #5d87ff 0%, #13deb9 100%);
            border-radius: 15px;
            color: white;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .welcome-card::after {
            content: '';
            position: absolute;
            right: -50px;
            bottom: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .badge-role {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 700;
        }
        .badge-ta {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="welcome-card shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-white text-primary badge-role mb-2">{{ $role }}</span>
                <span class="badge-ta mb-2 ms-2"><iconify-icon icon="solar:calendar-date-bold-duotone" class="align-middle"></iconify-icon> T.A. {{ $activeTahunAjaran }}</span>
                <h2 class="fw-bold mb-1 border-0 text-white">Selamat Datang, {{ $user->profile->nama_lengkap ?? $user->email }}!</h2>
                <p class="mb-0 opacity-75">Anda login sebagai <strong>{{ ucwords($role) }}</strong> di Sistem Pembayaran SPP SMPN 1 Mauponggo Satap. Tahun ajaran aktif: <strong>{{ $activeTahunAjaran }}</strong>.</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <iconify-icon icon="solar:globus-bold-duotone" style="font-size: 80px; opacity: 0.2;"></iconify-icon>
            </div>
        </div>
    </div>

    {{-- ROLE: ADMIN --}}
    @if($role === 'admin')
    <div class="row g-4 mb-4 text-white">
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3" style="background: #5d87ff;">
                <div class="card-body">
                    <div class="icon-box bg-white text-primary shadow-sm"><iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon></div>
                    <h5 class="card-title text-black-50 small text-uppercase fw-bold opacity-75">Total Siswa</h5>
                    <h2 class="fw-bold mb-0 text-white">{{ number_format($stats['total_siswa']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3" style="background: #13deb9;">
                <div class="card-body">
                    <div class="icon-box bg-white text-success shadow-sm"><iconify-icon icon="solar:home-smile-bold-duotone"></iconify-icon></div>
                    <h5 class="card-title text-black-50 small text-uppercase fw-bold opacity-75">Total Kelas</h5>
                    <h2 class="fw-bold mb-0 text-white">{{ number_format($stats['total_kelas']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3" style="background: #ffa117;">
                <div class="card-body">
                    <div class="icon-box bg-white text-warning shadow-sm"><iconify-icon icon="solar:shield-user-bold-duotone"></iconify-icon></div>
                    <h5 class="card-title text-black-50 small text-uppercase fw-bold opacity-75">Total User</h5>
                    <h2 class="fw-bold mb-0 text-white">{{ number_format($stats['total_user']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3" style="background: #ff4560;">
                <div class="card-body">
                    <div class="icon-box bg-white text-danger shadow-sm"><iconify-icon icon="solar:wad-of-money-bold-duotone"></iconify-icon></div>
                    <h5 class="card-title text-black-50 small text-uppercase fw-bold opacity-75">Total Pendapatan</h5>
                    <h2 class="fw-bold mb-0 text-white">Rp {{ number_format($stats['total_pembayaran_all'], 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold">Akses Cepat</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('user.index') }}" class="btn btn-light w-100 py-3 rounded-3 d-flex flex-column align-items-center gap-2 border">
                                <iconify-icon icon="solar:user-bold" class="fs-2 text-primary"></iconify-icon>
                                <span>Manajemen User</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('kelas.index') }}" class="btn btn-light w-100 py-3 rounded-3 d-flex flex-column align-items-center gap-2 border">
                                <iconify-icon icon="solar:home-bold" class="fs-2 text-success"></iconify-icon>
                                <span>Manajemen Kelas</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('siswa.index') }}" class="btn btn-light w-100 py-3 rounded-3 d-flex flex-column align-items-center gap-2 border">
                                <iconify-icon icon="solar:users-group-two-rounded-bold" class="fs-2 text-warning"></iconify-icon>
                                <span>Data Siswa</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('tarif-spp.index') }}" class="btn btn-light w-100 py-3 rounded-3 d-flex flex-column align-items-center gap-2 border">
                                <iconify-icon icon="solar:tag-bold" class="fs-2 text-danger"></iconify-icon>
                                <span>Tarif SPP</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROLE: BENDAHARA --}}
    @elseif($role === 'bendahara')
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100 p-2 overflow-hidden shadow-sm" style="background: #ecf2ff">
                <div class="card-body">
                    <div class="icon-box bg-primary text-white"><iconify-icon icon="solar:card-send-bold-duotone"></iconify-icon></div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Trx Hari Ini</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['bayar_hari_ini_count'] }} Transaksi</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100 p-2 overflow-hidden shadow-sm" style="background: #e6fffa">
                <div class="card-body">
                    <div class="icon-box bg-success text-white"><iconify-icon icon="solar:wad-of-money-bold-duotone"></iconify-icon></div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Setoran Hari Ini</p>
                    <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($stats['bayar_hari_ini_sum'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100 p-2 overflow-hidden shadow-sm" style="background: #fef5e5">
                <div class="card-body">
                    <div class="icon-box bg-warning text-white"><iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon></div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Tunggakan (Tagihan)</p>
                    <h3 class="fw-bold mb-0 text-warning">{{ number_format($stats['tagihan_belum_lunas']) }} Item</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100 p-2 overflow-hidden shadow-sm" style="background: #fdf3f3">
                <div class="card-body">
                    <div class="icon-box bg-danger text-white"><iconify-icon icon="solar:danger-triangle-bold-duotone"></iconify-icon></div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Piutang</p>
                    <h3 class="fw-bold mb-0 text-danger">Rp {{ number_format($stats['tunggakan_nominal'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent py-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Transaksi Terbaru</h5>
                    <a href="{{ route('pembayaran-spp.index') }}" class="btn btn-primary btn-sm rounded-pill px-3">Semua Riwayat</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">No. Kwitansi</th>
                                    <th>Siswa</th>
                                    <th>Total Bayar</th>
                                    <th>Metode</th>
                                    <th class="pe-4">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_transactions'] as $trx)
                                <tr>
                                    <td class="ps-4"><code class="fw-bold text-primary">{{ $trx->no_kwitansi }}</code></td>
                                    <td>
                                        <div class="fw-semibold">{{ $trx->siswa->nama_lengkap ?? '-' }}</div>
                                        <div class="small text-muted">{{ $trx->siswa->kelas->nama ?? '-' }}</div>
                                    </td>
                                    <td><span class="fw-bold text-success">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</span></td>
                                    <td>
                                        @php $m = strtolower($trx->metode_bayar); @endphp
                                        <span class="badge {{ $m=='tunai'?'bg-success':($m=='transfer'?'bg-info':'bg-warning') }} rounded-pill px-2">
                                            {{ ucfirst($trx->metode_bayar) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-muted small">{{ $trx->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROLE: KEPALA SEKOLAH --}}
    @elseif($role === 'kepala sekolah')
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Ringkasan Eksekutif</h5>
                <div class="row g-4">
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-primary-subtle border border-primary-subtle text-center">
                            <div class="text-muted small fw-bold mb-1">TOTAL SISWA</div>
                            <h2 class="fw-bold text-primary mb-0">{{ number_format($stats['total_siswa']) }}</h2>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-success-subtle border border-success-subtle text-center">
                            <div class="text-muted small fw-bold mb-1">TOTAL PENDAPATAN</div>
                            <h2 class="fw-bold text-success mb-0" style="font-size: 20px">Rp {{ number_format($stats['total_pembayaran'], 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4 opacity-50">
                
                <h6 class="fw-bold text-muted mb-3 small text-uppercase">Pendapatan 6 Bulan Terakhir</h6>
                <div class="list-group list-group-flush">
                @foreach($stats['pendapatan_bulan'] as $pb)
                    <div class="list-group-item px-0 d-flex align-items-center justify-content-between border-0 bg-transparent">
                        <span class="small fw-semibold">{{ date('F Y', strtotime($pb->bulan . '-01')) }}</span>
                        <span class="badge bg-light text-dark border">Rp {{ number_format($pb->total, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Distribusi Siswa per Kelas</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless align-middle">
                        <thead>
                            <tr class="text-muted small border-bottom">
                                <th>NAMA KELAS</th>
                                <th class="text-center">JUMLAH SISWA</th>
                                <th>PERSENTASE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['siswa_per_kelas'] as $sk)
                            @php $pct = $stats['total_siswa'] > 0 ? ($sk->total / $stats['total_siswa'] * 100) : 0; @endphp
                            <tr>
                                <td class="fw-bold py-2 text-primary">{{ $sk->nama }}</td>
                                <td class="text-center">{{ $sk->total }}</td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- CHART SECTION FOR ADMIN & KEPSEK --}}
    @if(in_array($role, ['admin', 'kepala sekolah']))
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent py-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Tren Pendapatan SPP</h5>
                        <p class="text-muted small mb-0">Visualisasi penerimaan biaya pendidikan per bulan</p>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-bold">Tingkat:</span>
                            <select id="chart-tingkat-filter" class="form-select form-select-sm border-0 bg-light fw-bold" style="width: auto; cursor: pointer;">
                                <option value="">Semua</option>
                                @foreach($tingkatList as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-bold">Kelas:</span>
                            <select id="chart-kelas-filter" class="form-select form-select-sm border-0 bg-light fw-bold" style="width: auto; cursor: pointer;">
                                <option value="">Semua</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" data-tingkat="{{ $k->tingkat }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-bold">Tahun:</span>
                            <select id="chart-year-filter" class="form-select form-select-sm border-0 bg-light fw-bold" style="width: 100px; cursor: pointer;">
                                @php $currentYear = date('Y'); @endphp
                                @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="income-chart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const role = "{{ $role }}";
            if (role === 'bendahara') return;

            const chartOptions = {
                series: [{
                    name: 'Pendapatan SPP',
                    data: []
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    zoom: { enabled: false }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#5d87ff']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 4,
                },
                xaxis: {
                    categories: [],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                },
                colors: ['#5d87ff'],
                markers: {
                    size: 5,
                    colors: ['#5d87ff'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                }
            };

            const chart = new ApexCharts(document.querySelector("#income-chart"), chartOptions);
            chart.render();

            function updateChart() {
                const year = document.getElementById('chart-year-filter').value;
                const tingkat = document.getElementById('chart-tingkat-filter').value;
                const kelasId = document.getElementById('chart-kelas-filter').value;

                fetch(`{{ route('dashboard.chart-data') }}?year=${year}&tingkat=${tingkat}&kelas_id=${kelasId}`)
                    .then(response => response.json())
                    .then(res => {
                        chart.updateOptions({
                            xaxis: { categories: res.months }
                        });
                        chart.updateSeries([{
                            name: `Pendapatan SPP ${res.year}`,
                            data: res.data
                        }]);
                    });
            }

            // Sync Kelas dropdown with Tingkat filter
            document.getElementById('chart-tingkat-filter').addEventListener('change', function() {
                const tingkat = this.value;
                const kelasSelect = document.getElementById('chart-kelas-filter');
                const options = kelasSelect.querySelectorAll('option');
                
                options.forEach(opt => {
                    if (opt.value === "") {
                        opt.style.display = "block";
                    } else if (tingkat === "" || opt.dataset.tingkat === tingkat) {
                        opt.style.display = "block";
                    } else {
                        opt.style.display = "none";
                    }
                });
                
                if (kelasSelect.selectedOptions[0] && kelasSelect.selectedOptions[0].style.display === "none") {
                    kelasSelect.value = "";
                }
                
                updateChart();
            });

            // Handle other filter changes
            document.getElementById('chart-kelas-filter').addEventListener('change', updateChart);
            document.getElementById('chart-year-filter').addEventListener('change', updateChart);

            // Initial Load
            updateChart();
        });
    </script>
@endpush
