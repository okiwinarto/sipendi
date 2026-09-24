<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StageOneVerificationTest extends TestCase
{
    /**
     * Test koneksi database MySQL aktif dan dapat diakses.
     */
    public function test_mysql_database_connection_is_healthy(): void
    {
        $pdo = DB::connection()->getPdo();
        $this->assertNotNull($pdo);
        $this->assertEquals('mysql', DB::connection()->getDriverName());
    }

    /**
     * Test halaman utama MAS PENDI mengembalikan HTTP 200 dan memuat identitas RSUD Sidawangi.
     */
    public function test_landing_page_renders_successfully_with_mas_pendi_brand(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('MAS PENDI');
        $response->assertSee('RSUD Sidawangi');
        $response->assertSee('admin@sidawangi.id');
        $response->assertSee('garasi@sidawangi.id');
    }

    /**
     * Test halaman login Filament Admin Panel dapat diakses.
     */
    public function test_filament_admin_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('MAS PENDI');
    }

    /**
     * Test 4 peran utama sesuai PRD telah dibuat.
     */
    public function test_four_primary_roles_exist(): void
    {
        $expectedRoles = ['admin_it', 'kepala_garasi', 'pimpinan', 'user_aplikasi'];

        foreach ($expectedRoles as $roleName) {
            $this->assertTrue(
                Role::where('name', $roleName)->exists(),
                "Peran {$roleName} harus terdaftar di database."
            );
        }
    }

    /**
     * Test akun pengguna awal terdaftar dengan peran yang sesuai.
     */
    public function test_seeded_users_exist_with_correct_roles(): void
    {
        $admin = User::where('email', 'admin@sidawangi.id')->first();
        $this->assertNotNull($admin, 'Akun admin@sidawangi.id harus ada.');
        $this->assertTrue($admin->hasRole('admin_it'), 'Admin harus memiliki peran admin_it.');

        $garasi = User::where('email', 'garasi@sidawangi.id')->first();
        $this->assertNotNull($garasi, 'Akun garasi@sidawangi.id harus ada.');
        $this->assertTrue($garasi->hasRole('kepala_garasi'), 'Akun garasi harus memiliki peran kepala_garasi.');

        $pimpinan = User::where('email', 'pimpinan@sidawangi.id')->first();
        $this->assertNotNull($pimpinan, 'Akun pimpinan@sidawangi.id harus ada.');
        $this->assertTrue($pimpinan->hasRole('pimpinan'), 'Akun pimpinan harus memiliki peran pimpinan.');

        $pemohon = User::where('email', 'pemohon@sidawangi.id')->first();
        $this->assertNotNull($pemohon, 'Akun pemohon@sidawangi.id harus ada.');
        $this->assertTrue($pemohon->hasRole('user_aplikasi'), 'Akun pemohon harus memiliki peran user_aplikasi.');
    }

    /**
     * Test Admin IT yang login dapat mengakses dashboard Filament.
     */
    public function test_authenticated_admin_can_access_filament_dashboard(): void
    {
        $admin = User::where('email', 'admin@sidawangi.id')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Kembali ke Portal Pegawai');
    }

    /**
     * Test akun pengguna biasa dan pimpinan ditolak dari panel Filament backoffice.
     */
    public function test_regular_and_pimpinan_users_cannot_access_filament_dashboard(): void
    {
        $pemohon = User::where('email', 'pemohon@sidawangi.id')->first();
        $this->assertNotNull($pemohon);
        $response = $this->actingAs($pemohon)->get('/admin');
        $response->assertStatus(403);

        $pimpinan = User::where('email', 'pimpinan@sidawangi.id')->first();
        $this->assertNotNull($pimpinan);
        $response = $this->actingAs($pimpinan)->get('/admin');
        $response->assertStatus(403);
    }

    /**
     * Test Kepala Garasi dapat mengakses backoffice Filament namun dibatasi dari menu Pengguna & Pengaturan Sistem.
     */
    public function test_kepala_garasi_can_access_filament_but_restricted_from_admin_menus(): void
    {
        $garasi = User::where('email', 'garasi@sidawangi.id')->first();
        $this->assertNotNull($garasi);

        // Dashboard backoffice dapat diakses
        $response = $this->actingAs($garasi)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Kembali ke Portal Pegawai');

        // Menu Armada dapat diakses
        $this->actingAs($garasi)->get('/admin/vehicles')->assertStatus(200);
        $this->actingAs($garasi)->get('/admin/vehicle-maintenances')->assertStatus(200);
        $this->actingAs($garasi)->get('/admin/vehicle-taxes')->assertStatus(200);

        // Menu Manajemen Pengguna & Pengaturan Sistem DITOLAK (403)
        $this->actingAs($garasi)->get('/admin/users')->assertStatus(403);
        $this->actingAs($garasi)->get('/admin/unit-kerjas')->assertStatus(403);
        $this->actingAs($garasi)->get('/admin/settings')->assertStatus(403);
        $this->actingAs($garasi)->get('/admin/reminder-settings')->assertStatus(403);
        $this->actingAs($garasi)->get('/admin/activity-logs')->assertStatus(403);
    }

    /**
     * Test direktori symlink storage terhubung untuk berkas upload.
     */
    public function test_storage_symlink_exists(): void
    {
        $storagePath = public_path('storage');
        $this->assertTrue(file_exists($storagePath), 'Direktori public/storage harus ada dan terhubung.');
    }
}
