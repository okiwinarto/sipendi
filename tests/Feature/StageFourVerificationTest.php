<?php

namespace Tests\Feature;

use App\Livewire\Portal\PeminjamanForm;
use App\Livewire\Portal\PersetujuanPimpinan;
use App\Livewire\Portal\VerifikasiGarasi;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\Garasi;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\BookingDecidedNotification;
use App\Notifications\BookingSubmittedNotification;
use App\Notifications\BookingVerifiedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class StageFourVerificationTest extends TestCase
{
    protected User $adminUser;
    protected User $garasiUser;
    protected User $pimpinanUser;
    protected User $pemohonUser;
    protected UnitKerja $unitKerja;
    protected Vehicle $vehicleAvanza;
    protected Vehicle $vehicleInnova;
    protected Driver $driverAsep;

    protected function setUp(): void
    {
        parent::setUp();

        // Ambil akun pengguna ter-seed
        $this->adminUser = User::where('email', 'admin@sidawangi.id')->firstOrFail();
        $this->garasiUser = User::where('email', 'garasi@sidawangi.id')->firstOrFail();
        $this->pimpinanUser = User::where('email', 'pimpinan@sidawangi.id')->firstOrFail();
        $this->pemohonUser = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();

        $this->unitKerja = $this->pemohonUser->unitKerja ?? UnitKerja::firstOrFail();

        // Ambil kendaraan dan sopir
        $this->vehicleAvanza = Vehicle::where('status', 'tersedia')->firstOrFail();
        $this->vehicleInnova = Vehicle::where('id', '!=', $this->vehicleAvanza->id)->firstOrFail();
        $this->driverAsep = Driver::where('status', 'aktif')->firstOrFail();
    }

    /**
     * 1. Verifikasi skema tabel dan relasi model BookingApproval.
     */
    public function test_booking_approval_relationships_and_model_helpers(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4001',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Dinkes Kab Cirebon',
            'kota_tujuan' => 'Sumber',
            'tanggal_berangkat' => '2026-10-01',
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => '2026-10-01',
            'jam_kembali_rencana' => '14:00',
            'jumlah_penumpang' => 3,
            'status' => 'diajukan',
        ]);

        $approval = BookingApproval::create([
            'booking_id' => $booking->id,
            'approver_id' => $this->garasiUser->id,
            'role_approval' => 'kepala_garasi',
            'tindakan' => 'setuju',
            'catatan' => 'Unit dan sopir siap beroperasi.',
            'waktu_tindakan' => now(),
        ]);

        $this->assertInstanceOf(Booking::class, $approval->booking);
        $this->assertInstanceOf(User::class, $approval->approver);
        $this->assertEquals($this->garasiUser->id, $approval->approver->id);

        $this->assertTrue($booking->approvals->contains($approval));
        $this->assertNotNull($booking->garasiApproval);
        $this->assertEquals('setuju', $booking->garasiApproval->tindakan);
        $this->assertNull($booking->pimpinanApproval);

        $booking->delete();
    }

    /**
     * 2. Pengajuan baru otomatis mengirim notifikasi ke Kepala Garasi.
     */
    public function test_submission_triggers_notification_to_kepala_garasi(): void
    {
        Notification::fake();

        Livewire::actingAs($this->pemohonUser)
            ->test(PeminjamanForm::class)
            ->set('tujuan_perjalanan', 'Pengambilan vaksin rutin')
            ->set('kota_tujuan', 'Bandung')
            ->set('tanggal_berangkat', Carbon::now()->addDays(20)->format('Y-m-d'))
            ->set('jam_berangkat', '07:00')
            ->set('tanggal_kembali_rencana', Carbon::now()->addDays(20)->format('Y-m-d'))
            ->set('jam_kembali_rencana', '18:00')
            ->set('jumlah_penumpang', 2)
            ->set('jenis_pengemudi', 'sopir_dinas')
            ->set('tingkat_prioritas', 'mendesak')
            ->call('submit')
            ->assertRedirect(route('portal.riwayat'));

        Notification::assertSentTo(
            $this->garasiUser,
            BookingSubmittedNotification::class
        );
    }

    /**
     * 3. Pengguna biasa (user_aplikasi) tidak dapat mengakses verifikasi garasi atau persetujuan pimpinan.
     */
    public function test_unauthorized_user_cannot_access_verification_pages(): void
    {
        $this->actingAs($this->pemohonUser)
            ->get('/portal/verifikasi')
            ->assertStatus(403);

        $this->actingAs($this->pemohonUser)
            ->get('/portal/persetujuan')
            ->assertStatus(403);
    }

    /**
     * 4. Kepala Garasi dapat memverifikasi teknis, menetapkan armada definitif & sopir, dan meneruskan ke Pimpinan.
     */
    public function test_kepala_garasi_can_verify_and_assign_fleet(): void
    {
        Notification::fake();

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4002',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Rapat Koordinasi Dinkes',
            'kota_tujuan' => 'Cirebon',
            'tanggal_berangkat' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'jam_kembali_rencana' => '15:00',
            'jumlah_penumpang' => 4,
            'status' => 'diajukan',
        ]);

        Livewire::actingAs($this->garasiUser)
            ->test(VerifikasiGarasi::class)
            ->call('openVerifyModal', $booking->id)
            ->set('vehicle_id', $this->vehicleAvanza->id)
            ->set('driver_id', $this->driverAsep->id)
            ->set('catatan_garasi', 'Avanza telah dicek oli dan tekanan ban.')
            ->call('verifikasiDanTeruskan')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('diverifikasi_garasi', $booking->status);
        $this->assertEquals($this->vehicleAvanza->id, $booking->vehicle_id);
        $this->assertEquals($this->driverAsep->id, $booking->driver_id);

        $this->assertDatabaseHas('booking_approvals', [
            'booking_id' => $booking->id,
            'approver_id' => $this->garasiUser->id,
            'role_approval' => 'kepala_garasi',
            'tindakan' => 'setuju',
        ]);

        Notification::assertSentTo(
            $this->pimpinanUser,
            BookingVerifiedNotification::class
        );

        $booking->delete();
    }

    /**
     * 5. Penolakan oleh Kepala Garasi wajib menyertakan alasan penolakan.
     */
    public function test_kepala_garasi_rejection_requires_reason(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4003',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Distribusi Dokumen',
            'kota_tujuan' => 'Kuningan',
            'tanggal_berangkat' => Carbon::now()->addDays(26)->format('Y-m-d'),
            'jam_berangkat' => '09:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(26)->format('Y-m-d'),
            'jam_kembali_rencana' => '12:00',
            'jumlah_penumpang' => 1,
            'status' => 'diajukan',
        ]);

        // Coba tolak tanpa alasan -> harus gagal validasi
        Livewire::actingAs($this->garasiUser)
            ->test(VerifikasiGarasi::class)
            ->call('openRejectModal', $booking->id)
            ->set('alasan_penolakan', '')
            ->call('tolakPengajuan')
            ->assertHasErrors(['alasan_penolakan' => 'required']);

        // Tolak dengan alasan valid
        Livewire::actingAs($this->garasiUser)
            ->test(VerifikasiGarasi::class)
            ->call('openRejectModal', $booking->id)
            ->set('alasan_penolakan', 'Seluruh unit kendaraan sedang dalam jadwal servis rem.')
            ->call('tolakPengajuan')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('ditolak_garasi', $booking->status);
        $this->assertEquals('Seluruh unit kendaraan sedang dalam jadwal servis rem.', $booking->alasan_penolakan);

        $this->assertDatabaseHas('booking_approvals', [
            'booking_id' => $booking->id,
            'approver_id' => $this->garasiUser->id,
            'role_approval' => 'kepala_garasi',
            'tindakan' => 'tolak',
        ]);

        $booking->delete();
    }

    /**
     * 6. Validasi bentrok armada mencegah penetapan kendaraan yang sudah memiliki jadwal lain.
     */
    public function test_garasi_cannot_assign_conflicting_vehicle(): void
    {
        $testDate = Carbon::now()->addDays(28)->format('Y-m-d');

        // Buat booking 1 yang sudah aktif memegang Avanza
        $booking1 = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4004',
            'user_id' => $this->adminUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicleAvanza->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Tugas Luar 1',
            'kota_tujuan' => 'Majalengka',
            'tanggal_berangkat' => $testDate,
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => $testDate,
            'jam_kembali_rencana' => '14:00',
            'jumlah_penumpang' => 2,
            'status' => 'disetujui',
        ]);

        // Buat booking 2 yang bentrok di tanggal & jam yang sama
        $booking2 = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4005',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Tugas Luar 2',
            'kota_tujuan' => 'Majalengka',
            'tanggal_berangkat' => $testDate,
            'jam_berangkat' => '10:00',
            'tanggal_kembali_rencana' => $testDate,
            'jam_kembali_rencana' => '16:00',
            'jumlah_penumpang' => 2,
            'status' => 'diajukan',
        ]);

        Livewire::actingAs($this->garasiUser)
            ->test(VerifikasiGarasi::class)
            ->call('openVerifyModal', $booking2->id)
            ->set('vehicle_id', $this->vehicleAvanza->id)
            ->call('verifikasiDanTeruskan')
            ->assertHasErrors(['vehicle_id']);

        $booking2->refresh();
        $this->assertEquals('diajukan', $booking2->status);

        $booking1->delete();
        $booking2->delete();
    }

    /**
     * 7. Pimpinan dapat menyetujui pengajuan berstatus diverifikasi_garasi dalam 1-klik.
     */
    public function test_pimpinan_can_approve_booking(): void
    {
        Notification::fake();

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4006',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicleInnova->id,
            'driver_id' => $this->driverAsep->id,
            'jenis_pengemudi' => 'sopir_dinas',
            'tujuan_perjalanan' => 'Kunjungan Kerja Kemenkes',
            'kota_tujuan' => 'Jakarta',
            'tanggal_berangkat' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'jam_berangkat' => '06:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(31)->format('Y-m-d'),
            'jam_kembali_rencana' => '20:00',
            'jumlah_penumpang' => 5,
            'status' => 'diverifikasi_garasi',
        ]);

        Livewire::actingAs($this->pimpinanUser)
            ->test(PersetujuanPimpinan::class)
            ->call('setujui', $booking->id);

        $booking->refresh();
        $this->assertEquals('disetujui', $booking->status);

        $this->assertDatabaseHas('booking_approvals', [
            'booking_id' => $booking->id,
            'approver_id' => $this->pimpinanUser->id,
            'role_approval' => 'pimpinan',
            'tindakan' => 'setuju',
        ]);

        Notification::assertSentTo(
            $this->pemohonUser,
            BookingDecidedNotification::class
        );

        $booking->delete();
    }

    /**
     * 8. Penolakan oleh Pimpinan wajib mengisi alasan penolakan dan mengubah status menjadi ditolak_pimpinan.
     */
    public function test_pimpinan_rejection_requires_reason(): void
    {
        Notification::fake();

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4007',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicleAvanza->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Pelatihan Eksternal',
            'kota_tujuan' => 'Semarang',
            'tanggal_berangkat' => Carbon::now()->addDays(35)->format('Y-m-d'),
            'jam_berangkat' => '07:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(36)->format('Y-m-d'),
            'jam_kembali_rencana' => '18:00',
            'jumlah_penumpang' => 2,
            'status' => 'diverifikasi_garasi',
        ]);

        // Penolakan tanpa alasan harus ditolak
        Livewire::actingAs($this->pimpinanUser)
            ->test(PersetujuanPimpinan::class)
            ->call('openRejectModal', $booking->id)
            ->set('alasan_penolakan', '')
            ->call('tolakPengajuan')
            ->assertHasErrors(['alasan_penolakan' => 'required']);

        // Penolakan dengan alasan
        Livewire::actingAs($this->pimpinanUser)
            ->test(PersetujuanPimpinan::class)
            ->call('openRejectModal', $booking->id)
            ->set('alasan_penolakan', 'Perjalanan luar kota ditangguhkan sementara karena efisiensi operasional.')
            ->call('tolakPengajuan')
            ->assertHasNoErrors();

        $booking->refresh();
        $this->assertEquals('ditolak_pimpinan', $booking->status);
        $this->assertEquals('Perjalanan luar kota ditangguhkan sementara karena efisiensi operasional.', $booking->alasan_penolakan);

        $this->assertDatabaseHas('booking_approvals', [
            'booking_id' => $booking->id,
            'approver_id' => $this->pimpinanUser->id,
            'role_approval' => 'pimpinan',
            'tindakan' => 'tolak',
        ]);

        Notification::assertSentTo(
            $this->pemohonUser,
            BookingDecidedNotification::class
        );

        $booking->delete();
    }

    /**
     * 9. Pengujian fitur tandai semua notifikasi in-app telah dibaca (mark all as read).
     */
    public function test_mark_all_notifications_as_read(): void
    {
        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST/202609/4008',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Pengantaran sample lab',
            'kota_tujuan' => 'Cirebon',
            'tanggal_berangkat' => Carbon::now()->addDays(40)->format('Y-m-d'),
            'jam_berangkat' => '09:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(40)->format('Y-m-d'),
            'jam_kembali_rencana' => '12:00',
            'jumlah_penumpang' => 1,
            'status' => 'diajukan',
        ]);

        $this->garasiUser->notify(new BookingSubmittedNotification($booking));

        $this->assertTrue($this->garasiUser->unreadNotifications->count() >= 1);

        $this->actingAs($this->garasiUser)
            ->post(route('portal.notifications.markAllAsRead'))
            ->assertStatus(302);

        $this->garasiUser->refresh();
        $this->assertEquals(0, $this->garasiUser->unreadNotifications->count());

        $booking->delete();
    }
}
