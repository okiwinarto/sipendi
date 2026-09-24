<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Notifications\BookingDecidedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PersetujuanPimpinan extends Component
{
    use WithPagination;

    public string $activeTab = 'pending'; // 'pending' | 'history'
    public string $search = '';

    // State Modal Penolakan
    public bool $showRejectModal = false;
    public ?int $rejectBookingId = null;
    public ?Booking $rejectBooking = null;
    public string $alasan_penolakan = '';

    // State Modal Detail / Preview Surat Tugas
    public bool $showDetailModal = false;
    public ?Booking $detailBooking = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isPimpinan()) {
            abort(403, 'Akses terbatas untuk Pimpinan Direksi atau Administrator IT.');
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
     * Setujui pengajuan peminjaman dalam 1-klik (Executive Quick Decision).
     */
    public function setujui(int $bookingId): void
    {
        $booking = Booking::with('user')->findOrFail($bookingId);

        if ($booking->status !== 'diverifikasi_garasi') {
            session()->flash('warning', 'Pengajuan ini tidak dalam status siap disetujui Pimpinan.');
            return;
        }

        $user = Auth::user();

        $booking->update([
            'status' => 'disetujui',
        ]);

        BookingApproval::create([
            'booking_id' => $booking->id,
            'approver_id' => $user->id,
            'role_approval' => 'pimpinan',
            'tindakan' => 'setuju',
            'catatan' => 'Disetujui untuk penugasan kedinasan RSUD Sidawangi.',
            'waktu_tindakan' => Carbon::now(),
        ]);

        // Notifikasi ke Pemohon
        if ($booking->user) {
            $booking->user->notify(new BookingDecidedNotification($booking, 'disetujui'));
        }

        session()->flash('success', "Pengajuan {$booking->kode_peminjaman} untuk {$booking->user?->name} berhasil DISETUJUI.");
    }

    /**
     * Buka modal penolakan pimpinan.
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
     * Eksekusi penolakan pengajuan oleh Pimpinan.
     */
    public function tolakPengajuan(): void
    {
        $this->validate([
            'alasan_penolakan' => 'required|string|min:5|max:1000',
        ], [
            'alasan_penolakan.required' => 'Pimpinan wajib memberikan alasan penolakan untuk catatan kedinasan pemohon.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $booking = Booking::with('user')->findOrFail($this->rejectBookingId);
        $user = Auth::user();

        $booking->update([
            'status' => 'ditolak_pimpinan',
            'alasan_penolakan' => $this->alasan_penolakan,
        ]);

        BookingApproval::create([
            'booking_id' => $booking->id,
            'approver_id' => $user->id,
            'role_approval' => 'pimpinan',
            'tindakan' => 'tolak',
            'catatan' => $this->alasan_penolakan,
            'waktu_tindakan' => Carbon::now(),
        ]);

        // Notifikasi ke Pemohon
        if ($booking->user) {
            $booking->user->notify(new BookingDecidedNotification($booking, 'ditolak_pimpinan', $this->alasan_penolakan));
        }

        $this->closeRejectModal();
        session()->flash('warning', "Pengajuan {$booking->kode_peminjaman} telah ditolak oleh Pimpinan.");
    }

    /**
     * Buka modal rincian pengajuan & surat tugas.
     */
    public function openDetailModal(int $bookingId): void
    {
        $this->detailBooking = Booking::with(['user', 'unitKerja', 'vehicle', 'driver', 'approvals.approver'])->findOrFail($bookingId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->detailBooking = null;
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
                ->where('status', 'diverifikasi_garasi')
                ->orderByRaw("CASE WHEN tingkat_prioritas = 'mendesak' THEN 0 ELSE 1 END")
                ->orderBy('tanggal_berangkat', 'asc')
                ->paginate(6);
        } else {
            $bookings = (clone $query)
                ->whereIn('status', ['disetujui', 'ditolak_pimpinan', 'kendaraan_keluar', 'selesai'])
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        }

        $pendingCount = Booking::where('status', 'diverifikasi_garasi')->count();

        return view('livewire.portal.persetujuan-pimpinan', [
            'bookings' => $bookings,
            'pendingCount' => $pendingCount,
        ])->layout('layouts.portal');
    }
}
