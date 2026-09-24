<?php

namespace Tests\Feature;

use App\Livewire\Portal\KalenderArmada;
use App\Livewire\Portal\PeminjamanForm;
use App\Livewire\Portal\PersetujuanPimpinan;
use App\Livewire\Portal\RiwayatPeminjaman;
use App\Livewire\Portal\SerahTerima;
use App\Livewire\Portal\VerifikasiGarasi;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class StageEightUATVerificationTest extends TestCase
{
    protected User $adminUser;
    protected User $garasiUser;
    protected User $pimpinanUser;
    protected User $pemohonUser;
    protected UnitKerja $unitKerja;
    protected Vehicle $vehicle;
    protected Driver $driver;

    protected function setUp(): void
    {
        parent::setUp();

        // Akun-akun ter-seed
        $this->adminUser = User::where('email', 'admin@sidawangi.id')->firstOrFail();
        $this->garasiUser = User::where('email', 'garasi@sidawangi.id')->firstOrFail();
        $this->pimpinanUser = User::where('email', 'pimpinan@sidawangi.id')->firstOrFail();
        $this->pemohonUser = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();

        $this->unitKerja = $this->pemohonUser->unitKerja ?? UnitKerja::firstOrFail();
        $this->vehicle = Vehicle::where('status', 'tersedia')->firstOrFail();
        $this->vehicle->update([
            'status' => 'tersedia',
            'odometer_terakhir' => 42500,
        ]);
        $this->vehicle->refresh();
        $this->driver = Driver::where('status', 'aktif')->firstOrFail();

        // Bersihkan data tes Tahap 8
        Booking::where('kode_peminjaman', 'like', 'SPD/UAT8/%')
            ->orWhere('tujuan_perjalanan', 'like', '%Kunjungan Koordinasi Dinas Kesehatan Jabar%')
            ->delete();
    }

    protected function tearDown(): void
    {
        Booking::where('kode_peminjaman', 'like', 'SPD/UAT8/%')
            ->orWhere('tujuan_perjalanan', 'like', '%Kunjungan Koordinasi Dinas Kesehatan Jabar%')
            ->delete();

        // Pastikan armada kembali tersedia
        Vehicle::where('id', $this->vehicle->id)->update([
            'status' => 'tersedia',
            'odometer_terakhir' => 42500,
        ]);

        parent::tearDown();
    }

    /**
     * UAT Skenario 1 (Happy Path Lengkap):
     * Pengajuan oleh Pemohon -> Verifikasi Garasi -> Persetujuan Pimpinan -> Checkout BAST Keluar -> Checkin BAST Masuk.
     */
    public function test_uat_full_lifecycle_happy_path(): void
    {
        $today = Carbon::today()->addDays(10)->format('Y-m-d');
        $tomorrow = Carbon::today()->addDays(11)->format('Y-m-d');

        // 1. Pemohon mengajukan peminjaman
        $this->actingAs($this->pemohonUser);

        Livewire::test(PeminjamanForm::class)
            ->set('tujuan_perjalanan', 'Kunjungan Koordinasi Dinas Kesehatan Jabar')
            ->set('kota_tujuan', 'Bandung')
            ->set('tanggal_berangkat', $today)
            ->set('jam_berangkat', '08:00')
            ->set('tanggal_kembali_rencana', $tomorrow)
            ->set('jam_kembali_rencana', '17:00')
            ->set('jumlah_penumpang', 4)
            ->set('jenis_pengemudi', 'sopir_dinas')
            ->set('tingkat_prioritas', 'normal')
            ->set('catatan_pemohon', 'Perjalanan dinas koordinasi obat')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('portal.riwayat'));

        $booking = Booking::where('user_id', $this->pemohonUser->id)
            ->where('tujuan_perjalanan', 'Kunjungan Koordinasi Dinas Kesehatan Jabar')
            ->latest()
            ->firstOrFail();

        $this->assertEquals('diajukan', $booking->status);

        // 2. Kepala Garasi memverifikasi & menetapkan mobil dan sopir
        $this->actingAs($this->garasiUser);

        Livewire::test(VerifikasiGarasi::class)
            ->call('openVerifyModal', $booking->id)
            ->set('vehicle_id', $this->vehicle->id)
            ->set('driver_id', $this->driver->id)
            ->set('catatan_garasi', 'Armada siap laik jalan, BBM penuh, sopir siap tugas')
            ->call('verifikasiDanTeruskan')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('diverifikasi_garasi', $booking->status);
        $this->assertEquals($this->vehicle->id, $booking->vehicle_id);
        $this->assertEquals($this->driver->id, $booking->driver_id);

        // 3. Pimpinan menyetujui pengajuan
        $this->actingAs($this->pimpinanUser);

        Livewire::test(PersetujuanPimpinan::class)
            ->call('setujui', $booking->id)
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('disetujui', $booking->status);

        // 4. Petugas Garasi melakukan Checkout (Kendaraan Keluar)
        $this->actingAs($this->garasiUser);

        Livewire::test(SerahTerima::class)
            ->call('openCheckoutModal', $booking->id)
            ->set('odometer_keluar', 42500)
            ->set('level_bbm_keluar', 'F')
            ->set('catatan_keluar', 'Kelengkapan dan ban serep terverifikasi lengkap')
            ->call('prosesCheckout')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->vehicle->refresh();
        $this->assertEquals('kendaraan_keluar', $booking->status);
        $this->assertEquals('dipinjam', $this->vehicle->status);
        $this->assertNotNull($booking->checkout);
        $this->assertEquals(42500, $booking->checkout->odometer_keluar);

        // 5. Petugas Garasi melakukan Checkin (Kendaraan Kembali)
        Livewire::test(SerahTerima::class)
            ->call('openCheckinModal', $booking->id)
            ->set('odometer_masuk', 42850) // Selisih 350 KM
            ->set('level_bbm_masuk', '3/4')
            ->set('kondisi_kendaraan_masuk', 'baik')
            ->set('ada_kerusakan', false)
            ->set('rating_kondisi', 5)
            ->set('catatan_masuk', 'Kendaraan kembali dalam kondisi bersih dan terawat')
            ->call('prosesCheckin')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->vehicle->refresh();
        $this->assertEquals('selesai', $booking->status);
        $this->assertEquals('tersedia', $this->vehicle->status);
        $this->assertEquals(42850, $this->vehicle->odometer_terakhir);
        $this->assertNotNull($booking->checkin);
        $this->assertEquals(42850, $booking->checkin->odometer_masuk);
    }

    /**
     * UAT Skenario 2:
     * Penolakan oleh Kepala Garasi (alasan penolakan tercatat dan terlihat oleh pemohon).
     */
    public function test_uat_rejection_by_kepala_garasi(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/UAT8/202609/8002',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'tujuan_perjalanan' => 'Dinas Lapangan Uji Penolakan Garasi',
            'kota_tujuan' => 'Kuningan',
            'tanggal_berangkat' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_berangkat' => '09:00',
            'tanggal_kembali_rencana' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_kembali_rencana' => '15:00',
            'jumlah_penumpang' => 2,
            'jenis_pengemudi' => 'sopir_dinas',
            'status' => 'diajukan',
        ]);

        $this->actingAs($this->garasiUser);

        Livewire::test(VerifikasiGarasi::class)
            ->call('openRejectModal', $booking->id)
            ->set('alasan_penolakan', 'Seluruh sopir dinas sedang bertugas di luar kota')
            ->call('tolakPengajuan')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('ditolak_garasi', $booking->status);
        $this->assertEquals('Seluruh sopir dinas sedang bertugas di luar kota', $booking->alasan_penolakan);

        // Pastikan Pemohon melihat status dan alasan penolakan di riwayat
        $this->actingAs($this->pemohonUser);
        Livewire::test(RiwayatPeminjaman::class)
            ->assertSee('Ditolak Garasi')
            ->assertSee('Seluruh sopir dinas sedang bertugas di luar kota');
    }

    /**
     * UAT Skenario 3:
     * Penolakan oleh Pimpinan Direksi (setelah lolos verifikasi teknis garasi).
     */
    public function test_uat_rejection_by_pimpinan(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/UAT8/202609/8003',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'tujuan_perjalanan' => 'Seminar Luar Kota Uji Penolakan Pimpinan',
            'kota_tujuan' => 'Jakarta',
            'tanggal_berangkat' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_berangkat' => '06:00',
            'tanggal_kembali_rencana' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
            'jam_kembali_rencana' => '18:00',
            'jumlah_penumpang' => 3,
            'jenis_pengemudi' => 'sopir_dinas',
            'status' => 'diverifikasi_garasi',
        ]);

        $this->actingAs($this->pimpinanUser);

        Livewire::test(PersetujuanPimpinan::class)
            ->call('openRejectModal', $booking->id)
            ->set('alasan_penolakan', 'Dinas dapat diwakilkan melalui pertemuan daring (Zoom meeting)')
            ->call('tolakPengajuan')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('ditolak_pimpinan', $booking->status);
        $this->assertEquals('Dinas dapat diwakilkan melalui pertemuan daring (Zoom meeting)', $booking->alasan_penolakan);
    }

    /**
     * UAT Skenario 4:
     * Pembatalan mandiri oleh Pemohon sebelum proses verifikasi.
     */
    public function test_uat_self_cancellation_by_applicant(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/UAT8/202609/8004',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'tujuan_perjalanan' => 'Pengujian Fitur Pembatalan Mandiri',
            'kota_tujuan' => 'Cirebon',
            'tanggal_berangkat' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_berangkat' => '10:00',
            'tanggal_kembali_rencana' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_kembali_rencana' => '16:00',
            'jumlah_penumpang' => 1,
            'jenis_pengemudi' => 'swakemudi',
            'status' => 'diajukan',
        ]);

        $this->actingAs($this->pemohonUser);

        Livewire::test(RiwayatPeminjaman::class)
            ->call('cancelBooking', $booking->id);

        $booking->refresh();
        $this->assertEquals('dibatalkan', $booking->status);
        $this->assertStringContainsString('Dibatalkan oleh pemohon', $booking->alasan_penolakan);
    }

    /**
     * UAT Skenario 5:
     * Validasi tanggal pengajuan tidak boleh tanggal lampau (Hanya hari ini ke depan).
     */
    public function test_uat_past_date_submission_is_rejected(): void
    {
        $this->actingAs($this->pemohonUser);

        Livewire::test(PeminjamanForm::class)
            ->set('tujuan_perjalanan', 'Pengajuan Tanggal Lampau')
            ->set('kota_tujuan', 'Majalengka')
            ->set('tanggal_berangkat', Carbon::yesterday()->format('Y-m-d'))
            ->set('jam_berangkat', '08:00')
            ->set('tanggal_kembali_rencana', Carbon::today()->format('Y-m-d'))
            ->set('jam_kembali_rencana', '17:00')
            ->set('jumlah_penumpang', 2)
            ->set('jenis_pengemudi', 'swakemudi')
            ->set('tingkat_prioritas', 'normal')
            ->call('submit')
            ->assertHasErrors(['tanggal_berangkat']);
    }

    /**
     * UAT Skenario 6:
     * Pengerasan Keamanan (Security Authorization & RBAC).
     */
    public function test_uat_security_and_authorization_guards(): void
    {
        // 1. Pemohon biasa tidak boleh mengakses halaman Verifikasi Garasi
        $this->actingAs($this->pemohonUser);
        $response = $this->get(route('portal.verifikasi'));
        $response->assertStatus(403);

        // 2. Pemohon biasa tidak boleh mengakses halaman Persetujuan Pimpinan
        $response = $this->get(route('portal.persetujuan'));
        $response->assertStatus(403);

        // 3. Pemohon biasa tidak boleh mengakses modul Serah Terima Digital
        $response = $this->get(route('portal.serah-terima'));
        $response->assertStatus(403);

        // 4. Guest / Tanpa login harus diarahkan ke halaman login
        auth()->logout();
        $guestResponse = $this->get(route('portal.index'));
        $guestResponse->assertRedirect(route('login'));
    }

    /**
     * UAT Skenario 7:
     * Komponen Kalender Armada menampilkan kendaraan dinas secara akurat.
     */
    public function test_uat_calendar_renders_fleet_schedule(): void
    {
        $this->actingAs($this->pemohonUser);

        Livewire::test(KalenderArmada::class)
            ->assertSuccessful()
            ->assertSee($this->vehicle->plat_nomor);
    }
}
