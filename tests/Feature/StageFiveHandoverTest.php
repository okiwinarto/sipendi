<?php

namespace Tests\Feature;

use App\Livewire\Portal\SerahTerima;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCheckin;
use App\Models\VehicleCheckout;
use App\Notifications\VehicleHandoverNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class StageFiveHandoverTest extends TestCase
{
    protected User $adminUser;
    protected User $garasiUser;
    protected User $pemohonUser;
    protected UnitKerja $unitKerja;
    protected Vehicle $vehicle;
    protected Driver $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('email', 'admin@sidawangi.id')->firstOrFail();
        $this->garasiUser = User::where('email', 'garasi@sidawangi.id')->firstOrFail();
        $this->pemohonUser = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();

        $this->unitKerja = $this->pemohonUser->unitKerja ?? UnitKerja::firstOrFail();
        $this->vehicle = Vehicle::firstOrFail();
        $this->driver = Driver::where('status', 'aktif')->firstOrFail();

        Booking::where('kode_peminjaman', 'like', 'SPD/TEST/%')->delete();
    }

    protected function tearDown(): void
    {
        Booking::where('kode_peminjaman', 'like', 'SPD/TEST/%')->delete();

        $avanza = Vehicle::where('no_polisi', 'E 1234 YX')->first();
        if ($avanza) {
            $avanza->update([
                'status' => 'tersedia',
                'odometer_terakhir' => 42500,
            ]);
        }

        parent::tearDown();
    }

    /**
     * 1. Verifikasi skema tabel dan relasi model VehicleCheckout & VehicleCheckin.
     */
    public function test_vehicle_checkout_and_checkin_relationships(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/5001',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Uji Coba Handover',
            'kota_tujuan' => 'Cirebon',
            'tanggal_berangkat' => '2026-10-15',
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => '2026-10-15',
            'jam_kembali_rencana' => '17:00',
            'jumlah_penumpang' => 2,
            'status' => 'kendaraan_keluar',
        ]);

        $checkout = VehicleCheckout::create([
            'booking_id' => $booking->id,
            'petugas_id' => $this->garasiUser->id,
            'odometer_keluar' => 50000,
            'level_bbm_keluar' => 'F',
            'kondisi_kendaraan' => 'baik',
            'checklist_kelengkapan' => ['ban_serep' => true, 'dongkrak' => true],
            'waktu_keluar' => now(),
        ]);

        $checkin = VehicleCheckin::create([
            'booking_id' => $booking->id,
            'petugas_id' => $this->garasiUser->id,
            'odometer_masuk' => 50120,
            'level_bbm_masuk' => '3/4',
            'kondisi_kendaraan' => 'baik',
            'ada_kerusakan' => false,
            'checklist_kelengkapan' => ['ban_serep' => true, 'dongkrak' => true],
            'rating_kondisi' => 5,
            'waktu_masuk' => now(),
        ]);

        $this->assertInstanceOf(VehicleCheckout::class, $booking->checkout);
        $this->assertInstanceOf(VehicleCheckin::class, $booking->checkin);
        $this->assertEquals(50000, $booking->checkout->odometer_keluar);
        $this->assertEquals(50120, $booking->checkin->odometer_masuk);
        $this->assertEquals(120, $booking->checkin->jarak_tempuh);

        $booking->delete();
    }

    /**
     * 2. Pengguna biasa (user_aplikasi) dilarang mengakses halaman serah terima (403).
     */
    public function test_unauthorized_user_cannot_access_serah_terima(): void
    {
        $this->actingAs($this->pemohonUser)
            ->get('/portal/serah-terima')
            ->assertStatus(403);
    }

    /**
     * 3. Petugas garasi dapat melakukan Serah Terima Keluar (Checkout) pada booking disetujui.
     */
    public function test_officer_can_perform_vehicle_checkout(): void
    {
        Notification::fake();

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/5002',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Pengantaran Berkas Dinkes',
            'kota_tujuan' => 'Sumber',
            'tanggal_berangkat' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'jam_kembali_rencana' => '16:00',
            'jumlah_penumpang' => 2,
            'status' => 'disetujui',
        ]);

        $baselineOdo = $this->vehicle->odometer_terakhir ?? 10000;
        $odoOut = $baselineOdo + 10;

        Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckoutModal', $booking->id)
            ->set('odometer_keluar', $odoOut)
            ->set('level_bbm_keluar', 'F')
            ->set('kondisi_kendaraan_keluar', 'baik')
            ->set('checklist_ban_serep', true)
            ->set('checklist_dongkrak', true)
            ->set('catatan_keluar', 'Kendaraan bersih dan siap pakai.')
            ->call('prosesCheckout')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->vehicle->refresh();

        $this->assertEquals('kendaraan_keluar', $booking->status);
        $this->assertEquals('dipinjam', $this->vehicle->status);
        $this->assertNotNull($booking->checkout);
        $this->assertEquals($odoOut, $booking->checkout->odometer_keluar);

        Notification::assertSentTo(
            $this->pemohonUser,
            VehicleHandoverNotification::class
        );

        $booking->delete();
    }

    /**
     * 4. Checkout gagal jika odometer keluar lebih rendah dari odometer terakhir kendaraan.
     */
    public function test_checkout_fails_if_odometer_lower_than_vehicle_baseline(): void
    {
        $this->vehicle->update(['odometer_terakhir' => 45000]);

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/5003',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Dinas Cek Odo Rendah',
            'kota_tujuan' => 'Cirebon',
            'tanggal_berangkat' => Carbon::now()->addDays(6)->format('Y-m-d'),
            'jam_berangkat' => '09:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(6)->format('Y-m-d'),
            'jam_kembali_rencana' => '13:00',
            'jumlah_penumpang' => 1,
            'status' => 'disetujui',
        ]);

        Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckoutModal', $booking->id)
            ->set('odometer_keluar', 44000) // lebih rendah dari 45000
            ->call('prosesCheckout')
            ->assertHasErrors(['odometer_keluar']);

        $booking->delete();
    }

    /**
     * 5. Petugas garasi dapat melakukan Serah Terima Masuk (Checkin) dan memperbarui odometer kendaraan.
     */
    public function test_officer_can_perform_vehicle_checkin(): void
    {
        Notification::fake();

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/5004',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Dinas Checkin Test',
            'kota_tujuan' => 'Kuningan',
            'tanggal_berangkat' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'jam_kembali_rencana' => '17:00',
            'jumlah_penumpang' => 1,
            'status' => 'kendaraan_keluar',
        ]);

        VehicleCheckout::create([
            'booking_id' => $booking->id,
            'petugas_id' => $this->garasiUser->id,
            'odometer_keluar' => 52000,
            'level_bbm_keluar' => 'F',
            'kondisi_kendaraan' => 'baik',
            'checklist_kelengkapan' => ['ban_serep' => true],
            'waktu_keluar' => now(),
        ]);

        Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckinModal', $booking->id)
            ->set('odometer_masuk', 52180) // jarak 180 km
            ->set('level_bbm_masuk', '1/2')
            ->set('kondisi_kendaraan_masuk', 'baik')
            ->set('ada_kerusakan', false)
            ->set('rating_kondisi', 5)
            ->call('prosesCheckin')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->vehicle->refresh();

        $this->assertEquals('selesai', $booking->status);
        $this->assertEquals('tersedia', $this->vehicle->status);
        $this->assertEquals(52180, $this->vehicle->odometer_terakhir);
        $this->assertNotNull($booking->checkin);
        $this->assertEquals(180, $booking->checkin->jarak_tempuh);

        Notification::assertSentTo(
            $this->pemohonUser,
            VehicleHandoverNotification::class
        );

        $booking->delete();
    }

    /**
     * 6. Checkin gagal jika odometer masuk lebih kecil dari odometer keluar.
     */
    public function test_checkin_fails_if_odometer_masuk_lower_than_odometer_keluar(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/5005',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Dinas Validasi Odo Masuk',
            'kota_tujuan' => 'Cirebon',
            'tanggal_berangkat' => Carbon::now()->addDays(8)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(8)->format('Y-m-d'),
            'jam_kembali_rencana' => '17:00',
            'jumlah_penumpang' => 1,
            'status' => 'kendaraan_keluar',
        ]);

        VehicleCheckout::create([
            'booking_id' => $booking->id,
            'petugas_id' => $this->garasiUser->id,
            'odometer_keluar' => 60000,
            'level_bbm_keluar' => 'F',
            'kondisi_kendaraan' => 'baik',
            'checklist_kelengkapan' => ['ban_serep' => true],
            'waktu_keluar' => now(),
        ]);

        Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckinModal', $booking->id)
            ->set('odometer_masuk', 59900) // lebih kecil dari 60000
            ->call('prosesCheckin')
            ->assertHasErrors(['odometer_masuk']);

        $booking->delete();
    }

    /**
     * 7. Jika ada kerusakan dilaporkan, deskripsi wajib diisi dan armada disetel ke status perlu_perhatian.
     */
    public function test_checkin_with_damage_updates_vehicle_to_perlu_perhatian(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/5006',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Dinas Kerusakan Test',
            'kota_tujuan' => 'Indramayu',
            'tanggal_berangkat' => Carbon::now()->addDays(9)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(9)->format('Y-m-d'),
            'jam_kembali_rencana' => '17:00',
            'jumlah_penumpang' => 1,
            'status' => 'kendaraan_keluar',
        ]);

        VehicleCheckout::create([
            'booking_id' => $booking->id,
            'petugas_id' => $this->garasiUser->id,
            'odometer_keluar' => 70000,
            'level_bbm_keluar' => 'F',
            'kondisi_kendaraan' => 'baik',
            'checklist_kelengkapan' => ['ban_serep' => true],
            'waktu_keluar' => now(),
        ]);

        // Coba submit ada_kerusakan true tanpa deskripsi -> harus gagal
        Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckinModal', $booking->id)
            ->set('odometer_masuk', 70150)
            ->set('ada_kerusakan', true)
            ->set('deskripsi_kerusakan', '')
            ->call('prosesCheckin')
            ->assertHasErrors(['deskripsi_kerusakan']);

        // Submit dengan deskripsi valid
        Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckinModal', $booking->id)
            ->set('odometer_masuk', 70150)
            ->set('ada_kerusakan', true)
            ->set('deskripsi_kerusakan', 'Bumper kanan depan baret terkena ranting pohon.')
            ->set('rating_kondisi', 3)
            ->call('prosesCheckin')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->vehicle->refresh();

        $this->assertEquals('selesai', $booking->status);
        $this->assertEquals('perlu_perhatian', $this->vehicle->status);
        $booking->delete();
    }

    /**
     * 8. Verifikasi pencatatan dan acuan waktu checkin dan checkout menggunakan zona waktu Asia/Jakarta (WIB).
     */
    public function test_checkout_and_checkin_use_asia_jakarta_timezone(): void
    {
        $this->assertEquals('Asia/Jakarta', config('app.timezone'));

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/5007',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Dinas Uji Waktu Jakarta',
            'kota_tujuan' => 'Bandung',
            'tanggal_berangkat' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'jam_kembali_rencana' => '17:00',
            'jumlah_penumpang' => 2,
            'status' => 'disetujui',
        ]);

        $jakartaNow = Carbon::now('Asia/Jakarta');

        // Test Checkout
        $checkoutComponent = Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckoutModal', $booking->id)
            ->assertSet('showCheckoutModal', true);

        $waktuKeluarVal = $checkoutComponent->get('waktu_keluar');
        $this->assertNotEmpty($waktuKeluarVal);
        $this->assertEquals($jakartaNow->format('Y-m-d\TH:i'), $waktuKeluarVal);

        $checkoutComponent
            ->set('odometer_keluar', 43000)
            ->call('prosesCheckout')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('kendaraan_keluar', $booking->status);
        $this->assertNotNull($booking->checkout);
        $this->assertEquals('Asia/Jakarta', $booking->checkout->waktu_keluar->timezoneName);

        // Test Checkin
        $checkinComponent = Livewire::actingAs($this->garasiUser)
            ->test(SerahTerima::class)
            ->call('openCheckinModal', $booking->id)
            ->assertSet('showCheckinModal', true);

        $waktuMasukVal = $checkinComponent->get('waktu_masuk');
        $this->assertNotEmpty($waktuMasukVal);
        $this->assertEquals($jakartaNow->format('Y-m-d\TH:i'), $waktuMasukVal);

        $checkinComponent
            ->set('odometer_masuk', 43120)
            ->call('prosesCheckin')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('selesai', $booking->status);
        $this->assertNotNull($booking->checkin);
        $this->assertEquals('Asia/Jakarta', $booking->checkin->waktu_masuk->timezoneName);

        $booking->delete();
    }
}
