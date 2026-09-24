<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPeminjaman = Booking::count();
        $disetujui = Booking::whereIn('status', ['disetujui', 'kendaraan_keluar', 'selesai'])->count();
        $armadaTersedia = Vehicle::where('status', 'tersedia')->count();
        $armadaPerhatian = Vehicle::where('status', 'perlu_perhatian')->count();

        return [
            Stat::make('Total Pengajuan Dinas', $totalPeminjaman)
                ->description("{$disetujui} disetujui / selesai")
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),

            Stat::make('Armada Siap di Pool', "{$armadaTersedia} Unit")
                ->description('Siap untuk penugasan dinas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Armada Butuh Perhatian', "{$armadaPerhatian} Unit")
                ->description('Pajak jatuh tempo / servis berkala')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($armadaPerhatian > 0 ? 'warning' : 'gray'),
        ];
    }
}
