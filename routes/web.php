<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KegiatanController;

// Halaman utama (Menampilkan kegiatan lintas OPD)
Route::get('/', [KegiatanController::class, 'index']);

// Halaman baru untuk Sidebar: Agenda Per OPD
Route::get('/kegiatan', [KegiatanController::class, 'agendaOpd'])->name('kegiatan.opd');

// Halaman Form Presensi (Tampilan)
Route::get('/hadir/{id}', [KegiatanController::class, 'hadir']);

// Proses Simpan Presensi (Submit Form)
Route::post('/hadir/{id}', [KegiatanController::class, 'storeHadir']);