<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RiwayatPeminjaman extends Component
{
    use WithPagination;

    public string $filterStatus = '';
    public string $search = '';

    public function cancelBooking(int $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->where('status', 'diajukan')
            ->firstOrFail();

        $booking->update([
            'status' => 'dibatalkan',
            'alasan_penolakan' => 'Dibatalkan oleh pemohon sebelum proses verifikasi garasi.',
        ]);

        session()->flash('success', "Pengajuan peminjaman {$booking->kode_peminjaman} berhasil dibatalkan.");
    }

    public function render()
    {
        $bookings = Booking::with(['vehicle.kategori', 'driver', 'unitKerja', 'approvals.approver', 'checkout.petugas', 'checkin.petugas'])
            ->where('user_id', Auth::id())
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('kode_peminjaman', 'like', "%{$this->search}%")
                        ->orWhere('tujuan_perjalanan', 'like', "%{$this->search}%")
                        ->orWhere('kota_tujuan', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.portal.riwayat-peminjaman', [
            'bookings' => $bookings,
        ])->layout('layouts.portal');
    }
}
