<div class="space-y-6">
    <!-- Breadcrumb & Header Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none">
            <svg class="w-64 h-64 text-sky-900" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100/90 border border-sky-200 text-sky-800 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-sky-600 animate-pulse"></span>
                    <span>Pelacakan Status & Arsip Perjalanan Dinas</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Peminjaman Kendaraan Saya</h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 max-w-xl leading-relaxed">
                    Pantau tahapan verifikasi Kepala Garasi, persetujuan Pimpinan, hingga jadwal keberangkatan armada secara transparan dan mandiri.
                </p>
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('portal.ajukan') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-bold text-xs shadow-md shadow-sky-600/25 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Ajukan Peminjaman Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Status Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center gap-4">
        <!-- Search Input -->
        <div class="w-full sm:flex-1 relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari kode peminjaman (SPD/...), tujuan dinas, atau kota..."
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:outline-hidden transition-colors">
        </div>

        <!-- Filter Status Dropdown -->
        <div class="w-full sm:w-auto">
            <select wire:model.live="filterStatus"
                class="w-full sm:w-64 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:outline-hidden transition-colors">
                <option value="">Semua Status Pengajuan</option>
                <option value="diajukan">🟡 Menunggu Verifikasi Garasi</option>
                <option value="diverifikasi_garasi">🔵 Menunggu Persetujuan Pimpinan</option>
                <option value="disetujui">🟢 Telah Disetujui (Siap Berangkat)</option>
                <option value="kendaraan_keluar">🚗 Kendaraan Sedang Digunakan</option>
                <option value="selesai">✅ Selesai (Kembali ke Pool)</option>
                <option value="ditolak_garasi">❌ Ditolak oleh Garasi</option>
                <option value="ditolak_pimpinan">❌ Ditolak oleh Pimpinan</option>
                <option value="dibatalkan">⚪ Dibatalkan Pemohon</option>
            </select>
        </div>
    </div>

    <!-- Bookings Cards Feed -->
    @if ($bookings->count() > 0)
        <div class="space-y-5">
            @foreach ($bookings as $b)
                @php
                    $isDitolak = in_array($b->status, ['ditolak_garasi', 'ditolak_pimpinan', 'dibatalkan']);
                    $isSelesai = $b->status === 'selesai';
                    $isBerjalan = $b->status === 'kendaraan_keluar';
                    $isDisetujui = in_array($b->status, ['disetujui', 'kendaraan_keluar', 'selesai']);
                    $isDiverifikasi = in_array($b->status, ['diverifikasi_garasi', 'disetujui', 'kendaraan_keluar', 'selesai']);

                    // Progress percentage for stepper line
                    $progressWidth = '0%';
                    if ($b->status === 'diverifikasi_garasi') $progressWidth = '25%';
                    elseif ($b->status === 'disetujui') $progressWidth = '50%';
                    elseif ($b->status === 'kendaraan_keluar') $progressWidth = '75%';
                    elseif ($b->status === 'selesai') $progressWidth = '100%';

                    $badgeColors = [
                        'diajukan' => 'bg-amber-100 text-amber-900 border-amber-200',
                        'diverifikasi_garasi' => 'bg-indigo-100 text-indigo-900 border-indigo-200',
                        'disetujui' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
                        'kendaraan_keluar' => 'bg-sky-100 text-sky-900 border-sky-200',
                        'kendaraan_kembali' => 'bg-teal-100 text-teal-900 border-teal-200',
                        'selesai' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        'ditolak_garasi' => 'bg-rose-100 text-rose-900 border-rose-200',
                        'ditolak_pimpinan' => 'bg-rose-100 text-rose-900 border-rose-200',
                        'dibatalkan' => 'bg-slate-100 text-slate-700 border-slate-200',
                    ];
                @endphp

                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:border-sky-300 transition-all space-y-5">
                    <!-- Top Info Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="font-extrabold text-slate-900 text-base sm:text-lg tracking-tight">{{ $b->kode_peminjaman }}</span>
                            <span class="text-xs px-3 py-0.5 rounded-full border font-bold {{ $badgeColors[$b->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $b->status_label }}
                            </span>
                            @if ($b->tingkat_prioritas === 'mendesak')
                                <span class="text-[10px] px-2.5 py-0.5 rounded-md bg-rose-500 text-white font-extrabold uppercase tracking-wider">
                                    Cito / Mendesak
                                </span>
                            @endif
                        </div>
                        <div class="text-[11px] text-slate-400 font-medium">
                            Diregistrasi: {{ $b->created_at->translatedFormat('d F Y, H:i') }} WIB
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                        <!-- Col 1: Destination -->
                        <div class="space-y-1.5">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] block">Maksud Perjalanan Dinas:</span>
                            <p class="font-bold text-slate-900 text-sm leading-snug">{{ $b->tujuan_perjalanan }}</p>
                            <span class="inline-flex items-center gap-1.5 text-sky-700 font-bold text-xs mt-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span>Wilayah: {{ $b->kota_tujuan }}</span>
                            </span>
                        </div>

                        <!-- Col 2: Schedule -->
                        <div class="space-y-1.5">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] block">Jadwal Penugasan:</span>
                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Berangkat:</span>
                                    <strong class="text-slate-800">{{ $b->tanggal_berangkat->translatedFormat('d M Y') }} • {{ substr($b->jam_berangkat, 0, 5) }} WIB</strong>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Kembali:</span>
                                    <strong class="text-slate-800">{{ $b->tanggal_kembali_rencana->translatedFormat('d M Y') }} • {{ substr($b->jam_kembali_rencana, 0, 5) }} WIB</strong>
                                </div>
                            </div>
                            <span class="text-slate-500 text-[11px] block mt-0.5">Penumpang: <strong class="text-slate-700">{{ $b->jumlah_penumpang }} Orang</strong></span>
                        </div>

                        <!-- Col 3: Vehicle & Driver -->
                        <div class="space-y-1.5">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] block">Armada & Penugasan Sopir:</span>
                            @if ($b->vehicle)
                                <div class="p-2.5 rounded-xl bg-sky-50/60 border border-sky-100 space-y-0.5">
                                    <span class="font-extrabold text-slate-900 text-xs block">{{ $b->vehicle->nama_lengkap }}</span>
                                    <span class="text-[11px] text-slate-500 font-mono">{{ $b->vehicle->no_polisi }} ({{ $b->vehicle->kategori?->nama_kategori }})</span>
                                </div>
                            @else
                                <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-100 text-amber-800 text-xs font-semibold">
                                    Menunggu verifikasi kelaikan teknis & penetapan unit oleh Kepala Garasi
                                </div>
                            @endif

                            <p class="text-slate-600 mt-1">
                                Pengemudi: <strong class="text-slate-900">{{ $b->jenis_pengemudi === 'sopir_dinas' ? ($b->driver?->nama_driver ?? 'Sopir Dinas Pool') : 'Swakemudi (Bawa Sendiri)' }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Rejection Alert -->
                    @if ($isDitolak && $b->alasan_penolakan)
                        <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-800 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <strong class="font-bold text-rose-950">Catatan Penolakan / Alasan:</strong>
                                <span class="text-rose-900">{{ $b->alasan_penolakan }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Perfectly Aligned Progress Timeline Stepper -->
                    @if (! $isDitolak)
                        <div class="pt-4 border-t border-slate-100">
                            <div class="max-w-xl mx-auto px-4">
                                <div class="relative">
                                    <!-- Background Track Line (Top centered at 14px) -->
                                    <div class="absolute top-3.5 left-6 right-6 h-0.5 bg-slate-200">
                                        <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $progressWidth }};"></div>
                                    </div>

                                    <!-- Stepper Items -->
                                    <div class="relative z-10 flex items-start justify-between text-center">
                                        <!-- Step 1: Diajukan -->
                                        <div class="flex flex-col items-center w-16">
                                            <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                                                ✓
                                            </div>
                                            <span class="text-[10px] sm:text-[11px] font-bold text-emerald-700 mt-1.5">Diajukan</span>
                                        </div>

                                        <!-- Step 2: Verifikasi Garasi -->
                                        <div class="flex flex-col items-center w-16">
                                            <div class="w-7 h-7 rounded-full {{ $isDiverifikasi ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                                                {{ $isDiverifikasi ? '✓' : '2' }}
                                            </div>
                                            <span class="text-[10px] sm:text-[11px] font-bold {{ $isDiverifikasi ? 'text-emerald-700' : 'text-slate-400' }} mt-1.5">Garasi</span>
                                        </div>

                                        <!-- Step 3: Persetujuan Pimpinan -->
                                        <div class="flex flex-col items-center w-16">
                                            <div class="w-7 h-7 rounded-full {{ $isDisetujui ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                                                {{ $isDisetujui ? '✓' : '3' }}
                                            </div>
                                            <span class="text-[10px] sm:text-[11px] font-bold {{ $isDisetujui ? 'text-emerald-700' : 'text-slate-400' }} mt-1.5">Pimpinan</span>
                                        </div>

                                        <!-- Step 4: Checkout -->
                                        <div class="flex flex-col items-center w-16">
                                            <div class="w-7 h-7 rounded-full {{ $isBerjalan || $isSelesai ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                                                {{ $isBerjalan || $isSelesai ? '✓' : '4' }}
                                            </div>
                                            <span class="text-[10px] sm:text-[11px] font-bold {{ $isBerjalan || $isSelesai ? 'text-emerald-700' : 'text-slate-400' }} mt-1.5">Checkout</span>
                                        </div>

                                        <!-- Step 5: Selesai -->
                                        <div class="flex flex-col items-center w-16">
                                            <div class="w-7 h-7 rounded-full {{ $isSelesai ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                                                {{ $isSelesai ? '✓' : '5' }}
                                            </div>
                                            <span class="text-[10px] sm:text-[11px] font-bold {{ $isSelesai ? 'text-emerald-700' : 'text-slate-400' }} mt-1.5">Selesai</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Riwayat & Audit Trail Persetujuan (Tahap 4) -->
                    @if ($b->approvals->isNotEmpty())
                        <div class="pt-4 border-t border-slate-100 space-y-2">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] block">
                                Jejak Audit Persetujuan & Verifikasi:
                            </span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($b->approvals as $appr)
                                    <div class="p-3 rounded-2xl border text-xs {{ $appr->tindakan === 'setuju' ? 'bg-emerald-50/60 border-emerald-200/80 text-emerald-950' : 'bg-rose-50/60 border-rose-200/80 text-rose-950' }}">
                                        <div class="flex items-center justify-between font-bold mb-1">
                                            <span class="flex items-center gap-1.5">
                                                @if($appr->tindakan === 'setuju')
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                @else
                                                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                @endif
                                                {{ $appr->role_approval === 'kepala_garasi' ? 'Verifikasi Kepala Garasi' : 'Persetujuan Pimpinan' }}
                                            </span>
                                            <span class="text-[10px] font-normal text-slate-500">
                                                {{ $appr->waktu_tindakan?->translatedFormat('d M Y H:i') }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-600">
                                            Oleh: <strong class="text-slate-800">{{ $appr->approver?->name ?? 'Pejabat Terkait' }}</strong>
                                        </p>
                                        @if ($appr->catatan)
                                            <p class="text-[11px] italic mt-1 text-slate-700 bg-white/70 p-1.5 rounded-lg border border-slate-200/50">
                                                "{{ $appr->catatan }}"
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Data Serah Terima Digital (Tahap 5) -->
                    @if ($b->checkout)
                        <div class="pt-4 border-t border-slate-100 space-y-2">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] block">
                                Rekam Serah Terima Fisik Armada (Checkout & Checkin):
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-sky-50/50 p-3 rounded-2xl border border-sky-100 text-xs">
                                <div>
                                    <span class="text-slate-500 block font-medium">Odometer Keluar:</span>
                                    <strong class="text-slate-800 font-mono">{{ number_format($b->checkout->odometer_keluar, 0, ',', '.') }} km</strong>
                                    <span class="text-[11px] text-slate-400 block">BBM: {{ $b->checkout->level_bbm_keluar }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block font-medium">Odometer Masuk:</span>
                                    @if ($b->checkin)
                                        <strong class="text-slate-800 font-mono">{{ number_format($b->checkin->odometer_masuk, 0, ',', '.') }} km</strong>
                                        <span class="text-[11px] text-slate-400 block">BBM: {{ $b->checkin->level_bbm_masuk }}</span>
                                    @else
                                        <span class="text-amber-700 italic font-semibold">Sedang Digunakan</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-slate-500 block font-medium">Total Jarak Tempuh:</span>
                                    @if ($b->checkin)
                                        <strong class="text-sky-700 font-bold font-mono">{{ number_format($b->checkin->jarak_tempuh, 0, ',', '.') }} km</strong>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-slate-500 block font-medium">Kondisi & Rating:</span>
                                    @if ($b->checkin)
                                        <span class="text-amber-500 font-bold">
                                            @for ($i = 1; $i <= ($b->checkin->rating_kondisi ?? 5); $i++) ★ @endfor
                                        </span>
                                    @else
                                        <span class="text-emerald-700 font-medium">Diserahkan Baik</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Cancel Action Button (Jika Masih Status Diajukan) -->
                    @if ($b->status === 'diajukan')
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Pengajuan dapat dibatalkan mandiri sebelum diperiksa oleh Kepala Garasi.</span>
                            <button type="button"
                                wire:click="cancelBooking({{ $b->id }})"
                                wire:confirm="Apakah Anda yakin ingin membatalkan permohonan peminjaman kendaraan dinas ini?"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-rose-600 hover:text-white hover:bg-rose-600 bg-rose-50 border border-rose-100 transition-all cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Batalkan Permohonan Ini</span>
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Pagination Links -->
            <div class="pt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white p-12 sm:p-16 rounded-3xl border border-slate-200/80 shadow-xs text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-4 shadow-xs">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg">Belum Ada Riwayat Peminjaman</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                Anda belum memiliki catatan pengajuan peminjaman mobil dinas dengan kata kunci atau filter saat ini.
            </p>
            <a href="{{ route('portal.ajukan') }}"
                class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-bold text-xs shadow-md shadow-sky-600/20 transition-all hover:scale-[1.02]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Mulai Buat Pengajuan Peminjaman</span>
            </a>
        </div>
    @endif
</div>
