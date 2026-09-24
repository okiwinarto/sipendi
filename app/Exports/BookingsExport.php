<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $bookings;
    protected int $rowNumber = 0;

    public function __construct(Collection $bookings)
    {
        $this->bookings = $bookings;
    }

    public function collection(): Collection
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Peminjaman',
            'Tanggal Berangkat',
            'Jam Berangkat',
            'Tanggal Kembali',
            'Jam Kembali',
            'Nama Pemohon',
            'Unit Kerja',
            'Armada Kendaraan',
            'Nomor Polisi',
            'Jenis Pengemudi',
            'Pengemudi / Sopir',
            'Kota Tujuan',
            'Tujuan Kedinasan',
            'Jumlah Penumpang',
            'Odometer Keluar (km)',
            'Odometer Masuk (km)',
            'Jarak Tempuh (km)',
            'Status Peminjaman',
        ];
    }

    /**
     * @param Booking $booking
     */
    public function map($booking): array
    {
        $this->rowNumber++;

        $odoKeluar = $booking->checkout?->odometer_keluar;
        $odoMasuk = $booking->checkin?->odometer_masuk;
        $jarakTempuh = ($odoKeluar && $odoMasuk) ? max(0, $odoMasuk - $odoKeluar) : null;

        $sopirNama = match ($booking->jenis_pengemudi) {
            'sopir_dinas' => $booking->driver?->nama ?? 'Sopir Dinas Pool',
            'swakemudi' => 'Swakemudi (' . ($booking->user?->name ?? 'Pemohon') . ')',
            default => '-',
        };

        return [
            $this->rowNumber,
            $booking->kode_peminjaman,
            $booking->tanggal_berangkat?->format('d/m/Y') ?? '-',
            $booking->jam_berangkat ?? '-',
            $booking->tanggal_kembali_rencana?->format('d/m/Y') ?? '-',
            $booking->jam_kembali_rencana ?? '-',
            $booking->user?->name ?? '-',
            $booking->unitKerja?->nama_unit ?? '-',
            $booking->vehicle ? "{$booking->vehicle->merk} {$booking->vehicle->tipe_model}" : 'Belum Ditunjuk',
            $booking->vehicle?->no_polisi ?? '-',
            $booking->jenis_pengemudi === 'sopir_dinas' ? 'Sopir Dinas' : 'Swakemudi',
            $sopirNama,
            $booking->kota_tujuan ?? '-',
            $booking->tujuan_perjalanan ?? '-',
            $booking->jumlah_penumpang ?? 1,
            $odoKeluar ?? '-',
            $odoMasuk ?? '-',
            $jarakTempuh ?? '-',
            $booking->status_label,
        ];
    }
}
