<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header & Export Buttons -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/60 mb-2">
                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Tahap 7: Pelaporan Administratif & Rekapitulasi Dinas
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Laporan Operasional & Utilisasi Armada
                </h1>
                <p class="text-sm text-slate-500 mt-1 max-w-2xl">
                    Rekapitulasi lengkap seluruh permohonan, penugasan armada non-ambulans, jarak tempuh, dan catatan dinas resmi RSUD Sidawangi.
                </p>
            </div>

            <!-- Export Buttons -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <!-- Export PDF Button -->
                <button wire:click="exportPdf"
                        wire:loading.attr="disabled"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm hover:shadow transition-all disabled:opacity-50">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span wire:loading.remove wire:target="exportPdf">Cetak PDF Resmi</span>
                    <span wire:loading wire:target="exportPdf">Memproses PDF...</span>
                </button>

                <!-- Export Excel Button -->
                <button wire:click="exportExcel"
                        wire:loading.attr="disabled"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow transition-all disabled:opacity-50">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span wire:loading.remove wire:target="exportExcel">Ekspor Excel (.xlsx)</span>
                    <span wire:loading wire:target="exportExcel">Mengunduh...</span>
                </button>
            </div>
        </div>

        <!-- Filter Controls Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter & Parameter Kustom
                </h2>
                <button wire:click="resetFilters" type="button" class="text-xs font-semibold text-sky-600 hover:text-sky-800">
                    Reset Filter
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4">
                <!-- Tanggal Mulai -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-600">Dari Tanggal</label>
                    <input type="date" wire:model.live="startDate" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>

                <!-- Tanggal Selesai -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-600">Sampai Tanggal</label>
                    <input type="date" wire:model.live="endDate" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>

                <!-- Unit Kerja -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-600">Unit Kerja Pengusul</label>
                    <select wire:model.live="unitKerjaId" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">Semua Unit Kerja</option>
                        @foreach ($unitKerjas as $uk)
                            <option value="{{ $uk->id }}">{{ $uk->nama_unit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kendaraan -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-600">Armada Kendaraan</label>
                    <select wire:model.live="vehicleId" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">Semua Armada</option>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->tipe_model }} ({{ $v->no_polisi }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sopir -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-600">Sopir Dinas Pool</label>
                    <select wire:model.live="driverId" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">Semua Sopir</option>
                        @foreach ($drivers as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-600">Status Penugasan</label>
                    <select wire:model.live="status" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">Semua Status</option>
                        <option value="diajukan">Diajukan</option>
                        <option value="diverifikasi_garasi">Diverifikasi Garasi</option>
                        <option value="disetujui">Disetujui Pimpinan</option>
                        <option value="kendaraan_keluar">Sedang Berjalan</option>
                        <option value="selesai">Selesai Kembali</option>
                        <option value="ditolak_garasi">Ditolak Garasi</option>
                        <option value="ditolak_pimpinan">Ditolak Pimpinan</option>
                    </select>
                </div>
            </div>

            <!-- Search Bar Inline -->
            <div class="pt-2">
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Pencarian cepat kata kunci (kode pendaftaran, tujuan, nama pegawai)..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Summary Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-white rounded-xl p-3 border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-400">Total Ditemukan</span>
                <p class="text-lg font-black text-slate-900">{{ $summary['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-3 border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-emerald-600">Disetujui / Jalan</span>
                <p class="text-lg font-black text-emerald-600">{{ $summary['disetujui'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-3 border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-rose-600">Ditolak</span>
                <p class="text-lg font-black text-rose-600">{{ $summary['ditolak'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-3 border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-sky-600">Tuntas Selesai</span>
                <p class="text-lg font-black text-sky-600">{{ $summary['selesai'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-3 border border-slate-200 text-center col-span-2 sm:col-span-1">
                <span class="text-[10px] font-bold uppercase text-amber-600">Akumulasi Jarak</span>
                <p class="text-lg font-black text-amber-600">{{ number_format($summary['total_km'], 0, ',', '.') }} km</p>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Kode & Jadwal</th>
                            <th class="py-3 px-4">Pemohon & Unit</th>
                            <th class="py-3 px-4">Armada & Sopir</th>
                            <th class="py-3 px-4">Tujuan</th>
                            <th class="py-3 px-4 text-right">Odometer & Jarak</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($bookings as $idx => $b)
                            @php
                                $odoKeluar = $b->checkout?->odometer_keluar;
                                $odoMasuk = $b->checkin?->odometer_masuk;
                                $jarak = ($odoKeluar && $odoMasuk) ? ($odoMasuk - $odoKeluar) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-4 text-slate-400 font-medium">
                                    {{ $bookings->firstItem() + $idx }}
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-900 font-mono text-xs">{{ $b->kode_peminjaman }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $b->tanggal_berangkat?->format('d/m/Y') }} ({{ $b->jam_berangkat }}) s.d.
                                        {{ $b->tanggal_kembali_rencana?->format('d/m/Y') }}
                                    </p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-slate-900">{{ $b->user?->name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $b->unitKerja?->nama_unit }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-slate-900">{{ $b->vehicle?->no_polisi ?? 'Belum Ditunjuk' }}</p>
                                    <p class="text-[11px] text-slate-500">
                                        {{ $b->vehicle?->tipe_model }} •
                                        {{ $b->jenis_pengemudi === 'sopir_dinas' ? ($b->driver?->nama ?? 'Sopir Pool') : 'Swakemudi' }}
                                    </p>
                                </td>
                                <td class="py-3 px-4 max-w-xs">
                                    <p class="font-semibold text-slate-900">{{ $b->kota_tujuan }}</p>
                                    <p class="text-[11px] text-slate-500 truncate" title="{{ $b->tujuan_perjalanan }}">{{ $b->tujuan_perjalanan }}</p>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if ($jarak !== null)
                                        <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-xs">
                                            {{ number_format($jarak, 0, ',', '.') }} km
                                        </span>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ number_format($odoKeluar, 0, ',', '.') }} &rarr; {{ number_format($odoMasuk, 0, ',', '.') }}</p>
                                    @elseif ($odoKeluar)
                                        <span class="text-xs text-sky-600 font-medium">Keluar: {{ number_format($odoKeluar, 0, ',', '.') }} km</span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @php
                                        $badgeColor = match ($b->status) {
                                            'disetujui' => 'bg-sky-100 text-sky-800',
                                            'kendaraan_keluar' => 'bg-indigo-100 text-indigo-800',
                                            'selesai' => 'bg-emerald-100 text-emerald-800',
                                            'ditolak_garasi', 'ditolak_pimpinan' => 'bg-rose-100 text-rose-800',
                                            default => 'bg-amber-100 text-amber-800',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $badgeColor }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 text-xs sm:text-sm">
                                    Tidak ada data peminjaman yang cocok dengan kriteria filter saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($bookings->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
