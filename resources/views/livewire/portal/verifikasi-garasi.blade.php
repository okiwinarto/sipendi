<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 mb-2">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.745 3.745 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                    Pintu 1: Verifikasi Teknis
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Verifikasi Armada (Kepala Garasi)</h1>
                <p class="text-sm text-slate-500 mt-1">Periksa kelaikan teknis, tetapkan kendaraan definitif dan sopir dinas sebelum diteruskan ke Pimpinan.</p>
            </div>

            <!-- Search bar -->
            <div class="w-full md:w-72">
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari kode, pemohon, kota..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Notification -->
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="flex border-b border-slate-200 space-x-4">
            <button wire:click="setTab('pending')"
                    class="pb-3 text-sm font-semibold relative transition-colors flex items-center gap-2 {{ $activeTab === 'pending' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Menunggu Verifikasi</span>
                @if ($pendingCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white animate-pulse">
                        {{ $pendingCount }}
                    </span>
                @endif
            </button>
            <button wire:click="setTab('history')"
                    class="pb-3 text-sm font-semibold relative transition-colors flex items-center gap-2 {{ $activeTab === 'history' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Riwayat Tindakan Garasi</span>
            </button>
        </div>

        <!-- Bookings List -->
        @if ($bookings->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">Tidak ada pengajuan ditemukan</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    {{ $activeTab === 'pending' ? 'Seluruh permohonan peminjaman sudah diverifikasi atau belum ada pengajuan baru.' : 'Belum ada riwayat tindakan verifikasi garasi.' }}
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach ($bookings as $booking)
                    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            
                            <!-- Left: Request details -->
                            <div class="space-y-3 flex-1">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="font-mono text-sm font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/60">
                                        {{ $booking->kode_peminjaman }}
                                    </span>

                                    @if ($booking->tingkat_prioritas === 'mendesak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <svg class="w-3 h-3 text-rose-500 animate-ping" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                            Mendesak / Urgent
                                        </span>
                                    @endif

                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($booking->status === 'diajukan') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($booking->status === 'diverifikasi_garasi') bg-sky-50 text-sky-700 border border-sky-200
                                        @elseif($booking->status === 'disetujui') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif(str_starts_with($booking->status, 'ditolak')) bg-rose-50 text-rose-700 border border-rose-200
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ $booking->status_label }}
                                    </span>

                                    <span class="text-xs text-slate-400">
                                        Diajukan: {{ $booking->created_at->translatedFormat('d M Y H:i') }}
                                    </span>
                                </div>

                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                                        <span>{{ $booking->tujuan_perjalanan }}</span>
                                        <span class="text-xs font-normal text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                                            Kota: {{ $booking->kota_tujuan }}
                                        </span>
                                    </h3>
                                    <p class="text-sm text-slate-600 mt-0.5 flex items-center gap-2">
                                        <span class="font-medium text-slate-900">{{ $booking->user?->name }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span>{{ $booking->unitKerja?->nama_unit ?? 'Unit Umum' }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-xs text-slate-500">NIP: {{ $booking->user?->nip ?? '-' }}</span>
                                    </p>
                                </div>

                                <!-- Schedule & Specs Grid -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50/80 p-3 rounded-xl border border-slate-100">
                                    <div>
                                        <span class="text-slate-400 block font-medium">Jadwal Berangkat:</span>
                                        <span class="font-semibold text-slate-800">
                                            {{ $booking->tanggal_berangkat->translatedFormat('d M Y') }}, {{ substr($booking->jam_berangkat, 0, 5) }} WIB
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-medium">Rencana Kembali:</span>
                                        <span class="font-semibold text-slate-800">
                                            {{ $booking->tanggal_kembali_rencana->translatedFormat('d M Y') }}, {{ substr($booking->jam_kembali_rencana, 0, 5) }} WIB
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-medium">Penumpang / Sopir:</span>
                                        <span class="font-semibold text-slate-800">
                                            {{ $booking->jumlah_penumpang }} Orang · {{ $booking->jenis_pengemudi === 'sopir_dinas' ? 'Sopir Dinas' : 'Swakemudi' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-medium">Pilihan Awal:</span>
                                        <span class="font-semibold text-slate-800">
                                            {{ $booking->vehicle ? $booking->vehicle->tipe_model . ' (' . $booking->vehicle->plat_nomor . ')' : 'Belum ditetapkan' }}
                                        </span>
                                    </div>
                                </div>

                                @if ($booking->driver)
                                    <div class="text-xs text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Sopir Ditugaskan: <span class="font-semibold">{{ $booking->driver->nama }}</span> (SIM {{ $booking->driver->jenis_sim }})
                                    </div>
                                @endif

                                @if ($booking->alasan_penolakan)
                                    <div class="text-xs text-rose-800 bg-rose-50 p-2.5 rounded-lg border border-rose-100">
                                        <span class="font-bold">Alasan Penolakan:</span> {{ $booking->alasan_penolakan }}
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Actions -->
                            <div class="flex flex-row lg:flex-col items-center lg:items-end justify-end gap-2 border-t lg:border-t-0 pt-3 lg:pt-0 border-slate-100 flex-shrink-0">
                                @if ($booking->status === 'diajukan')
                                    <button wire:click="openVerifyModal({{ $booking->id }})"
                                            type="button"
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm hover:shadow transition-all w-full sm:w-auto">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Verifikasi & Tetapkan
                                    </button>

                                    <button wire:click="openRejectModal({{ $booking->id }})"
                                            type="button"
                                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </button>
                                @else
                                    <span class="text-xs text-slate-500 italic">Sudah diverifikasi</span>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>

    <!-- MODAL VERIFIKASI & PENETAPAN UNIT -->
    @if ($showVerifyModal && $selectedBooking)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeVerifyModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Verifikasi Teknis Armada</span>
                                <h3 class="text-lg font-bold">{{ $selectedBooking->kode_peminjaman }}</h3>
                            </div>
                            <button wire:click="closeVerifyModal" class="text-white/80 hover:text-white">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4">
                        <!-- Ringkasan Kebutuhan -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-xs space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Pemohon:</span>
                                <span class="font-bold text-slate-800">{{ $selectedBooking->user?->name }} ({{ $selectedBooking->unitKerja?->nama_unit }})</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Tujuan:</span>
                                <span class="font-bold text-slate-800">{{ $selectedBooking->tujuan_perjalanan }} ({{ $selectedBooking->kota_tujuan }})</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Waktu:</span>
                                <span class="font-bold text-slate-800">
                                    {{ $selectedBooking->tanggal_berangkat->format('d/m/Y') }} {{ substr($selectedBooking->jam_berangkat, 0, 5) }} s.d. {{ $selectedBooking->tanggal_kembali_rencana->format('d/m/Y') }} {{ substr($selectedBooking->jam_kembali_rencana, 0, 5) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Kebutuhan Sopir:</span>
                                <span class="font-bold {{ $selectedBooking->jenis_pengemudi === 'sopir_dinas' ? 'text-emerald-700' : 'text-slate-700' }}">
                                    {{ $selectedBooking->jenis_pengemudi === 'sopir_dinas' ? 'Membutuhkan Sopir Dinas' : 'Swakemudi (Bawa Sendiri)' }}
                                </span>
                            </div>
                        </div>

                        <!-- Pilih Kendaraan Definitif -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Unit Kendaraan Definitif <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="vehicle_id"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Armada Laik Jalan --</option>
                                @foreach ($vehicles as $v)
                                    <option value="{{ $v->id }}">
                                        {{ $v->tipe_model }} · {{ $v->plat_nomor }} (Kapasitas: {{ $v->kapasitas_penumpang }} org, Status: {{ ucfirst($v->status) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror

                            @if ($conflictWarning)
                                <div class="mt-2 p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>{{ $conflictWarning }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Pilih Sopir Dinas jika diperlukan -->
                        @if ($selectedBooking->jenis_pengemudi === 'sopir_dinas')
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                    Sopir Dinas yang Ditugaskan <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="driver_id"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="">-- Pilih Petugas Sopir Pool --</option>
                                    @foreach ($drivers as $d)
                                        <option value="{{ $d->id }}">
                                            {{ $d->nama }} (SIM {{ $d->jenis_sim }}: {{ $d->no_sim }}) - {{ $d->no_hp }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Catatan Garasi -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Catatan Kesiapan Teknis (Opsional)
                            </label>
                            <textarea wire:model="catatan_garasi"
                                      rows="2"
                                      placeholder="Contoh: Unit Avanza dalam kondisi bersih, ban serep baru, BBM terisi 3/4."
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 border-t border-slate-100">
                        <button wire:click="closeVerifyModal"
                                type="button"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-white transition-all">
                            Batal
                        </button>
                        <button wire:click="verifikasiDanTeruskan"
                                type="button"
                                @if($conflictWarning) disabled @endif
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Verifikasi & Teruskan ke Pimpinan
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- MODAL TOLAK PENGADAAN OLEH GARASI -->
    @if ($showRejectModal && $rejectBooking)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeRejectModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4 border border-rose-100">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900">Tolak Permohonan Peminjaman</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Anda akan menolak permohonan <span class="font-bold text-slate-800">{{ $rejectBooking->kode_peminjaman }}</span> pemohon <span class="font-bold text-slate-800">{{ $rejectBooking->user?->name }}</span>.
                        </p>

                        <div class="mt-4">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Alasan Penolakan <span class="text-rose-500">* (Wajib diisi)</span>
                            </label>
                            <textarea wire:model="alasan_penolakan"
                                      rows="3"
                                      placeholder="Sebutkan kendala teknis atau alasan penolakan secara jelas..."
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                            @error('alasan_penolakan') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                            <button wire:click="closeRejectModal"
                                    type="button"
                                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                                Batal
                            </button>
                            <button wire:click="tolakPengajuan"
                                    type="button"
                                    class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold shadow-md transition-all">
                                Konfirmasi Tolak Pengajuan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
