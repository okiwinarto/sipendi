@extends('layouts.portal')

@section('title', 'Beranda Portal — MAS PENDI RSUD Sidawangi')

@section('content')
<div class="space-y-8">
    <!-- Welcome Hero Banner (Aksen Elegan Senada Landing Page) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-sky-950 to-slate-900 text-white p-7 sm:p-10 shadow-xl border border-sky-950/80">
        <!-- Radial Dot Texture Overlay -->
        <div class="absolute inset-0 opacity-20 [background-image:radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>

        <!-- Ambient Vehicle Silhouette Background -->
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-80 h-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
            </svg>
        </div>

        <div class="relative z-10">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-sky-500/20 border border-sky-400/30 text-sky-200 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Unit Kerja: {{ auth()->user()->unitKerja?->nama_unit ?? 'RSUD Sidawangi' }}</span>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-xs font-bold backdrop-blur-xs">
                    <span>MAS PENDI</span>
                    <span class="text-sky-300 font-normal hidden sm:inline">• Manajemen Aset & Peminjaman Kendaraan Dinas</span>
                </div>
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-300">{{ auth()->user()->name }}</span>
            </h1>
            <p class="mt-3 text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                Kelola permohonan transportasi dinas operasional dengan mudah, transparan, terhindar dari jadwal bentrok, dan terpantau secara mandiri.
            </p>

            <!-- Quick Action Buttons -->
            <div class="mt-8 flex flex-wrap items-center gap-3.5">
                <a href="{{ route('portal.ajukan') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-white font-bold text-xs shadow-lg shadow-sky-500/25 transition-all hover:scale-[1.02] cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Ajukan Peminjaman Mobil Baru</span>
                </a>
                <a href="{{ route('portal.kalender') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-xs backdrop-blur-xs transition-colors cursor-pointer">
                    <svg class="w-4 h-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Cek Ketersediaan Armada</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid (Identik dengan Card Metrik Landing Page) -->
    @php
        $userId = auth()->id();
        $totalBookings = \App\Models\Booking::where('user_id', $userId)->count();
        $pendingBookings = \App\Models\Booking::where('user_id', $userId)->whereIn('status', ['diajukan', 'diverifikasi_garasi'])->count();
        $approvedBookings = \App\Models\Booking::where('user_id', $userId)->where('status', 'disetujui')->count();
        $activeBookings = \App\Models\Booking::where('user_id', $userId)->where('status', 'kendaraan_keluar')->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Stat 1 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Riwayat</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ $totalBookings }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Seluruh permohonan dinas saya</span>
        </div>

        <!-- Stat 2 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dalam Proses</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-amber-600">{{ $pendingBookings }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Verifikasi garasi & pimpinan</span>
        </div>

        <!-- Stat 3 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Siap Berangkat</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600">{{ $approvedBookings }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Disetujui pimpinan RSUD</span>
        </div>

        <!-- Stat 4 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sedang Dinas</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-sky-600">{{ $activeBookings }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Mobil beroperasi di luar pool</span>
        </div>
    </div>

    <!-- Main Grid: Recent Bookings & Flow Guide -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Recent Bookings Table (8 Cols) -->
        <div class="lg:col-span-8 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-extrabold text-slate-900 text-base sm:text-lg">Pengajuan Terbaru Anda</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar permohonan dinas terakhir yang Anda registrasikan</p>
                </div>
                <a href="{{ route('portal.riwayat') }}" class="inline-flex items-center gap-1 text-xs font-bold text-sky-600 hover:text-sky-700 transition-colors">
                    <span>Lihat Semua</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php
                $recent = \App\Models\Booking::with(['vehicle', 'unitKerja'])
                    ->where('user_id', $userId)
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp

            @if ($recent->count() > 0)
                <div class="space-y-3.5">
                    @foreach ($recent as $item)
                        <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-sky-50/40 hover:border-sky-200 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-slate-900 text-xs sm:text-sm">{{ $item->kode_peminjaman }}</span>
                                    <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-sky-100 text-sky-800 border border-sky-200/60">
                                        {{ $item->status_label }}
                                    </span>
                                    @if ($item->tingkat_prioritas === 'mendesak')
                                        <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-rose-500 text-white uppercase">Cito</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-800 font-semibold leading-relaxed">
                                    {{ $item->tujuan_perjalanan }}
                                </p>
                                <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-500">
                                    <span class="inline-flex items-center gap-1 text-sky-700 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $item->kota_tujuan }}
                                    </span>
                                    <span>•</span>
                                    <span>Berangkat: {{ $item->tanggal_berangkat->translatedFormat('d M Y') }} • {{ substr($item->jam_berangkat, 0, 5) }} WIB</span>
                                </div>
                            </div>
                            <div class="sm:text-right shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/60">
                                <span class="text-xs font-bold text-slate-800 block">
                                    {{ $item->vehicle ? $item->vehicle->nama_lengkap : 'Armada Menunggu Garasi' }}
                                </span>
                                <span class="text-[11px] text-slate-500">
                                    {{ $item->jenis_pengemudi === 'sopir_dinas' ? 'Sopir Pool RSUD' : 'Swakemudi' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Belum Ada Pengajuan Dinas</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                        Anda belum memiliki permohonan peminjaman kendaraan yang tercatat. Klik tombol di bawah untuk membuat pengajuan baru.
                    </p>
                    <a href="{{ route('portal.ajukan') }}"
                        class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-xs transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Ajukan Peminjaman Pertama Anda</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar / Flow Guide (4 Cols) -->
        <div class="lg:col-span-4 space-y-5">
            <!-- 4 Langkah Alur Cepat -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 mb-1 block">Panduan Singkat</span>
                <h3 class="text-base font-extrabold text-slate-900 mb-4">Alur Peminjaman Mobil</h3>

                <div class="space-y-3.5 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-sky-100 text-sky-700 font-bold flex items-center justify-center shrink-0 text-[11px] mt-0.5">1</div>
                        <div>
                            <span class="font-bold text-slate-800 block">Isi Formulir Online</span>
                            <span class="text-slate-500 text-[11px]">Tentukan tujuan dinas, tanggal, jam & jumlah orang.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 font-bold flex items-center justify-center shrink-0 text-[11px] mt-0.5">2</div>
                        <div>
                            <span class="font-bold text-slate-800 block">Verifikasi Garasi</span>
                            <span class="text-slate-500 text-[11px]">Kepala Garasi menetapkan armada laik jalan & sopir pool.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center shrink-0 text-[11px] mt-0.5">3</div>
                        <div>
                            <span class="font-bold text-slate-800 block">Persetujuan Pimpinan</span>
                            <span class="text-slate-500 text-[11px]">Pimpinan menyetujui pengajuan secara resmi.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0 text-[11px] mt-0.5">4</div>
                        <div>
                            <span class="font-bold text-slate-800 block">Serah Terima & Berangkat</span>
                            <span class="text-slate-500 text-[11px]">Pencatatan kilometer, BBM & foto kondisi awal mobil.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak Pool Garasi -->
            <div class="p-5 rounded-3xl bg-gradient-to-b from-sky-50 to-white border border-sky-100 shadow-xs">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-sky-600 text-white flex items-center justify-center text-sm shadow-xs">
                        📞
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Bantuan Pool & Garasi</h4>
                        <span class="text-[11px] text-sky-700 font-semibold">Siaga Dinas Operasional</span>
                    </div>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed">
                    Untuk kebutuhan darurat atau pengajuan mendadak (*Cito*), hubungi Petugas Garasi:
                </p>
                <div class="mt-3 pt-2.5 border-t border-sky-100/80 flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-800">H. Suhendar (Garasi)</span>
                    <span class="font-mono text-sky-700 font-bold text-[11px]">0812-3456-7890</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
