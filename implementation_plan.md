# Implementasi Tahap 1 — Fondasi Sistem, Lingkungan & Arsitektur Dasar SIPENDI

Rencana ini merinci langkah-langkah penyiapan lingkungan dan fondasi proyek **SIPENDI** (*Sistem Peminjaman Kendaraan Dinas*) RSUD Sidawangi sesuai arahan pada [timeline.md](file:///c:/laragon/www/sipendi/timeline.md) dan [prd.md](file:///c:/laragon/www/sipendi/prd.md).

## User Review Required

> [!IMPORTANT]
> **Database & Environment Local:**
> - MySQL berjalan di port `3306` (host: `127.0.0.1`, user: `root`, password: *(kosong)*) yang telah teruji aktif.
> - Basis data yang akan dibuat dan digunakan adalah `sipendi_db`.
> - Aplikasi Laravel akan diinisialisasi di direktori kerja saat ini (`c:\laragon\www\sipendi`) dengan tetap mempertahankan berkas dokumentasi [prd.md](file:///c:/laragon/www/sipendi/prd.md) dan [timeline.md](file:///c:/laragon/www/sipendi/timeline.md).

> [!NOTE]
> **Paket & Ekosistem:**
> - PHP yang terdeteksi adalah **PHP 8.4.0** dan Composer **2.8.6**.
> - Menggunakan rilis stabil Laravel terbaru yang kompatibel penuh dengan PHP 8.4.
> - Panel Admin Backoffice menggunakan **Filament v3/v4** terbaru.

---

## Open Questions

Tidak ada pertanyaan penghalang. Seluruh dependensi utama, spesifikasi basis data, dan peran telah terdefinisi secara terperinci di dalam PRD.

---

## Proposed Changes

### Inisialisasi Proyek Laravel

#### [NEW] Proyek Scaffolding Laravel
- Melakukan inisialisasi scaffolding Laravel ke dalam direktori `c:\laragon\www\sipendi` tanpa menimpa berkas dokumentasi markdown yang sudah ada.
- Menyiapkan berkas `.env` dengan konfigurasi:
  - `APP_NAME="SIPENDI - RSUD Sidawangi"`
  - `APP_TIMEZONE="Asia/Jakarta"`
  - `APP_LOCALE="id"`
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=3306`
  - `DB_DATABASE=sipendi_db`
  - `DB_USERNAME=root`
  - `DB_PASSWORD=`
  - `QUEUE_CONNECTION=database`
- Menjalankan `php artisan key:generate`.
- Menjalankan perintah pembuatan database `sipendi_db` di MySQL jika belum ada.

---

### Instalasi & Setup Dependensi Inti

#### [MODIFY] [composer.json](file:///c:/laragon/www/sipendi/composer.json)
Instalasi paket-paket yang disyaratkan PRD:
1. **Filament** (`filament/filament`): Panel manajemen Admin IT & Kepala Garasi.
2. **Spatie Permission** (`spatie/laravel-permission`): Manajemen peran (Admin IT, User Aplikasi, Kepala Garasi, Pimpinan).
3. **Spatie Activitylog** (`spatie/laravel-activitylog`): Pencatatan jejak audit (Audit Trail).
4. **Ekspor Laporan**: `barryvdh/laravel-dompdf` (PDF) dan `maatwebsite/excel` (Excel).
5. Menjalankan perintah instalasi Filament: `php artisan filament:install --panels`.

---

### Konfigurasi Penyimpanan & Aset Frontend

#### [NEW] Setup Storage Symlink & Aset
- Menjalankan `php artisan storage:link` untuk akses publik berkas foto kendaraan dan lampiran surat tugas.
- Instalasi dependensi Node via `npm install` dan build awal aset Vite/Tailwind CSS (`npm run build`).

---

### Layout & Identitas Visual RSUD Sidawangi

#### [NEW] [resources/views/layouts/app.blade.php](file:///c:/laragon/www/sipendi/resources/views/layouts/app.blade.php)
- Membuat layout dasar aplikasi dengan Tailwind CSS bernuansa kesehatan / rumah sakit (palet biru medik `#0284c7` / `#0369a1` dan hijau `#059669` yang ramah dan profesional).
- Header responsif yang ramah mobile (*mobile-first navbar*) untuk User Aplikasi dan Pimpinan.

#### [NEW] [resources/views/welcome.blade.php](file:///c:/laragon/www/sipendi/resources/views/welcome.blade.php)
- Landing page interaktif dan modern yang memperkenalkan SIPENDI RSUD Sidawangi dengan tautan cepat ke Login, Portal Peminjaman, dan Panel Garasi/Admin.

---

## Verification Plan

### Automated Tests / Artisan Diagnostics
- Menjalankan pemeriksaan konfigurasi:
  ```powershell
  php artisan about
  ```
- Menjalankan migrasi dasar tabel bawaan (users, cache, jobs, notifications):
  ```powershell
  php artisan migrate
  ```
- Memastikan panel Filament terdaftar:
  ```powershell
  php artisan route:list --path=admin
  ```

### Manual Verification
- Menjalankan development server (`php artisan serve`) untuk memverifikasi:
  1. Halaman beranda SIPENDI dapat diakses dan menampilkan visual RSUD Sidawangi secara profesional.
  2. Halaman login panel admin Filament (`/admin`) terbuka tanpa eror.
  3. Koneksi database MySQL berhasil terhubung dan tabel dasar tercipta.
