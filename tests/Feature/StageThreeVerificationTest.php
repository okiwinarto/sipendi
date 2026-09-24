<?php

namespace Tests\Feature;

use App\Livewire\Portal\KalenderArmada;
use App\Livewire\Portal\PeminjamanForm;
use App\Livewire\Portal\RiwayatPeminjaman;
use App\Models\Booking;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class StageThreeVerificationTest extends TestCase
{
    protected User $pemohon;
    protected User $admin;
    protected Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pemohon = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();
        $this->admin = User::where('email', 'admin@sidawangi.id')->firstOrFail();
        $this->vehicle = Vehicle::firstOrFail();
    }

    /**
     * Verifikasi format penomoran registrasi SPD/{unit}/{tahun}{bulan}/{urut}
     */
    public function test_kode_peminjaman_format_generation(): void
    {
        $unitKerja = $this->pemohon->unitKerja;
        $this->assertNotNull($unitKerja);

        $kode1 = Booking::generateKodePeminjaman($unitKerja);
        $prefix = "SPD/{$unitKerja->kode_unit}/" . date('Ym') . '/';

        $this->assertStringStartsWith($prefix, $kode1);

        // Buat record booking nyata untuk menguji auto-increment
        $booking = Booking::create([
            'kode_peminjaman' => $kode1,
            'user_id' => $this->pemohon->id,
            'unit_kerja_id' => $unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Pengambilan Reagen Lab ke Dinkes Jabar',
            'kota_tujuan' => 'Bandung',
            'tanggal_berangkat' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'jam_kembali_rencana' => '17:00',
            'jumlah_penumpang' => 2,
            'tingkat_prioritas' => 'normal',
            'status' => 'diajukan',
        ]);

        $kode2 = Booking::generateKodePeminjaman($unitKerja);
        $this->assertNotEquals($kode1, $kode2);

        // Hapus test booking
        $booking->forceDelete();
    }

    /**
     * Verifikasi deteksi tumpang tindih jadwal peminjaman kendaraan (overlap detection).
     */
    public function test_schedule_overlap_detection(): void
    {
        $testDate = Carbon::now()->addDays(10)->format('Y-m-d');

        // Buat booking referensi
        $existing = Booking::create([
            'kode_peminjaman' => 'TEST-OVERLAP-001',
            'user_id' => $this->pemohon->id,
            'unit_kerja_id' => $this->pemohon->unit_kerja_id ?? 1,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Dinas Overlap Test',
            'kota_tujuan' => 'Cirebon',
            'tanggal_berangkat' => $testDate,
            'jam_berangkat' => '08:00:00',
            'tanggal_kembali_rencana' => $testDate,
            'jam_kembali_rencana' => '16:00:00',
            'jumlah_penumpang' => 3,
            'tingkat_prioritas' => 'normal',
            'status' => 'diajukan',
        ]);

        // 1. Cek rentang yang persis bertabrakan (10:00 - 14:00 di hari yang sama)
        $hasOverlap = Booking::checkOverlap($this->vehicle->id, $testDate, '10:00', $testDate, '14:00')->exists();
        $this->assertTrue($hasOverlap, 'Seharusnya mendeteksi bentrok jadwal pada jam yang bersinggungan.');

        // 2. Cek di hari berbeda (bebas bentrok)
        $anotherDate = Carbon::now()->addDays(11)->format('Y-m-d');
        $noOverlap = Booking::checkOverlap($this->vehicle->id, $anotherDate, '08:00', $anotherDate, '16:00')->exists();
        $this->assertFalse($noOverlap, 'Hari yang berbeda tidak boleh bentrok.');

        // 3. Jika booking dibatalkan, tidak boleh dianggap bentrok
        $existing->update(['status' => 'dibatalkan']);
        $afterCancel = Booking::checkOverlap($this->vehicle->id, $testDate, '10:00', $testDate, '14:00')->exists();
        $this->assertFalse($afterCancel, 'Peminjaman yang dibatalkan tidak boleh memicu bentrok.');

        $existing->forceDelete();
    }

    /**
     * Verifikasi proteksi rute portal pegawai (harus login).
     */
    public function test_portal_routes_require_authentication(): void
    {
        $this->get('/portal')->assertRedirect('/login');
        $this->get('/portal/ajukan')->assertRedirect('/login');
        $this->get('/portal/kalender')->assertRedirect('/login');
        $this->get('/portal/riwayat')->assertRedirect('/login');
    }

    /**
     * Verifikasi halaman login pegawai menampilkan form dan opsi 1-klik akun uji coba.
     */
    public function test_login_page_renders_with_test_credentials(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('MAS PENDI');
        $response->assertSee('Portal Masuk Pegawai');
        $response->assertSee('pemohon@sidawangi.id');
        $response->assertSee('admin@sidawangi.id');
    }

    /**
     * Verifikasi alur login pegawai dan pengalihan ke portal.
     */
    public function test_login_authentication_and_redirection(): void
    {
        // Login sebagai pemohon
        $response = $this->post('/login', [
            'email' => 'pemohon@sidawangi.id',
            'password' => 'password',
        ]);
        $response->assertRedirect('/portal');
        $this->assertAuthenticatedAs($this->pemohon);

        // Logout
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Verifikasi pengajuan peminjaman baru via komponen Livewire PeminjamanForm.
     */
    public function test_employee_can_submit_booking_via_livewire(): void
    {
        $berangkat = Carbon::now()->addDays(3)->format('Y-m-d');
        $kembali = Carbon::now()->addDays(3)->format('Y-m-d');

        Livewire::actingAs($this->pemohon)
            ->test(PeminjamanForm::class)
            ->set('tujuan_perjalanan', 'Pengantaran Sampel Uji Klinis ke Labkesda Provinsi Jawa Barat')
            ->set('kota_tujuan', 'Bandung')
            ->set('tanggal_berangkat', $berangkat)
            ->set('jam_berangkat', '07:30')
            ->set('tanggal_kembali_rencana', $kembali)
            ->set('jam_kembali_rencana', '18:00')
            ->set('jumlah_penumpang', 3)
            ->set('jenis_pengemudi', 'sopir_dinas')
            ->set('tingkat_prioritas', 'mendesak')
            ->set('preferred_vehicle_id', $this->vehicle->id)
            ->set('catatan_pemohon', 'Membawa box sampel medis steril pendingin.')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('portal.riwayat'));

        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->pemohon->id,
            'kota_tujuan' => 'Bandung',
            'tingkat_prioritas' => 'mendesak',
            'status' => 'diajukan',
        ]);

        // Bersihkan data tes
        Booking::where('user_id', $this->pemohon->id)
            ->where('kota_tujuan', 'Bandung')
            ->forceDelete();
    }

    /**
     * Verifikasi peringatan bentrok jadwal di formulir Livewire.
     */
    public function test_schedule_conflict_warning_in_livewire_form(): void
    {
        $testDate = Carbon::now()->addDays(7)->format('Y-m-d');

        // Buat booking yang menduduki kendaraan
        $booking = Booking::create([
            'kode_peminjaman' => 'TEST-CONFLICT-01',
            'user_id' => $this->pemohon->id,
            'unit_kerja_id' => $this->pemohon->unit_kerja_id ?? 1,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Jadwal Aktif',
            'kota_tujuan' => 'Jakarta',
            'tanggal_berangkat' => $testDate,
            'jam_berangkat' => '08:00:00',
            'tanggal_kembali_rencana' => $testDate,
            'jam_kembali_rencana' => '17:00:00',
            'jumlah_penumpang' => 2,
            'tingkat_prioritas' => 'normal',
            'status' => 'diajukan',
        ]);

        Livewire::actingAs($this->pemohon)
            ->test(PeminjamanForm::class)
            ->set('preferred_vehicle_id', $this->vehicle->id)
            ->set('tanggal_berangkat', $testDate)
            ->set('jam_berangkat', '09:00')
            ->set('tanggal_kembali_rencana', $testDate)
            ->set('jam_kembali_rencana', '14:00')
            ->assertSee('Potensi Bentrok Jadwal Terdeteksi');

        $booking->forceDelete();
    }

    /**
     * Verifikasi komponen Kalender Armada dapat dirender dan dinavigasikan.
     */
    public function test_kalender_armada_component_renders_and_navigates(): void
    {
        Livewire::actingAs($this->pemohon)
            ->test(KalenderArmada::class)
            ->assertStatus(200)
            ->assertSee('Kalender Ketersediaan Armada')
            ->call('nextMonth')
            ->assertStatus(200)
            ->call('prevMonth')
            ->assertStatus(200);
    }

    /**
     * Verifikasi pemohon dapat membatalkan pengajuannya sendiri yang masih berstatus 'diajukan'.
     */
    public function test_employee_can_cancel_own_pending_booking(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'TEST-CANCEL-001',
            'user_id' => $this->pemohon->id,
            'unit_kerja_id' => $this->pemohon->unit_kerja_id ?? 1,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Peminjaman Batal',
            'kota_tujuan' => 'Kuningan',
            'tanggal_berangkat' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'jam_kembali_rencana' => '12:00',
            'jumlah_penumpang' => 1,
            'tingkat_prioritas' => 'normal',
            'status' => 'diajukan',
        ]);

        Livewire::actingAs($this->pemohon)
            ->test(RiwayatPeminjaman::class)
            ->assertSee('TEST-CANCEL-001')
            ->call('cancelBooking', $booking->id);

        $booking->refresh();
        $this->assertEquals('dibatalkan', $booking->status);

        $booking->forceDelete();
    }

    /**
     * Verifikasi Filament BookingResource dapat diakses oleh Admin IT.
     */
    public function test_filament_booking_resource_accessible(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/bookings')
            ->assertStatus(200)
            ->assertSee('Peminjaman Kendaraan');
    }
}
