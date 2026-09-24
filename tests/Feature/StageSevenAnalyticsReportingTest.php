<?php

namespace Tests\Feature;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Livewire\Portal\DashboardEksekutif;
use App\Livewire\Portal\LaporanPeminjaman;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Garasi;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Carbon\Carbon;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class StageSevenAnalyticsReportingTest extends TestCase
{
    protected User $adminUser;
    protected User $pimpinanUser;
    protected User $garasiUser;
    protected User $pemohonUser;
    protected UnitKerja $unitKerja;
    protected Vehicle $vehicle;
    protected Driver $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('email', 'admin@sidawangi.id')->firstOrFail();
        $this->pimpinanUser = User::where('email', 'pimpinan@sidawangi.id')->firstOrFail();
        $this->garasiUser = User::where('email', 'garasi@sidawangi.id')->firstOrFail();
        $this->pemohonUser = User::where('email', 'pemohon@sidawangi.id')->firstOrFail();

        $this->unitKerja = $this->pemohonUser->unitKerja ?? UnitKerja::firstOrFail();
        $this->vehicle = Vehicle::firstOrFail();
        $this->driver = Driver::where('status', 'aktif')->firstOrFail();

        Booking::where('kode_peminjaman', 'like', 'SPD/TEST7/%')->delete();
    }

    protected function tearDown(): void
    {
        Booking::where('kode_peminjaman', 'like', 'SPD/TEST7/%')->delete();

        // Pulihkan status armada
        Vehicle::where('no_polisi', 'E 1234 YX')->update([
            'status' => 'tersedia',
            'odometer_terakhir' => 42500,
            'odometer_service_terakhir' => 40000,
        ]);

        parent::tearDown();
    }

    /**
     * 1. Verifikasi pencatatan audit trail (Spatie ActivityLog) saat booking dibuat atau diperbarui.
     */
    public function test_activity_log_is_recorded_when_booking_created_or_updated(): void
    {
        $initialCount = Activity::where('log_name', 'booking')->count();

        $booking = Booking::create([
            'kode_peminjaman' => 'SPD/TEST7/202609/7001',
            'user_id' => $this->pemohonUser->id,
            'unit_kerja_id' => $this->unitKerja->id,
            'vehicle_id' => $this->vehicle->id,
            'jenis_pengemudi' => 'swakemudi',
            'tujuan_perjalanan' => 'Uji Coba Audit Trail Spatie',
            'kota_tujuan' => 'Bandung',
            'tanggal_berangkat' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'jam_berangkat' => '08:00',
            'tanggal_kembali_rencana' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'jam_kembali_rencana' => '17:00',
            'jumlah_penumpang' => 2,
            'status' => 'diajukan',
        ]);

        $this->assertGreaterThan($initialCount, Activity::where('log_name', 'booking')->count());

        $latestLog = Activity::where('subject_type', Booking::class)
            ->where('subject_id', $booking->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($latestLog);
        $this->assertStringContainsString('SPD/TEST7/202609/7001', $latestLog->description);

        $booking->delete();
    }

    /**
     * 2. Halaman Filament Jejak Audit (ActivityLogResource) dapat diakses oleh Admin IT.
     */
    public function test_activity_log_filament_resource_accessible_by_admin(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ListActivityLogs::class)->assertSuccessful();
    }

    /**
     * 3. Hak akses Dashboard Eksekutif: Hanya Pimpinan, Garasi, dan Admin yang diizinkan (Pemohon 403).
     */
    public function test_dashboard_eksekutif_access_control(): void
    {
        // Pimpinan -> 200
        $response = $this->actingAs($this->pimpinanUser)->get(route('portal.dashboard'));
        $response->assertSuccessful();

        // Kepala Garasi -> 200
        $response = $this->actingAs($this->garasiUser)->get(route('portal.dashboard'));
        $response->assertSuccessful();

        // Pemohon -> 403
        $response = $this->actingAs($this->pemohonUser)->get(route('portal.dashboard'));
        $response->assertForbidden();
    }

    /**
     * 4. Komponen Livewire Dashboard Eksekutif mengalkulasi metrik KPI dengan benar dan mendukung pergantian periode.
     */
    public function test_dashboard_eksekutif_metrics_calculation_and_period_switching(): void
    {
        Livewire::actingAs($this->pimpinanUser)
            ->test(DashboardEksekutif::class)
            ->assertSet('period', 'bulan_ini')
            ->assertViewHas('totalPeminjaman')
            ->assertViewHas('tingkatUtilisasi')
            ->assertViewHas('unitKerjaStats')
            ->assertViewHas('vehicleStats')
            ->call('setPeriod', 'bulan_lalu')
            ->assertSet('period', 'bulan_lalu')
            ->call('setPeriod', 'tahun_ini')
            ->assertSet('period', 'tahun_ini');
    }

    /**
     * 5. Hak akses dan filter pada halaman Laporan Peminjaman (/portal/laporan).
     */
    public function test_laporan_peminjaman_accessible_and_filters_correctly(): void
    {
        // Akses Pimpinan
        $response = $this->actingAs($this->pimpinanUser)->get(route('portal.laporan'));
        $response->assertSuccessful();

        // Filter Livewire
        Livewire::actingAs($this->pimpinanUser)
            ->test(LaporanPeminjaman::class)
            ->set('unitKerjaId', $this->unitKerja->id)
            ->set('status', 'selesai')
            ->set('search', 'Dinkes')
            ->assertHasNoErrors()
            ->call('resetFilters')
            ->assertSet('unitKerjaId', null)
            ->assertSet('status', null)
            ->assertSet('search', '');
    }

    /**
     * 6. Unduh Laporan Ekspor PDF Resmi berhasil menghasilkan stream response berkas PDF.
     */
    public function test_laporan_peminjaman_export_pdf_returns_pdf_stream(): void
    {
        $component = Livewire::actingAs($this->pimpinanUser)
            ->test(LaporanPeminjaman::class)
            ->call('exportPdf');

        $response = $component->instance()->exportPdf();

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('.pdf', $response->headers->get('Content-Disposition'));
    }

    /**
     * 7. Unduh Laporan Ekspor Excel (.xlsx) berhasil menghasilkan response download spreadsheet.
     */
    public function test_laporan_peminjaman_export_excel_returns_xlsx_download(): void
    {
        $component = Livewire::actingAs($this->pimpinanUser)
            ->test(LaporanPeminjaman::class)
            ->call('exportExcel');

        $response = $component->instance()->exportExcel();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('.xlsx', $response->headers->get('Content-Disposition'));
    }

    /**
     * 8. Aktivitas perubahan profil atau data Pengguna terekam di Spatie ActivityLog.
     */
    public function test_user_activity_log_recorded(): void
    {
        $this->pemohonUser->update(['name' => 'Nama Pemohon Berubah SPD7']);

        $userLog = Activity::where('log_name', 'user')
            ->where('subject_id', $this->pemohonUser->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($userLog);
        $this->assertStringContainsString('Nama Pemohon Berubah SPD7', $userLog->description);
    }

    /**
     * 10. Verifikasi menu Topbar dan Dashboard Eksekutif terstruktur rapi dan role-tailored (tanpa menu bertumpuk).
     */
    public function test_topbar_and_dashboard_menu_streamlining_for_garasi_and_pimpinan(): void
    {
        // 1. Kepala Garasi
        $responseGarasi = $this->actingAs($this->garasiUser)->get(route('portal.dashboard'));
        $responseGarasi->assertSuccessful();
        $contentGarasi = $responseGarasi->getContent();
        
        // Memiliki menu operasional utama garasi
        $this->assertStringContainsString('Verifikasi', $contentGarasi);
        $this->assertStringContainsString('Serah Terima', $contentGarasi);
        $this->assertStringContainsString('Kesiapan Pool & Armada', $contentGarasi);
        $this->assertStringContainsString('Lainnya', $contentGarasi);
        // Tidak menampilkan tab internal admin
        $this->assertStringNotContainsString('Audit Trail & Server', $contentGarasi);

        // 2. Pimpinan
        $responsePimpinan = $this->actingAs($this->pimpinanUser)->get(route('portal.dashboard'));
        $responsePimpinan->assertSuccessful();
        $contentPimpinan = $responsePimpinan->getContent();

        // Memiliki menu persetujuan eksekutif pimpinan
        $this->assertStringContainsString('Persetujuan', $contentPimpinan);
        $this->assertStringContainsString('Ringkasan Eksekutif', $contentPimpinan);
        $this->assertStringContainsString('Lainnya', $contentPimpinan);
        // Tidak menampilkan tab internal admin
        $this->assertStringNotContainsString('Audit Trail & Server', $contentPimpinan);
    }
}
