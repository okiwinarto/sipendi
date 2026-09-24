<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\UnitKerja;
use App\Models\Vehicle;
use App\Models\VehicleCheckin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class DashboardEksekutif extends Component
{
    public string $period = 'bulan_ini'; // 'bulan_ini' | 'bulan_lalu' | 'tahun_ini'
    public string $activeTab = 'eksekutif'; // 'eksekutif' | 'garasi' | 'admin'

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user || ! ($user->isPimpinan() || $user->isKepalaGarasi() || $user->isAdmin())) {
            abort(403, 'Akses terbatas untuk Pimpinan, Kepala Garasi, dan Administrator.');
        }

        if ($user->hasRole('kepala_garasi') && ! $user->hasRole('admin_it') && ! $user->hasRole('pimpinan')) {
            $this->activeTab = 'garasi';
        }
    }

    public function setPeriod(string $p): void
    {
        $this->period = $p;
    }

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['eksekutif', 'garasi', 'admin'])) {
            $this->activeTab = $tab;
        }
    }

    public function render()
    {
        $startDate = match ($this->period) {
            'bulan_lalu' => Carbon::now()->subMonth()->startOfMonth(),
            'tahun_ini' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $endDate = match ($this->period) {
            'bulan_lalu' => Carbon::now()->subMonth()->endOfMonth(),
            'tahun_ini' => Carbon::now()->endOfYear(),
            default => Carbon::now()->endOfMonth(),
        };

        // Query bookings dalam periode
        $bookingsInPeriod = Booking::whereBetween('tanggal_berangkat', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();

        $totalPeminjaman = $bookingsInPeriod->count();
        $disetujuiCount = $bookingsInPeriod->whereIn('status', ['disetujui', 'kendaraan_keluar', 'kendaraan_kembali', 'selesai'])->count();
        $ditolakCount = $bookingsInPeriod->whereIn('status', ['ditolak_garasi', 'ditolak_pimpinan'])->count();
        $rasioDisetujui = $totalPeminjaman > 0 ? round(($disetujuiCount / $totalPeminjaman) * 100) : 0;

        // Rata-rata Durasi Persetujuan Pimpinan
        $approvals = BookingApproval::with('booking')
            ->where('role_approval', 'pimpinan')
            ->where('tindakan', 'disetujui')
            ->whereHas('booking', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_berangkat', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            })->get();

        $durations = [];
        foreach ($approvals as $appr) {
            if ($appr->booking && $appr->booking->created_at && $appr->waktu_tindakan) {
                $durations[] = $appr->waktu_tindakan->diffInMinutes($appr->booking->created_at);
            }
        }
        $avgMinutes = count($durations) > 0 ? round(array_sum($durations) / count($durations)) : 0;
        if ($avgMinutes >= 60) {
            $durasiPersetujuan = round($avgMinutes / 60, 1) . ' Jam';
        } elseif ($avgMinutes > 0) {
            $durasiPersetujuan = $avgMinutes . ' Menit';
        } else {
            $durasiPersetujuan = '< 1 Jam';
        }

        // Total Jarak Tempuh dari Checkin
        $totalKm = VehicleCheckin::whereHas('booking', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_berangkat', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        })->get()->sum('jarak_tempuh');

        // Status Armada Realtime
        $totalArmada = Vehicle::where('status', '!=', 'nonaktif')->count();
        $armadaTersedia = Vehicle::where('status', 'tersedia')->count();
        $armadaDipinjam = Vehicle::where('status', 'dipinjam')->count();
        $armadaPerhatian = Vehicle::where('status', 'perlu_perhatian')->count();

        $tingkatUtilisasi = $totalArmada > 0 ? round(($armadaDipinjam / $totalArmada) * 100) : 0;

        // Distribusi Pemakaian per Unit Kerja
        $unitKerjaStats = UnitKerja::withCount(['bookings' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_berangkat', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
              ->whereNotIn('status', ['ditolak_garasi', 'ditolak_pimpinan', 'dibatalkan']);
        }])->orderBy('bookings_count', 'desc')->get();

        // Utilisasi per Unit Armada (Ranking paling sering vs jarang)
        $vehicleStats = Vehicle::withCount(['bookings' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_berangkat', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
              ->whereIn('status', ['kendaraan_keluar', 'selesai']);
        }])->orderBy('bookings_count', 'desc')->get();

        // 5 Peminjaman Terkini
        $recentBookings = Booking::with(['user', 'unitKerja', 'vehicle', 'driver'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // --- Data Khusus Kepala Garasi ---
        $avgRatingKembali = round(VehicleCheckin::avg('rating_kondisi') ?? 5.0, 1);
        $totalCheckins = VehicleCheckin::count();

        // Pengingat Servis & Pajak Terdekat (< 30 Hari)
        $thresholdDate = Carbon::now()->addDays(30);
        $taxReminders = Vehicle::where('status', '!=', 'nonaktif')
            ->where(function ($q) use ($thresholdDate) {
                $q->where('tanggal_pajak_tahunan', '<=', $thresholdDate)
                  ->orWhere('tanggal_pajak_5tahunan', '<=', $thresholdDate)
                  ->orWhere('tanggal_kir_berlaku', '<=', $thresholdDate);
            })
            ->take(5)
            ->get();

        $serviceReminders = Vehicle::where('status', '!=', 'nonaktif')
            ->where(function ($q) {
                $q->where('status', 'perlu_perhatian')
                  ->orWhereRaw('odometer_terakhir - odometer_service_terakhir >= 4500');
            })
            ->take(5)
            ->get();

        $driverSimReminders = Driver::where('status', 'aktif')
            ->where('masa_berlaku_sim', '<=', $thresholdDate)
            ->take(5)
            ->get();

        // --- Data Khusus Admin IT ---
        $recentActivityLogs = Activity::with('causer')
            ->latest()
            ->take(10)
            ->get();

        $pendingJobs = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();
        $dbHealthy = true;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbHealthy = false;
        }

        return view('livewire.portal.dashboard-eksekutif', [
            'totalPeminjaman' => $totalPeminjaman,
            'disetujuiCount' => $disetujuiCount,
            'ditolakCount' => $ditolakCount,
            'rasioDisetujui' => $rasioDisetujui,
            'durasiPersetujuan' => $durasiPersetujuan,
            'totalKm' => $totalKm,
            'totalArmada' => $totalArmada,
            'armadaTersedia' => $armadaTersedia,
            'armadaDipinjam' => $armadaDipinjam,
            'armadaPerhatian' => $armadaPerhatian,
            'tingkatUtilisasi' => $tingkatUtilisasi,
            'unitKerjaStats' => $unitKerjaStats,
            'vehicleStats' => $vehicleStats,
            'recentBookings' => $recentBookings,
            'avgRatingKembali' => $avgRatingKembali,
            'totalCheckins' => $totalCheckins,
            'taxReminders' => $taxReminders,
            'serviceReminders' => $serviceReminders,
            'driverSimReminders' => $driverSimReminders,
            'recentActivityLogs' => $recentActivityLogs,
            'pendingJobs' => $pendingJobs,
            'failedJobs' => $failedJobs,
            'dbHealthy' => $dbHealthy,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->layout('layouts.portal');
    }
}
