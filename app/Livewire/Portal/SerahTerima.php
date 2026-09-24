<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\VehicleCheckin;
use App\Models\VehicleCheckout;
use App\Notifications\VehicleHandoverNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SerahTerima extends Component
{
    use WithPagination, WithFileUploads;

    public string $activeTab = 'checkout'; // 'checkout' | 'checkin' | 'history'
    public string $search = '';

    // State Modal Checkout Keluar
    public bool $showCheckoutModal = false;
    public ?int $checkoutBookingId = null;
    public ?Booking $checkoutBooking = null;
    public ?int $odometer_keluar = null;
    public string $level_bbm_keluar = 'F';
    public string $kondisi_kendaraan_keluar = 'baik';
    public bool $checklist_ban_serep = true;
    public bool $checklist_dongkrak = true;
    public bool $checklist_kunci_roda = true;
    public bool $checklist_segitiga = true;
    public bool $checklist_p3k = true;
    public bool $checklist_stnk = true;
    public array $foto_kondisi_keluar = [];
    public string $catatan_keluar = '';
    public string $waktu_keluar = '';

    // State Modal Checkin Masuk
    public bool $showCheckinModal = false;
    public ?int $checkinBookingId = null;
    public ?Booking $checkinBooking = null;
    public ?int $odometer_masuk = null;
    public string $level_bbm_masuk = '1/2';
    public string $kondisi_kendaraan_masuk = 'baik';
    public bool $ada_kerusakan = false;
    public string $deskripsi_kerusakan = '';
    public bool $checklist_in_ban_serep = true;
    public bool $checklist_in_dongkrak = true;
    public bool $checklist_in_kunci_roda = true;
    public bool $checklist_in_segitiga = true;
    public bool $checklist_in_p3k = true;
    public bool $checklist_in_stnk = true;
    public int $rating_kondisi = 5;
    public array $foto_kondisi_masuk = [];
    public string $catatan_masuk = '';
    public string $waktu_masuk = '';

    // State Modal Berita Acara Serah Terima (BAST)
    public bool $showBastModal = false;
    public ?Booking $bastBooking = null;

    // State Modal QR Code Kendaraan
    public bool $showQrModal = false;
    public ?Vehicle $qrVehicle = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isKepalaGarasi()) {
            abort(403, 'Akses terbatas untuk Petugas Garasi atau Administrator IT.');
        }

        // Support direct vehicle filtering from QR scanner
        $vehicleId = request()->query('vehicle_id');
        if ($vehicleId) {
            $v = Vehicle::find($vehicleId);
            if ($v) {
                $this->search = $v->plat_nomor;
            }
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    /**
     * Buka modal Checkout Keluar
     */
    public function openCheckoutModal(int $bookingId): void
    {
        $this->checkoutBookingId = $bookingId;
        $this->checkoutBooking = Booking::with(['user', 'unitKerja', 'vehicle', 'driver'])->findOrFail($bookingId);

        // Baseline odometer dari data terakhir kendaraan
        $this->odometer_keluar = $this->checkoutBooking->vehicle?->odometer_terakhir ?? 0;
        $this->level_bbm_keluar = 'F';
        $this->kondisi_kendaraan_keluar = 'baik';
        $this->checklist_ban_serep = true;
        $this->checklist_dongkrak = true;
        $this->checklist_kunci_roda = true;
        $this->checklist_segitiga = true;
        $this->checklist_p3k = true;
        $this->checklist_stnk = true;
        $this->foto_kondisi_keluar = [];
        $this->catatan_keluar = '';
        $this->waktu_keluar = Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i');
        $this->showCheckoutModal = true;
    }

    public function closeCheckoutModal(): void
    {
        $this->showCheckoutModal = false;
        $this->checkoutBookingId = null;
        $this->checkoutBooking = null;
        $this->foto_kondisi_keluar = [];
        $this->waktu_keluar = '';
        $this->resetValidation();
    }

    /**
     * Eksekusi Serah Terima Keluar
     */
    public function prosesCheckout(): void
    {
        $booking = Booking::with('vehicle')->findOrFail($this->checkoutBookingId);
        $minOdo = $booking->vehicle?->odometer_terakhir ?? 0;

        $this->validate([
            'odometer_keluar' => "required|integer|min:{$minOdo}",
            'level_bbm_keluar' => 'required|in:E,1/4,1/2,3/4,F',
            'kondisi_kendaraan_keluar' => 'required|in:baik,perlu_perhatian',
            'catatan_keluar' => 'nullable|string|max:500',
            'foto_kondisi_keluar.*' => 'nullable|image|max:3072',
            'waktu_keluar' => 'required',
        ], [
            'odometer_keluar.min' => "Nilai odometer keluar tidak boleh lebih rendah dari odometer terakhir tercatat ({$minOdo} km).",
            'odometer_keluar.required' => 'Odometer keluar wajib dicatat.',
            'waktu_keluar.required' => 'Waktu serah terima keluar wajib diisi.',
        ]);

        $uploadedPhotos = [];
        if (!empty($this->foto_kondisi_keluar)) {
            foreach ($this->foto_kondisi_keluar as $foto) {
                $uploadedPhotos[] = $foto->store('checkouts', 'public');
            }
        }

        $checklist = [
            'ban_serep' => $this->checklist_ban_serep,
            'dongkrak' => $this->checklist_dongkrak,
            'kunci_roda' => $this->checklist_kunci_roda,
            'segitiga_pengaman' => $this->checklist_segitiga,
            'kotak_p3k' => $this->checklist_p3k,
            'stnk_asli' => $this->checklist_stnk,
        ];

        $waktuKeluarParsed = !empty($this->waktu_keluar)
            ? Carbon::parse($this->waktu_keluar, 'Asia/Jakarta')
            : Carbon::now('Asia/Jakarta');

        // Buat record VehicleCheckout
        VehicleCheckout::create([
            'booking_id' => $booking->id,
            'petugas_id' => Auth::id(),
            'odometer_keluar' => $this->odometer_keluar,
            'level_bbm_keluar' => $this->level_bbm_keluar,
            'kondisi_kendaraan' => $this->kondisi_kendaraan_keluar,
            'checklist_kelengkapan' => $checklist,
            'foto_kondisi' => !empty($uploadedPhotos) ? $uploadedPhotos : null,
            'catatan' => $this->catatan_keluar ?: null,
            'waktu_keluar' => $waktuKeluarParsed,
        ]);

        // Perbarui status booking & status armada
        $booking->update([
            'status' => 'kendaraan_keluar',
        ]);

        if ($booking->vehicle) {
            $booking->vehicle->update([
                'status' => 'dipinjam',
                'odometer_terakhir' => max($booking->vehicle->odometer_terakhir, $this->odometer_keluar),
            ]);
        }

        // Kirim notifikasi serah terima keluar ke pemohon
        if ($booking->user) {
            $booking->user->notify(new VehicleHandoverNotification(
                $booking,
                'checkout',
                $this->odometer_keluar,
                $this->level_bbm_keluar
            ));
        }

        $this->closeCheckoutModal();
        session()->flash('success', "Kendaraan {$booking->vehicle?->tipe_model} ({$booking->vehicle?->plat_nomor}) berhasil diserahkan keluar kepada peminjam.");
    }

    /**
     * Buka modal Checkin Masuk
     */
    public function openCheckinModal(int $bookingId): void
    {
        $this->checkinBookingId = $bookingId;
        $this->checkinBooking = Booking::with(['user', 'unitKerja', 'vehicle', 'driver', 'checkout'])->findOrFail($bookingId);

        $odoKeluar = $this->checkinBooking->checkout?->odometer_keluar ?? $this->checkinBooking->vehicle?->odometer_terakhir ?? 0;
        $this->odometer_masuk = $odoKeluar;
        $this->level_bbm_masuk = '1/2';
        $this->kondisi_kendaraan_masuk = 'baik';
        $this->ada_kerusakan = false;
        $this->deskripsi_kerusakan = '';
        $this->checklist_in_ban_serep = true;
        $this->checklist_in_dongkrak = true;
        $this->checklist_in_kunci_roda = true;
        $this->checklist_in_segitiga = true;
        $this->checklist_in_p3k = true;
        $this->checklist_in_stnk = true;
        $this->rating_kondisi = 5;
        $this->foto_kondisi_masuk = [];
        $this->catatan_masuk = '';
        $this->waktu_masuk = Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i');
        $this->showCheckinModal = true;
    }

    public function closeCheckinModal(): void
    {
        $this->showCheckinModal = false;
        $this->checkinBookingId = null;
        $this->checkinBooking = null;
        $this->foto_kondisi_masuk = [];
        $this->waktu_masuk = '';
        $this->resetValidation();
    }

    /**
     * Eksekusi Serah Terima Masuk
     */
    public function prosesCheckin(): void
    {
        $booking = Booking::with(['vehicle', 'checkout', 'user'])->findOrFail($this->checkinBookingId);
        $odoKeluar = $booking->checkout?->odometer_keluar ?? 0;

        $rules = [
            'odometer_masuk' => "required|integer|min:{$odoKeluar}",
            'level_bbm_masuk' => 'required|in:E,1/4,1/2,3/4,F',
            'kondisi_kendaraan_masuk' => 'required|in:baik,perlu_perhatian',
            'rating_kondisi' => 'required|integer|min:1|max:5',
            'catatan_masuk' => 'nullable|string|max:500',
            'foto_kondisi_masuk.*' => 'nullable|image|max:3072',
            'waktu_masuk' => 'required',
        ];

        if ($this->ada_kerusakan) {
            $rules['deskripsi_kerusakan'] = 'required|string|min:5|max:1000';
        }

        $this->validate($rules, [
            'odometer_masuk.min' => "Odometer masuk ({$this->odometer_masuk} km) tidak boleh lebih kecil dari odometer keluar ({$odoKeluar} km).",
            'deskripsi_kerusakan.required' => 'Jika ada kerusakan, mohon jelaskan indikasi atau bagian yang rusak.',
            'waktu_masuk.required' => 'Waktu pengembalian masuk wajib diisi.',
        ]);

        $uploadedPhotos = [];
        if (!empty($this->foto_kondisi_masuk)) {
            foreach ($this->foto_kondisi_masuk as $foto) {
                $uploadedPhotos[] = $foto->store('checkins', 'public');
            }
        }

        $checklist = [
            'ban_serep' => $this->checklist_in_ban_serep,
            'dongkrak' => $this->checklist_in_dongkrak,
            'kunci_roda' => $this->checklist_in_kunci_roda,
            'segitiga_pengaman' => $this->checklist_in_segitiga,
            'kotak_p3k' => $this->checklist_in_p3k,
            'stnk_asli' => $this->checklist_in_stnk,
        ];

        $waktuMasukParsed = !empty($this->waktu_masuk)
            ? Carbon::parse($this->waktu_masuk, 'Asia/Jakarta')
            : Carbon::now('Asia/Jakarta');

        // Buat record VehicleCheckin
        VehicleCheckin::create([
            'booking_id' => $booking->id,
            'petugas_id' => Auth::id(),
            'odometer_masuk' => $this->odometer_masuk,
            'level_bbm_masuk' => $this->level_bbm_masuk,
            'kondisi_kendaraan' => $this->kondisi_kendaraan_masuk,
            'ada_kerusakan' => $this->ada_kerusakan,
            'deskripsi_kerusakan' => $this->ada_kerusakan ? $this->deskripsi_kerusakan : null,
            'checklist_kelengkapan' => $checklist,
            'foto_kondisi' => !empty($uploadedPhotos) ? $uploadedPhotos : null,
            'rating_kondisi' => $this->rating_kondisi,
            'catatan' => $this->catatan_masuk ?: null,
            'waktu_masuk' => $waktuMasukParsed,
        ]);

        // Perbarui status booking ke selesai
        $booking->update([
            'status' => 'selesai',
        ]);

        // Perbarui status kendaraan & kilometer terakhir
        if ($booking->vehicle) {
            $newVehicleStatus = ($this->ada_kerusakan || $this->kondisi_kendaraan_masuk === 'perlu_perhatian')
                ? 'perlu_perhatian'
                : 'tersedia';

            $booking->vehicle->update([
                'status' => $newVehicleStatus,
                'odometer_terakhir' => $this->odometer_masuk,
            ]);
        }

        // Hitung jarak tempuh dinas
        $jarakTempuh = max(0, $this->odometer_masuk - $odoKeluar);

        // Kirim notifikasi serah terima masuk ke pemohon
        if ($booking->user) {
            $booking->user->notify(new VehicleHandoverNotification(
                $booking,
                'checkin',
                $this->odometer_masuk,
                $this->level_bbm_masuk,
                $jarakTempuh
            ));
        }

        $this->closeCheckinModal();
        session()->flash('success', "Kendaraan {$booking->vehicle?->tipe_model} telah diterima kembali di pool. Total jarak tempuh: " . number_format($jarakTempuh, 0, ',', '.') . " km.");
    }

    /**
     * Buka modal Berita Acara Serah Terima (BAST)
     */
    public function openBastModal(int $bookingId): void
    {
        $this->bastBooking = Booking::with([
            'user',
            'unitKerja',
            'vehicle',
            'driver',
            'checkout.petugas',
            'checkin.petugas',
        ])->findOrFail($bookingId);

        $this->showBastModal = true;
    }

    public function closeBastModal(): void
    {
        $this->showBastModal = false;
        $this->bastBooking = null;
    }

    /**
     * Tampilkan Modal QR Code Unit
     */
    public function openQrModal(int $vehicleId): void
    {
        $this->qrVehicle = Vehicle::with('garasi')->findOrFail($vehicleId);
        $this->showQrModal = true;
    }

    public function closeQrModal(): void
    {
        $this->showQrModal = false;
        $this->qrVehicle = null;
    }

    public function render()
    {
        $query = Booking::with(['user', 'unitKerja', 'vehicle', 'driver', 'checkout.petugas', 'checkin.petugas'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('kode_peminjaman', 'like', "%{$this->search}%")
                        ->orWhere('tujuan_perjalanan', 'like', "%{$this->search}%")
                        ->orWhere('kota_tujuan', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('vehicle', fn ($v) => $v->where('plat_nomor', 'like', "%{$this->search}%")->orWhere('tipe_model', 'like', "%{$this->search}%"));
                });
            });

        if ($this->activeTab === 'checkout') {
            $bookings = (clone $query)
                ->where('status', 'disetujui')
                ->orderBy('tanggal_berangkat', 'asc')
                ->paginate(8);
        } elseif ($this->activeTab === 'checkin') {
            $bookings = (clone $query)
                ->where('status', 'kendaraan_keluar')
                ->orderBy('tanggal_kembali_rencana', 'asc')
                ->paginate(8);
        } else {
            $bookings = (clone $query)
                ->whereIn('status', ['kendaraan_kembali', 'selesai'])
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        }

        $readyCheckoutCount = Booking::where('status', 'disetujui')->count();
        $inTransitCount = Booking::where('status', 'kendaraan_keluar')->count();

        // Daftar seluruh armada untuk panduan & QR code
        $vehicles = Vehicle::with('garasi')->orderBy('tipe_model', 'asc')->get();

        // Armada yang butuh perhatian servis atau pajak
        $maintenanceAlertVehicles = Vehicle::where('status', 'perlu_perhatian')
            ->orWhere(function ($q) {
                $q->whereNotNull('tanggal_pajak_tahunan')->where('tanggal_pajak_tahunan', '<', now()->addDays(30));
            })
            ->orWhere(function ($q) {
                $q->whereNotNull('tanggal_kir_berlaku')->where('tanggal_kir_berlaku', '<', now()->addDays(30));
            })
            ->get();

        return view('livewire.portal.serah-terima', [
            'bookings' => $bookings,
            'readyCheckoutCount' => $readyCheckoutCount,
            'inTransitCount' => $inTransitCount,
            'vehicles' => $vehicles,
            'maintenanceAlertVehicles' => $maintenanceAlertVehicles,
        ])->layout('layouts.portal');
    }
}
