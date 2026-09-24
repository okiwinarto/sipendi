# MAS PENDI — Sistem Peminjaman Kendaraan Dinas RSUD Sidawangi

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13" />
  <img src="https://img.shields.io/badge/PHP-8.3%20%7C%208.5-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.5" />
  <img src="https://img.shields.io/badge/Filament-v3%2Fv4-F59E0B?style=for-the-badge&logo=filament&logoColor=white" alt="Filament" />
  <img src="https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 3" />
  <img src="https://img.shields.io/badge/TailwindCSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Tests-69%20Passed%20(100%25)-success?style=for-the-badge&logo=php&logoColor=white" alt="Tests Passed" />
</p>

---

## 📌 Tentang Aplikasi

**MAS PENDI** (*Manajemen Aset dan Peminjaman Kendaraan Dinas*) adalah sistem informasi resmi terintegrasi milik **RSUD Sidawangi Provinsi Jawa Barat** untuk mendigitalkan seluruh siklus pengelolaan kendaraan dinas operasional (*Fase 1: Khusus Kendaraan Dinas Non-Ambulans*).

Platform ini menggantikan prosedur manual berbasis kertas menjadi alur digital yang transparan, akuntabel, terhindar dari jadwal bentrok (*conflict schedule prevention*), tertib administrasi pajak & uji KIR, serta proaktif dalam pemeliharaan servis berkala armada.

---

## 🚀 Fitur Unggulan

### 1. 👥 Multi-Peran & RBAC Berjenjang (Spatie Permission)
- **User Aplikasi (Pegawai Pemohon):** Pengajuan permohonan dinas online, cek jadwal di kalender armada, pemantauan riwayat status, dan pembatalan mandiri.
- **Kepala Garasi (Pool Kendaraan):** Verifikasi teknis armada laik jalan, penetapan unit kendaraan, alokasi sopir dinas pool, Berita Acara Serah Terima (BAST) Checkout & Checkin, serta pencatatan servis dan pajak.
- **Pimpinan Direksi:** Otorisasi persetujuan akhir atau penolakan pengajuan perjalanan dinas dengan antarmuka ringkas ramah ponsel (*mobile-first 1-click decision*).
- **Administrator IT:** Manajemen master data (pengguna, unit kerja, garasi, sopir, kategori mobil), manajemen peran/izin, konfigurasi reminder scheduler, dan pemantauan audit trail.

### 2. 📅 Kalender Ketersediaan Armada Interaktif
- Visualisasi jadwal armada yang terisi (*booked/on duty*) vs armada yang kosong (*tersedia*).
- Klik langsung pada tanggal kalender untuk otomatis mengisi tanggal pada formulir pengajuan.
- Mesin validasi bentrok jadwal otomatis (*double booking / conflict schedule prevention*).

### 3. 📝 Formulir Pengajuan Cerdas
- Validasi anti-mundur: pengajuan hanya dapat dilakukan mulai hari ini ke depan.
- Pemilihan jenis pengemudi: *Sopir Dinas Pool* atau *Swakemudi* (lepas kunci).
- Pilihan tingkat urgensi: *Normal* atau *Mendesak (Cito)* untuk penanganan operasional darurat.
- Unggah scan berkas surat tugas dinas (PDF/JPG/PNG).
- Penomoran kode registrasi unik otomatis (format: `SPD/{KODE_UNIT}/{TAHUN}{BULAN}/{URUT}`).

### 4. 🔑 Serah Terima Digital (Checkout & Checkin)
- **Checkout (Keluar):** Pencatatan angka odometer awal, posisi indikator BBM (*E, 1/4, 1/2, 3/4, F*), checklist fisik (ban serep, dongkrak, kunci roda, segitiga, P3K, STNK), dan unggah foto kondisi sebelum berangkat.
- **Checkin (Kembali):** Validasi odometer masuk ($\ge$ odometer keluar), posisi BBM kembali, checklist, rating kondisi/kebersihan (1–5 bintang), pelaporan insiden kerusakan fisik, dan pencetakan Berita Acara Serah Terima (BAST).

### 5. ⏰ Pengingat Otomatis Servis, Pajak & SIM (Cron Engine)
- Pemeriksaan otomatis setiap hari pukul 06.00 WIB melalui Laravel Scheduler:
  - Servis berkala berdasarkan selisih kilometer odometer dan interval bulan.
  - Jatuh tempo pajak tahunan (STNK) dan pajak 5 tahunan (PKB).
  - Masa berlaku uji kelayakan kendaraan (KIR).
  - Masa berlaku SIM pengemudi dinas pool.
- Ambang batas pengingat bertahap (*H-30, H-14, H-1*) dengan notifikasi in-app dan email.
- Armada yang melewati jatuh tempo otomatis berstatus `perlu_perhatian` dan dicegah dari pengajuan baru.

