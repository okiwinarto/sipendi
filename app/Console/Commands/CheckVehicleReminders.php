<?php

namespace App\Console\Commands;

use App\Models\Driver;
use App\Models\ReminderSetting;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\MaintenanceReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckVehicleReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipendi:check-reminders {--dry-run : Jalankan simulasi tanpa menyimpan perubahan atau mengirim notifikasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pemeriksaan otomatis jatuh tempo servis armada, pajak STNK/KIR, dan masa berlaku SIM sopir dinas.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $today = Carbon::today();

        $this->info("=== SIPENDI: Pemeriksaan Pengingat Armada & Sopir ({$today->format('d-m-Y')}) ===");
        if ($isDryRun) {
            $this->warn('Mode: DRY RUN (Tidak ada data disimpan atau notifikasi dikirim)');
        }

        // 1. Muat Pengaturan Reminder
        $settings = ReminderSetting::where('aktif', true)->get()->keyBy('tipe_reminder');

        $alertsSent = 0;
        $vehiclesChecked = 0;
        $driversChecked = 0;

        // Ambil User Target berdasarkan Peran
        $adminUsers = User::role('admin_it')->get();
        $garasiUsers = User::role('kepala_garasi')->get();

        // 2. Periksa Seluruh Armada Aktif
        $vehicles = Vehicle::where('status', '!=', 'nonaktif')->get();

        foreach ($vehicles as $vehicle) {
            $vehiclesChecked++;

            // --- A. PEMERIKSAAN PAJAK TAHUNAN ---
            if ($vehicle->tanggal_pajak_tahunan) {
                $days = $today->diffInDays($vehicle->tanggal_pajak_tahunan, false);
                $st = $settings->get('pajak_tahunan');
                $t1 = $st->h_minus_tahap1 ?? 30;
                $t2 = $st->h_minus_tahap2 ?? 14;
                $t3 = $st->h_minus_tahap3 ?? 1;

                if ($days < 0) {
                    // Kedaluwarsa
                    $overdueDays = abs($days);
                    $this->error("[PAJAK KADALUARSA] {$vehicle->nama_lengkap} - Lewat {$overdueDays} hari!");
                    if (! $isDryRun) {
                        if ($vehicle->status !== 'perlu_perhatian' && $vehicle->status !== 'dipinjam') {
                            $vehicle->update(['status' => 'perlu_perhatian']);
                        }
                        $this->sendAlert(
                            $this->resolveRecipients($st, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'pajak',
                                title: "Pajak Tahunan Terlewat: {$vehicle->no_polisi}",
                                message: "Pajak tahunan unit {$vehicle->nama_lengkap} telah kedaluwarsa sejak {$vehicle->tanggal_pajak_tahunan->format('d/m/Y')}. Status dialihkan ke Perlu Perhatian.",
                                urgensi: 'danger',
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                } elseif ($days <= $t1) {
                    // Mendekati jatuh tempo
                    $this->line("<comment>[PAJAK MENDEKATI]</comment> {$vehicle->nama_lengkap} - {$days} hari lagi ({$vehicle->tanggal_pajak_tahunan->format('d/m/Y')})");
                    if (! $isDryRun) {
                        $urgensi = $days <= $t3 ? 'danger' : ($days <= $t2 ? 'warning' : 'info');
                        $this->sendAlert(
                            $this->resolveRecipients($st, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'pajak',
                                title: "Jatuh Tempo Pajak Tahunan: {$vehicle->no_polisi} ({$days} hari lagi)",
                                message: "Masa berlaku pajak tahunan unit {$vehicle->nama_lengkap} akan berakhir pada {$vehicle->tanggal_pajak_tahunan->format('d/m/Y')}. Segera proses perpanjangan PKB.",
                                urgensi: $urgensi,
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                }
            }

            // --- B. PEMERIKSAAN PAJAK 5 TAHUNAN (PLAT NOMOR) ---
            if ($vehicle->tanggal_pajak_5tahunan) {
                $days = $today->diffInDays($vehicle->tanggal_pajak_5tahunan, false);
                $st = $settings->get('pajak_5tahunan');
                $t1 = $st->h_minus_tahap1 ?? 30;

                if ($days < 0) {
                    $overdueDays = abs($days);
                    $this->error("[PLAT 5 TAHUNAN KADALUARSA] {$vehicle->nama_lengkap} - Lewat {$overdueDays} hari!");
                    if (! $isDryRun) {
                        if ($vehicle->status !== 'perlu_perhatian' && $vehicle->status !== 'dipinjam') {
                            $vehicle->update(['status' => 'perlu_perhatian']);
                        }
                        $this->sendAlert(
                            $this->resolveRecipients($st, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'pajak',
                                title: "Pajak 5 Tahunan Kedaluwarsa: {$vehicle->no_polisi}",
                                message: "Plat nomor unit {$vehicle->nama_lengkap} kedaluwarsa sejak {$vehicle->tanggal_pajak_5tahunan->format('d/m/Y')}. Lakukan cek fisik samsat segera.",
                                urgensi: 'danger',
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                } elseif ($days <= $t1) {
                    $this->line("<comment>[PLAT 5 TAHUNAN MENDEKATI]</comment> {$vehicle->nama_lengkap} - {$days} hari lagi");
                    if (! $isDryRun) {
                        $this->sendAlert(
                            $this->resolveRecipients($st, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'pajak',
                                title: "Jatuh Tempo Pajak 5 Tahunan: {$vehicle->no_polisi}",
                                message: "Pajak 5 tahunan & ganti plat unit {$vehicle->nama_lengkap} jatuh tempo pada {$vehicle->tanggal_pajak_5tahunan->format('d/m/Y')} ({$days} hari lagi).",
                                urgensi: $days <= 7 ? 'danger' : 'warning',
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                }
            }

            // --- C. PEMERIKSAAN UJI BERKALA KIR ---
            if ($vehicle->tanggal_kir_berlaku) {
                $days = $today->diffInDays($vehicle->tanggal_kir_berlaku, false);
                $st = $settings->get('kir');
                $t1 = $st->h_minus_tahap1 ?? 30;

                if ($days < 0) {
                    $overdueDays = abs($days);
                    $this->error("[KIR KADALUARSA] {$vehicle->nama_lengkap} - Lewat {$overdueDays} hari!");
                    if (! $isDryRun) {
                        if ($vehicle->status !== 'perlu_perhatian' && $vehicle->status !== 'dipinjam') {
                            $vehicle->update(['status' => 'perlu_perhatian']);
                        }
                        $this->sendAlert(
                            $this->resolveRecipients($st, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'pajak',
                                title: "Uji KIR Kedaluwarsa: {$vehicle->no_polisi}",
                                message: "Masa berlaku uji KIR unit {$vehicle->nama_lengkap} telah habis pada {$vehicle->tanggal_kir_berlaku->format('d/m/Y')}. Kendaraan tidak laik jalan dinas.",
                                urgensi: 'danger',
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                } elseif ($days <= $t1) {
                    $this->line("<comment>[KIR MENDEKATI]</comment> {$vehicle->nama_lengkap} - {$days} hari lagi");
                    if (! $isDryRun) {
                        $this->sendAlert(
                            $this->resolveRecipients($st, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'pajak',
                                title: "Jatuh Tempo Uji KIR: {$vehicle->no_polisi}",
                                message: "Uji berkala KIR unit {$vehicle->nama_lengkap} akan berakhir dalam {$days} hari ({$vehicle->tanggal_kir_berlaku->format('d/m/Y')}).",
                                urgensi: 'warning',
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                }
            }

            // --- D. PEMERIKSAAN SERVIS BERKALA (KM & BULAN) ---
            $stServis = $settings->get('service');
            $kmDiff = ($vehicle->odometer_terakhir ?? 0) - ($vehicle->odometer_service_terakhir ?? 0);

            // Cek kilometer servis
            if ($vehicle->interval_service_km && $kmDiff >= $vehicle->interval_service_km) {
                $this->warn("[SERVIS JATUH TEMPO (KM)] {$vehicle->nama_lengkap} - Selisih {$kmDiff} km / Maks {$vehicle->interval_service_km} km");
                if (! $isDryRun) {
                    $this->sendAlert(
                        $this->resolveRecipients($stServis, $adminUsers, $garasiUsers),
                        new MaintenanceReminderNotification(
                            kategori: 'servis',
                            title: "Jadwal Servis Berkala (Odometer): {$vehicle->no_polisi}",
                            message: "Unit {$vehicle->nama_lengkap} telah menempuh {$kmDiff} km sejak servis terakhir (Batas: {$vehicle->interval_service_km} km). Jadwalkan servis rutin ke bengkel rekanan.",
                            urgensi: 'danger',
                            vehicleId: $vehicle->id
                        )
                    );
                    $alertsSent++;
                }
            } elseif ($vehicle->interval_service_km && $kmDiff >= ($vehicle->interval_service_km - 500)) {
                $this->line("<comment>[SERVIS MENDEKATI (KM)]</comment> {$vehicle->nama_lengkap} - Sisa " . ($vehicle->interval_service_km - $kmDiff) . " km lagi");
                if (! $isDryRun) {
                    $this->sendAlert(
                        $this->resolveRecipients($stServis, $adminUsers, $garasiUsers),
                        new MaintenanceReminderNotification(
                            kategori: 'servis',
                            title: "Mendekati Batas Servis: {$vehicle->no_polisi}",
                            message: "Unit {$vehicle->nama_lengkap} tersisa " . ($vehicle->interval_service_km - $kmDiff) . " km sebelum mencapai ambang servis berkala.",
                            urgensi: 'warning',
                            vehicleId: $vehicle->id
                        )
                    );
                    $alertsSent++;
                }
            }

            // Cek interval bulan servis
            if ($vehicle->interval_service_bulan && $vehicle->tanggal_service_terakhir) {
                $dueDate = $vehicle->tanggal_service_terakhir->copy()->addMonths($vehicle->interval_service_bulan);
                $days = $today->diffInDays($dueDate, false);

                if ($days < 0) {
                    $overdueDays = abs($days);
                    $this->warn("[SERVIS JATUH TEMPO (WAKTU)] {$vehicle->nama_lengkap} - Lewat {$overdueDays} hari ({$dueDate->format('d/m/Y')})");
                    if (! $isDryRun) {
                        $this->sendAlert(
                            $this->resolveRecipients($stServis, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'servis',
                                title: "Jadwal Servis Berkala (Waktu): {$vehicle->no_polisi}",
                                message: "Unit {$vehicle->nama_lengkap} telah melewati interval servis berkala ({$dueDate->format('d/m/Y')}).",
                                urgensi: 'warning',
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                } elseif ($days <= 14) {
                    $this->line("<comment>[SERVIS MENDEKATI (WAKTU)]</comment> {$vehicle->nama_lengkap} - {$days} hari lagi");
                    if (! $isDryRun) {
                        $this->sendAlert(
                            $this->resolveRecipients($stServis, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'servis',
                                title: "Pengingat Servis Berkala: {$vehicle->no_polisi}",
                                message: "Servis berkala unit {$vehicle->nama_lengkap} dijadwalkan dalam {$days} hari ({$dueDate->format('d/m/Y')}).",
                                urgensi: 'info',
                                vehicleId: $vehicle->id
                            )
                        );
                        $alertsSent++;
                    }
                }
            }
        }

        // 3. Periksa Seluruh Sopir Aktif (SIM)
        $stSim = $settings->get('sim_sopir');
        $drivers = Driver::where('status', 'aktif')->get();

        foreach ($drivers as $driver) {
            $driversChecked++;
            if ($driver->masa_berlaku_sim) {
                $days = $today->diffInDays($driver->masa_berlaku_sim, false);
                $t1 = $stSim->h_minus_tahap1 ?? 30;

                if ($days < 0) {
                    $overdueDays = abs($days);
                    $this->error("[SIM SOPIR KADALUARSA] {$driver->nama} (SIM {$driver->jenis_sim}) - Lewat {$overdueDays} hari!");
                    if (! $isDryRun) {
                        $this->sendAlert(
                            $this->resolveRecipients($stSim, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'sim',
                                title: "SIM Sopir Kedaluwarsa: {$driver->nama}",
                                message: "SIM {$driver->jenis_sim} atas nama {$driver->nama} telah habis masa berlakunya sejak {$driver->masa_berlaku_sim->format('d/m/Y')}. Segera lakukan perpanjangan SIM di Satpas Polres.",
                                urgensi: 'danger',
                                driverId: $driver->id
                            )
                        );
                        $alertsSent++;
                    }
                } elseif ($days <= $t1) {
                    $this->line("<comment>[SIM SOPIR MENDEKATI]</comment> {$driver->nama} - {$days} hari lagi");
                    if (! $isDryRun) {
                        $this->sendAlert(
                            $this->resolveRecipients($stSim, $adminUsers, $garasiUsers),
                            new MaintenanceReminderNotification(
                                kategori: 'sim',
                                title: "Pengingat Perpanjangan SIM: {$driver->nama}",
                                message: "SIM {$driver->jenis_sim} sopir {$driver->nama} akan kedaluwarsa dalam {$days} hari ({$driver->masa_berlaku_sim->format('d/m/Y')}).",
                                urgensi: $days <= 7 ? 'danger' : 'warning',
                                driverId: $driver->id
                            )
                        );
                        $alertsSent++;
                    }
                }
            }
        }

        $this->info("Pemeriksaan tuntas! Armada diperiksa: {$vehiclesChecked}, Sopir diperiksa: {$driversChecked}, Notifikasi terkirim: {$alertsSent}");

        return Command::SUCCESS;
    }

    /**
     * Resolusi daftar user penerima berdasarkan target_role pada setting.
     */
    protected function resolveRecipients(?ReminderSetting $setting, $adminUsers, $garasiUsers)
    {
        $targetRoles = $setting?->target_role ?? ['admin_it', 'kepala_garasi'];

        $recipients = collect();

        if (in_array('admin_it', $targetRoles)) {
            $recipients = $recipients->merge($adminUsers);
        }

        if (in_array('kepala_garasi', $targetRoles)) {
            $recipients = $recipients->merge($garasiUsers);
        }

        return $recipients->unique('id');
    }

    /**
     * Kirim notifikasi dengan pencegah spam duplikat di hari yang sama.
     */
    protected function sendAlert($recipients, MaintenanceReminderNotification $notification): void
    {
        foreach ($recipients as $user) {
            // Cek apakah user telah menerima notifikasi dengan judul yang sama hari ini
            $existsToday = $user->unreadNotifications()
                ->whereDate('created_at', Carbon::today())
                ->where('data->title', $notification->title)
                ->exists();

            if (! $existsToday) {
                $user->notify($notification);
            }
        }
    }
}
