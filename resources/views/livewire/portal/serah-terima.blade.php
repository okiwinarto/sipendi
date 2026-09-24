<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/60 mb-2">
                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                    Tahap 5: Serah Terima Digital (Checkout & Checkin)
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Inspeksi & Serah Terima Armada</h1>
                <p class="text-sm text-slate-500 mt-1">Pencatatan digital kondisi fisik, odometer, BBM, dan checklist instrumen sebelum dan sesudah kedinasan.</p>
            </div>

            <!-- Search & Quick QR Bar -->
            <div class="flex items-center gap-3">
                <div class="w-full sm:w-64">
                    <div class="relative">
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Cari plat, kode, pemohon..."
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- QR Hub Button -->
                <button wire:click="openQrModal({{ $vehicles->first()?->id ?? 1 }})"
                        type="button"
                        class="p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 shadow-xs transition-colors shrink-0"
                        title="Kode QR Akses Cepat Kendaraan">
                    <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Session Alert -->
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <!-- Maintenance & Tax Alert Banner (Tahap 6) -->
        @if ($maintenanceAlertVehicles->isNotEmpty())
            <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-4 sm:p-5 shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-xl bg-amber-100 text-amber-700 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-amber-900">Perhatian Pengelola Armada: Pemeliharaan & Kepatuhan Pajak</h3>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-200/70 text-amber-800">
                                {{ $maintenanceAlertVehicles->count() }} Unit Perlu Tindakan
                            </span>
                        </div>
                        <p class="text-xs text-amber-700 mt-1">Armada berikut membutuhkan servis rutin atau memiliki masa berlaku pajak/KIR yang mendekati/lewat jatuh tempo:</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($maintenanceAlertVehicles as $mVehicle)
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-amber-200 text-xs text-slate-700 shadow-2xs">
                                    <span class="font-bold text-slate-900">{{ $mVehicle->no_polisi }}</span>
                                    <span class="text-slate-500">({{ $mVehicle->tipe_model }})</span>
                                    @if ($mVehicle->status === 'perlu_perhatian')
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">Perlu Perhatian</span>
                                    @elseif ($mVehicle->hasExpiredTax())
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">Pajak Lewat</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Jatuh Tempo Dekat</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="flex border-b border-slate-200 space-x-4">
            <button wire:click="setTab('checkout')"
                    class="pb-3 text-sm font-semibold relative transition-colors flex items-center gap-2 {{ $activeTab === 'checkout' ? 'text-sky-600 border-b-2 border-sky-600' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Siap Berangkat (Checkout Keluar)</span>
                @if ($readyCheckoutCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-sky-500 text-white animate-pulse">
                        {{ $readyCheckoutCount }}
                    </span>
                @endif
            </button>
            <button wire:click="setTab('checkin')"
                    class="pb-3 text-sm font-semibold relative transition-colors flex items-center gap-2 {{ $activeTab === 'checkin' ? 'text-sky-600 border-b-2 border-sky-600' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Dalam Perjalanan (Checkin Masuk)</span>
                @if ($inTransitCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white animate-pulse">
                        {{ $inTransitCount }}
                    </span>
                @endif
            </button>
            <button wire:click="setTab('history')"
                    class="pb-3 text-sm font-semibold relative transition-colors flex items-center gap-2 {{ $activeTab === 'history' ? 'text-sky-600 border-b-2 border-sky-600' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Riwayat & Berita Acara (BAST)</span>
            </button>
        </div>

        <!-- Content Feed -->
        @if ($bookings->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">Tidak ada data serah terima</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    @if ($activeTab === 'checkout')
                        Belum ada permohonan disetujui yang siap diserahterimakan keluar.
                    @elseif ($activeTab === 'checkin')
                        Tidak ada armada yang sedang bertugas di luar pool saat ini.
                    @else
                        Belum ada arsip serah terima digital yang tersimpan.
                    @endif
                </p>
            </div>
        @else
            <!-- TAB 1: CHECKOUT KELUAR -->
            @if ($activeTab === 'checkout')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($bookings as $b)
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                        {{ $b->kode_peminjaman }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Disetujui Pimpinan
                                    </span>
                                </div>

                                <div>
                                    <h3 class="text-base font-bold text-slate-900">{{ $b->tujuan_perjalanan }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Pemohon: <span class="font-semibold text-slate-700">{{ $b->user?->name }}</span> ({{ $b->unitKerja?->nama_unit }})
                                    </p>
                                </div>

                                <!-- Vehicle & Departure Plan -->
                                <div class="bg-slate-50/80 p-3 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Unit Armada:</span>
                                        <span class="font-bold text-slate-900">{{ $b->vehicle?->tipe_model }} ({{ $b->vehicle?->plat_nomor }})</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Odometer Terakhir:</span>
                                        <span class="font-mono font-bold text-sky-700">{{ number_format($b->vehicle?->odometer_terakhir ?? 0, 0, ',', '.') }} km</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Pengemudi:</span>
                                        <span class="font-medium text-slate-800">{{ $b->jenis_pengemudi === 'sopir_dinas' ? ($b->driver?->nama ?? 'Sopir Dinas Pool') : 'Swakemudi' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Rencana Berangkat:</span>
                                        <span class="font-medium text-slate-800">{{ $b->tanggal_berangkat->format('d/m/Y') }} {{ substr($b->jam_berangkat, 0, 5) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">Petugas: {{ auth()->user()->name }}</span>
                                <button wire:click="openCheckoutModal({{ $b->id }})"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                                    Proses Checkout Keluar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

            <!-- TAB 2: CHECKIN MASUK -->
            @elseif ($activeTab === 'checkin')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($bookings as $b)
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                        {{ $b->kode_peminjaman }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                                        Kendaraan Keluar
                                    </span>
                                </div>

                                <div>
                                    <h3 class="text-base font-bold text-slate-900">{{ $b->tujuan_perjalanan }} ({{ $b->kota_tujuan }})</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Pemohon: <span class="font-semibold text-slate-700">{{ $b->user?->name }}</span>
                                    </p>
                                </div>

                                <!-- Checkout Baseline Info -->
                                <div class="bg-amber-50/70 p-3 rounded-xl border border-amber-100 space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-amber-800">Armada Digunakan:</span>
                                        <span class="font-bold text-slate-900">{{ $b->vehicle?->tipe_model }} ({{ $b->vehicle?->plat_nomor }})</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-amber-800">Odometer Saat Keluar:</span>
                                        <span class="font-mono font-bold text-amber-900">{{ number_format($b->checkout?->odometer_keluar ?? 0, 0, ',', '.') }} km</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-amber-800">Level BBM Saat Keluar:</span>
                                        <span class="font-bold text-slate-900">{{ $b->checkout?->level_bbm_keluar ?? 'F' }}</span>
                                    </div>
                                     <div class="flex justify-between">
                                         <span class="text-amber-800">Waktu Keluar:</span>
                                         <span class="text-slate-700 font-semibold">{{ $b->checkout?->waktu_keluar ? $b->checkout->waktu_keluar->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}</span>
                                     </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">Petugas Checkout: {{ $b->checkout?->petugas?->name ?? 'Garasi' }}</span>
                                <button wire:click="openCheckinModal({{ $b->id }})"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Proses Checkin Kembali
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

            <!-- TAB 3: RIWAYAT & BAST -->
            @else
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-100 overflow-hidden">
                    @foreach ($bookings as $b)
                        <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $b->kode_peminjaman }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Selesai Digunakan
                                    </span>
                                    @if ($b->checkin?->ada_kerusakan)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Ada Kerusakan Dilaporkan
                                        </span>
                                    @endif
                                </div>

                                <h4 class="text-base font-bold text-slate-900">{{ $b->tujuan_perjalanan }} ({{ $b->kota_tujuan }})</h4>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <div>
                                        <span class="text-slate-400 block font-medium">Kendaraan:</span>
                                        <span class="font-bold text-slate-800">{{ $b->vehicle?->tipe_model }} ({{ $b->vehicle?->plat_nomor }})</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-medium">Kilometer Tempuh:</span>
                                        <span class="font-bold text-sky-700 font-mono">
                                            {{ number_format($b->checkin?->jarak_tempuh ?? 0, 0, ',', '.') }} km
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-medium">BBM (Keluar ➔ Kembali):</span>
                                        <span class="font-bold text-slate-800">{{ $b->checkout?->level_bbm_keluar ?? '-' }} ➔ {{ $b->checkin?->level_bbm_masuk ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-medium">Rating Kelaikan:</span>
                                        <span class="font-bold text-amber-500">
                                            @for ($i = 1; $i <= ($b->checkin?->rating_kondisi ?? 5); $i++) ★ @endfor
                                        </span>
                                    </div>
                                </div>

                                @if ($b->checkin?->deskripsi_kerusakan)
                                    <div class="text-xs text-rose-800 bg-rose-50 p-2.5 rounded-lg border border-rose-100">
                                        <span class="font-bold">Laporan Kerusakan:</span> {{ $b->checkin->deskripsi_kerusakan }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-end shrink-0">
                                <button wire:click="openBastModal({{ $b->id }})"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-all">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Berita Acara (BAST)
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>

    <!-- MODAL CHECKOUT KELUAR -->
    @if ($showCheckoutModal && $checkoutBooking)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeCheckoutModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                    <div class="bg-gradient-to-r from-sky-600 to-cyan-600 px-6 py-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-sky-100">Serah Terima Keluar</span>
                                <h3 class="text-lg font-bold">{{ $checkoutBooking->kode_peminjaman }}</h3>
                            </div>
                            <button wire:click="closeCheckoutModal" class="text-white/80 hover:text-white">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <!-- Waktu Checkout Keluar (Acuan Waktu Indonesia Barat / Jakarta) -->
                        <div class="p-3.5 bg-sky-50/80 rounded-2xl border border-sky-200/80">
                            <label class="block text-xs font-bold uppercase tracking-wider text-sky-950 mb-1.5 flex items-center justify-between">
                                <span>Waktu Serah Terima Keluar (WIB) <span class="text-rose-500">*</span></span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-sky-700 bg-sky-100/90 px-2 py-0.5 rounded-lg border border-sky-200">
                                    <svg class="w-3 h-3 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Asia/Jakarta (WIB)
                                </span>
                            </label>
                            <input type="datetime-local" wire:model="waktu_keluar"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-sky-300/80 bg-white text-sm font-semibold text-slate-900 focus:ring-2 focus:ring-sky-500">
                            <p class="text-[11px] text-sky-700 mt-1.5 leading-relaxed">
                                Acuan waktu resmi pencatatan armada keluar penugasan sesuai zona waktu RSUD Sidawangi.
                            </p>
                            @error('waktu_keluar') <span class="text-xs text-rose-600 mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Odometer & BBM Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Odometer Keluar (km) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" wire:model="odometer_keluar"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono font-bold focus:ring-2 focus:ring-sky-500">
                                @error('odometer_keluar') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Level BBM Keluar <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="level_bbm_keluar"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-sky-500">
                                    <option value="F">F (Penuh / Full)</option>
                                    <option value="3/4">3/4 Tangki</option>
                                    <option value="1/2">1/2 Tangki</option>
                                    <option value="1/4">1/4 Tangki</option>
                                    <option value="E">E (Rendah / Empty)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kondisi Kendaraan -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Kondisi Fisik Armada Saat Keluar
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 p-3 rounded-xl border {{ $kondisi_kendaraan_keluar === 'baik' ? 'border-emerald-400 bg-emerald-50 text-emerald-900 font-bold' : 'border-slate-200' }} cursor-pointer text-xs">
                                    <input type="radio" wire:model="kondisi_kendaraan_keluar" value="baik" class="text-emerald-600">
                                    <span>Baik & Siap Jalan</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 rounded-xl border {{ $kondisi_kendaraan_keluar === 'perlu_perhatian' ? 'border-amber-400 bg-amber-50 text-amber-900 font-bold' : 'border-slate-200' }} cursor-pointer text-xs">
                                    <input type="radio" wire:model="kondisi_kendaraan_keluar" value="perlu_perhatian" class="text-amber-600">
                                    <span>Perlu Perhatian</span>
                                </label>
                            </div>
                        </div>

                        <!-- Checklist Kelengkapan Digital -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Checklist Kelengkapan Fisik (6 Instrumen)
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_ban_serep" class="rounded text-sky-600">
                                    <span>Ban Serep</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_dongkrak" class="rounded text-sky-600">
                                    <span>Dongkrak</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_kunci_roda" class="rounded text-sky-600">
                                    <span>Kunci Roda</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_segitiga" class="rounded text-sky-600">
                                    <span>Segitiga Pengaman</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_p3k" class="rounded text-sky-600">
                                    <span>Kotak P3K</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_stnk" class="rounded text-sky-600">
                                    <span>STNK Asli</span>
                                </label>
                            </div>
                        </div>

                        <!-- Foto Kondisi Keluar -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Unggah Foto Kondisi / Dashboard (Opsional)
                            </label>
                            <input type="file" wire:model="foto_kondisi_keluar" multiple accept="image/*"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Catatan Tambahan Petugas
                            </label>
                            <textarea wire:model="catatan_keluar" rows="2" placeholder="Catatan fisik kendaraan saat berangkat..."
                                      class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500"></textarea>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 border-t border-slate-100">
                        <button wire:click="closeCheckoutModal" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                        <button wire:click="prosesCheckout" type="button" class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold shadow-md">
                            Konfirmasi Serah Terima Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL CHECKIN MASUK -->
    @if ($showCheckinModal && $checkinBooking)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeCheckinModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Serah Terima Masuk (Pengembalian)</span>
                                <h3 class="text-lg font-bold">{{ $checkinBooking->kode_peminjaman }}</h3>
                            </div>
                            <button wire:click="closeCheckinModal" class="text-white/80 hover:text-white">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <!-- Waktu Checkin Masuk (Acuan Waktu Indonesia Barat / Jakarta) -->
                        <div class="p-3.5 bg-emerald-50/80 rounded-2xl border border-emerald-200/80">
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-950 mb-1.5 flex items-center justify-between">
                                <span>Waktu Pengembalian Masuk (WIB) <span class="text-rose-500">*</span></span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-800 bg-emerald-100/90 px-2 py-0.5 rounded-lg border border-emerald-200">
                                    <svg class="w-3 h-3 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Asia/Jakarta (WIB)
                                </span>
                            </label>
                            <input type="datetime-local" wire:model="waktu_masuk"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-emerald-300/80 bg-white text-sm font-semibold text-slate-900 focus:ring-2 focus:ring-emerald-500">
                            <p class="text-[11px] text-emerald-700 mt-1.5 leading-relaxed">
                                Acuan waktu resmi pencatatan armada kembali ke pool sesuai zona waktu RSUD Sidawangi.
                            </p>
                            @error('waktu_masuk') <span class="text-xs text-rose-600 mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Baseline Keluar Indicator -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs flex items-center justify-between">
                            <span class="text-slate-500">Odometer Saat Berangkat:</span>
                            <span class="font-mono font-bold text-slate-800">{{ number_format($checkinBooking->checkout?->odometer_keluar ?? 0, 0, ',', '.') }} km</span>
                        </div>

                        <!-- Odometer Masuk & BBM Masuk -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Odometer Kembali (km) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" wire:model="odometer_masuk"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono font-bold focus:ring-2 focus:ring-emerald-500">
                                @error('odometer_masuk') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Level BBM Kembali <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="level_bbm_masuk"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-emerald-500">
                                    <option value="F">F (Penuh / Full)</option>
                                    <option value="3/4">3/4 Tangki</option>
                                    <option value="1/2">1/2 Tangki</option>
                                    <option value="1/4">1/4 Tangki</option>
                                    <option value="E">E (Rendah / Empty)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Rating Kelaikan & Kondisi -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Rating Kondisi Armada (1-5)
                                </label>
                                <select wire:model="rating_kondisi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-amber-600">
                                    <option value="5">★★★★★ Sangat Bersih & Prima</option>
                                    <option value="4">★★★★☆ Baik & Normal</option>
                                    <option value="3">★★★☆☆ Cukup (Perlu Cuci)</option>
                                    <option value="2">★★☆☆☆ Kurang (Kotor/Baret Ringan)</option>
                                    <option value="1">★☆☆☆☆ Buruk / Rusak</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Status Kelaikan Akhir
                                </label>
                                <select wire:model="kondisi_kendaraan_masuk" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold">
                                    <option value="baik">Baik & Siap Bertugas Lagi</option>
                                    <option value="perlu_perhatian">Perlu Perhatian / Servis</option>
                                </select>
                            </div>
                        </div>

                        <!-- Toggle Kerusakan -->
                        <div class="p-3.5 rounded-xl border {{ $ada_kerusakan ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50' }} transition-colors">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="ada_kerusakan" class="w-4 h-4 text-rose-600 rounded">
                                <span class="text-xs font-bold {{ $ada_kerusakan ? 'text-rose-900' : 'text-slate-700' }}">
                                    Ada indikasi benturan, kerusakan bodi, atau keluhan teknis?
                                </span>
                            </label>

                            @if ($ada_kerusakan)
                                <div class="mt-3">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-rose-800 mb-1">
                                        Deskripsi Kerusakan <span class="text-rose-500">* (Wajib diisi)</span>
                                    </label>
                                    <textarea wire:model="deskripsi_kerusakan" rows="2"
                                              placeholder="Jelaskan detail bagian yang lecet/rusak atau bunyi abnormal..."
                                              class="w-full px-3 py-2 rounded-xl border border-rose-200 text-xs focus:ring-2 focus:ring-rose-500"></textarea>
                                    @error('deskripsi_kerusakan') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        <!-- Checklist Kembali -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Verifikasi Kelengkapan Instrumen Kembali
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_in_ban_serep" class="rounded text-emerald-600">
                                    <span>Ban Serep</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_in_dongkrak" class="rounded text-emerald-600">
                                    <span>Dongkrak</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_in_kunci_roda" class="rounded text-emerald-600">
                                    <span>Kunci Roda</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_in_segitiga" class="rounded text-emerald-600">
                                    <span>Segitiga Pengaman</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_in_p3k" class="rounded text-emerald-600">
                                    <span>Kotak P3K</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer">
                                    <input type="checkbox" wire:model="checklist_in_stnk" class="rounded text-emerald-600">
                                    <span>STNK Asli</span>
                                </label>
                            </div>
                        </div>

                        <!-- Foto Kembali -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Unggah Foto Kondisi Saat Kembali
                            </label>
                            <input type="file" wire:model="foto_kondisi_masuk" multiple accept="image/*"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Catatan Pengembalian
                            </label>
                            <textarea wire:model="catatan_masuk" rows="2" placeholder="Catatan pengembalian atau pesan perawatan..."
                                      class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 border-t border-slate-100">
                        <button wire:click="closeCheckinModal" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                        <button wire:click="prosesCheckin" type="button" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md">
                            Selesaikan Serah Terima Masuk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL BERITA ACARA SERAH TERIMA (BAST) DIGITAL -->
    @if ($showBastModal && $bastBooking)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeBastModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                    <!-- BAST Print Area -->
                    <div class="p-8 space-y-6" id="bast-print-area">
                        <!-- Kop Surat RSUD -->
                        <div class="text-center pb-4 border-b-2 border-slate-800 space-y-1">
                            <h2 class="text-xs font-bold tracking-widest text-slate-600 uppercase">Pemerintah Daerah Provinsi Jawa Barat</h2>
                            <h1 class="text-lg font-extrabold text-slate-900">RSUD SIDAWANGI PROVINSI JAWA BARAT</h1>
                            <p class="text-[10px] text-slate-500">Jl. Raya Siliwangi No. 123, Sidawangi, Kec. Sumber, Kab. Cirebon · Telp: (0231) 8331234</p>
                            <div class="pt-2">
                                <span class="font-mono text-xs font-bold uppercase tracking-wider bg-slate-100 px-3 py-1 rounded border border-slate-200">
                                    Berita Acara Serah Terima Kendaraan Dinas (BAST)
                                </span>
                            </div>
                        </div>

                        <!-- Data Utama -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-slate-400 block">Nomor Registrasi:</span>
                                <span class="font-mono font-bold text-slate-900">{{ $bastBooking->kode_peminjaman }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Pemohon / Unit:</span>
                                <span class="font-bold text-slate-900">{{ $bastBooking->user?->name }} ({{ $bastBooking->unitKerja?->nama_unit }})</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Maksud & Tujuan:</span>
                                <span class="font-semibold text-slate-800">{{ $bastBooking->tujuan_perjalanan }} ({{ $bastBooking->kota_tujuan }})</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Armada Ditetapkan:</span>
                                <span class="font-bold text-slate-900">{{ $bastBooking->vehicle?->tipe_model }} · {{ $bastBooking->vehicle?->plat_nomor }}</span>
                            </div>
                        </div>

                        <!-- Matriks Perbandingan Checkout vs Checkin -->
                        <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-100 text-slate-700 font-bold">
                                <tr>
                                    <th class="p-2.5">Parameter Pemeriksaan</th>
                                    <th class="p-2.5">Serah Terima Keluar</th>
                                    <th class="p-2.5">Serah Terima Masuk</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-800">
                                <tr>
                                    <td class="p-2.5 font-semibold text-slate-600">Waktu Transaksi</td>
                                    <td class="p-2.5 font-semibold text-slate-800">{{ $bastBooking->checkout?->waktu_keluar ? $bastBooking->checkout->waktu_keluar->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}</td>
                                    <td class="p-2.5 font-semibold text-slate-800">{{ $bastBooking->checkin?->waktu_masuk ? $bastBooking->checkin->waktu_masuk->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-semibold text-slate-600">Odometer</td>
                                    <td class="p-2.5 font-mono">{{ number_format($bastBooking->checkout?->odometer_keluar ?? 0, 0, ',', '.') }} km</td>
                                    <td class="p-2.5 font-mono">{{ number_format($bastBooking->checkin?->odometer_masuk ?? 0, 0, ',', '.') }} km</td>
                                </tr>
                                <tr class="bg-sky-50/50 font-bold text-sky-900">
                                    <td class="p-2.5">Total Jarak Tempuh Dinas</td>
                                    <td colspan="2" class="p-2.5 font-mono">
                                        {{ number_format($bastBooking->checkin?->jarak_tempuh ?? 0, 0, ',', '.') }} km
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-semibold text-slate-600">Level Bahan Bakar</td>
                                    <td class="p-2.5 font-bold">{{ $bastBooking->checkout?->level_bbm_keluar ?? '-' }}</td>
                                    <td class="p-2.5 font-bold">{{ $bastBooking->checkin?->level_bbm_masuk ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-semibold text-slate-600">Kondisi Fisik / Rating</td>
                                    <td class="p-2.5 capitalize">{{ $bastBooking->checkout?->kondisi_kendaraan ?? '-' }}</td>
                                    <td class="p-2.5 capitalize">
                                        {{ $bastBooking->checkin?->kondisi_kendaraan ?? '-' }} (Rating: {{ $bastBooking->checkin?->rating_kondisi ?? 5 }}/5)
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-semibold text-slate-600">Petugas Penanggung Jawab</td>
                                    <td class="p-2.5">{{ $bastBooking->checkout?->petugas?->name ?? 'Petugas Garasi' }}</td>
                                    <td class="p-2.5">{{ $bastBooking->checkin?->petugas?->name ?? 'Petugas Garasi' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if ($bastBooking->checkin?->ada_kerusakan)
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900">
                                <span class="font-bold">Laporan Catatan Insiden/Kerusakan:</span>
                                <p class="mt-0.5">{{ $bastBooking->checkin->deskripsi_kerusakan }}</p>
                            </div>
                        @endif

                        <!-- Tanda Tangan Digital / Nama Tertera -->
                        <div class="grid grid-cols-2 text-center pt-4 text-xs">
                            <div>
                                <p class="text-slate-500">Peminjam / Pemohon,</p>
                                <div class="h-14 flex items-center justify-center font-bold text-slate-800 italic">
                                    [ Terverifikasi Digital ]
                                </div>
                                <p class="font-bold text-slate-900 underline">{{ $bastBooking->user?->name }}</p>
                                <p class="text-[10px] text-slate-500">NIP. {{ $bastBooking->user?->nip ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">Petugas Garasi / Pool,</p>
                                <div class="h-14 flex items-center justify-center font-bold text-slate-800 italic">
                                    [ Terverifikasi Digital ]
                                </div>
                                <p class="font-bold text-slate-900 underline">{{ $bastBooking->checkin?->petugas?->name ?? $bastBooking->checkout?->petugas?->name ?? 'Petugas Garasi' }}</p>
                                <p class="text-[10px] text-slate-500">RSUD Sidawangi</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-100">
                        <button wire:click="closeBastModal" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Tutup</button>
                        <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-md">
                            Cetak / Simpan PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL QR CODE CEPAT PER ARMADA -->
    @if ($showQrModal && $qrVehicle)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeQrModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100 p-6 text-center">
                    <h3 class="text-base font-bold text-slate-900">Kode QR Unit Kendaraan Dinas</h3>
                    <p class="text-xs text-slate-500 mt-1">Tempelkan kode QR ini di dashboard kendaraan untuk akses cepat serah terima oleh petugas garasi.</p>

                    <!-- Unit Selector -->
                    <div class="my-4">
                        <select wire:change="openQrModal($event.target.value)" class="w-full text-xs font-bold rounded-xl border-slate-200">
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->id }}" {{ $v->id === $qrVehicle->id ? 'selected' : '' }}>
                                    {{ $v->tipe_model }} · {{ $v->plat_nomor }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Visual QR Card -->
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col items-center justify-center space-y-3">
                        <div class="w-44 h-44 bg-white p-3 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center">
                            <!-- SVG QR Graphic -->
                            <svg class="w-full h-full text-slate-900" viewBox="0 0 100 100" fill="currentColor">
                                <rect x="10" y="10" width="25" height="25" fill="black" />
                                <rect x="15" y="15" width="15" height="15" fill="white" />
                                <rect x="18" y="18" width="9" height="9" fill="black" />
                                <rect x="65" y="10" width="25" height="25" fill="black" />
                                <rect x="70" y="15" width="15" height="15" fill="white" />
                                <rect x="73" y="18" width="9" height="9" fill="black" />
                                <rect x="10" y="65" width="25" height="25" fill="black" />
                                <rect x="15" y="70" width="15" height="15" fill="white" />
                                <rect x="18" y="73" width="9" height="9" fill="black" />
                                <rect x="45" y="15" width="8" height="8" fill="black" />
                                <rect x="45" y="30" width="8" height="15" fill="black" />
                                <rect x="15" y="45" width="10" height="8" fill="black" />
                                <rect x="35" y="45" width="20" height="8" fill="black" />
                                <rect x="65" y="45" width="20" height="10" fill="black" />
                                <rect x="45" y="65" width="15" height="20" fill="black" />
                                <rect x="70" y="65" width="15" height="8" fill="black" />
                                <rect x="70" y="80" width="20" height="10" fill="black" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <span class="font-mono text-sm font-black text-slate-900 block">{{ $qrVehicle->plat_nomor }}</span>
                            <span class="text-xs text-slate-500 font-medium">{{ $qrVehicle->tipe_model }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button wire:click="closeQrModal" type="button" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
