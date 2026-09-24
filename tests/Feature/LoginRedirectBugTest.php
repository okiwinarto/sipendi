<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginRedirectBugTest extends TestCase
{
    protected User $pemohonUser;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pemohonUser = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();
        $this->adminUser = User::where('email', 'admin@sidawangi.id')->firstOrFail();
    }

    /**
     * Test pencegahan bug intended /admin redirect saat pemohon login setelah admin logout.
     */
    public function test_pemohon_login_with_stale_admin_intended_url_is_redirected_to_portal(): void
    {
        // Simulasikan session memiliki intended URL ke /admin (sisa sesi admin sebelumnya)
        $response = $this->withSession(['url.intended' => 'http://127.0.0.1:8000/admin'])
            ->post('/login', [
                'email' => 'pemohon@sidawangi.id',
                'password' => 'password',
            ]);

        // Verifikasi bahwa pemohon TIDAK dilempar ke /admin (yang memicu 403), melainkan diarahkan ke /portal
        $response->assertRedirect('/portal');
        $this->assertNull(session('url.intended'));
    }

    /**
     * Test admin login tetap diarahkan ke /admin.
     */
    public function test_admin_login_is_redirected_to_admin_panel(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@sidawangi.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
    }

    /**
     * Test jika pemohon yang sudah login mencoba mengakses /admin langsung via URL,
     * ditolak dengan 403 Forbidden sesuai batasan akses role.
     */
    public function test_authenticated_pemohon_visiting_admin_is_forbidden(): void
    {
        $this->actingAs($this->pemohonUser);

        $response = $this->get('/admin');

        $response->assertStatus(403);
    }

    /**
     * Test logout filament membersihkan session url.intended.
     */
    public function test_filament_logout_clears_intended_url(): void
    {
        $this->actingAs($this->adminUser);
        session(['url.intended' => 'http://127.0.0.1:8000/admin/users']);

        $response = $this->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertNull(session('url.intended'));
    }
}
