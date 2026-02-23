@extends('templates.backend.master')

@section('page-title', 'Panduan Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom-0">
                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-3" id="pills-tab" role="tablist">
                    @if(auth()->user()->role == 'admin')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-2 hstack gap-2 justify-content-center py-2" id="pills-admin-tab" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button" role="tab">
                            <iconify-icon icon="solar:shield-user-bold-duotone" class="fs-5"></iconify-icon> Panduan Admin
                        </button>
                    </li>
                    @endif

                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'bendahara')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ auth()->user()->role == 'bendahara' ? 'active' : '' }} rounded-2 hstack gap-2 justify-content-center py-2" id="pills-bendahara-tab" data-bs-toggle="pill" data-bs-target="#pills-bendahara" type="button" role="tab">
                            <iconify-icon icon="solar:wad-of-money-bold-duotone" class="fs-5"></iconify-icon> Panduan Bendahara
                        </button>
                    </li>
                    @endif

                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'kepala sekolah')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ auth()->user()->role == 'kepala sekolah' ? 'active' : '' }} rounded-2 hstack gap-2 justify-content-center py-2" id="pills-kepsek-tab" data-bs-toggle="pill" data-bs-target="#pills-kepsek" type="button" role="tab">
                            <iconify-icon icon="solar:chart-square-bold-duotone" class="fs-5"></iconify-icon> Panduan Kepala Sekolah
                        </button>
                    </li>
                    @endif
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content" id="pills-tabContent">
                    {{-- TAB ADMIN --}}
                    @if(auth()->user()->role == 'admin')
                    <div class="tab-pane fade show active" id="pills-admin" role="tabpanel">
                        <h4 class="fw-bold mb-3">Selamat Datang, Administrator</h4>
                        <p class="text-muted">Sebagai Admin, Anda memiliki kontrol penuh atas sistem. Berikut adalah ringkasan fitur utama Anda:</p>
                        
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light-subtle">
                                    <h6 class="fw-bold hstack gap-2"><iconify-icon icon="solar:users-group-two-rounded-bold" class="text-primary"></iconify-icon> Manajemen User</h6>
                                    <ul class="small mb-0 mt-2 text-muted">
                                        <li>Menambah, mengedit, dan menghapus akun (Admin, Bendahara, Kepsek).</li>
                                        <li>Fitur Ganti Password untuk membantu staf yang lupa kredensial.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light-subtle">
                                    <h6 class="fw-bold hstack gap-2"><iconify-icon icon="solar:folder-with-files-bold" class="text-success"></iconify-icon> Master Data</h6>
                                    <ul class="small mb-0 mt-2 text-muted">
                                        <li>Mengelola data **Kelas** (Tingkat & Nama Kelas).</li>
                                        <li>Mengelola data **Siswa** dan memindahkan kelas.</li>
                                        <li>Mengatur **Tarif SPP** per tahun ajaran.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- TAB BENDAHARA --}}
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'bendahara')
                    <div class="tab-pane fade {{ auth()->user()->role == 'bendahara' ? 'show active' : '' }}" id="pills-bendahara" role="tabpanel">
                        <h4 class="fw-bold mb-3">Panduan Operasional Bendahara</h4>
                        <p class="text-muted">Fokus utama Anda adalah pengelolaan transaksi dan pelaporan keuangan harian.</p>
                        
                        <div class="accordion accordion-flush border rounded-3" id="accordionBendahara">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-transaksi">
                                        1. Melakukan Entri Pembayaran
                                    </button>
                                </h2>
                                <div id="flush-transaksi" class="accordion-collapse collapse" data-bs-parent="#accordionBendahara">
                                    <div class="accordion-body small text-muted">
                                        Cari siswa melalui menu **Pembayaran SPP** menggunakan filter kelas/nama. Klik tombol bayar pada bulan yang relevan, masukkan nominal, lalu simpan. Kwitansi akan tersedia otomatis.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-laporan">
                                        2. Cetak Laporan Bulanan
                                    </button>
                                </h2>
                                <div id="flush-laporan" class="accordion-collapse collapse" data-bs-parent="#accordionBendahara">
                                    <div class="accordion-body small text-muted">
                                        Gunakan menu **Laporan SPP**. Pilih filter (Bulan, Tahun, Status Lunas). Preview akan muncul di layar. Klik **Cetak PDF** untuk mendapatkan dokumen resmi dengan Kop Surat.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- TAB KEPALA SEKOLAH --}}
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'kepala sekolah')
                    <div class="tab-pane fade {{ auth()->user()->role == 'kepala sekolah' ? 'show active' : '' }}" id="pills-kepsek" role="tabpanel">
                        <h4 class="fw-bold mb-3">Panduan Monitoring Kepala Sekolah</h4>
                        <p class="text-muted">Gunakan sistem ini untuk memantau kesehatan finansial sekolah secara real-time.</p>
                        
                        <div class="card border-primary-subtle bg-primary-subtle">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-2">Dashboard Statistik</h6>
                                <p class="small text-muted mb-0">Halaman utama (Dashboard) menampilkan chart interaktif. Anda bisa melihat perbandingan total tagihan dengan pembayaran yang masuk per bulan tanpa perlu menunggu laporan manual dari bendahara.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
