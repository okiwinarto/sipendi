<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingDecidedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $tindakan, // 'disetujui', 'ditolak_garasi', 'ditolak_pimpinan'
        public ?string $catatan = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $title = match ($this->tindakan) {
            'disetujui' => 'Pengajuan Peminjaman Disetujui',
            'ditolak_garasi' => 'Pengajuan Ditolak Kepala Garasi',
            'ditolak_pimpinan' => 'Pengajuan Ditolak Pimpinan',
            default => 'Status Peminjaman Diperbarui',
        };

        $message = match ($this->tindakan) {
            'disetujui' => "Pengajuan {$this->booking->kode_peminjaman} tujuan {$this->booking->kota_tujuan} telah DISETUJUI oleh Pimpinan.",
            'ditolak_garasi' => "Pengajuan {$this->booking->kode_peminjaman} DITOLAK oleh Kepala Garasi. Alasan: {$this->catatan}",
            'ditolak_pimpinan' => "Pengajuan {$this->booking->kode_peminjaman} DITOLAK oleh Pimpinan. Alasan: {$this->catatan}",
            default => "Status pengajuan {$this->booking->kode_peminjaman} telah diperbarui.",
        };

        return [
            'booking_id' => $this->booking->id,
            'kode_peminjaman' => $this->booking->kode_peminjaman,
            'title' => $title,
            'message' => $message,
            'status' => $this->tindakan,
            'catatan' => $this->catatan,
            'type' => 'booking_decided',
            'action_url' => route('portal.riwayat'),
        ];
    }
}
