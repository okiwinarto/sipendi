@extends('layouts.app')

@section('title', 'Login Portal Pegawai — MAS PENDI RSUD Sidawangi')

@section('content')
<div class="relative overflow-hidden bg-gradient-to-b from-sky-50 via-white to-slate-50 py-16 sm:py-20 min-h-[85vh] flex items-center justify-center">
    <!-- Radial Dot Pattern Background (Identik dengan Landing Page) -->
    <div class="absolute inset-0 z-0 opacity-40 [background-image:radial-gradient(#0284c7_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top Pill Tag & Header -->
        <div class="text-center max-w-md mx-auto mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-100/90 border border-sky-200 text-sky-800 text-xs font-semibold mb-4 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-sky-600 animate-pulse"></span>
                <span>Fase 1: Khusus Kendaraan Dinas Non-Ambulans</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-sky-600 to-cyan-500 flex items-center justify-center text-white mx-auto shadow-lg shadow-sky-600/30 mb-3">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Portal Masuk Pegawai</h1>
            <p class="mt-1.5 text-sm text-slate-600">Manajemen Aset dan Peminjaman Kendaraan Dinas (MAS PENDI) RSUD Sidawangi</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Login Card -->
            <div class="lg:col-span-6 bg-white p-7 sm:p-9 rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50">
                <div class="mb-6 pb-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-900">Masuk ke Akun Anda</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Gunakan email dinas resmi yang telah didaftarkan oleh IT.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-800 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Dinas</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                placeholder="nama@sidawangi.id"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                            <span class="text-[11px] text-slate-400">Default: password</span>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </span>
                            <input type="password" name="password" id="password" required
                                placeholder="••••••••"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label for="remember" class="flex items-center cursor-pointer">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                            <span class="ml-2 block text-xs text-slate-600 font-medium">Ingat sesi login saya</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3 px-5 rounded-xl shadow-lg shadow-sky-600/25 text-sm font-bold text-white bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 transition-all hover:scale-[1.01]">
                            <span>Masuk ke Akun</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: 1-Click Role Accounts (Sesuai 4 Peran Landing Page) -->
            <div class="lg:col-span-6 space-y-4">
                <div class="bg-white/80 backdrop-blur p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-600">Akses Cepat Pengujian (1-Klik)</span>
                        <span class="text-[11px] text-slate-400">Klik kartu untuk mengisi formulir</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Role 1: Pemohon -->
                        <button type="button" onclick="fillLogin('pemohon@sidawangi.id')"
                            class="p-3.5 rounded-2xl bg-gradient-to-b from-sky-50/70 to-white border border-sky-100 hover:border-sky-300 hover:shadow-md text-left transition-all group">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-sky-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    👤
                                </div>
                                <div>
                                    <span class="block text-xs font-extrabold text-slate-900 group-hover:text-sky-700">Pemohon Dinas</span>
                                    <span class="block text-[10px] text-sky-700 font-semibold">User Aplikasi</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-600 font-medium">Budi Santoso (Farmasi)</p>
                            <span class="text-[10px] text-slate-400 font-mono mt-0.5 block">pemohon@sidawangi.id</span>
                        </button>

                        <!-- Role 2: Kepala Garasi -->
                        <button type="button" onclick="fillLogin('garasi@sidawangi.id')"
                            class="p-3.5 rounded-2xl bg-gradient-to-b from-amber-50/70 to-white border border-amber-100 hover:border-amber-300 hover:shadow-md text-left transition-all group">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-amber-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    🚛
                                </div>
                                <div>
                                    <span class="block text-xs font-extrabold text-slate-900 group-hover:text-amber-700">Kepala Garasi</span>
                                    <span class="block text-[10px] text-amber-700 font-semibold">Verifikator Teknis</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-600 font-medium">H. Suhendar (Pool Utama)</p>
                            <span class="text-[10px] text-slate-400 font-mono mt-0.5 block">garasi@sidawangi.id</span>
                        </button>

                        <!-- Role 3: Pimpinan -->
                        <button type="button" onclick="fillLogin('pimpinan@sidawangi.id')"
                            class="p-3.5 rounded-2xl bg-gradient-to-b from-indigo-50/70 to-white border border-indigo-100 hover:border-indigo-300 hover:shadow-md text-left transition-all group">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    👔
                                </div>
                                <div>
                                    <span class="block text-xs font-extrabold text-slate-900 group-hover:text-indigo-700">Pimpinan</span>
                                    <span class="block text-[10px] text-indigo-700 font-semibold">Penyetuju Akhir</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-600 font-medium">dr. Direktur RSUD</p>
                            <span class="text-[10px] text-slate-400 font-mono mt-0.5 block">pimpinan@sidawangi.id</span>
                        </button>

                        <!-- Role 4: Admin IT -->
                        <button type="button" onclick="fillLogin('admin@sidawangi.id')"
                            class="p-3.5 rounded-2xl bg-gradient-to-b from-slate-100/70 to-white border border-slate-200 hover:border-slate-400 hover:shadow-md text-left transition-all group">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-slate-800 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    ⚙️
                                </div>
                                <div>
                                    <span class="block text-xs font-extrabold text-slate-900 group-hover:text-slate-800">Admin IT</span>
                                    <span class="block text-[10px] text-slate-600 font-semibold">Superadmin Sistem</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-600 font-medium">Tim IT Sidawangi</p>
                            <span class="text-[10px] text-slate-400 font-mono mt-0.5 block">admin@sidawangi.id</span>
                        </button>
                    </div>
                </div>

                <!-- Guidance Box -->
                <div class="p-4 rounded-2xl bg-sky-50/70 border border-sky-200/60 text-xs text-sky-900 flex items-start gap-3">
                    <svg class="w-5 h-5 text-sky-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="leading-relaxed">
                        Masuk sebagai <strong>Pemohon</strong> untuk membuat permohonan dinas baru, mengecek kalender armada, dan memantau riwayat pengajuan Anda secara mandiri.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
</script>
@endsection
