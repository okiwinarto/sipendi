<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Portal\KalenderArmada;
use App\Livewire\Portal\PeminjamanForm;
use App\Livewire\Portal\PersetujuanPimpinan;
use App\Livewire\Portal\RiwayatPeminjaman;
use App\Livewire\Portal\SerahTerima;
use App\Livewire\Portal\VerifikasiGarasi;
use Illuminate\Support\Facades\Route;

// Halaman Depan / Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autentikasi Portal Pegawai
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Area Portal Pegawai (Dilindungi middleware auth)
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', function () {
        return view('portal.index');
    })->name('index');

    Route::get('/ajukan', PeminjamanForm::class)->name('ajukan');
    Route::get('/kalender', KalenderArmada::class)->name('kalender');
    Route::get('/riwayat', RiwayatPeminjaman::class)->name('riwayat');

    // Tahap 4: Verifikasi & Persetujuan Berjenjang
    Route::get('/verifikasi', VerifikasiGarasi::class)->name('verifikasi');
    Route::get('/persetujuan', PersetujuanPimpinan::class)->name('persetujuan');

    // Tahap 5: Serah Terima Digital (Checkout & Checkin)
    Route::get('/serah-terima', SerahTerima::class)->name('serah-terima');

    // Tahap 7: Dashboard Eksekutif & Pelaporan Lanjutan
    Route::get('/dashboard', \App\Livewire\Portal\DashboardEksekutif::class)->name('dashboard');
    Route::get('/laporan', \App\Livewire\Portal\LaporanPeminjaman::class)->name('laporan');

    // Notifikasi in-app
    Route::post('/notifications/mark-all-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllAsRead');
});

// Easter Egg: Ruang Rahasia "Hantu Laut" Tim IT RSUD Sidawangi
Route::get('/secret', function () {
    return view('secret');
})->name('secret');


