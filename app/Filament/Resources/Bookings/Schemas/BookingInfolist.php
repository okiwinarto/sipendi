<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_peminjaman'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('unitKerja.nama_unit')
                    ->label('Unit Kerja'),
                TextEntry::make('vehicle.nama_lengkap')
                    ->label('Armada Kendaraan')
                    ->placeholder('Belum ditugaskan (Menunggu Verifikasi Garasi)'),
                TextEntry::make('driver.nama_driver')
                    ->label('Driver Ditugaskan')
                    ->placeholder('Tanpa driver / Swakemudi'),
                TextEntry::make('jenis_pengemudi')
                    ->badge(),
                TextEntry::make('tujuan_perjalanan')
                    ->columnSpanFull(),
                TextEntry::make('kota_tujuan'),
                TextEntry::make('tanggal_berangkat')
                    ->date(),
                TextEntry::make('jam_berangkat')
                    ->time(),
                TextEntry::make('tanggal_kembali_rencana')
                    ->date(),
                TextEntry::make('jam_kembali_rencana')
                    ->time(),
                TextEntry::make('jumlah_penumpang')
                    ->numeric(),
                TextEntry::make('tingkat_prioritas')
                    ->badge(),
                TextEntry::make('no_surat_tugas')
                    ->placeholder('-'),
                TextEntry::make('file_surat_tugas')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('catatan_pemohon')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('alasan_penolakan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
