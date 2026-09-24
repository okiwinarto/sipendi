<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_peminjaman')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('Pemohon')
                    ->description(fn ($record) => $record->unitKerja?->nama_unit ?? '-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('vehicle.plat_nomor')
                    ->label('Armada')
                    ->description(fn ($record) => $record->vehicle?->tipe_model ?? 'Menunggu Garasi')
                    ->placeholder('Belum ditugaskan')
                    ->searchable(),

                TextColumn::make('tujuan_perjalanan')
                    ->label('Tujuan')
                    ->limit(25)
                    ->description(fn ($record) => $record->kota_tujuan)
                    ->searchable(),

                TextColumn::make('tanggal_berangkat')
                    ->label('Mulai')
                    ->date('d/m/Y')
                    ->description(fn ($record) => substr($record->jam_berangkat, 0, 5) . ' WIB')
                    ->sortable(),

                TextColumn::make('tanggal_kembali_rencana')
                    ->label('Rencana Selesai')
                    ->date('d/m/Y')
                    ->description(fn ($record) => substr($record->jam_kembali_rencana, 0, 5) . ' WIB')
                    ->sortable(),

                TextColumn::make('tingkat_prioritas')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mendesak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn (string $state): string => match ($state) {
                        'diajukan' => 'warning',
                        'diverifikasi_garasi' => 'info',
                        'disetujui' => 'success',
                        'kendaraan_keluar' => 'primary',
                        'selesai' => 'gray',
                        'ditolak_garasi', 'ditolak_pimpinan' => 'danger',
                        'dibatalkan' => 'gray',
                        default => 'secondary',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pengajuan')
                    ->options([
                        'diajukan' => 'Diajukan (Baru)',
                        'diverifikasi_garasi' => 'Diverifikasi Garasi',
                        'disetujui' => 'Disetujui Pimpinan',
                        'kendaraan_keluar' => 'Kendaraan Keluar (Aktif)',
                        'selesai' => 'Selesai',
                        'ditolak_garasi' => 'Ditolak Garasi',
                        'ditolak_pimpinan' => 'Ditolak Pimpinan',
                        'dibatalkan' => 'Dibatalkan Pemohon',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('tingkat_prioritas')
                    ->label('Tingkat Prioritas')
                    ->options([
                        'normal' => 'Normal',
                        'mendesak' => 'Mendesak / Urgent',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                \Filament\Actions\Action::make('verifikasiGarasi')
                    ->label('Verifikasi Garasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'diajukan' && auth()->user()?->isKepalaGarasi())
                    ->modalHeading('Verifikasi Teknis & Penetapan Armada')
                    ->modalDescription('Tetapkan unit kendaraan dan sopir dinas yang laik jalan untuk permohonan ini.')
                    ->schema([
                        \Filament\Forms\Components\Select::make('vehicle_id')
                            ->label('Unit Kendaraan')
                            ->options(fn () => \App\Models\Vehicle::whereIn('status', ['tersedia', 'dipinjam'])->pluck('tipe_model', 'id'))
                            ->required(),
                        \Filament\Forms\Components\Select::make('driver_id')
                            ->label('Sopir Dinas Pool')
                            ->options(fn () => \App\Models\Driver::where('status', 'aktif')->pluck('nama', 'id'))
                            ->visible(fn ($record) => $record->jenis_pengemudi === 'sopir_dinas')
                            ->required(fn ($record) => $record->jenis_pengemudi === 'sopir_dinas'),
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Kesiapan Armada')
                            ->placeholder('Unit kendaraan siap jalan...'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'vehicle_id' => $data['vehicle_id'],
                            'driver_id' => $record->jenis_pengemudi === 'sopir_dinas' ? ($data['driver_id'] ?? null) : null,
                            'status' => 'diverifikasi_garasi',
                        ]);

                        \App\Models\BookingApproval::create([
                            'booking_id' => $record->id,
                            'approver_id' => auth()->id(),
                            'role_approval' => 'kepala_garasi',
                            'tindakan' => 'setuju',
                            'catatan' => $data['catatan'] ?: 'Diverifikasi teknis oleh Kepala Garasi.',
                            'waktu_tindakan' => now(),
                        ]);

                        $pimpinanUsers = \App\Models\User::role('pimpinan')->get();
                        if ($pimpinanUsers->isEmpty()) {
                            $pimpinanUsers = \App\Models\User::role('admin_it')->get();
                        }
                        foreach ($pimpinanUsers as $pu) {
                            $pu->notify(new \App\Notifications\BookingVerifiedNotification($record));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Pengajuan Diverifikasi')
                            ->body("Pengajuan {$record->kode_peminjaman} berhasil diverifikasi dan diteruskan ke Pimpinan.")
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('persetujuanPimpinan')
                    ->label('Setujui Pimpinan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'diverifikasi_garasi' && auth()->user()?->isPimpinan())
                    ->requiresConfirmation()
                    ->modalHeading('Persetujuan Pimpinan (Direksi)')
                    ->modalDescription('Apakah Anda menyetujui permohonan peminjaman ini untuk penugasan kedinasan?')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'disetujui',
                        ]);

                        \App\Models\BookingApproval::create([
                            'booking_id' => $record->id,
                            'approver_id' => auth()->id(),
                            'role_approval' => 'pimpinan',
                            'tindakan' => 'setuju',
                            'catatan' => 'Disetujui oleh Pimpinan.',
                            'waktu_tindakan' => now(),
                        ]);

                        if ($record->user) {
                            $record->user->notify(new \App\Notifications\BookingDecidedNotification($record, 'disetujui'));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Pengajuan Disetujui')
                            ->body("Pengajuan {$record->kode_peminjaman} telah disetujui.")
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('tolakPengajuan')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['diajukan', 'diverifikasi_garasi']) && (auth()->user()?->isKepalaGarasi() || auth()->user()?->isPimpinan()))
                    ->modalHeading('Tolak Permohonan Peminjaman')
                    ->schema([
                        \Filament\Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Sebutkan kendala atau alasan penolakan...'),
                    ])
                    ->action(function ($record, array $data) {
                        $isGarasi = $record->status === 'diajukan';
                        $newStatus = $isGarasi ? 'ditolak_garasi' : 'ditolak_pimpinan';
                        $roleApproval = $isGarasi ? 'kepala_garasi' : 'pimpinan';

                        $record->update([
                            'status' => $newStatus,
                            'alasan_penolakan' => $data['alasan_penolakan'],
                        ]);

                        \App\Models\BookingApproval::create([
                            'booking_id' => $record->id,
                            'approver_id' => auth()->id(),
                            'role_approval' => $roleApproval,
                            'tindakan' => 'tolak',
                            'catatan' => $data['alasan_penolakan'],
                            'waktu_tindakan' => now(),
                        ]);

                        if ($record->user) {
                            $record->user->notify(new \App\Notifications\BookingDecidedNotification($record, $newStatus, $data['alasan_penolakan']));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Pengajuan Ditolak')
                            ->body("Pengajuan {$record->kode_peminjaman} telah ditolak.")
                            ->warning()
                            ->send();
                    }),

                \Filament\Actions\Action::make('prosesCheckout')
                    ->label('Checkout Keluar')
                    ->icon('heroicon-o-arrow-up-right')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'disetujui' && auth()->user()?->isKepalaGarasi())
                    ->modalHeading('Serah Terima Keluar (Checkout)')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('odometer_keluar')
                            ->label('Odometer Keluar (km)')
                            ->numeric()
                            ->default(fn ($record) => $record->vehicle?->odometer_terakhir ?? 0)
                            ->required(),
                        \Filament\Forms\Components\Select::make('level_bbm_keluar')
                            ->label('Level Bahan Bakar')
                            ->options([
                                'F' => 'F (Penuh / Full)',
                                '3/4' => '3/4 Tangki',
                                '1/2' => '1/2 Tangki',
                                '1/4' => '1/4 Tangki',
                                'E' => 'E (Rendah / Empty)',
                            ])
                            ->default('F')
                            ->required(),
                        \Filament\Forms\Components\Select::make('kondisi_kendaraan')
                            ->label('Kondisi Fisik')
                            ->options([
                                'baik' => 'Baik & Siap Jalan',
                                'perlu_perhatian' => 'Perlu Perhatian',
                            ])
                            ->default('baik')
                            ->required(),
                        \Filament\Forms\Components\DateTimePicker::make('waktu_keluar')
                            ->label('Waktu Serah Terima Keluar (WIB)')
                            ->timezone('Asia/Jakarta')
                            ->default(now('Asia/Jakarta'))
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Petugas'),
                    ])
                    ->action(function ($record, array $data) {
                        \App\Models\VehicleCheckout::create([
                            'booking_id' => $record->id,
                            'petugas_id' => auth()->id(),
                            'odometer_keluar' => $data['odometer_keluar'],
                            'level_bbm_keluar' => $data['level_bbm_keluar'],
                            'kondisi_kendaraan' => $data['kondisi_kendaraan'],
                            'checklist_kelengkapan' => [
                                'ban_serep' => true,
                                'dongkrak' => true,
                                'kunci_roda' => true,
                                'segitiga_pengaman' => true,
                                'kotak_p3k' => true,
                                'stnk_asli' => true,
                            ],
                            'catatan' => $data['catatan'] ?? null,
                            'waktu_keluar' => !empty($data['waktu_keluar']) ? \Carbon\Carbon::parse($data['waktu_keluar'], 'Asia/Jakarta') : now('Asia/Jakarta'),
                        ]);

                        $record->update(['status' => 'kendaraan_keluar']);

                        if ($record->vehicle) {
                            $record->vehicle->update([
                                'status' => 'dipinjam',
                                'odometer_terakhir' => max($record->vehicle->odometer_terakhir, $data['odometer_keluar']),
                            ]);
                        }

                        if ($record->user) {
                            $record->user->notify(new \App\Notifications\VehicleHandoverNotification(
                                $record,
                                'checkout',
                                $data['odometer_keluar'],
                                $data['level_bbm_keluar']
                            ));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Kendaraan Diserahkan')
                            ->body("Kendaraan {$record->vehicle?->tipe_model} resmi diserahkan keluar.")
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('prosesCheckin')
                    ->label('Checkin Masuk')
                    ->icon('heroicon-o-arrow-down-left')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'kendaraan_keluar' && auth()->user()?->isKepalaGarasi())
                    ->modalHeading('Serah Terima Masuk (Pengembalian)')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('odometer_masuk')
                            ->label('Odometer Kembali (km)')
                            ->numeric()
                            ->default(fn ($record) => $record->checkout?->odometer_keluar ?? 0)
                            ->required(),
                        \Filament\Forms\Components\Select::make('level_bbm_masuk')
                            ->label('Level Bahan Bakar')
                            ->options([
                                'F' => 'F (Penuh / Full)',
                                '3/4' => '3/4 Tangki',
                                '1/2' => '1/2 Tangki',
                                '1/4' => '1/4 Tangki',
                                'E' => 'E (Rendah / Empty)',
                            ])
                            ->default('1/2')
                            ->required(),
                        \Filament\Forms\Components\Select::make('rating_kondisi')
                            ->label('Rating Kelaikan (1-5)')
                            ->options([
                                5 => '★★★★★ Sangat Prima',
                                4 => '★★★★☆ Baik',
                                3 => '★★★☆☆ Cukup',
                                2 => '★★☆☆☆ Kurang',
                                1 => '★☆☆☆☆ Rusak',
                            ])
                            ->default(5)
                            ->required(),
                        \Filament\Forms\Components\DateTimePicker::make('waktu_masuk')
                            ->label('Waktu Pengembalian Masuk (WIB)')
                            ->timezone('Asia/Jakarta')
                            ->default(now('Asia/Jakarta'))
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Pengembalian'),
                    ])
                    ->action(function ($record, array $data) {
                        $odoKeluar = $record->checkout?->odometer_keluar ?? 0;
                        $jarakTempuh = max(0, $data['odometer_masuk'] - $odoKeluar);

                        \App\Models\VehicleCheckin::create([
                            'booking_id' => $record->id,
                            'petugas_id' => auth()->id(),
                            'odometer_masuk' => $data['odometer_masuk'],
                            'level_bbm_masuk' => $data['level_bbm_masuk'],
                            'kondisi_kendaraan' => 'baik',
                            'ada_kerusakan' => false,
                            'checklist_kelengkapan' => [
                                'ban_serep' => true,
                                'dongkrak' => true,
                                'kunci_roda' => true,
                                'segitiga_pengaman' => true,
                                'kotak_p3k' => true,
                                'stnk_asli' => true,
                            ],
                            'rating_kondisi' => $data['rating_kondisi'],
                            'catatan' => $data['catatan'] ?? null,
                            'waktu_masuk' => !empty($data['waktu_masuk']) ? \Carbon\Carbon::parse($data['waktu_masuk'], 'Asia/Jakarta') : now('Asia/Jakarta'),
                        ]);

                        $record->update(['status' => 'selesai']);

                        if ($record->vehicle) {
                            $record->vehicle->update([
                                'status' => 'tersedia',
                                'odometer_terakhir' => $data['odometer_masuk'],
                            ]);
                        }

                        if ($record->user) {
                            $record->user->notify(new \App\Notifications\VehicleHandoverNotification(
                                $record,
                                'checkin',
                                $data['odometer_masuk'],
                                $data['level_bbm_masuk'],
                                $jarakTempuh
                            ));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Kendaraan Diterima')
                            ->body("Pengembalian {$record->vehicle?->tipe_model} selesai tercatat. Jarak tempuh: {$jarakTempuh} km.")
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
