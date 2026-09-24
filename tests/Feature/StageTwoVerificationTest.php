<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\Garasi;
use App\Models\Setting;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Tests\TestCase;

class StageTwoVerificationTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('email', 'admin@sidawangi.id')->first();
    }

    /**
     * Test master Unit Kerja tersimpan dan memiliki relasi ke User.
     */
    public function test_unit_kerja_master_and_relationships(): void
    {
        $this->assertGreaterThanOrEqual(5, UnitKerja::count());

        $farmasi = UnitKerja::where('kode_unit', 'FARM')->first();
        $this->assertNotNull($farmasi);
        $this->assertEquals('Instalasi Farmasi', $farmasi->nama_unit);

        // Verifikasi relasi ke pegawai (Budi Santoso)
        $this->assertTrue($farmasi->users()->where('email', 'pemohon@sidawangi.id')->exists());
    }

    /**
     * Test master Garasi tersimpan dan memiliki relasi ke penanggung jawab dan armada.
     */
    public function test_garasi_master_and_relationships(): void
    {
        $garasi = Garasi::first();
        $this->assertNotNull($garasi);
        $this->assertStringContainsString('Garasi Utama', $garasi->nama_garasi);

        // Penanggung jawab adalah H. Suhendar
        $this->assertNotNull($garasi->penanggungJawab);
        $this->assertEquals('garasi@sidawangi.id', $garasi->penanggungJawab->email);

        // Relasi ke armada mobil dan sopir
        $this->assertGreaterThan(0, $garasi->vehicles()->count());
        $this->assertGreaterThan(0, $garasi->drivers()->count());
    }

    /**
     * Test master Kategori Kendaraan tersimpan dan terhubung ke armada.
     */
    public function test_vehicle_categories_and_relationships(): void
    {
        $this->assertGreaterThanOrEqual(4, VehicleCategory::count());

        $mpv = VehicleCategory::where('nama_kategori', 'Minibus (MPV)')->first();
        $this->assertNotNull($mpv);
        $this->assertGreaterThan(0, $mpv->vehicles()->count());
    }

    /**
     * Test master Armada Kendaraan Dinas (Vehicles) tersimpan lengkap dengan data pajak & servis.
     */
    public function test_vehicles_table_and_data_integrity(): void
    {
        $this->assertGreaterThanOrEqual(4, Vehicle::count());

        $avanza = Vehicle::where('no_polisi', 'E 1234 YX')->first();
        $this->assertNotNull($avanza);
        $this->assertEquals('Toyota', $avanza->merk);
        $this->assertEquals('Avanza 1.3 G', $avanza->tipe_model);
        $this->assertEquals('tersedia', $avanza->status);
        $this->assertEquals(7, $avanza->kapasitas_penumpang);
        $this->assertEquals(42500, $avanza->odometer_terakhir);

        // Relasi
        $this->assertNotNull($avanza->garasi);
        $this->assertNotNull($avanza->kategori);
        $this->assertEquals('Toyota Avanza 1.3 G (E 1234 YX)', $avanza->nama_lengkap);
    }

    /**
     * Test master Sopir Dinas (Drivers) dengan nomor SIM dan masa berlaku.
     */
    public function test_drivers_table_and_relationships(): void
    {
        $this->assertGreaterThanOrEqual(2, Driver::count());

        $sopir = Driver::where('nama', 'Supardi')->first();
        $this->assertNotNull($sopir);
        $this->assertEquals('A', $sopir->jenis_sim);
        $this->assertEquals('aktif', $sopir->status);
        $this->assertNotNull($sopir->garasi);
    }

    /**
     * Test tabel Users memiliki NIP, status, dan relasi Unit Kerja / Garasi.
     */
    public function test_users_table_extended_attributes(): void
    {
        $admin = User::where('email', 'admin@sidawangi.id')->first();
        $this->assertNotNull($admin->nip);
        $this->assertEquals('aktif', $admin->status);
        $this->assertNotNull($admin->unitKerja);

        $garasiUser = User::where('email', 'garasi@sidawangi.id')->first();
        $this->assertNotNull($garasiUser->garasi);
    }

    /**
     * Test Admin IT dapat mengakses seluruh halaman indeks Filament Resources master data.
     */
    public function test_admin_can_access_all_filament_master_resources(): void
    {
        $routes = [
            '/admin/unit-kerjas',
            '/admin/garasis',
            '/admin/vehicle-categories',
            '/admin/vehicles',
            '/admin/drivers',
            '/admin/users',
            '/admin/settings',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->admin)->get($route);
            $response->assertStatus(200, "Gagal mengakses rute {$route}");
        }
    }
}
