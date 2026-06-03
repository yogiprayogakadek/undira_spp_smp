<?php

use App\Http\Controllers\Main\DashboardController;
use App\Http\Controllers\Main\KelasController;
use App\Http\Controllers\Main\PembayaranSppController;
use App\Http\Controllers\Main\LaporanSppController;
use App\Http\Controllers\Main\PanduanController;
use App\Http\Controllers\Main\SiswaController;
use App\Http\Controllers\Main\TagihanSppController;
use App\Http\Controllers\Main\TarifSppController;
use App\Http\Controllers\Main\UserController;
use App\Http\Controllers\Main\KenaikanKelasController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->prefix('/user')->name('user.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::put('/update/{id}', 'update')->name('update');
    });

    Route::controller(KelasController::class)->prefix('/kelas')->name('kelas.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::put('/update/{id}', 'update')->name('update');
    });

    Route::controller(SiswaController::class)->prefix('/siswa')->name('siswa.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'delete')->name('delete');
    });

    Route::controller(TarifSppController::class)->prefix('/tarif-spp')->name('tarif-spp.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'delete')->name('delete');
    });

    Route::controller(TagihanSppController::class)->prefix('/tagihan-spp')->name('tagihan-spp.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
        Route::put('/update/{id}', 'update')->name('update');
        Route::get('/get-kelas', 'getKelas')->name('get-kelas');
        Route::get('/get-siswa', 'getSiswa')->name('get-siswa');
    });

    Route::controller(PembayaranSppController::class)->prefix('/pembayaran-spp')->name('pembayaran-spp.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::delete('/delete/{id}', 'delete')->name('delete');
        Route::get('/tagihan-siswa', 'getTagihanSiswa')->name('tagihan-siswa');
    });

    Route::controller(LaporanSppController::class)->prefix('/laporan-spp')->name('laporan-spp.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/preview', 'preview')->name('preview');
        Route::get('/print', 'print')->name('print');
    });

    Route::get('/panduan', [PanduanController::class, 'index'])->name('panduan.index');

    Route::controller(KenaikanKelasController::class)->prefix('/kenaikan-kelas')->name('kenaikan-kelas.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/siswa', 'getSiswaByKelas')->name('get-siswa');
        Route::post('/proses', 'proses')->name('proses');
    });
});


Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->middleware(['auth'])->name('dashboard.chart-data');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
