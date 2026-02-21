# 🏫 SI-PEMBAYARAN SPP - SMPN 1 MAUPONGGO SATAP

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySql](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com)

Sistem Informasi Pembayaran SPP (Siswa Digital) yang dirancang khusus untuk **SMPN 1 Mauponggo Satap**. Aplikasi ini mempermudah pengelolaan data akademik, administrasi pengguna, dan manajemen keuangan sekolah secara terintegrasi dan modern.

---

## ✨ Fitur Utama

### 🏦 Manajemen Keuangan (SPP)
- **Master Tarif SPP**: Pengaturan biaya SPP per tingkat kelas (7, 8, 9) dan tahun ajaran.
- **Auto-Generate Tagihan**: Fitur cerdas untuk membuat 12 bulan tagihan sekaligus bagi siswa baru atau naik kelas.
- **Transaksi Pembayaran**: Pencatatan pembayaran multi-bulan dengan satu kali input.
- **Kwitansi Digital**: Generate kwitansi resmi dengan nomor otomatis (`SPP-YYYY-XXXXX`) yang siap cetak.
- **Dashboard Finansial**: Statistik pendapatan berdasarkan metode bayar (Tunai, Transfer, QRIS).

### 🎓 Manajemen Akademik
- **Data Siswa**: Pengelolaan profil lengkap siswa dengan tracking kelas.
- **Data Kelas**: Pengelompokan siswa per grade (7, 8, 9) dengan statistik jumlah siswa real-time.

### 👤 Administrasi & Keamanan
- **Manajemen Pengguna**: Pengaturan akses bendahara dan petugas sekolah.
- **Audit Trail**: Tracking petugas yang memproses setiap transaksi pembayaran.
- **Data Trashed**: Sistem *soft-delete* untuk mengamankan data yang tidak sengaja terhapus.

---

## 🚀 Teknologi yang Digunakan

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Template Engine, Bootstrap 5.3
- **Database**: MySQL 8.0 (Relational Schema)
- **UI Architecture**: Corporate Elegant Theme, Glassmorphism elements.
- **Assets**: 
  - **Iconify**: Solar Icons Duotone (Modern & Premium look)
  - **DataTables**: Server-side processing untuk performa optimal.
  - **SweetAlert2 & Toastr**: Notifikasi interaktif dan elegan.

---

## 🛠️ Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/si-pembayaran-spp.git
   cd si-pembayaran-spp
   ```

2. **Composer Install**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Konfigurasi DB_DATABASE, DB_USERNAME, dan DB_PASSWORD di file .env*

4. **Migrasi & Seeding**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

---

## 🛡️ Akun Akses Default

- **URL**: `http://localhost:8000/login`
- **Email**: `admin@gmail.com`
- **Password**: `password`

---

## 📐 Struktur Alur Data

1. **Pengaturan Tarif**: Admin menetapkan nominal SPP per tingkat per tahun ajaran.
2. **Setup Siswa**: Input data siswa dan masukkan ke kelas yang sesuai.
3. **Generate Tagihan**: Gunakan menu "Generate Tagihan" untuk membuat buku kas piutang siswa selama satu tahun.
4. **Proses Bayar**: Bendahara mencari siswa, memilih bulan yang dibayar, dan mencetak kwitansi sebagai bukti sah.

---

<p align="center">
  Dikelola oleh <b>Tim IT SMPN 1 Mauponggo Satap</b><br>
  <i>"Modernizing Education Administration"</i>
</p>
