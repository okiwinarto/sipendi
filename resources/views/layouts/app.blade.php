<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MAS PENDI — Manajemen Aset & Peminjaman Kendaraan Dinas RSUD Sidawangi')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800 antialiased selection:bg-sky-500 selection:text-white">

    <!-- Navbar Navigasi Utama -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo & Title -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-600 to-cyan-500 flex items-center justify-center text-white shadow-md shadow-sky-500/20 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-lg tracking-tight text-slate-900">MAS PENDI</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-sky-100 text-sky-800">Fase 1</span>
                        </div>
                        <p class="text-xs font-medium text-slate-500">Manajemen Aset & Peminjaman Kendaraan Dinas</p>
                    </div>
                </a>

                <!-- Navigation Links & Action -->
                <div class="flex items-center gap-2.5">
                    <a href="{{ url('/') }}#alur" class="hidden md:inline-flex text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors px-3 py-2">
                        Alur Peminjaman
                    </a>
                    <a href="{{ url('/') }}#peran" class="hidden md:inline-flex text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors px-3 py-2">
                        Peran Pengguna
                    </a>
                    <a href="{{ url('/login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 rounded-xl border border-sky-200 transition-all">
                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Portal Pegawai</span>
                    </a>
                    @if(auth()->check() && auth()->user()->hasRole('admin_it'))
                    <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 rounded-xl shadow-sm shadow-sky-600/30 transition-all hover:shadow-md hover:shadow-sky-600/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Masuk Panel Sistem</span>
                    </a>
                @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-sky-500 flex items-center justify-center text-white font-bold text-xs">MP</div>
                        <span class="text-white font-bold text-lg tracking-tight">MAS PENDI</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Manajemen Aset dan Peminjaman Kendaraan Dinas (MAS PENDI) RSUD Sidawangi. Transformasi digital operasional transportasi dinas non-ambulans yang transparan, akuntabel, dan terawat.
                    </p>
                    <p class="text-xs text-slate-500 mt-4">
                        Jl. Pangeran Kejaksan, Sidawangi, Kec. Sumber, Kabupaten Cirebon, Jawa Barat
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/admin') }}" class="hover:text-sky-400 transition-colors">Portal Login Pegawai</a></li>
                        <li><a href="{{ url('/') }}#alur" class="hover:text-sky-400 transition-colors">Panduan Alur Peminjaman</a></li>
                        <li><a href="{{ url('/') }}#fitur" class="hover:text-sky-400 transition-colors">Fitur Servis & Pajak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Informasi Sistem</h4>
                    <ul class="space-y-1 text-xs text-slate-400">
                        <li><strong class="text-slate-300">Versi:</strong> 1.0 (Fase 1 Non-Ambulans)</li>
                        <li><strong class="text-slate-300">Basis:</strong> Laravel 13 & Filament</li>
                        <li><strong class="text-slate-300">Dukungan Teknis:</strong> Tim IT RSUD Sidawangi</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} RSUD Sidawangi. Seluruh hak cipta dilindungi.</p>
                <p class="mt-2 sm:mt-0">Manajemen Aset dan Peminjaman Kendaraan Dinas (MAS PENDI)</p>
            </div>
        </div>
    </footer>

</body>
</html>
