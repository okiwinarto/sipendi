<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Pegawai — MAS PENDI RSUD Sidawangi')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800 antialiased selection:bg-sky-500 selection:text-white">

    <!-- Top Header Nav (Role-Tailored, Clean & Fully Responsive) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs"
            x-data="{ mobileMenuOpen: false, moreMenuOpen: false, opMenuOpen: false, notifOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo & Title -->
                <div class="flex items-center gap-2 xl:gap-3 shrink-0">
                    <a href="{{ url('/portal') }}" class="flex items-center gap-2 sm:gap-2.5 group shrink-0">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-sky-600 to-cyan-500 flex items-center justify-center text-white shadow-md shadow-sky-500/20 group-hover:scale-105 transition-transform duration-200 shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div class="shrink-0">
                            <div class="flex items-center gap-1.5">
                                <span class="font-black text-base sm:text-lg tracking-tight text-slate-900 leading-none">MAS PENDI</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800 leading-none">Portal</span>
                            </div>
                            <p class="text-[10px] font-medium text-slate-400 hidden 2xl:block leading-none mt-1">RSUD Sidawangi</p>
                        </div>
                    </a>

                    <!-- Navigation Links Desktop (Role-Focused, Max 4-5 Items, No Overflow) -->
                    <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1 text-xs xl:text-xs 2xl:text-sm font-semibold ml-1 xl:ml-2">
                        @auth
                            {{-- ======================================================== --}}
                            {{-- 1. NAVIGASI KHUSUS KEPALA GARASI                        --}}
                            {{-- ======================================================== --}}
                            @if (auth()->user()->hasRole('kepala_garasi') && !auth()->user()->isAdmin())
                                @php $pendingGarasiCount = \App\Models\Booking::where('status', 'diajukan')->count(); @endphp
                                
                                <a href="{{ url('/portal/dashboard') }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 xl:px-3 xl:py-2 rounded-xl transition-all {{ request()->is('portal/dashboard*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Dashboard</span>
                                </a>

                                <a href="{{ url('/portal/verifikasi') }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 xl:px-3 xl:py-2 rounded-xl transition-all {{ request()->is('portal/verifikasi*') ? 'bg-emerald-50 text-emerald-700 font-bold border border-emerald-200/60 shadow-xs' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Verifikasi</span>
                                    @if ($pendingGarasiCount > 0)
                                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-500 text-white animate-pulse">
                                            {{ $pendingGarasiCount }}
                                        </span>
                                    @endif
                                </a>

                                <a href="{{ url('/portal/serah-terima') }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 xl:px-3 xl:py-2 rounded-xl transition-all {{ request()->is('portal/serah-terima*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" /></svg>
                                    <span>Serah Terima</span>
                                </a>

                                <a href="{{ url('/portal/kalender') }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 xl:px-3 xl:py-2 rounded-xl transition-all {{ request()->is('portal/kalender*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Kalender</span>
                                </a>

                                <!-- Dropdown Menu Lainnya untuk Kepala Garasi -->
                                <div class="relative" @click.outside="moreMenuOpen = false">
                                    <button @click="moreMenuOpen = !moreMenuOpen"
                                            class="inline-flex items-center gap-1 px-2 py-1.5 xl:px-2.5 xl:py-2 rounded-xl text-slate-600 hover:text-sky-600 hover:bg-slate-100/70 transition-all">
                                        <span>Lainnya</span>
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    <div x-show="moreMenuOpen"
                                         x-transition
                                         class="absolute left-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-left"
                                         style="display: none;">
                                        <a href="{{ url('/portal/laporan') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Laporan Operasional</span>
                                        </a>
                                        <a href="{{ url('/portal/riwayat') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <span>Peminjaman Saya</span>
                                        </a>
                                        <a href="{{ url('/portal/ajukan') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span>Ajukan Peminjaman</span>
                                        </a>
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <a href="{{ url('/admin') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                                            <span>Backoffice Filament</span>
                                        </a>
                                    </div>
                                </div>


                            {{-- ======================================================== --}}
                            {{-- 2. NAVIGASI KHUSUS PIMPINAN                              --}}
                            {{-- ======================================================== --}}
                            @elseif (auth()->user()->hasRole('pimpinan') && !auth()->user()->isAdmin())
                                @php $pendingPimpinanCount = \App\Models\Booking::where('status', 'diverifikasi_garasi')->count(); @endphp

                                <a href="{{ url('/portal/dashboard') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl transition-all {{ request()->is('portal/dashboard*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Dashboard</span>
                                </a>

                                <a href="{{ url('/portal/persetujuan') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl transition-all {{ request()->is('portal/persetujuan*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-200/60 shadow-xs' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                    <span>Persetujuan</span>
                                    @if ($pendingPimpinanCount > 0)
                                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-indigo-600 text-white animate-pulse">
                                            {{ $pendingPimpinanCount }}
                                        </span>
                                    @endif
                                </a>

                                <a href="{{ url('/portal/kalender') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl transition-all {{ request()->is('portal/kalender*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Kalender</span>
                                </a>

                                <a href="{{ url('/portal/laporan') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl transition-all {{ request()->is('portal/laporan*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Laporan</span>
                                </a>

                                <!-- Dropdown Menu Lainnya untuk Pimpinan -->
                                <div class="relative" @click.outside="moreMenuOpen = false">
                                    <button @click="moreMenuOpen = !moreMenuOpen"
                                            class="inline-flex items-center gap-1 px-2.5 py-2 rounded-xl text-slate-600 hover:text-sky-600 hover:bg-slate-100/70 transition-all">
                                        <span>Lainnya</span>
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    <div x-show="moreMenuOpen"
                                         x-transition
                                         class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-left"
                                         style="display: none;">
                                        <a href="{{ url('/portal/riwayat') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <span>Peminjaman Saya</span>
                                        </a>
                                        <a href="{{ url('/portal/ajukan') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span>Ajukan Peminjaman</span>
                                        </a>
                                        <a href="{{ url('/portal') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            <span>Beranda Depan</span>
                                        </a>
                                    </div>
                                </div>

                            {{-- ======================================================== --}}
                            {{-- 3. NAVIGASI KHUSUS ADMIN IT                              --}}
                            {{-- ======================================================== --}}
                            @elseif (auth()->user()->isAdmin())
                                <a href="{{ url('/portal/dashboard') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl transition-all {{ request()->is('portal/dashboard*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ url('/portal/kalender') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl transition-all {{ request()->is('portal/kalender*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <span>Kalender</span>
                                </a>
                                <a href="{{ url('/portal/laporan') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl transition-all {{ request()->is('portal/laporan*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <span>Laporan</span>
                                </a>

                                <!-- Dropdown Operasional Pool (Admin IT) -->
                                <div class="relative" @click.outside="opMenuOpen = false">
                                    <button @click="opMenuOpen = !opMenuOpen"
                                            class="inline-flex items-center gap-1 px-2.5 py-2 rounded-xl text-slate-600 hover:text-sky-600 hover:bg-slate-100/70 transition-all">
                                        <span>Operasional Pool</span>
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    <div x-show="opMenuOpen"
                                         x-transition
                                         class="absolute left-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-left"
                                         style="display: none;">
                                        <a href="{{ url('/portal/verifikasi') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <span>Verifikasi Permohonan</span>
                                        </a>
                                        <a href="{{ url('/portal/serah-terima') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <span>Serah Terima Fisik</span>
                                        </a>
                                        <a href="{{ url('/portal/persetujuan') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <span>Persetujuan Pimpinan</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Dropdown Layanan Pegawai (Admin IT) -->
                                <div class="relative" @click.outside="moreMenuOpen = false">
                                    <button @click="moreMenuOpen = !moreMenuOpen"
                                            class="inline-flex items-center gap-1 px-2.5 py-2 rounded-xl text-slate-600 hover:text-sky-600 hover:bg-slate-100/70 transition-all">
                                        <span>Lainnya</span>
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    <div x-show="moreMenuOpen"
                                         x-transition
                                         class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-left"
                                         style="display: none;">
                                        <a href="{{ url('/portal/riwayat') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <span>Peminjaman Saya</span>
                                        </a>
                                        <a href="{{ url('/portal/ajukan') }}" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                            <span>Ajukan Peminjaman</span>
                                        </a>
                                    </div>
                                </div>

                            {{-- ======================================================== --}}
                            {{-- 4. NAVIGASI PEGAWAI / PEMOHON BIASA                      --}}
                            {{-- ======================================================== --}}
                            @else
                                <a href="{{ url('/portal') }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl transition-all {{ request()->is('portal') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>Beranda</span>
                                </a>
                                <a href="{{ url('/portal/ajukan') }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl transition-all {{ request()->is('portal/ajukan*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Ajukan Peminjaman</span>
                                </a>
                                <a href="{{ url('/portal/kalender') }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl transition-all {{ request()->is('portal/kalender*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Kalender Armada</span>
                                </a>
                                <a href="{{ url('/portal/riwayat') }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl transition-all {{ request()->is('portal/riwayat*') ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200/60 shadow-xs' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100/70' }}">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Peminjaman Saya</span>
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>

                <!-- User Profile & Action Right Section -->
                <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                    @auth
                        <!-- In-App Notification Bell with Alpine Dropdown -->
                        <div class="relative">
                            <button @click="notifOpen = !notifOpen"
                                    type="button"
                                    title="Pemberitahuan Sistem"
                                    class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors focus:outline-none">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                                @if ($unreadCount > 0)
                                    <span class="absolute top-1 right-1 flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-white text-[10px] font-bold items-center justify-center">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown Panel -->
                            <div x-show="notifOpen"
                                 @click.outside="notifOpen = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-left"
                                 style="display: none;">
                                <div class="px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold text-slate-800">Pemberitahuan Sistem</span>
                                        @if ($unreadCount > 0)
                                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-sky-100 text-sky-700">{{ $unreadCount }} Baru</span>
                                        @endif
                                    </div>
                                    @if ($unreadCount > 0)
                                        <form action="{{ route('portal.notifications.markAllAsRead') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[11px] text-sky-600 hover:text-sky-800 font-semibold">
                                                Tandai dibaca
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-72 overflow-y-auto divide-y divide-slate-50">
                                    @forelse (auth()->user()->notifications()->take(6)->get() as $notif)
                                        <a href="{{ $notif->data['action_url'] ?? '#' }}" 
                                           class="block px-4 py-3 hover:bg-slate-50 transition-colors {{ $notif->read_at ? 'opacity-70' : 'bg-sky-50/40' }}">
                                            <div class="text-xs font-bold text-slate-900 mb-0.5 flex items-center justify-between">
                                                <span>{{ $notif->data['title'] ?? 'Pemberitahuan' }}</span>
                                                @if (!$notif->read_at)
                                                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-600 line-clamp-2">{{ $notif->data['message'] ?? '' }}</p>
                                            <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                        </a>
                                    @empty
                                        <div class="p-6 text-center text-xs text-slate-400">
                                            Tidak ada notifikasi saat ini
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Compact User Profile -->
                        <div class="hidden sm:flex items-center gap-2.5 pl-1 pr-2">
                            @php
                                $nameWords = explode(' ', auth()->user()->name);
                                $initials = strtoupper(substr($nameWords[0] ?? 'U', 0, 1) . substr($nameWords[1] ?? '', 0, 1));
                            @endphp
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-500 to-cyan-400 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                {{ $initials }}
                            </div>
                            <div class="flex flex-col text-left max-w-[100px] xl:max-w-[160px]">
                                <span class="text-xs font-bold text-slate-900 truncate leading-tight">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-slate-400 truncate hidden xl:block">
                                    {{ auth()->user()->unitKerja?->nama_unit ?? 'Pegawai RSUD' }}
                                </span>
                            </div>
                        </div>

                        <!-- Logout Button -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                title="Keluar dari Portal"
                                class="inline-flex items-center gap-1.5 p-2 sm:px-3 sm:py-2 rounded-xl text-xs font-bold text-rose-600 hover:text-white hover:bg-rose-600 bg-rose-50 transition-all border border-rose-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    @endauth

                    <!-- Mobile / Tablet Hamburger Toggle Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile & Tablet Drawer Navigation (Clean Vertical List, Zero Horizontal Scroll!) -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-3 shadow-xl"
             style="display: none;">
            
            @auth
                <!-- User Summary Card in Mobile -->
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-600 text-white font-bold flex items-center justify-center text-sm">
                        {{ $initials ?? 'U' }}
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->unitKerja?->nama_unit ?? 'Pegawai RSUD' }}</p>
                    </div>
                </div>

                <!-- Navigation Links List Mobile -->
                <div class="space-y-1 text-sm font-semibold pt-1">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 px-3">Menu Utama</span>
                    
                    @if (auth()->user()->isKepalaGarasi() || auth()->user()->isPimpinan() || auth()->user()->isAdmin())
                        <a href="{{ url('/portal/dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl {{ request()->is('portal/dashboard*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Dashboard Eksekutif</span>
                        </a>
                    @endif

                    @if (auth()->user()->isKepalaGarasi())
                        <a href="{{ url('/portal/verifikasi') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->is('portal/verifikasi*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Verifikasi Armada</span>
                            </div>
                            @if (($pendingGarasiCount ?? 0) > 0)
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white">{{ $pendingGarasiCount }}</span>
                            @endif
                        </a>
                        <a href="{{ url('/portal/serah-terima') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl {{ request()->is('portal/serah-terima*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" /></svg>
                            <span>Serah Terima Fisik (Checkout/Checkin)</span>
                        </a>
                    @endif

                    @if (auth()->user()->isPimpinan())
                        <a href="{{ url('/portal/persetujuan') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->is('portal/persetujuan*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <span>Persetujuan Pimpinan</span>
                            </div>
                            @if (($pendingPimpinanCount ?? 0) > 0)
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-600 text-white">{{ $pendingPimpinanCount }}</span>
                            @endif
                        </a>
                    @endif

                    <a href="{{ url('/portal/kalender') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl {{ request()->is('portal/kalender*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Kalender Ketersediaan Armada</span>
                    </a>

                    @if (auth()->user()->isPimpinan() || auth()->user()->isKepalaGarasi() || auth()->user()->isAdmin())
                        <a href="{{ url('/portal/laporan') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl {{ request()->is('portal/laporan*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Laporan & Rekapitulasi</span>
                        </a>
                    @endif

                    <div class="border-t border-slate-100 my-2"></div>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 px-3">Layanan Pegawai</span>

                    <a href="{{ url('/portal/ajukan') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl {{ request()->is('portal/ajukan*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Ajukan Peminjaman</span>
                    </a>

                    <a href="{{ url('/portal/riwayat') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl {{ request()->is('portal/riwayat*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Peminjaman Saya</span>
                    </a>

                    @if (auth()->user()->hasAnyRole(['admin_it', 'kepala_garasi']))
                        <div class="border-t border-slate-100 my-2"></div>
                        <a href="{{ url('/admin') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 font-bold">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                            <span>Buka Backoffice Filament</span>
                        </a>
                    @endif
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Area with Subtle Mesh Gradient -->
    <main class="flex-grow relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <!-- Toast / Flash Success Alert -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 text-emerald-900 text-sm flex items-start gap-3 shadow-xs animate-in fade-in slide-in-from-top-2 duration-300">
                    <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-600/30">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="flex-grow pt-0.5">
                        <p class="font-bold text-emerald-950">Berhasil!</p>
                        <p class="text-xs text-emerald-800 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Toast / Flash Error Alert -->
            @if (session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-rose-50 to-amber-50 border border-rose-200 text-rose-900 text-sm flex items-start gap-3 shadow-xs animate-in fade-in slide-in-from-top-2 duration-300">
                    <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-600/30">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="flex-grow pt-0.5">
                        <p class="font-bold text-rose-950">Terjadi Kesalahan</p>
                        <p class="text-xs text-rose-800 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <!-- Footer Standar Resmi RSUD Sidawangi (Sesuai Landing Page) -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-600 to-cyan-500 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-sky-500/20">MP</div>
                        <span class="text-white font-extrabold text-lg tracking-tight">MAS PENDI</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Manajemen Aset dan Peminjaman Kendaraan Dinas (MAS PENDI) RSUD Sidawangi. Transformasi digital operasional transportasi dinas non-ambulans yang transparan, akuntabel, dan terawat.
                    </p>
                    <p class="text-xs text-slate-500 mt-4 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Jl. Pangeran Kejaksan, Sidawangi, Kec. Sumber, Kabupaten Cirebon, Jawa Barat</span>
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Navigasi Portal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/portal') }}" class="hover:text-sky-400 transition-colors">Beranda Portal</a></li>
                        <li><a href="{{ url('/portal/ajukan') }}" class="hover:text-sky-400 transition-colors">Ajukan Peminjaman Mobil</a></li>
                        <li><a href="{{ url('/portal/kalender') }}" class="hover:text-sky-400 transition-colors">Kalender Ketersediaan</a></li>
                        <li><a href="{{ url('/portal/riwayat') }}" class="hover:text-sky-400 transition-colors">Riwayat Peminjaman Saya</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Informasi Sistem</h4>
                    <ul class="space-y-1.5 text-xs text-slate-400">
                        <li><strong class="text-slate-300">Versi:</strong> 1.0 (Fase 1 Non-Ambulans)</li>
                        <li><strong class="text-slate-300">Pengelola Pool:</strong> H. Suhendar (Garasi Utama)</li>
                        <li><strong class="text-slate-300">Dukungan Teknis:</strong> Tim IT RSUD Sidawangi</li>
                        <li><strong class="text-slate-300">Jam Operasional Pool:</strong> 24 Jam (Siaga Dinas)</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} RSUD Sidawangi. Seluruh hak cipta dilindungi.</p>
                <p class="mt-2 sm:mt-0">Manajemen Aset dan Peminjaman Kendaraan Dinas (MAS PENDI)</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
