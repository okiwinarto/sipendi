<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/60 mb-2">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                    Pintu 2: Persetujuan Akhir (Executive Decision)
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Persetujuan Pimpinan</h1>
                <p class="text-sm text-slate-500 mt-1">Otorisasi peminjaman kendaraan dinas yang telah siap secara teknis diverifikasi oleh Kepala Garasi.</p>
            </div>

            <!-- Search bar -->
            <div class="w-full md:w-72">
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari kode, nama pemohon..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Flash Alerts -->
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

        <!-- Tab Switcher -->
        <div class="flex border-b border-slate-200 space-x-4">
            <button wire:click="setTab('pending')"
                    class="pb-3 text-sm font-semibold relative transition-colors flex items-center gap-2 {{ $activeTab === 'pending' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Menunggu Persetujuan</span>
                @if ($pendingCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-600 text-white animate-pulse">
                        {{ $pendingCount }}
                    </span>
                @endif
            </button>
            <button wire:click="setTab('history')"
                    class="pb-3 text-sm font-semibold relative transition-colors flex items-center gap-2 {{ $activeTab === 'history' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Riwayat Keputusan Pimpinan</span>
            </button>
        </div>

        <!-- Content Area -->
        @if ($bookings->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">Tidak ada pengajuan yang menunggu keputusan</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    {{ $activeTab === 'pending' ? 'Semua pengajuan yang diverifikasi garasi telah diproses oleh Pimpinan.' : 'Belum ada riwayat persetujuan pimpinan.' }}
                </p>
            </div>
        @else
            <!-- PENDING CARDS: Mobile-First Card View Sesuai PRD 7.4 Wireframe -->
            @if ($activeTab === 'pending')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($bookings as $booking)
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
                            
                            <!-- Card Header -->
                            <div class="p-5 border-b border-slate-100 bg-gradient-to-br from-slate-50 to-white">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-mono text-xs font-bold text-slate-800 bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                        {{ $booking->kode_peminjaman }}
                                    </span>

                                    @if ($booking->tingkat_prioritas === 'mendesak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                            Mendesak
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Prioritas Normal</span>
                                    @endif
                                </div>

                                <h3 class="text-base font-bold text-slate-900 leading-snug">
                                    {{ $booking->tujuan_perjalanan }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Kota Tujuan: <span class="font-semibold text-slate-700">{{ $booking->kota_tujuan }}</span>
                                </p>
                            </div>

                            <!-- Card Body: User & Vehicle specs -->
                            <div class="p-5 space-y-3.5 flex-1 text-xs">
                                <!-- Pemohon Info -->
                                <div class="flex items-start gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ substr($booking->user?->name ?? 'P', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $booking->user?->name }}</div>
                                        <div class="text-slate-500">{{ $booking->unitKerja?->nama_unit ?? 'Pegawai RSUD' }}</div>
                                    </div>
                                </div>

                                <!-- Jadwal -->
                                <div class="space-y-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Berangkat:</span>
                                        <span class="font-semibold text-slate-700">
                                            {{ $booking->tanggal_berangkat->format('d/m/Y') }} {{ substr($booking->jam_berangkat, 0, 5) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Kembali:</span>
                                        <span class="font-semibold text-slate-700">
                                            {{ $booking->tanggal_kembali_rencana->format('d/m/Y') }} {{ substr($booking->jam_kembali_rencana, 0, 5) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Penumpang:</span>
                                        <span class="font-semibold text-slate-700">{{ $booking->jumlah_penumpang }} Orang</span>
                                    </div>
                                </div>

                                <!-- Armada Disiapkan Garasi -->
                                <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100 space-y-1">
                                    <div class="text-xs font-bold text-emerald-900 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        Ditetapkan Kepala Garasi:
                                    </div>
                                    <div class="text-sm font-bold text-slate-900">
                                        {{ $booking->vehicle?->tipe_model ?? 'Belum ditentukan' }}
                                        <span class="text-xs font-normal text-emerald-700 font-mono">({{ $booking->vehicle?->plat_nomor }})</span>
                                    </div>
                                    <div class="text-xs text-slate-600">
                                        Pengemudi: 
                                        @if($booking->jenis_pengemudi === 'sopir_dinas')
                                            <span class="font-semibold text-slate-800">{{ $booking->driver?->nama ?? 'Sopir Pool' }}</span>
                                        @else
                                            <span class="font-medium text-slate-700">Swakemudi (Bawa Sendiri)</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($booking->file_surat_tugas)
                                    <div class="pt-1">
                                        <a href="{{ asset('storage/' . $booking->file_surat_tugas) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            Lampiran Surat Tugas (Unduh/Lihat)
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer: Quick Decision Actions -->
                            <div class="p-4 bg-slate-50 border-t border-slate-100 grid grid-cols-2 gap-2">
                                <button wire:click="openRejectModal({{ $booking->id }})"
                                        type="button"
                                        class="w-full py-2.5 px-3 rounded-xl border border-rose-200 text-rose-700 bg-white hover:bg-rose-50 text-xs font-bold transition-all text-center flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                                <button wire:click="setujui({{ $booking->id }})"
                                        type="button"
                                        class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all text-center flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <!-- HISTORY LIST -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-100">
                    @foreach ($bookings as $booking)
                        <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $booking->kode_peminjaman }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold 
                                        @if($booking->status === 'disetujui') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif(str_starts_with($booking->status, 'ditolak')) bg-rose-50 text-rose-700 border border-rose-200
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ $booking->status_label }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        {{ $booking->updated_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <h4 class="text-base font-bold text-slate-900">{{ $booking->tujuan_perjalanan }} ({{ $booking->kota_tujuan }})</h4>
                                <p class="text-xs text-slate-500">
                                    Pemohon: <span class="font-medium text-slate-700">{{ $booking->user?->name }}</span> ({{ $booking->unitKerja?->nama_unit }}) · Armada: <span class="font-semibold text-slate-700">{{ $booking->vehicle?->tipe_model }} ({{ $booking->vehicle?->plat_nomor }})</span>
                                </p>
                                @if ($booking->alasan_penolakan)
                                    <div class="text-xs text-rose-700 bg-rose-50 p-2 rounded-lg mt-1 border border-rose-100">
                                        <span class="font-bold">Alasan Penolakan:</span> {{ $booking->alasan_penolakan }}
                                    </div>
                                @endif
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

    <!-- MODAL TOLAK OLEH PIMPINAN -->
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

                        <h3 class="text-lg font-bold text-slate-900">Tolak Otorisasi Peminjaman</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Anda akan menolak pengajuan <span class="font-bold text-slate-800">{{ $rejectBooking->kode_peminjaman }}</span> dari <span class="font-bold text-slate-800">{{ $rejectBooking->user?->name }}</span>.
                        </p>

                        <div class="mt-4">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Catatan / Alasan Penolakan Pimpinan <span class="text-rose-500">* (Wajib diisi)</span>
                            </label>
                            <textarea wire:model="alasan_penolakan"
                                      rows="3"
                                      placeholder="Contoh: Jadwal bertabrakan dengan rapat koordinasi internal atau keperluan mendesak lainnya..."
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
