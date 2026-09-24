# CHECKLIST GO-LIVE & PANDUAN DEPLOYMENT PRODUKSI
**SIPENDI — Sistem Peminjaman Kendaraan Dinas RSUD Sidawangi**

Dokumen ini menjadi acuan teknis bagi Administrator IT dan Tim Pengembang sebelum dan saat proses peluncuran sistem ke lingkungan produksi (*Production Environment*).

---

## 1. Verifikasi Lingkungan Server (Server Prerequisites)
- [x] **PHP Runtime:** PHP 8.3+ (diuji stabil pada PHP 8.5.10).
- [x] **Ekstensi PHP Wajib:**
  - `ext-intl` & ICU library (untuk format mata uang, penanggalan Carbon lokal Indonesia, dan agregasi data).
  - `ext-pdo_mysql` (koneksi database MySQL/MariaDB).
  - `ext-gd` atau `ext-imagick` (pemrosesan foto fisik kendaraan & serah terima).
  - `ext-zip`, `ext-xml`, `ext-mbstring`, `ext-curl`.
- [x] **Basis Data:** MySQL 8.0+ atau MariaDB 10.6+ dengan set karakter `utf8mb4` dan collation `utf8mb4_unicode_ci`.
- [x] **Node.js & NPM:** Node.js v20+ untuk kompilasi aset frontend Tailwind & Vite.

---

## 2. Konfigurasi Lingkungan (`.env` Production)
Pastikan berkas `.env` di server produksi telah disesuaikan dari mode development:

```dotenv
APP_NAME="SIPENDI - RSUD Sidawangi"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sipendi.sidawangi.id

# Keamanan Kunci Aplikasi
# Jalankan: php artisan key:generate
APP_KEY=base64:...

# Koneksi Database Produksi
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipendi_prod
DB_USERNAME=sipendi_user
DB_PASSWORD=[PASSWORD_KUAT_TERENKRIPSI]

# Queue Driver (Wajib database atau redis untuk background processing)
QUEUE_CONNECTION=database

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

# Konfigurasi SMTP Email Resmi RSUD Sidawangi
MAIL_MAILER=smtp
MAIL_HOST=smtp.sidawangi.id
MAIL_PORT=587
MAIL_USERNAME=notifikasi-sipendi@sidawangi.id
MAIL_PASSWORD=[SMTP_PASSWORD]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="notifikasi-sipendi@sidawangi.id"
MAIL_FROM_NAME="MAS PENDI - RSUD Sidawangi"
```

---

## 3. Langkah Instalasi & Optimasi Performa (Production Build)
Jalankan urutan perintah berikut di terminal server produksi:

```bash
# 1. Unduh pustaka PHP tanpa dependensi dev (hemat memori)
composer install --no-dev --optimize-autoloader

# 2. Build aset frontend produksi (CSS & JS ter-minify)
npm ci
npm run build

# 3. Jalankan migrasi basis data dan seeder awal
php artisan migrate --force
php artisan db:seed --force

# 4. Hubungkan penyimpanan publik (untuk foto kendaraan & BAST)
php artisan storage:link

# 5. Caching konfigurasi, rute, view, dan event untuk performa maksimal
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 4. Konfigurasi Background Services & Cron Scheduler

### 4.1 Queue Worker (Supervisor)
SIPENDI memproses pengiriman notifikasi email dan audit trail melalui antrean latar belakang (*background queue*). Buat konfigurasi Supervisor `/etc/supervisor/conf.d/sipendi-worker.conf`:

```ini
[program:sipendi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sipendi/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/sipendi/storage/logs/worker.log
stopwaitsecs=3600
```

### 4.2 Cron Scheduler (Pengecekan Otomatis Servis, Pajak & SIM)
Tambahkan entry berikut ke dalam `crontab -e` milik pengguna web server:

```cron
* * * * * cd /var/www/sipendi && php artisan schedule:run >> /dev/null 2>&1
```
> **Catatan:** Cron ini secara otomatis menjalankan engine reminder harian pukul 06.00 WIB untuk mendeteksi jatuh tempo servis berkala, STNK, PKB 5 tahunan, KIR, dan masa berlaku SIM pengemudi dinas.

---

## 5. Keamanan & Izin Direktori (Permission & Hardening)
- [x] Pastikan folder `storage` dan `bootstrap/cache` memiliki izin tulis oleh pengguna web server (`chmod -R 775 storage bootstrap/cache`).
- [x] Pastikan file `.env` memiliki izin ketat (`chmod 600 .env` atau `chmod 640 .env`).
- [x] Aktifkan sertifikat SSL/TLS HTTPS (Let's Encrypt / Cloudflare).
- [x] Konfigurasi reverse proxy trust: `trustProxies(at: '*')` telah terpasang di `bootstrap/app.php`.

---

## 6. Prosedur Pencadangan Rutin (Backup Policy)
1. **Basis Data:** Lakukan backup harian otomatis (dump MySQL) setiap pukul 02.00 WIB ke penyimpanan terpisah / offsite cloud.
2. **Media Unggahan:** Sinkronisasi berkala direktori `storage/app/public/` (foto kondisi fisik kendaraan, bukti kerusakan, dan dokumen surat tugas dinas).

---

## 7. Rencana Penanganan Insiden & Kontak Darurat
- **Administrator IT RSUD Sidawangi:** admin@sidawangi.id / Ekstensi 101
- **Kepala Bagian Umum & Rumah Tangga:** Kusnadi, S.Sos., M.Si (PIC Pool)
- **Kepala Garasi (Operasional Lapangan):** H. Suhendar (0812-3456-7890)
