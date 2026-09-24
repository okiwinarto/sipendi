@extends('layouts.app')

@section('title', 'MAS PENDI — Manajemen Aset dan Peminjaman Kendaraan Dinas RSUD Sidawangi')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-sky-50 via-white to-slate-50 pt-16 pb-20 border-b border-slate-200/70">
    <div class="absolute inset-0 z-0 opacity-40 [background-image:radial-gradient(#0284c7_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-100/90 border border-sky-200 text-sky-800 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-sky-600 animate-pulse"></span>
                <span>Fase 1: Khusus Kendaraan Dinas Non-Ambulans</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Peminjaman Kendaraan Dinas Jadi <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-cyan-600">Tertib, Transparan, & Akuntabel</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-slate-600 leading-relaxed">
                Platform resmi RSUD Sidawangi untuk mendigitalkan pengajuan perjalanan dinas, persetujuan pimpinan berjenjang, serah terima digital armada, serta pemeliharaan servis dan pajak kendaraan tepat waktu.
            </p>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-base font-bold text-white bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 rounded-xl shadow-lg shadow-sky-600/30 transition-all hover:scale-[1.02]">
                    <span>Buka Portal Peminjaman Mobil</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="{{ url('/admin') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-base font-semibold text-slate-700 bg-white hover:bg-slate-100 rounded-xl border border-slate-300 shadow-xs transition-colors">
                    <span>Masuk Panel Backoffice</span>
                </a>
            </div>

            <!-- Key Feature Highlights -->
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 text-left">
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                    <div class="text-sky-600 font-extrabold text-2xl mb-1">Real-Time</div>
                    <div class="text-xs font-semibold text-slate-700">Kalender Ketersediaan</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Bebas bentrok jadwal</div>
                </div>
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                    <div class="text-sky-600 font-extrabold text-2xl mb-1">2 Tingkat</div>
                    <div class="text-xs font-semibold text-slate-700">Otorisasi Berjenjang</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Garasi & Pimpinan</div>
                </div>
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                    <div class="text-sky-600 font-extrabold text-2xl mb-1">Digital</div>
                    <div class="text-xs font-semibold text-slate-700">Serah Terima & Foto</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Catat BBM & Odometer</div>
                </div>
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                    <div class="text-sky-600 font-extrabold text-2xl mb-1">Otomatis</div>
                    <div class="text-xs font-semibold text-slate-700">Pengingat Servis & Pajak</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Notifikasi H-30 s/d H-1</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4 Peran Pengguna Section -->
