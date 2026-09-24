<?php

namespace Tests\Feature;

use App\Filament\Resources\ReminderSettings\Pages\ListReminderSettings;
use App\Filament\Resources\VehicleMaintenances\Pages\ListVehicleMaintenances;
use App\Filament\Resources\VehicleTaxes\Pages\ListVehicleTaxes;
use App\Livewire\Portal\PeminjamanForm;
use App\Livewire\Portal\VerifikasiGarasi;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Garasi;
use App\Models\ReminderSetting;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleMaintenance;
use App\Models\VehicleTax;
use App\Notifications\MaintenanceReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class StageSixMaintenanceReminderTest extends TestCase
{
    protected User $adminUser;
    protected User $garasiUser;
    protected User $pemohonUser;
    protected Garasi $garasi;
    protected VehicleCategory $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('email', 'admin@sidawangi.id')->firstOrFail();
        $this->garasiUser = User::where('email', 'garasi@sidawangi.id')->firstOrFail();
        $this->pemohonUser = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();
        $this->garasi = Garasi::firstOrFail();
        $this->kategori = VehicleCategory::firstOrFail();
    }

    protected function tearDown(): void
    {
        // Pulihkan seluruh armada dan sopir ke status awal terverifikasi
        Vehicle::where('no_polisi', 'E 1234 YX')->update([
            'status' => 'tersedia',
            'odometer_terakhir' => 42500,
            'odometer_service_terakhir' => 40000,
            'tanggal_service_terakhir' => '2026-05-10',
            'tanggal_pajak_tahunan' => '2026-11-15',
            'tanggal_pajak_5tahunan' => '2027-11-15',
        ]);

        Vehicle::where('no_polisi', 'E 8765 YX')->update([
            'status' => 'tersedia',
            'odometer_terakhir' => 68100,
            'odometer_service_terakhir' => 65000,
            'tanggal_service_terakhir' => '2026-06-20',
            'tanggal_pajak_tahunan' => '2026-10-30',
            'tanggal_kir_berlaku' => '2026-12-15',
        ]);

        Vehicle::where('no_polisi', 'E 1001 YX')->update([
            'status' => 'tersedia',
            'odometer_terakhir' => 29400,
            'odometer_service_terakhir' => 20000,
            'tanggal_service_terakhir' => '2026-03-15',
            'tanggal_pajak_tahunan' => '2027-02-20',
            'tanggal_pajak_5tahunan' => '2028-02-20',
        ]);

        Driver::where('nama', 'Supardi')->update([
            'masa_berlaku_sim' => '2028-05-12',
            'status' => 'aktif',
        ]);

        // Hapus dummy vehicle test jika ada
        Vehicle::where('no_polisi', 'like', 'E 999%')->delete();

        parent::tearDown();
    }

    /**
     * 1. Verifikasi skema tabel dan relasi model VehicleMaintenance, VehicleTax, ReminderSetting.
     */
    public function test_maintenance_and_tax_and_reminder_setting_relationships(): void
    {
        $vehicle = Vehicle::where('no_polisi', 'E 1234 YX')->firstOrFail();

        $maintenance = VehicleMaintenance::create([
            'vehicle_id' => $vehicle->id,
            'jenis_service' => 'rutin',
            'tanggal_service' => '2026-09-01',
            'odometer_saat_service' => 41000,
            'bengkel' => 'Bengkel Resmi Toyota Cirebon',
            'biaya' => 750000,
            'deskripsi_pekerjaan' => 'Ganti oli mesin TMO 10W-40 dan filter oli.',
            'dicatat_oleh' => $this->garasiUser->id,
        ]);

        $tax = VehicleTax::create([
            'vehicle_id' => $vehicle->id,
            'jenis_pajak' => 'tahunan',
            'tanggal_bayar' => '2026-09-02',
            'masa_berlaku_sampai' => '2027-11-15',
            'biaya' => 2150000,
            'status' => 'aktif',
            'dicatat_oleh' => $this->garasiUser->id,
        ]);

        $this->assertEquals($vehicle->id, $maintenance->vehicle->id);
        $this->assertEquals($this->garasiUser->id, $maintenance->pencatat->id);

        $this->assertEquals($vehicle->id, $tax->vehicle->id);
        $this->assertEquals($this->garasiUser->id, $tax->pencatat->id);

        $setting = ReminderSetting::getSettingFor('service');
        $this->assertNotNull($setting);
        $this->assertIsArray($setting->target_role);

        $maintenance->delete();
        $tax->delete();
    }

    /**
     * 2. Pencatatan servis otomatis memperbarui odometer_service_terakhir dan tanggal_service_terakhir.
     */
    public function test_vehicle_maintenance_creation_syncs_vehicle_service_attributes(): void
    {
        $dummyVehicle = Vehicle::create([
            'garasi_id' => $this->garasi->id,
            'kategori_id' => $this->kategori->id,
            'no_polisi' => 'E 9991 TST',
            'merk' => 'Toyota',
            'tipe_model' => 'Calya Test',
            'tahun_pembuatan' => 2023,
            'warna' => 'Hitam',
            'bahan_bakar' => 'bensin',
            'kapasitas_penumpang' => 7,
            'status' => 'perlu_perhatian',
            'odometer_terakhir' => 25000,
            'interval_service_km' => 5000,
            'odometer_service_terakhir' => 20000,
            'tanggal_service_terakhir' => '2026-01-10',
        ]);

        $maintenance = VehicleMaintenance::create([
            'vehicle_id' => $dummyVehicle->id,
            'jenis_service' => 'perbaikan_kerusakan',
            'tanggal_service' => '2026-09-20',
            'odometer_saat_service' => 25000,
            'bengkel' => 'Pool RSUD',
            'biaya' => 350000,
            'deskripsi_pekerjaan' => 'Perbaikan bumper baret dan servis rem.',
            'dicatat_oleh' => $this->garasiUser->id,
        ]);

        $dummyVehicle->refresh();

        $this->assertEquals(25000, $dummyVehicle->odometer_service_terakhir);
        $this->assertEquals('2026-09-20', $dummyVehicle->tanggal_service_terakhir->format('Y-m-d'));
        $this->assertEquals('tersedia', $dummyVehicle->status); // Status pulih dari perlu_perhatian

        $maintenance->delete();
        $dummyVehicle->delete();
    }

    /**
     * 3. Pencatatan pembayaran pajak otomatis memperbarui masa berlaku di tabel vehicles.
     */
    public function test_vehicle_tax_creation_syncs_vehicle_tax_dates(): void
    {
        $dummyVehicle = Vehicle::create([
            'garasi_id' => $this->garasi->id,
            'kategori_id' => $this->kategori->id,
            'no_polisi' => 'E 9992 TST',
            'merk' => 'Daihatsu',
            'tipe_model' => 'Gran Max Test',
            'tahun_pembuatan' => 2022,
            'warna' => 'Putih',
            'bahan_bakar' => 'bensin',
            'kapasitas_penumpang' => 3,
            'status' => 'tersedia',
            'odometer_terakhir' => 30000,
            'tanggal_pajak_tahunan' => '2026-09-01',
        ]);

        $tax = VehicleTax::create([
            'vehicle_id' => $dummyVehicle->id,
            'jenis_pajak' => 'tahunan',
            'tanggal_bayar' => '2026-09-15',
            'masa_berlaku_sampai' => '2027-09-01',
            'biaya' => 1500000,
            'dicatat_oleh' => $this->garasiUser->id,
        ]);

        $dummyVehicle->refresh();

        $this->assertEquals('2027-09-01', $dummyVehicle->tanggal_pajak_tahunan->format('Y-m-d'));
        $this->assertEquals('aktif', $tax->status);

        $tax->delete();
        $dummyVehicle->delete();
    }

    /**
     * 4. Scheduler mendeteksi pajak kedaluwarsa, mengubah status ke 'perlu_perhatian', dan mengirim notifikasi.
     */
    public function test_check_reminders_command_detects_overdue_tax_and_updates_vehicle(): void
    {
        Notification::fake();

        $dummyVehicle = Vehicle::create([
            'garasi_id' => $this->garasi->id,
            'kategori_id' => $this->kategori->id,
            'no_polisi' => 'E 9993 TST',
            'merk' => 'Suzuki',
            'tipe_model' => 'APV Overdue Test',
            'tahun_pembuatan' => 2021,
            'warna' => 'Silver',
            'bahan_bakar' => 'bensin',
            'kapasitas_penumpang' => 8,
            'status' => 'tersedia',
            'odometer_terakhir' => 50000,
            'tanggal_pajak_tahunan' => Carbon::now()->subDays(5)->format('Y-m-d'), // 5 hari lewat
        ]);

        $exitCode = Artisan::call('sipendi:check-reminders');
        $this->assertEquals(0, $exitCode);

        $dummyVehicle->refresh();
        $this->assertEquals('perlu_perhatian', $dummyVehicle->status);

        Notification::assertSentTo(
            [$this->adminUser, $this->garasiUser],
            MaintenanceReminderNotification::class,
            function (MaintenanceReminderNotification $notif) use ($dummyVehicle) {
                return $notif->kategori === 'pajak' && $notif->urgensi === 'danger' && $notif->vehicleId === $dummyVehicle->id;
            }
        );

        $dummyVehicle->delete();
    }

    /**
     * 5. Scheduler mendeteksi armada yang jatuh tempo servis (KM & Waktu).
     */
    public function test_check_reminders_command_detects_service_due(): void
    {
        Notification::fake();

        $dummyVehicle = Vehicle::create([
            'garasi_id' => $this->garasi->id,
            'kategori_id' => $this->kategori->id,
            'no_polisi' => 'E 9994 TST',
            'merk' => 'Toyota',
            'tipe_model' => 'Hiace Test Service',
            'tahun_pembuatan' => 2022,
            'warna' => 'Putih',
            'bahan_bakar' => 'solar',
            'kapasitas_penumpang' => 14,
            'status' => 'tersedia',
            'odometer_terakhir' => 60500,
            'interval_service_km' => 5000,
            'odometer_service_terakhir' => 55000, // Selisih 5500 km >= 5000 km
            'tanggal_service_terakhir' => Carbon::now()->subMonths(3)->format('Y-m-d'),
        ]);

        $exitCode = Artisan::call('sipendi:check-reminders');
        $this->assertEquals(0, $exitCode);

        Notification::assertSentTo(
            [$this->adminUser, $this->garasiUser],
            MaintenanceReminderNotification::class,
            function (MaintenanceReminderNotification $notif) use ($dummyVehicle) {
                return $notif->kategori === 'servis' && $notif->vehicleId === $dummyVehicle->id;
            }
        );

        $dummyVehicle->delete();
    }

    /**
     * 6. Scheduler mendeteksi masa berlaku SIM sopir yang mendekati habis (H-30).
     */
    public function test_check_reminders_command_detects_driver_sim_expiring(): void
    {
        Notification::fake();

        $driver = Driver::where('nama', 'Supardi')->firstOrFail();
        $driver->update([
            'masa_berlaku_sim' => Carbon::now()->addDays(10)->format('Y-m-d'), // 10 hari lagi
        ]);

        $exitCode = Artisan::call('sipendi:check-reminders');
        $this->assertEquals(0, $exitCode);

        Notification::assertSentTo(
            [$this->adminUser, $this->garasiUser],
            MaintenanceReminderNotification::class,
            function (MaintenanceReminderNotification $notif) use ($driver) {
                return $notif->kategori === 'sim' && $notif->driverId === $driver->id;
            }
        );
    }

    /**
     * 7. Kendaraan berstatus 'perlu_perhatian' atau pajak kedaluwarsa dicegah dari permohonan baru.
     */
    public function test_vehicle_with_expired_tax_or_perlu_perhatian_rejected_in_booking(): void
    {
        $dummyVehicle = Vehicle::create([
            'garasi_id' => $this->garasi->id,
            'kategori_id' => $this->kategori->id,
            'no_polisi' => 'E 9995 TST',
            'merk' => 'Toyota',
            'tipe_model' => 'Rush Expired Tax',
            'tahun_pembuatan' => 2021,
            'warna' => 'Hitam',
            'bahan_bakar' => 'bensin',
            'kapasitas_penumpang' => 7,
            'status' => 'perlu_perhatian',
            'odometer_terakhir' => 40000,
            'tanggal_pajak_tahunan' => Carbon::now()->subDays(20)->format('Y-m-d'),
        ]);

        // Coba ajukan peminjaman dengan armada tidak laik
        Livewire::actingAs($this->pemohonUser)
            ->test(PeminjamanForm::class)
            ->set('tujuan_perjalanan', 'Perjalanan Dinas Uji Kepatuhan')
            ->set('kota_tujuan', 'Bandung')
            ->set('tanggal_berangkat', Carbon::now()->addDays(2)->format('Y-m-d'))
            ->set('jam_berangkat', '08:00')
            ->set('tanggal_kembali_rencana', Carbon::now()->addDays(2)->format('Y-m-d'))
            ->set('jam_kembali_rencana', '18:00')
            ->set('jumlah_penumpang', 4)
            ->set('jenis_pengemudi', 'swakemudi')
            ->set('tingkat_prioritas', 'normal')
            ->set('preferred_vehicle_id', $dummyVehicle->id)
            ->call('submit')
            ->assertHasErrors(['preferred_vehicle_id']);

        $dummyVehicle->delete();
    }

    /**
     * 8. Halaman Filament Resource Servis, Pajak, dan Pengingat dapat diakses oleh Admin IT.
     */
    public function test_filament_resources_accessible_by_admin(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ListVehicleMaintenances::class)->assertSuccessful();
        Livewire::test(ListVehicleTaxes::class)->assertSuccessful();
        Livewire::test(ListReminderSettings::class)->assertSuccessful();
    }
}
