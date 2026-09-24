<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\BookingSubmittedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class PeminjamanForm extends Component
{
    use WithFileUploads;

    public string $tujuan_perjalanan = '';
    public string $kota_tujuan = '';
    public string $tanggal_berangkat = '';
    public string $jam_berangkat = '08:00';
    public string $tanggal_kembali_rencana = '';
    public string $jam_kembali_rencana = '16:00';
    public int $jumlah_penumpang = 2;
    public string $jenis_pengemudi = 'sopir_dinas';
    public string $tingkat_prioritas = 'normal';
    public ?int $preferred_vehicle_id = null;
    public string $no_surat_tugas = '';
    public $file_surat_tugas;
    public string $catatan_pemohon = '';

    public ?string $conflictWarning = null;

    public function mount()
    {
        $requestedDate = request()->query('tanggal');
        if ($requestedDate && $requestedDate >= Carbon::today()->format('Y-m-d')) {
            $this->tanggal_berangkat = $requestedDate;
            $this->tanggal_kembali_rencana = $requestedDate;
        } else {
            $this->tanggal_berangkat = Carbon::today()->format('Y-m-d');
            $this->tanggal_kembali_rencana = Carbon::today()->format('Y-m-d');
        }

        $requestedVehicle = request()->query('kendaraan_id');
        if ($requestedVehicle) {
            $this->preferred_vehicle_id = (int) $requestedVehicle;
            $this->checkScheduleConflict();
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'tanggal_berangkat') {
            if ($this->tanggal_berangkat && $this->tanggal_berangkat < Carbon::today()->format('Y-m-d')) {
                $this->addError('tanggal_berangkat', 'Tanggal keberangkatan tidak boleh tanggal lampau (hanya bisa hari ini ke depan).');
            }
            if ($this->tanggal_kembali_rencana && $this->tanggal_kembali_rencana < $this->tanggal_berangkat) {
                $this->tanggal_kembali_rencana = $this->tanggal_berangkat;
            }
        }

        if ($propertyName === 'tanggal_kembali_rencana') {
            if ($this->tanggal_kembali_rencana && $this->tanggal_kembali_rencana < $this->tanggal_berangkat) {
                $this->tanggal_kembali_rencana = $this->tanggal_berangkat;
                $this->addError('tanggal_kembali_rencana', 'Tanggal rencana kembali harus sama atau setelah tanggal berangkat.');
            }
        }

        if (in_array($propertyName, ['preferred_vehicle_id', 'tanggal_berangkat', 'jam_berangkat', 'tanggal_kembali_rencana', 'jam_kembali_rencana'])) {
            $this->checkScheduleConflict();
        }
    }

    public function checkScheduleConflict()
    {
        $this->conflictWarning = null;

        if ($this->preferred_vehicle_id && $this->tanggal_berangkat && $this->jam_berangkat && $this->tanggal_kembali_rencana && $this->jam_kembali_rencana) {
            $hasConflict = Booking::checkOverlap(
                $this->preferred_vehicle_id,
                $this->tanggal_berangkat,
                $this->jam_berangkat,
                $this->tanggal_kembali_rencana,
                $this->jam_kembali_rencana
            )->exists();

            if ($hasConflict) {
                $vehicle = Vehicle::find($this->preferred_vehicle_id);
                $this->conflictWarning = "Perhatian: Kendaraan {$vehicle?->nama_lengkap} sudah memiliki jadwal terisi pada rentang waktu ini. Silakan pilih unit lain atau serahkan penentuan unit kepada Kepala Garasi.";
            }
        }
    }

    public function submit()
    {
        $this->validate([
            'tujuan_perjalanan' => 'required|string|min:5|max:500',
            'kota_tujuan' => 'required|string|max:100',
            'tanggal_berangkat' => 'required|date|after_or_equal:today',
            'jam_berangkat' => 'required',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_berangkat',
            'jam_kembali_rencana' => 'required',
            'jumlah_penumpang' => 'required|integer|min:1|max:30',
            'jenis_pengemudi' => 'required|in:sopir_dinas,swakemudi',
            'tingkat_prioritas' => 'required|in:normal,mendesak',
            'file_surat_tugas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'tanggal_berangkat.after_or_equal' => 'Tanggal keberangkatan tidak boleh tanggal lampau (hanya bisa hari ini ke depan).',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal rencana kembali harus sama atau setelah tanggal berangkat.',
        ]);

        if ($this->preferred_vehicle_id) {
            $prefVehicle = Vehicle::find($this->preferred_vehicle_id);
            if ($prefVehicle && ($prefVehicle->status === 'perlu_perhatian' || $prefVehicle->hasExpiredTax())) {
                $this->addError('preferred_vehicle_id', 'Kendaraan yang dipilih sedang memerlukan pemeliharaan atau pajak/KIR telah kedaluwarsa sehingga tidak laik dinas.');
                return;
            }
        }

        $filePath = null;
        if ($this->file_surat_tugas) {
            $filePath = $this->file_surat_tugas->store('surat_tugas', 'public');
        }

        $user = Auth::user();
        $unitKerja = $user->unitKerja;

        $kodePeminjaman = Booking::generateKodePeminjaman($unitKerja);

        $booking = Booking::create([
            'kode_peminjaman' => $kodePeminjaman,
            'user_id' => $user->id,
            'unit_kerja_id' => $unitKerja?->id ?? 1,
            'vehicle_id' => $this->preferred_vehicle_id,
            'jenis_pengemudi' => $this->jenis_pengemudi,
            'tujuan_perjalanan' => $this->tujuan_perjalanan,
            'kota_tujuan' => $this->kota_tujuan,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'jam_berangkat' => $this->jam_berangkat,
            'tanggal_kembali_rencana' => $this->tanggal_kembali_rencana,
            'jam_kembali_rencana' => $this->jam_kembali_rencana,
            'jumlah_penumpang' => $this->jumlah_penumpang,
            'tingkat_prioritas' => $this->tingkat_prioritas,
            'no_surat_tugas' => $this->no_surat_tugas ?: null,
            'file_surat_tugas' => $filePath,
            'catatan_pemohon' => $this->catatan_pemohon ?: null,
            'status' => 'diajukan',
        ]);

        // Kirim notifikasi ke Kepala Garasi
        $garasiUsers = User::role('kepala_garasi')->get();
        if ($garasiUsers->isEmpty()) {
            $garasiUsers = User::role('admin_it')->get();
        }
        foreach ($garasiUsers as $gu) {
            $gu->notify(new BookingSubmittedNotification($booking));
        }

        session()->flash('success', "Permohonan peminjaman berhasil diajukan dengan Kode Registrasi: {$kodePeminjaman}. Menunggu verifikasi dari Kepala Garasi.");

        return redirect()->route('portal.riwayat');
    }

    public function render()
    {
        // Hanya kendaraan yang siap jalan (status tersedia, bukan nonaktif, pajak tidak kedaluwarsa)
        $vehicles = Vehicle::with('kategori')
            ->whereIn('status', ['tersedia', 'dipinjam'])
            ->get()
            ->reject(fn ($v) => $v->hasExpiredTax());

        return view('livewire.portal.peminjaman-form', [
            'vehicles' => $vehicles,
            'userUnit' => Auth::user()->unitKerja,
        ])->layout('layouts.portal');
    }
}
