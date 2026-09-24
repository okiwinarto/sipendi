<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $vehicle = $this->booking->vehicle?->tipe_model ?? 'Armada Operasional';
        $plat = $this->booking->vehicle?->plat_nomor ?? '';

        return [
            'booking_id' => $this->booking->id,
            'kode_peminjaman' => $this->booking->kode_peminjaman,
            'title' => 'Peminjaman Siap Disetujui',
            'message' => "Pengajuan {$this->booking->kode_peminjaman} telah diverifikasi teknis oleh Kepala Garasi (Unit: {$vehicle} [{$plat}]). Menunggu persetujuan Anda.",
            'type' => 'booking_verified',
            'action_url' => route('portal.persetujuan'),
        ];
    }
}
