<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VehicleHandoverNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $type, // 'checkout' | 'checkin'
        public ?int $odometer = null,
        public ?string $levelBbm = null,
        public ?int $jarakTempuh = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $vehicle = $this->booking->vehicle?->tipe_model ?? 'Kendaraan Dinas';
        $plat = $this->booking->vehicle?->plat_nomor ?? '';

        if ($this->type === 'checkout') {
            $title = 'Serah Terima Keluar: Kendaraan Siap Digunakan';
            $message = "Unit {$vehicle} ({$plat}) resmi diserahkan keluar oleh petugas garasi. Odometer awal: " . number_format($this->odometer, 0, ',', '.') . " km, BBM: {$this->levelBbm}. Selamat bertugas!";
        } else {
            $title = 'Serah Terima Masuk: Kendaraan Telah Kembali';
            $km = $this->jarakTempuh ? number_format($this->jarakTempuh, 0, ',', '.') . " km" : "-";
            $message = "Unit {$vehicle} ({$plat}) telah diterima kembali oleh petugas garasi. Total jarak tempuh penugasan: {$km}. Terima kasih telah merawat armada!";
        }

        return [
            'booking_id' => $this->booking->id,
            'kode_peminjaman' => $this->booking->kode_peminjaman,
            'title' => $title,
            'message' => $message,
            'type' => "vehicle_{$this->type}",
            'action_url' => route('portal.riwayat'),
        ];
    }
}
