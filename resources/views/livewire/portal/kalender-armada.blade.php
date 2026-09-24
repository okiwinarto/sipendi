<div class="space-y-6">
    <!-- Breadcrumb & Header Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none">
            <svg class="w-64 h-64 text-sky-900" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
            </svg>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100/90 border border-sky-200 text-sky-800 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-sky-600 animate-pulse"></span>
                    <span>Monitoring Ketersediaan Kendaraan Non-Ambulans</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kalender Ketersediaan Armada</h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 max-w-xl leading-relaxed">
                    Pantau agenda perjalanan dinas yang terisi secara *real-time* untuk memastikan kendaraan yang Anda butuhkan bebas bentrok jadwal sebelum mengajukan permohonan.
                </p>
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('portal.ajukan') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-bold text-xs shadow-md shadow-sky-600/25 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Ajukan Peminjaman Mobil</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Month Switcher & Filter Controls -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Month Navigator -->
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
            <button type="button" wire:click="prevMonth"
                class="p-2.5 rounded-xl border border-slate-200 hover:bg-sky-50 hover:border-sky-300 text-slate-700 hover:text-sky-700 transition-all shadow-2xs cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="px-4 text-center min-w-[180px]">
                <span class="text-lg font-extrabold text-slate-900 capitalize block leading-tight">
                    {{ $monthName }}
                </span>
                <span class="text-[11px] text-slate-400 font-semibold">Tahun Anggaran {{ $tahun }}</span>
            </div>
            <button type="button" wire:click="nextMonth"
                class="p-2.5 rounded-xl border border-slate-200 hover:bg-sky-50 hover:border-sky-300 text-slate-700 hover:text-sky-700 transition-all shadow-2xs cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <!-- Filter Category & Legend -->
        <div class="flex flex-wrap items-center gap-4 w-full md:w-auto justify-between md:justify-end">
            <!-- Legend Indicators -->
            <div class="hidden lg:flex items-center gap-3 text-xs font-semibold text-slate-600">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-emerald-100"></span>
                    <span>Siap / Bebas</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500 ring-2 ring-sky-100"></span>
                    <span>Terisi Sebagian</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 ring-2 ring-amber-100"></span>
                    <span>Seluruh Armada Terpakai</span>
                </div>
            </div>

            <!-- Category Selector -->
            <div class="w-full sm:w-auto flex items-center gap-2">
                <label class="text-xs font-bold text-slate-500 shrink-0">Kategori:</label>
                <select wire:model.live="filterKategoriId"
                    class="w-full sm:w-56 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors">
                    <option value="">Semua Kategori Armada</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Calendar Table Container with Responsive Horizontal Scroll Support -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <div class="min-w-[720px]">
                <!-- Day Names Header -->
                <div class="grid grid-cols-7 bg-slate-50/90 border-b border-slate-200 text-center text-xs font-extrabold text-slate-700 py-3.5">
                    <div>Senin</div>
                    <div>Selasa</div>
                    <div>Rabu</div>
                    <div>Kamis</div>
                    <div>Jumat</div>
                    <div class="text-rose-600">Sabtu</div>
                    <div class="text-rose-600">Minggu</div>
                </div>

                <!-- Days Grid -->
                <div class="grid grid-cols-7 gap-px bg-slate-200/80">
                    <!-- Empty offset days before 1st of month -->
                    @for ($i = 1; $i < $firstDayOfWeek; $i++)
                        <div class="bg-slate-50/40 min-h-[105px] p-2.5 text-slate-300 text-xs"></div>
                    @endfor

                    <!-- Days in Month -->
                    @foreach ($calendarDays as $dayNum => $info)
                        @php
                            $isToday = $info['date'] === date('Y-m-d');
                            $isPast = $info['date'] < date('Y-m-d');
                            $isSelected = $selectedDate === $info['date'];

                            if ($info['isFull']) {
                                $badgeColor = 'bg-amber-100 text-amber-900 border-amber-200';
                                $dotColor = 'bg-amber-500';
                                $badgeText = $info['bookingsCount'] . ' Agenda (Penuh)';
                            } elseif ($info['hasBookings']) {
                                $badgeColor = 'bg-sky-100 text-sky-900 border-sky-200';
                                $dotColor = 'bg-sky-500';
                                $badgeText = $info['bookingsCount'] . ' Terjadwal';
                            } else {
                                $badgeColor = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                $dotColor = 'bg-emerald-500';
                                $badgeText = 'Siap / Kosong';
                            }
                        @endphp
                        <div wire:click="selectDay('{{ $info['date'] }}')"
                            class="min-h-[105px] p-2.5 flex flex-col justify-between cursor-pointer transition-all hover:bg-sky-50/70 {{ $isPast ? 'bg-slate-50/70 opacity-60' : 'bg-white' }} {{ $isToday ? 'bg-sky-50/40 ring-1 ring-sky-300' : '' }} {{ $isSelected ? 'ring-2 ring-sky-600 bg-sky-50/90 shadow-sm' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-extrabold {{ $isToday ? 'w-6 h-6 rounded-full bg-sky-600 text-white flex items-center justify-center shadow-xs' : 'text-slate-800' }}">
                                        {{ $dayNum }}
                                    </span>
                                    @if ($isToday)
                                        <span class="hidden sm:inline-block text-[9px] font-black uppercase text-sky-600 tracking-wider">Kini</span>
                                    @endif
                                </div>
                                @if ($info['hasBookings'])
                                    <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }} ring-2 ring-white"></span>
                                @endif
                            </div>

                            <div class="mt-2">
                                <div class="inline-flex items-center justify-center px-2 py-1 rounded-lg border text-[10px] font-bold {{ $badgeColor }} w-full truncate">
                                    <span>{{ $badgeText }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Empty trailing cells to complete the grid -->
                    @php
                        $remainingCells = (7 - (($firstDayOfWeek - 1 + $daysInMonth) % 7)) % 7;
                    @endphp
                    @for ($j = 0; $j < $remainingCells; $j++)
                        <div class="bg-slate-50/40 min-h-[105px] p-2.5 text-slate-300 text-xs"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Day Detail Panel (Muncul Saat Tanggal Diklik) -->
    @if ($selectedDate)
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-sky-300 shadow-md animate-in fade-in slide-in-from-bottom-2 duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-extrabold text-slate-900 text-lg">
                                Agenda Dinas: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                            </h3>
                            @if ($selectedDate === date('Y-m-d'))
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-600 text-white tracking-wide uppercase shadow-xs">
                                    Hari Ini
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500">Rincian peminjaman dan kelaikan mobil operasional pada tanggal terpilih</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="text-xs px-3.5 py-1.5 rounded-full bg-slate-100 font-extrabold text-slate-700">
                        {{ count($selectedDayBookings) }} Mobil Terjadwal
                    </span>
                    @if ($selectedDate >= date('Y-m-d'))
                        <a href="{{ route('portal.ajukan', ['tanggal' => $selectedDate]) }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 shadow-xs transition-all hover:scale-[1.02]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>{{ $selectedDate === date('Y-m-d') ? '⚡ Ajukan Hari Ini' : 'Ajukan Tanggal Ini' }}</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-[11px] font-semibold text-slate-400 bg-slate-100">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Tanggal Lampau</span>
                        </span>
                    @endif
                </div>
            </div>

            @if (count($selectedDayBookings) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($selectedDayBookings as $item)
                        <div class="p-4 rounded-2xl border border-slate-200/90 bg-slate-50/70 hover:bg-sky-50/40 transition-colors space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-slate-900 text-sm">{{ $item['kode_peminjaman'] }}</span>
                                <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-sky-100 text-sky-800 border border-sky-200">
                                    {{ ucfirst(str_replace('_', ' ', $item['status'])) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-800 font-bold leading-snug">
                                {{ $item['tujuan_perjalanan'] }}
                            </p>
                            <div class="text-[11px] text-slate-500 space-y-0.5 pt-1 border-t border-slate-200/60">
                                <div class="flex items-center justify-between">
                                    <span>Tujuan: <strong class="text-slate-700">{{ $item['kota_tujuan'] }}</strong></span>
                                    <span>Unit: <strong class="text-slate-700">{{ $item['unit_kerja']['nama_unit'] ?? 'RSUD' }}</strong></span>
                                </div>
                                <div class="flex items-center justify-between text-sky-700 font-semibold">
                                    <span>Jam: {{ substr($item['jam_berangkat'], 0, 5) }} - {{ substr($item['jam_kembali_rencana'], 0, 5) }} WIB</span>
                                    <span>{{ $item['vehicle']['no_polisi'] ?? 'Mobil menyusul' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-slate-500 max-w-md mx-auto">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-base font-extrabold text-slate-900">Seluruh Armada Tersedia Bebas</p>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        @if ($selectedDate >= date('Y-m-d'))
                            Belum ada permohonan dinas yang terjadwal pada {{ $selectedDate === date('Y-m-d') ? 'hari ini' : 'tanggal ini' }}. Anda dapat mengajukan peminjaman sekarang dengan memilih armada yang sesuai.
                        @else
                            Tidak ada riwayat permohonan pada tanggal lampau ini.
                        @endif
                    </p>
                    @if ($selectedDate >= date('Y-m-d'))
                        <a href="{{ route('portal.ajukan', ['tanggal' => $selectedDate]) }}"
                            class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 shadow-md shadow-sky-600/25 transition-all hover:scale-[1.02]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>{{ $selectedDate === date('Y-m-d') ? '⚡ Buat Pengajuan untuk Hari Ini' : 'Buat Pengajuan untuk Tanggal Ini' }}</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
