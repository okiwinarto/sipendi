<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class EasterEggTest extends TestCase
{
    /**
     * Test halaman rahasia /secret dapat diakses dan menampilkan identitas Hantu Laut.
     */
    public function test_secret_easter_egg_page_is_accessible(): void
    {
        $response = $this->get('/secret');

        $response->assertStatus(200);
        $response->assertSee('HANTU LAUT');
        $response->assertSee('who am i?');
        $response->assertSee('RSUD Sidawangi');
        $response->assertSee('https://vidfast.vc/movie/533535');
    }

    /**
     * Test trigger easter egg dan modal "who am i?" tersedia di landing page utama.
     */
    public function test_landing_page_footer_contains_easter_egg_trigger(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('easter-egg-trigger');
        $response->assertSee('who am i?');
        $response->assertSee('Tim IT RSUD Sidawangi');
    }

    /**
     * Test trigger easter egg dan modal tersedia di portal pegawai.
     */
    public function test_portal_page_footer_contains_easter_egg_trigger(): void
    {
        $pemohon = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();

        $response = $this->actingAs($pemohon)->get('/portal');

        $response->assertStatus(200);
        $response->assertSee('easter-egg-trigger');
        $response->assertSee('who am i?');
    }
}
