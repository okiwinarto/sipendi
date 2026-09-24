<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingSubmittedNotification extends Notification
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
        return [
            'booking_id' => $this->booking->id,
            'kode_peminjaman' => $this->booking->kode_peminjaman,
            'title' => 'Permohonan Peminjaman Baru',
            'message' => "Permohonan baru {$this->booking->kode_peminjaman} oleh {$this->booking->user?->name} ({$this->booking->kota_tujuan}) menunggu verifikasi teknis armada.",
            'type' => 'booking_submitted',
            'action_url' => route('portal.verifikasi'),
        ];
    }
}
