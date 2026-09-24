<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaintenanceReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $kategori, // 'servis' | 'pajak' | 'sim'
        public string $title,
        public string $message,
        public string $urgensi = 'warning', // 'info' | 'warning' | 'danger'
        public ?int $vehicleId = null,
        public ?int $driverId = null,
        public ?string $actionUrl = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => "reminder_{$this->kategori}",
            'kategori' => $this->kategori,
            'urgensi' => $this->urgensi,
            'vehicle_id' => $this->vehicleId,
            'driver_id' => $this->driverId,
            'action_url' => $this->actionUrl ?? url('/portal/serah-terima'),
        ];
    }
}
