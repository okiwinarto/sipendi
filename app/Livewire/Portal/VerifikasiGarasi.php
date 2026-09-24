<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\BookingDecidedNotification;
use App\Notifications\BookingVerifiedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class VerifikasiGarasi extends Component
{
    use WithPagination;

    public string $activeTab = 'pending'; // 'pending' | 'history'
    public string $search = '';

    // State Modal Verifikasi & Penetapan Unit
    public bool $showVerifyModal = false;
    public ?int $selectedBookingId = null;
    public ?Booking $selectedBooking = null;
    public ?int $vehicle_id = null;
    public ?int $driver_id = null;
    public string $catatan_garasi = '';
    public ?string $conflictWarning = null;

    // State Modal Penolakan
    public bool $showRejectModal = false;
    public ?int $rejectBookingId = null;
    public ?Booking $rejectBooking = null;
    public string $alasan_penolakan = '';

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isKepalaGarasi()) {
            abort(403, 'Akses terbatas untuk Kepala Garasi atau Administrator IT.');
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
     * Buka modal verifikasi teknis dan penetapan armada/sopir.
     */
    public function openVerifyModal(int $bookingId): void
    {
        $this->selectedBookingId = $bookingId;
        $this->selectedBooking = Booking::with(['user', 'unitKerja', 'vehicle', 'driver'])->findOrFail($bookingId);
        $this->vehicle_id = $this->selectedBooking->vehicle_id;
        $this->driver_id = $this->selectedBooking->driver_id;
        $this->catatan_garasi = '';
        $this->conflictWarning = null;
        $this->showVerifyModal = true;

        if ($this->vehicle_id) {
            $this->checkVehicleConflict();
        }
    }

    public function closeVerifyModal(): void
    {
        $this->showVerifyModal = false;
        $this->selectedBookingId = null;
        $this->selectedBooking = null;
        $this->vehicle_id = null;
        $this->driver_id = null;
        $this->catatan_garasi = '';
        $this->conflictWarning = null;
        $this->resetValidation();
    }

    /**
     * Pantau perubahan pilihan kendaraan untuk mendeteksi potensi bentrok.
     */
    public function updatedVehicleId(): void
    {
        $this->checkVehicleConflict();
    }

    public function checkVehicleConflict(): void
    {
        $this->conflictWarning = null;
        if (!$this->vehicle_id || !$this->selectedBooking) {
            return;
        }

        $isConflict = Booking::checkOverlap(
            $this->vehicle_id,
            $this->selectedBooking->tanggal_berangkat->format('Y-m-d'),
            $this->selectedBooking->jam_berangkat,
            $this->selectedBooking->tanggal_kembali_rencana->format('Y-m-d'),
            $this->selectedBooking->jam_kembali_rencana,
            $this->selectedBooking->id
        )->exists();

        if ($isConflict) {
            $vehicle = Vehicle::find($this->vehicle_id);
            $this->conflictWarning = "Perhatian: Unit {$vehicle?->tipe_model} ({$vehicle?->plat_nomor}) sudah memiliki jadwal lain pada rentang waktu ini!";
        }
    }

    /**
     * Verifikasi teknis armada dan teruskan ke Pimpinan.
     */
    public function verifikasiDanTeruskan(): void
    {
        $booking = Booking::with(['user', 'unitKerja'])->findOrFail($this->selectedBookingId);

        $rules = [
            'vehicle_id' => 'required|exists:vehicles,id',
        ];

        if ($booking->jenis_pengemudi === 'sopir_dinas') {
            $rules['driver_id'] = 'required|exists:drivers,id';
        }

        $this->validate($rules, [
            'vehicle_id.required' => 'Kepala Garasi wajib menetapkan unit armada kendaraan definitif.',
            'driver_id.required' => 'Permohonan dengan sopir dinas wajib menetapkan petugas sopir yang bertugas.',
        ]);

        // Cek bentrok final
        $isConflict = Booking::checkOverlap(
            $this->vehicle_id,
            $booking->tanggal_berangkat->format('Y-m-d'),
            $booking->jam_berangkat,
            $booking->tanggal_kembali_rencana->format('Y-m-d'),
            $booking->jam_kembali_rencana,
            $booking->id
        )->exists();

        if ($isConflict) {
            $this->addError('vehicle_id', 'Armada ini memiliki bentrok jadwal aktif. Silakan pilih kendaraan lain.');
            return;
        }

        $selectedVehicle = Vehicle::find($this->vehicle_id);
        if ($selectedVehicle && ($selectedVehicle->status === 'perlu_perhatian' || $selectedVehicle->hasExpiredTax())) {
            $this->addError('vehicle_id', 'Armada ini sedang memerlukan pemeliharaan atau masa berlaku pajak/KIR telah kedaluwarsa sehingga tidak laik dinas.');
            return;
        }

        $user = Auth::user();

        // Update booking
        $booking->update([
            'vehicle_id' => $this->vehicle_id,
            'driver_id' => $booking->jenis_pengemudi === 'sopir_dinas' ? $this->driver_id : null,
            'status' => 'diverifikasi_garasi',
        ]);

        // Rekam approval audit trail
        BookingApproval::create([
            'booking_id' => $booking->id,
            'approver_id' => $user->id,
            'role_approval' => 'kepala_garasi',
            'tindakan' => 'setuju',
            'catatan' => $this->catatan_garasi ?: 'Kelaikan teknis armada dan pengemudi terverifikasi siap bertugas.',
            'waktu_tindakan' => Carbon::now(),
        ]);

        // Notifikasi ke Pimpinan
        $pimpinanUsers = User::role('pimpinan')->get();
        if ($pimpinanUsers->isEmpty()) {
            $pimpinanUsers = User::role('admin_it')->get();
        }
        foreach ($pimpinanUsers as $pu) {
            $pu->notify(new BookingVerifiedNotification($booking));
        }

        $this->closeVerifyModal();
        session()->flash('success', "Permohonan {$booking->kode_peminjaman} berhasil diverifikasi dan diteruskan ke Pimpinan untuk persetujuan akhir.");
    }

    /**
     * Buka modal tolak pengajuan garasi.
     */
    public function openRejectModal(int $bookingId): void
    {
        $this->rejectBookingId = $bookingId;
        $this->rejectBooking = Booking::with(['user', 'unitKerja'])->findOrFail($bookingId);
        $this->alasan_penolakan = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->rejectBookingId = null;
        $this->rejectBooking = null;
        $this->alasan_penolakan = '';
        $this->resetValidation();
    }

    /**
     * Tolak pengajuan dari sisi garasi dengan alasan wajib.
     */
    public function tolakPengajuan(): void
    {
        $this->validate([
            'alasan_penolakan' => 'required|string|min:5|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi untuk transparansi kepada pemohon.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $booking = Booking::with('user')->findOrFail($this->rejectBookingId);
        $user = Auth::user();

        $booking->update([
            'status' => 'ditolak_garasi',
            'alasan_penolakan' => $this->alasan_penolakan,
        ]);

        BookingApproval::create([
            'booking_id' => $booking->id,
            'approver_id' => $user->id,
            'role_approval' => 'kepala_garasi',
            'tindakan' => 'tolak',
            'catatan' => $this->alasan_penolakan,
            'waktu_tindakan' => Carbon::now(),
        ]);

        // Notifikasi ke Pemohon
        if ($booking->user) {
            $booking->user->notify(new BookingDecidedNotification($booking, 'ditolak_garasi', $this->alasan_penolakan));
        }

        $this->closeRejectModal();
        session()->flash('warning', "Pengajuan {$booking->kode_peminjaman} telah ditolak.");
    }

    public function render()
    {
        $query = Booking::with(['user', 'unitKerja', 'vehicle', 'driver', 'approvals.approver'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('kode_peminjaman', 'like', "%{$this->search}%")
                        ->orWhere('tujuan_perjalanan', 'like', "%{$this->search}%")
                        ->orWhere('kota_tujuan', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('unitKerja', fn ($uk) => $uk->where('nama_unit', 'like', "%{$this->search}%"));
                });
            });

        if ($this->activeTab === 'pending') {
            $bookings = (clone $query)
                ->where('status', 'diajukan')
                ->orderByRaw("CASE WHEN tingkat_prioritas = 'mendesak' THEN 0 ELSE 1 END")
                ->orderBy('tanggal_berangkat', 'asc')
                ->orderBy('id', 'asc')
                ->paginate(10);
        } else {
            $bookings = (clone $query)
                ->whereIn('status', ['diverifikasi_garasi', 'ditolak_garasi', 'disetujui', 'ditolak_pimpinan', 'kendaraan_keluar', 'selesai', 'dibatalkan'])
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        }

        $pendingCount = Booking::where('status', 'diajukan')->count();

        // Daftar armada yang laik operasi
        $vehicles = Vehicle::with('kategori')
            ->whereIn('status', ['tersedia', 'dipinjam'])
            ->orderBy('tipe_model', 'asc')
            ->get();

        // Daftar sopir aktif
        $drivers = Driver::where('status', 'aktif')
            ->orderBy('nama', 'asc')
            ->get();

        return view('livewire.portal.verifikasi-garasi', [
            'bookings' => $bookings,
            'pendingCount' => $pendingCount,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
        ])->layout('layouts.portal');
    }
}
