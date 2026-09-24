# Implementasi Tahap 2 — Manajemen Pengguna, Organisasi & Master Data

Rencana ini merinci langkah-langkah pengembangan **Tahap 2** aplikasi **SIPENDI** (*Sistem Peminjaman Kendaraan Dinas*) RSUD Sidawangi berdasarkan [prd.md](file:///c:/laragon/www/sipendi/prd.md) (BAB VI) dan [timeline.md](file:///c:/laragon/www/sipendi/timeline.md).

## User Review Required

> [!IMPORTANT]
> **Struktur Master Data Kendaraan & Pengguna:**
> - Tabel `users` akan diperkaya dengan atribut kepegawaian RSUD Sidawangi (`nip`, `no_hp`, `unit_kerja_id`, `garasi_id`, `foto`, `status`).
> - Tabel `vehicles` akan menyimpan detail armada: plat nomor unik, kategori, garasi pool asal, nomor rangka/mesin, kapasitas, foto, status kesiapan, odometer terakhir, serta tanggal jatuh tempo pajak (tahunan, 5 tahunan, KIR) dan interval servis berkala.
> - Seluruh modul master data akan dapat dikelola secara visual oleh Admin IT melalui Panel Backoffice Filament dengan validasi form, pencarian, dan pemfilteran (*filters*).

---

## Open Questions

Tidak ada pertanyaan penghalang. Skema 16 tabel telah terdefinisi secara presisi pada BAB VI PRD.

---

## Proposed Changes

### Basis Data & Model Eloquent

#### [NEW] [database/migrations/2026_09_22_040001_create_unit_kerjas_table.php](file:///c:/laragon/www/sipendi/database/migrations/2026_09_22_040001_create_unit_kerjas_table.php)
- Membuat tabel `unit_kerja`: `id`, `nama_unit`, `kode_unit` (unique), `keterangan`, `created_at`, `updated_at`.
- Model: `App\Models\UnitKerja` dengan relasi `hasMany(User::class)`.

#### [NEW] [database/migrations/2026_09_22_040002_create_garasis_table.php](file:///c:/laragon/www/sipendi/database/migrations/2026_09_22_040002_create_garasis_table.php)
- Membuat tabel `garasi`: `id`, `nama_garasi`, `alamat`, `penanggung_jawab_id` (FK ke users), `no_telp`, `created_at`, `updated_at`.
- Model: `App\Models\Garasi` dengan relasi ke `penanggungJawab` (`User`), `vehicles()`, dan `drivers()`.

#### [NEW] [database/migrations/2026_09_22_040003_add_columns_to_users_table.php](file:///c:/laragon/www/sipendi/database/migrations/2026_09_22_040003_add_columns_to_users_table.php)
- Menambahkan kolom ke `users`: `unit_kerja_id` (FK nullable), `garasi_id` (FK nullable), `nip` (varchar 30 unique nullable), `no_hp` (varchar 20 nullable), `foto` (varchar 255 nullable), `status` (enum: 'aktif', 'nonaktif' default 'aktif').
- Update `App\Models\User` dengan relasi `belongsTo(UnitKerja::class)` dan `belongsTo(Garasi::class)`.

#### [NEW] [database/migrations/2026_09_22_040004_create_vehicle_categories_table.php](file:///c:/laragon/www/sipendi/database/migrations/2026_09_22_040004_create_vehicle_categories_table.php)
- Membuat tabel `vehicle_categories`: `id`, `nama_kategori`, `keterangan`, `created_at`, `updated_at`.
- Model: `App\Models\VehicleCategory` dengan relasi `hasMany(Vehicle::class)`.

#### [NEW] [database/migrations/2026_09_22_040005_create_vehicles_table.php](file:///c:/laragon/www/sipendi/database/migrations/2026_09_22_040005_create_vehicles_table.php)
- Membuat tabel `vehicles`:
  - `garasi_id` (FK), `kategori_id` (FK)
  - `no_polisi` (unique), `no_rangka`, `no_mesin`, `no_bpkb`, `merk`, `tipe_model`, `tahun_pembuatan`, `warna`
  - `bahan_bakar` (enum: bensin, solar, listrik, hybrid), `kapasitas_penumpang`, `foto_utama`
  - `status` (enum: 'tersedia', 'dipinjam', 'maintenance', 'perlu_perhatian', 'nonaktif' default 'tersedia')
  - `odometer_terakhir`, `interval_service_km`, `interval_service_bulan`, `tanggal_service_terakhir`, `odometer_service_terakhir`
  - `tanggal_pajak_tahunan`, `tanggal_pajak_5tahunan`, `tanggal_kir_berlaku`, `catatan`
- Model: `App\Models\Vehicle` dengan relasi ke `garasi()`, `kategori()`.

#### [NEW] [database/migrations/2026_09_22_040006_create_drivers_table.php](file:///c:/laragon/www/sipendi/database/migrations/2026_09_22_040006_create_drivers_table.php)
- Membuat tabel `drivers`: `garasi_id` (FK nullable), `user_id` (FK nullable), `nama`, `no_hp`, `no_sim`, `jenis_sim`, `masa_berlaku_sim`, `foto`, `status` (aktif, cuti, nonaktif).
- Model: `App\Models\Driver` dengan relasi ke `garasi()` dan `user()`.

#### [NEW] [database/migrations/2026_09_22_040007_create_settings_table.php](file:///c:/laragon/www/sipendi/database/migrations/2026_09_22_040007_create_settings_table.php)
- Membuat tabel `settings`: `key` (unique), `value`, `grup`.
- Model: `App\Models\Setting`.

---

### Seeder Data Master

#### [MODIFY] [database/seeders/DatabaseSeeder.php](file:///c:/laragon/www/sipendi/database/seeders/DatabaseSeeder.php)
- Menambahkan data seeder realistis RSUD Sidawangi:
  - **Unit Kerja:** Instalasi Farmasi, Bagian Umum & Rumah Tangga, Bidang Keperawatan, Bidang Pelayanan Medis, Instalasi Laboratorium, Instalasi Radiologi.
  - **Garasi / Pool:** Garasi Utama RSUD Sidawangi (PIC: H. Suhendar).
  - **Kategori Kendaraan:** Minibus (MPV), Pickup Operasional, Sedan Kedinasan, Blind Van Logistik.
  - **Armada Kendaraan Dinas:**
    - Toyota Avanza 1.3 G (E 1234 YX) — Minibus, Tersedia, Pajak aktif.
    - Daihatsu Gran Max Pickup (E 8765 YX) — Logistik/Operasional, Tersedia.
    - Toyota Kijang Innova (E 1001 YX) — Kendaraan Jabatan/Pimpinan, Tersedia.
    - Mitsubishi Triton 4x4 (E 1999 YX) — Operasional Lapangan.
  - **Sopir Dinas Pool:** Data 2 sopir ber-SIM aktif beserta nomor kontak.
  - **Pengaturan Sistem:** Nama instansi, kontak darurat, aturan operasional.

---

### Filament Admin Resources (Backoffice Management)

#### [NEW] Filament Resources di `app/Filament/Resources/`
1. **UnitKerjaResource:** CRUD Unit Kerja (nama, kode, keterangan, jumlah pegawai).
2. **GarasiResource:** CRUD Garasi/Pool (nama pool, alamat, nomor telepon, penanggung jawab).
3. **VehicleCategoryResource:** CRUD Kategori Kendaraan.
4. **VehicleResource:** CRUD Armada Kendaraan Dinas:
   - Form input komprehensif (tab/section: Identitas Kendaraan, Kelaikan & Servis, Dokumen Pajak & Foto).
   - Badge warna status ketersediaan (*Tersedia, Dipinjam, Maintenance, Perlu Perhatian*).
   - Filter tabel berdasarkan status, kategori, dan bahan bakar.
5. **DriverResource:** CRUD Sopir Dinas (nama, nomor kontak, jenis SIM, masa berlaku SIM, status penugasan).
6. **UserResource:** CRUD Pengguna & Peran (penugasan unit kerja, penetapan role Spatie).
7. **SettingResource:** Pengaturan konfigurasi sistem.

---

## Verification Plan

### Automated Tests
- Menambahkan berkas pengujian [StageTwoVerificationTest.php](file:///c:/laragon/www/sipendi/tests/Feature/StageTwoVerificationTest.php) untuk memverifikasi:
  1. Seluruh 6 tabel baru termigrasi dengan tipe data dan indeks yang benar.
  2. Data seeder unit kerja, garasi, kategori, kendaraan dinas, dan sopir tersimpan lengkap.
  3. Relasi Eloquent (Vehicle -> Garasi, Vehicle -> Kategori, User -> UnitKerja, Driver -> Garasi) berfungsi semestinya.
  4. Halaman Filament Resources (VehicleResource, DriverResource, UnitKerjaResource, dll) dapat diakses dengan respons HTTP 200 oleh Admin IT.
- Menjalankan `php artisan test`.

### Manual Verification
- Membuka halaman panel admin Filament (`http://127.0.0.1:8000/admin`) dengan akun `admin@sidawangi.id`.
- Memverifikasi kemunculan menu navigasi master data:
  - *Armada Kendaraan*
  - *Kategori Kendaraan*
  - *Garasi & Pool*
  - *Sopir Dinas*
  - *Unit Kerja*
  - *Pengguna Sistem*
- Menguji alur pembuatan (*Create*) dan pengeditan (*Edit*) armada kendaraan dinas.
