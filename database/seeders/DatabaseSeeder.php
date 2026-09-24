<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Garasi;
use App\Models\Setting;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat 4 Peran Utama sesuai PRD BAB II & III
        $roleAdmin = Role::firstOrCreate(['name' => 'admin_it', 'guard_name' => 'web']);
        $roleGarasi = Role::firstOrCreate(['name' => 'kepala_garasi', 'guard_name' => 'web']);
        $rolePimpinan = Role::firstOrCreate(['name' => 'pimpinan', 'guard_name' => 'web']);
        $rolePemohon = Role::firstOrCreate(['name' => 'user_aplikasi', 'guard_name' => 'web']);

        // 2. Master Data Unit Kerja RSUD Sidawangi
        $unitUmum = UnitKerja::firstOrCreate(
            ['kode_unit' => 'UMUM'],
            ['nama_unit' => 'Bagian Umum & Rumah Tangga', 'keterangan' => 'Pengelola operasional internal dan garasi dinas']
        );
        $unitFarmasi = UnitKerja::firstOrCreate(
            ['kode_unit' => 'FARM'],
            ['nama_unit' => 'Instalasi Farmasi', 'keterangan' => 'Pengelolaan obat-obatan dan logistik medis']
        );
        $unitYanmed = UnitKerja::firstOrCreate(
            ['kode_unit' => 'YANMED'],
            ['nama_unit' => 'Bidang Pelayanan Medis', 'keterangan' => 'Pelayanan medis dan rujukan kedinasan']
        );
        $unitKeperawatan = UnitKerja::firstOrCreate(
            ['kode_unit' => 'KEP'],
            ['nama_unit' => 'Bidang Keperawatan', 'keterangan' => 'Pelayanan asuhan keperawatan']
        );
        $unitLab = UnitKerja::firstOrCreate(
            ['kode_unit' => 'LAB'],
            ['nama_unit' => 'Instalasi Laboratorium', 'keterangan' => 'Pemeriksaan sampel dan diagnostik']
        );

        // 3. Akun Pengguna Inti & Relasi Unit Kerja
        $admin = User::updateOrCreate(
            ['email' => 'admin@sidawangi.id'],
            [
                'name' => 'Admin IT RSUD Sidawangi',
                'nip' => '198801122015031001',
                'no_hp' => '081234567801',
                'password' => Hash::make('password'),
                'unit_kerja_id' => $unitUmum->id,
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles([$roleAdmin]);

        $garasiUser = User::updateOrCreate(
            ['email' => 'garasi@sidawangi.id'],
            [
                'name' => 'H. Suhendar (Kepala Garasi)',
                'nip' => '197904252008011004',
                'no_hp' => '081234567802',
                'password' => Hash::make('password'),
                'unit_kerja_id' => $unitUmum->id,
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );
        $garasiUser->syncRoles([$roleGarasi]);

        $pimpinan = User::updateOrCreate(
            ['email' => 'pimpinan@sidawangi.id'],
            [
                'name' => 'dr. Direktur RSUD Sidawangi',
                'nip' => '197508192002121002',
                'no_hp' => '081234567803',
                'password' => Hash::make('password'),
                'unit_kerja_id' => $unitYanmed->id,
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );
        $pimpinan->syncRoles([$rolePimpinan]);

        $pemohon = User::updateOrCreate(
            ['email' => 'pemohon@sidawangi.id'],
            [
                'name' => 'Budi Santoso (Instalasi Farmasi)',
                'nip' => '199203152019021005',
                'no_hp' => '081234567804',
                'password' => Hash::make('password'),
                'unit_kerja_id' => $unitFarmasi->id,
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );
        $pemohon->syncRoles([$rolePemohon]);

        // 4. Master Garasi / Pool Kendaraan
        $garasiUtama = Garasi::firstOrCreate(
            ['nama_garasi' => 'Garasi Utama RSUD Sidawangi'],
            [
                'alamat' => 'Kompleks Pool Barat, RSUD Sidawangi, Sumber, Cirebon',
                'penanggung_jawab_id' => $garasiUser->id,
                'no_telp' => '(0231) 8331234',
            ]
        );

        // Update garasi_id pada kepala garasi
        $garasiUser->update(['garasi_id' => $garasiUtama->id]);

        // 5. Master Kategori Kendaraan
        $katMpv = VehicleCategory::firstOrCreate(
            ['nama_kategori' => 'Minibus (MPV)'],
            ['keterangan' => 'Kendaraan multi-penumpang untuk perjalanan dinas tim']
        );
        $katPickup = VehicleCategory::firstOrCreate(
            ['nama_kategori' => 'Pickup Operasional'],
            ['keterangan' => 'Kendaraan angkut barang dan logistik rumah sakit']
        );
        $katSedan = VehicleCategory::firstOrCreate(
            ['nama_kategori' => 'Sedan / SUV Dinas'],
            ['keterangan' => 'Kendaraan dinas pimpinan dan tamu kehormatan']
        );
        $katVan = VehicleCategory::firstOrCreate(
            ['nama_kategori' => 'Blind Van Logistik'],
            ['keterangan' => 'Kendaraan boks tertutup untuk pengantaran obat & dokumen']
        );

        // 6. Master Armada Kendaraan Dinas RSUD Sidawangi (Fase 1 Non-Ambulans)
        Vehicle::firstOrCreate(
            ['no_polisi' => 'E 1234 YX'],
            [
                'garasi_id' => $garasiUtama->id,
                'kategori_id' => $katMpv->id,
                'merk' => 'Toyota',
                'tipe_model' => 'Avanza 1.3 G',
                'tahun_pembuatan' => 2022,
                'warna' => 'Hitam Metalik',
                'bahan_bakar' => 'bensin',
                'kapasitas_penumpang' => 7,
                'status' => 'tersedia',
                'odometer_terakhir' => 42500,
                'interval_service_km' => 5000,
                'interval_service_bulan' => 6,
                'tanggal_service_terakhir' => '2026-05-10',
                'odometer_service_terakhir' => 40000,
                'tanggal_pajak_tahunan' => '2026-11-15',
                'tanggal_pajak_5tahunan' => '2027-11-15',
                'catatan' => 'Kondisi prima, AC dingin, ban serep lengkap.',
            ]
        );

        Vehicle::firstOrCreate(
            ['no_polisi' => 'E 8765 YX'],
            [
                'garasi_id' => $garasiUtama->id,
                'kategori_id' => $katPickup->id,
                'merk' => 'Daihatsu',
                'tipe_model' => 'Gran Max 1.5 Pickup',
                'tahun_pembuatan' => 2021,
                'warna' => 'Putih',
                'bahan_bakar' => 'bensin',
                'kapasitas_penumpang' => 3,
                'status' => 'tersedia',
                'odometer_terakhir' => 68100,
                'interval_service_km' => 5000,
                'interval_service_bulan' => 6,
                'tanggal_service_terakhir' => '2026-06-20',
                'odometer_service_terakhir' => 65000,
                'tanggal_pajak_tahunan' => '2026-10-30',
                'tanggal_kir_berlaku' => '2026-12-15',
                'catatan' => 'Uji KIR aktif, terpal bak tersedia di garasi.',
            ]
        );

        Vehicle::firstOrCreate(
            ['no_polisi' => 'E 1001 YX'],
            [
                'garasi_id' => $garasiUtama->id,
                'kategori_id' => $katSedan->id,
                'merk' => 'Toyota',
                'tipe_model' => 'Kijang Innova Reborn 2.4 V',
                'tahun_pembuatan' => 2023,
                'warna' => 'Abu-abu Metalik',
                'bahan_bakar' => 'solar',
                'kapasitas_penumpang' => 7,
                'status' => 'tersedia',
                'odometer_terakhir' => 29400,
                'interval_service_km' => 10000,
                'interval_service_bulan' => 6,
                'tanggal_service_terakhir' => '2026-03-15',
                'odometer_service_terakhir' => 20000,
                'tanggal_pajak_tahunan' => '2027-02-20',
                'tanggal_pajak_5tahunan' => '2028-02-20',
                'catatan' => 'Kendaraan dinas pejabat direksi / perjalanan luar kota.',
            ]
        );

        Vehicle::firstOrCreate(
            ['no_polisi' => 'E 1555 YX'],
            [
                'garasi_id' => $garasiUtama->id,
                'kategori_id' => $katVan->id,
                'merk' => 'Daihatsu',
                'tipe_model' => 'Luxio Blind Van',
                'tahun_pembuatan' => 2020,
                'warna' => 'Silver',
                'bahan_bakar' => 'bensin',
                'kapasitas_penumpang' => 2,
                'status' => 'tersedia',
                'odometer_terakhir' => 51200,
                'interval_service_km' => 5000,
                'interval_service_bulan' => 6,
                'tanggal_service_terakhir' => '2026-04-05',
                'odometer_service_terakhir' => 50000,
                'tanggal_pajak_tahunan' => '2026-12-25',
                'catatan' => 'Boks berinsulasi, khusus pengantaran perbekalan farmasi & laboratorium.',
            ]
        );

        // 7. Master Sopir Dinas Pool RSUD
        Driver::firstOrCreate(
            ['no_sim' => '320912345601'],
            [
                'garasi_id' => $garasiUtama->id,
                'nama' => 'Supardi',
                'no_hp' => '081398765432',
                'jenis_sim' => 'A',
                'masa_berlaku_sim' => '2028-05-12',
                'status' => 'aktif',
            ]
        );

        Driver::firstOrCreate(
            ['no_sim' => '320987654302'],
            [
                'garasi_id' => $garasiUtama->id,
                'nama' => 'Asep Saepudin',
                'no_hp' => '081223344556',
                'jenis_sim' => 'B1',
                'masa_berlaku_sim' => '2027-08-20',
                'status' => 'aktif',
            ]
        );

        // 8. Pengaturan Sistem
        Setting::set('nama_instansi', 'RSUD Sidawangi Provinsi Jawa Barat', 'umum');
        Setting::set('alamat_instansi', 'Jl. Pangeran Kejaksan, Sidawangi, Kec. Sumber, Kabupaten Cirebon, Jawa Barat', 'umum');
        Setting::set('jam_layanan_pool', '06:00 - 22:00 WIB', 'umum');

        // Pejabat Penandatangan Laporan & Dokumen Resmi
        Setting::set('direktur_nama', 'dr. H. Hadri Pramono, Sp.P', 'pejabat');
        Setting::set('direktur_nip', '197405102002121003', 'pejabat');
        Setting::set('direktur_jabatan', 'Plt. Direktur RSUD Sidawangi', 'pejabat');
        Setting::set('pejabat_pool_nama', 'Kusnadi, S.Sos., M.Si', 'pejabat');
        Setting::set('pejabat_pool_nip', '198003152008011006', 'pejabat');
        Setting::set('pejabat_pool_jabatan', 'Kepala Bagian Umum & Rumah Tangga / Pool', 'pejabat');
        Setting::set('kota_penandatanganan', 'Cirebon', 'pejabat');

        // 9. Konfigurasi Ambang Pengingat Servis, Pajak & SIM
        $defaultRoles = ['admin_it', 'kepala_garasi'];
        $reminderTypes = ['service', 'pajak_tahunan', 'pajak_5tahunan', 'kir', 'sim_sopir'];
        foreach ($reminderTypes as $type) {
            \App\Models\ReminderSetting::firstOrCreate(
                ['tipe_reminder' => $type],
                [
                    'h_minus_tahap1' => 30,
                    'h_minus_tahap2' => 14,
                    'h_minus_tahap3' => 1,
                    'target_role' => $defaultRoles,
                    'aktif' => true,
                ]
            );
        }
    }
}
