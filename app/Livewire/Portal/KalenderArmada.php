<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Carbon\Carbon;
use Livewire\Component;

class KalenderArmada extends Component
{
    public int $bulan;
    public int $tahun;
    public ?int $filterKategoriId = null;
    public ?string $selectedDate = null;
    public array $selectedDayBookings = [];

    public function mount()
    {
        $this->bulan = (int) Carbon::now()->format('m');
        $this->tahun = (int) Carbon::now()->format('Y');
        $this->selectDay(Carbon::now()->format('Y-m-d'));
    }

    public function goToToday()
    {
        $now = Carbon::now();
        $this->bulan = (int) $now->format('m');
        $this->tahun = (int) $now->format('Y');
        $this->selectDay($now->format('Y-m-d'));
    }

    public function prevMonth()
    {
        $dt = Carbon::createFromDate($this->tahun, $this->bulan, 1)->subMonth();
        $this->bulan = (int) $dt->format('m');
        $this->tahun = (int) $dt->format('Y');
        $this->selectedDate = null;
    }

    public function nextMonth()
    {
        $dt = Carbon::createFromDate($this->tahun, $this->bulan, 1)->addMonth();
        $this->bulan = (int) $dt->format('m');
        $this->tahun = (int) $dt->format('Y');
        $this->selectedDate = null;
    }

    public function selectDay(string $dateString)
    {
        $this->selectedDate = $dateString;

        // Ambil data peminjaman yang aktif di tanggal ini
        $this->selectedDayBookings = Booking::with(['vehicle', 'unitKerja', 'user'])
            ->whereDate('tanggal_berangkat', '<=', $dateString)
            ->whereDate('tanggal_kembali_rencana', '>=', $dateString)
            ->whereNotIn('status', ['ditolak_garasi', 'ditolak_pimpinan', 'dibatalkan'])
            ->get()
            ->toArray();
    }

    public function render()
    {
        $startDate = Carbon::createFromDate($this->tahun, $this->bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;
        $firstDayOfWeek = $startDate->dayOfWeekIso; // 1 (Monday) to 7 (Sunday)

        $totalVehicles = Vehicle::whereIn('status', ['tersedia', 'dipinjam'])
            ->when($this->filterKategoriId, fn ($q) => $q->where('kategori_id', $this->filterKategoriId))
            ->count();

        // Ambil semua booking bulan ini
        $monthlyBookings = Booking::with('vehicle')
            ->whereNotIn('status', ['ditolak_garasi', 'ditolak_pimpinan', 'dibatalkan'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_berangkat', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhereBetween('tanggal_kembali_rencana', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhere(function ($sub) use ($startDate, $endDate) {
                      $sub->where('tanggal_berangkat', '<=', $startDate->format('Y-m-d'))
                          ->where('tanggal_kembali_rencana', '>=', $endDate->format('Y-m-d'));
                  });
            })
            ->get();

        // Map penggunaan per hari
        $calendarDays = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($this->tahun, $this->bulan, $day)->format('Y-m-d');

            $bookingsOnDay = $monthlyBookings->filter(function ($b) use ($currentDate) {
                return $currentDate >= $b->tanggal_berangkat->format('Y-m-d')
                    && $currentDate <= $b->tanggal_kembali_rencana->format('Y-m-d');
            });

            $usedCount = $bookingsOnDay->unique('vehicle_id')->filter(fn ($b) => $b->vehicle_id !== null)->count();
            $isFull = $totalVehicles > 0 && $usedCount >= $totalVehicles;

            $calendarDays[$day] = [
                'date' => $currentDate,
                'day' => $day,
                'usedCount' => $usedCount,
                'totalVehicles' => $totalVehicles,
                'isFull' => $isFull,
                'hasBookings' => $bookingsOnDay->count() > 0,
                'bookingsCount' => $bookingsOnDay->count(),
            ];
        }

        $categories = VehicleCategory::all();

        return view('livewire.portal.kalender-armada', [
            'monthName' => $startDate->translatedFormat('F Y'),
            'firstDayOfWeek' => $firstDayOfWeek,
            'daysInMonth' => $daysInMonth,
            'calendarDays' => $calendarDays,
            'totalVehicles' => $totalVehicles,
            'categories' => $categories,
        ])->layout('layouts.portal');
    }
}