<section id="peran" class="py-16 bg-white border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-xs font-bold uppercase tracking-wider text-sky-600 mb-2">Struktur Akses Sistem</h2>
            <h3 class="text-3xl font-extrabold text-slate-900">4 Peran Pengguna MAS PENDI</h3>
            <p class="text-slate-600 mt-2 text-sm">Hak akses dirancang berjenjang dengan pembagian wewenang yang tegas sesuai tata kelola kedinasan RSUD Sidawangi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Role 1: Pemohon -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-sky-50/50 to-white border border-sky-100 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-sm shadow-sky-600/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h4 class="font-bold text-slate-900 text-lg">User Aplikasi</h4>
                <p class="text-xs font-semibold text-sky-700 mb-3">Pegawai / Unit Kerja</p>
                <ul class="text-xs text-slate-600 space-y-2">
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Pengajuan peminjaman online
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Kalender ketersediaan armada
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Lacak status pengajuan real-time
                    </li>
                </ul>
                <div class="mt-5 pt-4 border-t border-slate-100 text-[11px] text-slate-500">
                    <span class="font-semibold text-slate-700">Akun Uji:</span> pemohon@sidawangi.id
                </div>
            </div>

            <!-- Role 2: Kepala Garasi -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-amber-50/50 to-white border border-amber-100 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-sm shadow-amber-600/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h4 class="font-bold text-slate-900 text-lg">Kepala Garasi</h4>
                <p class="text-xs font-semibold text-amber-700 mb-3">Verifikator Teknis Armada</p>
                <ul class="text-xs text-slate-600 space-y-2">
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Verifikasi kelaikan & alokasi mobil
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Checklist keluar & masuk (foto)
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Catat servis berkala & pajak
                    </li>
                </ul>
                <div class="mt-5 pt-4 border-t border-slate-100 text-[11px] text-slate-500">
                    <span class="font-semibold text-slate-700">Akun Uji:</span> garasi@sidawangi.id
                </div>
            </div>

            <!-- Role 3: Pimpinan -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-indigo-50/50 to-white border border-indigo-100 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-sm shadow-indigo-600/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h4 class="font-bold text-slate-900 text-lg">Pimpinan</h4>
                <p class="text-xs font-semibold text-indigo-700 mb-3">Penyetuju Akhir</p>
                <ul class="text-xs text-slate-600 space-y-2">
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Persetujuan 1-klik di perangkat HP
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Dashboard statistik utilisasi
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Riwayat keputusan persetujuan
                    </li>
                </ul>
                <div class="mt-5 pt-4 border-t border-slate-100 text-[11px] text-slate-500">
                    <span class="font-semibold text-slate-700">Akun Uji:</span> pimpinan@sidawangi.id
                </div>
            </div>

            <!-- Role 4: Admin IT -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-slate-100/50 to-white border border-slate-200 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-sm shadow-slate-800/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h4 class="font-bold text-slate-900 text-lg">Admin IT</h4>
                <p class="text-xs font-semibold text-slate-700 mb-3">Pengelola Sistem & Master Data</p>
                <ul class="text-xs text-slate-600 space-y-2">
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Kelola master armada, unit & sopir
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Konfigurasi ambang pengingat
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">✓</span> Pantau audit log sistem
                    </li>
                </ul>
                <div class="mt-5 pt-4 border-t border-slate-100 text-[11px] text-slate-500">
                    <span class="font-semibold text-slate-700">Akun Uji:</span> admin@sidawangi.id
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alur Proses Peminjaman Section -->
<section id="alur" class="py-16 bg-slate-50 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-xs font-bold uppercase tracking-wider text-sky-600 mb-2">Siklus Hidup Peminjaman</h2>
            <h3 class="text-3xl font-extrabold text-slate-900">Alur Pengajuan Hingga Selesai</h3>
            <p class="text-slate-600 mt-2 text-sm">Setiap mutasi status terekam dengan jejak audit dan notifikasi otomatis kepada para pihak.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
                <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 font-bold text-sm mx-auto flex items-center justify-center mb-2">1</div>
                <h5 class="font-bold text-slate-900 text-sm">Diajukan</h5>
                <p class="text-[11px] text-slate-500 mt-1">Pemohon mengisi form dinas & jadwal</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 font-bold text-sm mx-auto flex items-center justify-center mb-2">2</div>
                <h5 class="font-bold text-slate-900 text-sm">Verifikasi Garasi</h5>
                <p class="text-[11px] text-slate-500 mt-1">Kepala Garasi menetapkan unit & sopir</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm mx-auto flex items-center justify-center mb-2">3</div>
                <h5 class="font-bold text-slate-900 text-sm">Persetujuan</h5>
                <p class="text-[11px] text-slate-500 mt-1">Pimpinan menyetujui secara resmi</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mx-auto flex items-center justify-center mb-2">4</div>
                <h5 class="font-bold text-slate-900 text-sm">Checkout</h5>
                <p class="text-[11px] text-slate-500 mt-1">Checklist kelengkapan, BBM & foto awal</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-sm mx-auto flex items-center justify-center mb-2">5</div>
                <h5 class="font-bold text-slate-900 text-sm">Checkin</h5>
                <p class="text-[11px] text-slate-500 mt-1">Odometer masuk & rating kondisi mobil</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 font-bold text-sm mx-auto flex items-center justify-center mb-2">6</div>
                <h5 class="font-bold text-slate-900 text-sm">Selesai</h5>
                <p class="text-[11px] text-slate-500 mt-1">Mobil kembali tersedia di pool garasi</p>
            </div>
        </div>
    </div>
</section>

<!-- Panel Akses Cepat Login Uji Coba -->
<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-6 rounded-2xl bg-gradient-to-r from-sky-900 to-slate-900 text-white shadow-xl">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-xl font-bold">Siap Menguji Coba Sistem?</h3>
                    <p class="text-sm text-slate-300 mt-1">
                        Gunakan akun uji coba bawaan (password default: <code class="px-2 py-0.5 rounded bg-slate-800 text-sky-400 font-mono text-xs">password</code>) untuk meninjau panel Filament.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ url('/login') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-sky-900 hover:bg-sky-50 font-bold text-sm shadow-md transition-all">
                        <span>Portal Pegawai (Peminjaman)</span>
                    </a>
                    <a href="{{ url('/admin/login') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm shadow-md transition-all">
                        <span>Panel Admin IT</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