### 6. 📊 Dashboard Analitik Eksekutif & Pelaporan Lanjutan
- **Dashboard Pimpinan:** Metrik utilisasi armada, total peminjaman, grafik frekuensi dinas per unit kerja, dan daftar mobil paling aktif.
- **Laporan & Ekspor:** Rekapitulasi dinas dengan filter rentang tanggal, unit kerja, armada, dan sopir, siap diekspor ke format **PDF resmi (kop surat RSUD Sidawangi)** atau **Excel (.xlsx)**.
- **Audit Trail Permanen:** Seluruh aktivitas operasional terekam lengkap via Spatie Activity Log.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

| Komponen | Teknologi |
|---|---|
| **Backend Framework** | Laravel 13 (PHP 8.3+ / PHP 8.5 compatible) |
| **Admin Backoffice** | Filament v3 / v4 |
| **Frontend Reaktif** | Livewire 3 + Alpine.js |
| **Desain & Styling** | Tailwind CSS v4 + Plus Jakarta Sans Typography |
| **Basis Data** | MySQL 8.0+ / MariaDB 10.6+ |
| **Audit Trail & RBAC** | `spatie/laravel-permission` & `spatie/laravel-activitylog` |
| **Ekspor Dokumen** | `barryvdh/laravel-dompdf` & `maatwebsite/excel` |
| **Automated Testing** | PHPUnit / Pest Feature Test Suite |

---

## 💻 Panduan Instalasi Lokal

### 1. Prasyarat Sistem
- PHP versi **8.3** atau **8.5+** (wajib mengaktifkan ekstensi: `intl`, `pdo_mysql`, `gd`, `zip`, `xml`, `mbstring`, `curl`).
- Composer **v2.x**.
- Node.js **v20+** & NPM.
- MySQL / MariaDB.

### 2. Kloning & Pengaturan Dependensi
```bash
# Clone repositori
git clone https://github.com/[username]/sipendi.git
cd sipendi

# Instalasi dependensi PHP
composer install

# Instalasi dependensi JavaScript
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin berkas `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka berkas `.env` dan sesuaikan pengaturan database Anda:
```dotenv
APP_NAME="SIPENDI - RSUD Sidawangi"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipendi_db
DB_USERNAME=root
DB_PASSWORD=
```
Lalu buat application encryption key:
```bash
php artisan key:generate
```

### 4. Migrasi & Seeder Database
Jalankan migrasi seluruh 16 tabel dan seeder data awal RSUD Sidawangi:
```bash
php artisan migrate --seed
```

### 5. Tautkan Penyimpanan Media
```bash
php artisan storage:link
```

### 6. Menjalankan Server Pengembangan
Jalankan dev server gabungan (Laravel server, queue, dan Vite compiler):
```bash
php artisan dev
```
Atau secara terpisah:
```bash
# Terminal 1: Backend
php artisan serve

# Terminal 2: Frontend Assets
npm run dev
```
Akses aplikasi melalui browser:
- **Landing Page Publik:** `http://127.0.0.1:8000`
- **Portal Masuk Pegawai:** `http://127.0.0.1:8000/portal`
- **Filament Admin Backoffice:** `http://127.0.0.1:8000/admin`

---

## 🔑 Akun Uji Coba Default (Seeder)

Seluruh akun default menggunakan kata sandi: `password`

| Peran | Alamat Email | Hak Akses Utama |
|---|---|---|
| **Administrator IT** | `admin@sidawangi.id` | Backoffice Filament, Master Data, Audit Trail, Config |
| **Kepala Garasi** | `garasi@sidawangi.id` | Verifikasi Armada, BAST Serah Terima, Servis & Pajak |
| **Pimpinan Direksi** | `pimpinan@sidawangi.id` | Persetujuan Cepat 1-Klik, Dashboard Eksekutif |
| **Pegawai Pemohon** | `pemohon@sidawangi.id` | Pengajuan Peminjaman, Kalender, Riwayat Pengajuan |

---

## 🧪 Pengujian Otomatis (Automated Testing)

Sistem telah dilengkapi dengan pengujian fitur menyeluruh untuk 8 tahap pengembangan:

```bash
php artisan test
```

**Hasil Pengujian:**
```text
Tests:       69 passed (69 total)
Assertions:  319 passed (319 total)
Duration:    ~26s
Status:      OK (100% Passed)
```

Untuk menjalankan pengujian UAT Siklus Penuh (Tahap 8):
```bash
php artisan test --filter StageEightUATVerificationTest
```

---

## 📚 Dokumentasi Terkait

- [USER_GUIDE.md](USER_GUIDE.md): Buku Panduan Pengguna Lengkap untuk 4 Peran (Pemohon, Garasi, Pimpinan, IT).
- [GO_LIVE_CHECKLIST.md](GO_LIVE_CHECKLIST.md): Panduan Deployment Produksi, Supervisor, dan Linux Cron Scheduler.
- [timeline.md](timeline.md): Roadmap Pelaksanaan 8 Tahap Pengembangan.
- [prd.md](prd.md): Dokumen Spesifikasi Kebutuhan Produk (*Product Requirement Document*).

---

## 📄 Lisensi & Hak Cipta
Hak Cipta © 2026 **RSUD Sidawangi Provinsi Jawa Barat**.  
Dikembangkan untuk mendukung tata kelola aset transportasi dinas yang tertib, modern, dan akuntabel.
