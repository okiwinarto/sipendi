<?php

namespace App\Livewire\Portal;

use App\Exports\BookingsExport;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\UnitKerja;
use App\Models\Vehicle;
use App\Models\VehicleCheckin;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPeminjaman extends Component
{
    use WithPagination;

    public string $startDate = '';
    public string $endDate = '';
    public ?int $unitKerjaId = null;
    public ?int $vehicleId = null;
    public ?int $driverId = null;
    public ?string $status = null;
    public string $search = '';

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user || ! ($user->isPimpinan() || $user->isKepalaGarasi() || $user->isAdmin())) {
            abort(403, 'Akses terbatas untuk Pimpinan, Kepala Garasi, dan Administrator.');
        }

        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['startDate', 'endDate', 'unitKerjaId', 'vehicleId', 'driverId', 'status', 'search'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->unitKerjaId = null;
        $this->vehicleId = null;
        $this->driverId = null;
        $this->status = null;
        $this->search = '';
        $this->resetPage();
    }

    protected function getFilteredQuery()
    {
        return Booking::with(['user', 'unitKerja', 'vehicle', 'driver', 'checkout', 'checkin'])
            ->when($this->startDate, fn ($q) => $q->where('tanggal_berangkat', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->where('tanggal_berangkat', '<=', $this->endDate))
            ->when($this->unitKerjaId, fn ($q) => $q->where('unit_kerja_id', $this->unitKerjaId))
            ->when($this->vehicleId, fn ($q) => $q->where('vehicle_id', $this->vehicleId))
            ->when($this->driverId, fn ($q) => $q->where('driver_id', $this->driverId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('kode_peminjaman', 'like', "%{$this->search}%")
                        ->orWhere('tujuan_perjalanan', 'like', "%{$this->search}%")
                        ->orWhere('kota_tujuan', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('vehicle', fn ($v) => $v->where('no_polisi', 'like', "%{$this->search}%")->orWhere('tipe_model', 'like', "%{$this->search}%"));
                });
            })
            ->orderBy('tanggal_berangkat', 'desc')
            ->orderBy('id', 'desc');
    }

    /**
     * Unduh Laporan Rekapitulasi Berkas PDF Resmi (DomPDF).
     */
    public function exportPdf()
    {
        $bookings = $this->getFilteredQuery()->get();
        $totalKm = VehicleCheckin::whereIn('booking_id', $bookings->pluck('id'))->get()->sum('jarak_tempuh');

        $unitKerjaNama = $this->unitKerjaId ? UnitKerja::find($this->unitKerjaId)?->nama_unit : null;
        $statusLabel = $this->status ? ucfirst(str_replace('_', ' ', $this->status)) : null;

        $pdf = Pdf::loadView('reports.peminjaman-pdf', [
            'bookings' => $bookings,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'totalKm' => $totalKm,
            'unitKerjaNama' => $unitKerjaNama,
            'statusLabel' => $statusLabel,
        ])->setPaper('a4', 'landscape');

        $fileName = 'Laporan_Peminjaman_RSUD_Sidawangi_' . date('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Unduh Laporan Rekapitulasi File Excel (.xlsx).
     */
    public function exportExcel()
    {
        $bookings = $this->getFilteredQuery()->get();
        $fileName = 'Laporan_Peminjaman_RSUD_Sidawangi_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new BookingsExport($bookings), $fileName);
    }

    public function render()
    {
        $bookings = $this->getFilteredQuery()->paginate(12);

        // Ambil data untuk opsi filter
        $unitKerjas = UnitKerja::orderBy('nama_unit')->get();
        $vehicles = Vehicle::orderBy('tipe_model')->get();
        $drivers = Driver::where('status', 'aktif')->orderBy('nama')->get();

        // Ringkasan metrik filter aktif
        $allFiltered = $this->getFilteredQuery()->get();
        $summary = [
            'total' => $allFiltered->count(),
            'disetujui' => $allFiltered->whereIn('status', ['disetujui', 'kendaraan_keluar', 'kendaraan_kembali', 'selesai'])->count(),
            'ditolak' => $allFiltered->whereIn('status', ['ditolak_garasi', 'ditolak_pimpinan'])->count(),
            'selesai' => $allFiltered->where('status', 'selesai')->count(),
            'total_km' => VehicleCheckin::whereIn('booking_id', $allFiltered->pluck('id'))->get()->sum('jarak_tempuh'),
        ];

        return view('livewire.portal.laporan-peminjaman', [
            'bookings' => $bookings,
            'unitKerjas' => $unitKerjas,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'summary' => $summary,
        ])->layout('layouts.portal');
    }
}
