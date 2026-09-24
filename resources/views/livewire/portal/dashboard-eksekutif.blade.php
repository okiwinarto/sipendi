<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Dashboard Eksekutif -->
        <div class="bg-gradient-to-r from-slate-900 via-sky-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <!-- Background glow & pattern -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-sky-500/20 text-sky-300 border border-sky-400/30 mb-3 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Executive Intelligence & Fleet Operations
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                        Dashboard Eksekutif & Pemantauan Armada
                    </h1>
                    <p class="text-sm sm:text-base text-slate-300 mt-2 max-w-2xl leading-relaxed">
                        Analitik komprehensif utilisasi armada kedinasan, efisiensi otorisasi, kesiapan pool, dan kepatuhan operasional RSUD Sidawangi Provinsi Jawa Barat.
                    </p>
                </div>

                <!-- Filter Periode Tab Buttons -->
                <div class="inline-flex p-1.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 self-start md:self-auto">
                    <button wire:click="setPeriod('bulan_ini')"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all {{ $period === 'bulan_ini' ? 'bg-sky-500 text-white shadow-md' : 'text-slate-300 hover:text-white' }}">
                        Bulan Ini
                    </button>
                    <button wire:click="setPeriod('bulan_lalu')"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all {{ $period === 'bulan_lalu' ? 'bg-sky-500 text-white shadow-md' : 'text-slate-300 hover:text-white' }}">
                        Bulan Lalu
                    </button>
                    <button wire:click="setPeriod('tahun_ini')"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all {{ $period === 'tahun_ini' ? 'bg-sky-500 text-white shadow-md' : 'text-slate-300 hover:text-white' }}">
                        Tahun 2026
                    </button>
                </div>
            </div>

            <!-- Role Perspective Selector Navigation Tabs (Role-tailored, Clean & Compact) -->
            <div class="mt-8 pt-6 border-t border-white/10 flex flex-wrap items-center gap-2 sm:gap-3">
                @if (auth()->user()->isKepalaGarasi() && !auth()->user()->isAdmin() && !auth()->user()->isPimpinan())
                    {{-- Kepala Garasi: Kesiapan Pool Utama, Ringkasan Eksekutif Sekunder --}}
                    <button wire:click="setActiveTab('garasi')"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'garasi' ? 'bg-white text-slate-900 shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Kesiapan Pool & Armada</span>
                    </button>
                    <button wire:click="setActiveTab('eksekutif')"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'eksekutif' ? 'bg-white text-slate-900 shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                        <svg class="w-4 h-4 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Ringkasan Eksekutif</span>
                    </button>
                @elseif (auth()->user()->isPimpinan() && !auth()->user()->isAdmin())
                    {{-- Pimpinan: Ringkasan Eksekutif Utama, Kesiapan Pool Sekunder --}}
                    <button wire:click="setActiveTab('eksekutif')"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'eksekutif' ? 'bg-white text-slate-900 shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                        <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Ringkasan Eksekutif</span>
                    </button>
                    <button wire:click="setActiveTab('garasi')"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'garasi' ? 'bg-white text-slate-900 shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Kesiapan Pool & Armada</span>
                    </button>
                @else
                    {{-- Admin IT / Superuser: Akses Semua 3 Tab dengan Label Ringkas --}}
                    <button wire:click="setActiveTab('eksekutif')"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'eksekutif' ? 'bg-white text-slate-900 shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                        <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Ringkasan Eksekutif</span>
                    </button>
                    <button wire:click="setActiveTab('garasi')"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'garasi' ? 'bg-white text-slate-900 shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Kesiapan Pool</span>
                    </button>
                    <button wire:click="setActiveTab('admin')"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'admin' ? 'bg-white text-slate-900 shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Audit Trail & Server</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 1: PERSPEKTIF PIMPINAN (RINGKASAN EKSEKUTIF)        --}}
        {{-- ======================================================== --}}
        @if ($activeTab === 'eksekutif')
            <!-- 5 KPI Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
                <!-- Card 1: Total Peminjaman -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Permohonan</span>
                        <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-slate-900">{{ $totalPeminjaman }}</span>
                        <span class="text-xs text-slate-500 font-medium">Pengajuan</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                        <span class="font-bold text-emerald-600">{{ $disetujuiCount }} Acc</span>
                        <span>•</span>
                        <span class="font-bold text-rose-600">{{ $ditolakCount }} Tolak</span>
                    </div>
                    <div class="h-1 w-full bg-slate-100 rounded-full mt-3 overflow-hidden">
                        <div class="h-full bg-sky-500 rounded-full transition-all duration-500" style="width: {{ $rasioDisetujui }}%"></div>
                    </div>
                </div>

                <!-- Card 2: Utilisasi Armada -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Utilisasi Armada</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-indigo-950">{{ $tingkatUtilisasi }}%</span>
                        <span class="text-xs text-slate-500 font-medium">Bertugas</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-500">
                        <span class="text-slate-700 font-semibold">{{ $armadaDipinjam }} dari {{ $totalArmada }} Unit Pool</span>
                    </div>
                    <div class="h-1 w-full bg-slate-100 rounded-full mt-3 overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full transition-all duration-500" style="width: {{ $tingkatUtilisasi }}%"></div>
                    </div>
                </div>

                <!-- Card 3: Rasio Persetujuan -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rasio Persetujuan</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-emerald-600">{{ $rasioDisetujui }}%</span>
                        <span class="text-xs text-slate-500 font-medium">Approved</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-500">
                        <span>Efisiensi alur disposisi dinas</span>
                    </div>
                    <div class="h-1 w-full bg-slate-100 rounded-full mt-3 overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $rasioDisetujui }}%"></div>
                    </div>
                </div>

                <!-- Card 4: Rata-rata Durasi Persetujuan -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Durasi Otorisasi</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-purple-900">{{ $durasiPersetujuan }}</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-500">
                        <span>Rata-rata respons disposisi</span>
                    </div>
                    <div class="h-1 w-full bg-slate-100 rounded-full mt-3 overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full transition-all duration-500" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Card 5: Total Jarak Tempuh Operasional -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Jarak Tempuh</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-slate-900">{{ number_format($totalKm, 0, ',', '.') }}</span>
                        <span class="text-xs text-slate-500 font-medium">KM</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-500">
                        <span>Akumulasi odometer selesai</span>
                    </div>
                    <div class="h-1 w-full bg-slate-100 rounded-full mt-3 overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <!-- 2 Kolom: Distribusi Unit Kerja & Kesiapan Armada -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Distribusi Pemakaian per Unit Kerja -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Distribusi Perjalanan per Unit Kerja</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Frekuensi permohonan kendaraan kedinasan berdasarkan unit pengusul</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">
                            {{ $unitKerjaStats->sum('bookings_count') }} Penugasan
                        </span>
                    </div>

                    <div class="space-y-4 pt-2">
                        @php $maxBookings = max(1, $unitKerjaStats->max('bookings_count')); @endphp
                        @forelse ($unitKerjaStats as $unit)
                            @php
                                $percentage = round(($unit->bookings_count / $maxBookings) * 100);
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs sm:text-sm">
                                    <span class="font-semibold text-slate-800">{{ $unit->nama_unit }}</span>
                                    <span class="font-bold text-slate-900">{{ $unit->bookings_count }} <span class="text-slate-400 font-normal">kali</span></span>
                                </div>
                                <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden flex">
                                    <div class="h-full bg-gradient-to-r from-sky-500 to-indigo-600 rounded-full transition-all duration-700" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-6">Belum ada aktivitas penugasan tercatat pada periode ini.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Kolom Kanan: Status Kesiapan Armada Pool -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-5 flex flex-col justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Kesiapan Armada Saat Ini</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Kondisi fisik operasional armada di Garasi Pool</p>

                        <div class="mt-6 space-y-3">
                            <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-emerald-950">Tersedia di Garasi</span>
                                </div>
                                <span class="text-base font-black text-emerald-700">{{ $armadaTersedia }} Unit</span>
                            </div>

                            <div class="p-3.5 rounded-xl bg-sky-50/70 border border-sky-200/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full bg-sky-500 animate-pulse"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-sky-950">Sedang Digunakan (Dinas)</span>
                                </div>
                                <span class="text-base font-black text-sky-700">{{ $armadaDipinjam }} Unit</span>
                            </div>

                            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-amber-950">Perlu Perhatian / Servis</span>
                                </div>
                                <span class="text-base font-black text-amber-700">{{ $armadaPerhatian }} Unit</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Total Seluruh Armada Pool:</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $totalArmada }} Unit</span>
                    </div>
                </div>
            </div>

            <!-- Tabel Utilisasi per Kendaraan & Rekap Peminjaman Terkini -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tabel Kiri: Utilisasi per Armada (Top vs Jarang) -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Perbandingan Utilisasi Kendaraan</h2>
                            <p class="text-xs text-slate-500">Peringkat intensitas penugasan armada pada periode ini</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3">Kendaraan</th>
                                    <th class="py-2.5 px-3 text-center">Status</th>
                                    <th class="py-2.5 px-3 text-right">Odometer</th>
                                    <th class="py-2.5 px-3 text-right">Intensitas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($vehicleStats as $v)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-3">
                                            <p class="font-bold text-slate-900">{{ $v->merk }} {{ $v->tipe_model }}</p>
                                            <p class="text-slate-400 text-xs">{{ $v->no_polisi }}</p>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            @if ($v->status === 'tersedia')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Tersedia</span>
                                            @elseif ($v->status === 'dipinjam')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800">Dipinjam</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Perhatian</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-right font-medium text-slate-700">
                                            {{ number_format($v->odometer_terakhir, 0, ',', '.') }} km
                                        </td>
                                        <td class="py-3 px-3 text-right">
                                            <span class="inline-flex items-center gap-1 font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-lg border border-sky-100">
                                                {{ $v->bookings_count }}x Dinas
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-xs text-slate-400">Belum ada armada terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Kanan: Log Peminjaman Terkini -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Aktivitas Peminjaman Terkini</h2>
                            <p class="text-xs text-slate-500">5 permohonan dinas terbaru pada sistem</p>
                        </div>
                        <a href="{{ url('/portal/laporan') }}" class="text-xs font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1">
                            Laporan Lengkap &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentBookings as $rb)
                            <div class="py-3 flex items-start justify-between gap-3">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 text-xs sm:text-sm">{{ $rb->kode_peminjaman }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                            {{ $rb->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 font-medium">{{ $rb->tujuan_perjalanan }} ({{ $rb->kota_tujuan }})</p>
                                    <p class="text-[11px] text-slate-400">
                                        Pemohon: {{ $rb->user?->name }} • {{ $rb->unitKerja?->nama_unit }} • {{ $rb->tanggal_berangkat?->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-semibold text-slate-700 block">{{ $rb->vehicle?->no_polisi ?? 'Belum Ditunjuk' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $rb->jenis_pengemudi === 'sopir_dinas' ? 'Sopir Pool' : 'Swakemudi' }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-6">Belum ada riwayat peminjaman.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        {{-- ======================================================== --}}
        {{-- TAB 2: PERSPEKTIF KEPALA GARASI (OPERASIONAL POOL)      --}}
        {{-- ======================================================== --}}
        @elseif ($activeTab === 'garasi')
            <!-- 4 KPI Card Garasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Armada Siap Jalan</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-emerald-600">{{ $armadaTersedia }}</span>
                        <span class="text-xs text-slate-500 font-medium">Unit di Garasi</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Dapat langsung ditugaskan dinas</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sedang Bertugas</span>
                        <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <span class="w-3 h-3 rounded-full bg-sky-500 animate-pulse"></span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-sky-700">{{ $armadaDipinjam }}</span>
                        <span class="text-xs text-slate-500 font-medium">Unit di Luar Pool</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Menunggu serah terima masuk (Checkin)</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Perlu Perhatian / Servis</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-amber-600">{{ $armadaPerhatian }}</span>
                        <span class="text-xs text-slate-500 font-medium">Unit Butuh Tindakan</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Pajak kedaluwarsa atau kendala teknis</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rating Kondisi Kembali</span>
                        <div class="w-9 h-9 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center">
                            ★
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-slate-900">{{ $avgRatingKembali }}</span>
                        <span class="text-xs text-slate-500 font-medium">/ 5.0 Bintang</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Dari {{ $totalCheckins }} formulir serah terima masuk</p>
                </div>
            </div>

            <!-- Action Buttons Shortcut -->
            <div class="bg-gradient-to-r from-sky-50 to-indigo-50 border border-sky-100 rounded-2xl p-5 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Pintasan Alur Otorisasi & Serah Terima</h3>
                    <p class="text-xs text-slate-600 mt-0.5">Kelola verifikasi teknis armada dan proses serah terima fisik langsung dari satu tempat.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ url('/portal/verifikasi') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Verifikasi Permohonan
                    </a>
                    <a href="{{ url('/portal/checkout') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Serah Terima Keluar (Checkout)
                    </a>
                    <a href="{{ url('/portal/checkin') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Serah Terima Masuk (Checkin)
                    </a>
                </div>
            </div>

            <!-- Tabel Pengingat Servis, Pajak & SIM (< 30 Hari) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pengingat Pajak & KIR -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Jatuh Tempo Pajak & KIR (&le; 30 Hari)</h2>
                            <p class="text-xs text-slate-500">Peringatan proaktif perpanjangan STNK, Pajak 5 Tahunan & KIR</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            {{ $taxReminders->count() }} Unit
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($taxReminders as $tv)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-bold text-slate-900 text-xs sm:text-sm">{{ $tv->no_polisi }} — {{ $tv->merk }} {{ $tv->tipe_model }}</p>
                                    <div class="flex items-center gap-3 mt-1 text-[11px] text-slate-500">
                                        <span>Pajak Tahunan: <strong class="text-slate-700">{{ $tv->tanggal_pajak_tahunan?->format('d/m/Y') ?? '-' }}</strong></span>
                                        @if ($tv->tanggal_kir_berlaku)
                                            <span>• KIR: <strong class="text-slate-700">{{ $tv->tanggal_kir_berlaku->format('d/m/Y') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black {{ $tv->hasExpiredTax() ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $tv->hasExpiredTax() ? 'LEWAT JATUH TEMPO' : 'SEGERA PERPANJANG' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-6">Seluruh administrasi pajak & KIR kendaraan dalam kondisi aman.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pengingat Servis Rutin & Pengemudi SIM -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Jadwal Servis Berkala & SIM Driver</h2>
                            <p class="text-xs text-slate-500">Pemantauan kilometer odometer & lisensi berkendara</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Kebutuhan Servis Rutin</span>
                            @forelse ($serviceReminders as $sv)
                                <div class="p-2.5 rounded-xl bg-slate-50 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="font-bold text-slate-800">{{ $sv->no_polisi }} ({{ $sv->merk }})</span>
                                        <p class="text-[11px] text-slate-500">Odo Terakhir: {{ number_format($sv->odometer_terakhir, 0, ',', '.') }} km (Servis: {{ number_format($sv->odometer_service_terakhir, 0, ',', '.') }} km)</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-lg bg-orange-100 text-orange-800 font-bold text-[10px]">
                                        {{ $sv->status === 'perlu_perhatian' ? 'Perlu Servis' : 'Mendekati Limit' }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Kondisi mesin seluruh armada terpantau prima.</p>
                            @endforelse
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Masa Berlaku SIM Driver Pool</span>
                            @forelse ($driverSimReminders as $drv)
                                <div class="p-2.5 rounded-xl bg-slate-50 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="font-bold text-slate-800">{{ $drv->nama_driver }} (SIM {{ $drv->jenis_sim }})</span>
                                        <p class="text-[11px] text-slate-500">Kedaluwarsa: {{ $drv->masa_berlaku_sim?->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-lg bg-amber-100 text-amber-800 font-bold text-[10px]">
                                        H-{{ ceil(now()->diffInDays($drv->masa_berlaku_sim, false)) }} Hari
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Masa berlaku SIM seluruh sopir aktif terpantau aman.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        {{-- ======================================================== --}}
        {{-- TAB 3: PERSPEKTIF ADMIN IT (AUDIT TRAIL & SERVER HEALTH)--}}
        {{-- ======================================================== --}}
        @elseif ($activeTab === 'admin')
            <!-- 4 System Health Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Database Health -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Database Engine</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $dbHealthy ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dbHealthy ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $dbHealthy ? 'Connected' : 'Error' }}
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xl font-black text-slate-900">MySQL sipendi_db</p>
                        <p class="text-xs text-slate-500 mt-1">InnoDB Engine • Latensi Optimal</p>
                    </div>
                </div>

                <!-- Background Queue Status -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Queue Worker Health</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $failedJobs === 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $failedJobs === 0 ? 'All Normal' : $failedJobs . ' Failed' }}
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xl font-black text-slate-900">{{ $pendingJobs }} Pending Jobs</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $failedJobs }} kegagalan eksekusi tercatat</p>
                    </div>
                </div>

                <!-- Storage & Symlink -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Storage Symlink</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                            Active
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xl font-black text-slate-900">/public/storage</p>
                        <p class="text-xs text-slate-500 mt-1">Dokumen tugas & BAST aman</p>
                    </div>
                </div>

                <!-- Scheduler Engine -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Scheduler & Cron</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800">
                            Harian 06:00
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xl font-black text-slate-900">sipendi:check-reminders</p>
                        <p class="text-xs text-slate-500 mt-1">Audit servis, pajak & SIM otomatis</p>
                    </div>
                </div>
            </div>

            <!-- Activity Logs Live Feed Table -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Live Feed Jejak Audit Sistem (Spatie Activity Log)</h2>
                        <p class="text-xs text-slate-500">10 riwayat manipulasi data dan tindakan pengguna terkini</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/admin/activity-logs') }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors inline-flex items-center gap-1">
                            Buka Semua di Filament &rarr;
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3">Waktu</th>
                                <th class="py-2.5 px-3">Pelaku (Causer)</th>
                                <th class="py-2.5 px-3">Log Name</th>
                                <th class="py-2.5 px-3 text-center">Tindakan</th>
                                <th class="py-2.5 px-3">Deskripsi Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentActivityLogs as $log)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-3 text-slate-500 whitespace-nowrap">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                        <span class="block text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="font-bold text-slate-800 block">{{ $log->causer?->name ?? 'Sistem / CLI' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $log->causer?->email ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                            {{ $log->log_name ?? 'default' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                            {{ $log->event === 'created' ? 'bg-emerald-100 text-emerald-800' : ($log->event === 'updated' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-800') }}">
                                            {{ strtoupper($log->event ?? 'action') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-slate-700">
                                        {{ $log->description }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-xs text-slate-400">Belum ada catatan aktivitas sistem.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
