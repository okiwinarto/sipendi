<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CLASSIFIED // HANTU LAUT CINEMA — Bioskop Rahasia Tim IT RSUD Sidawangi</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono-custom {
            font-family: 'JetBrains Mono', monospace;
        }
        /* Custom Ambilight & Cinema Glow */
        .cinema-glow {
            box-shadow: 0 0 70px -10px rgba(6, 182, 212, 0.4), 0 30px 60px -15px rgba(0, 0, 0, 0.95);
        }
        .text-glow-cyan {
            text-shadow: 0 0 12px rgba(6, 182, 212, 0.8), 0 0 24px rgba(6, 182, 212, 0.4);
        }
        .text-glow-amber {
            text-shadow: 0 0 12px rgba(245, 158, 11, 0.8);
        }
        /* Glassmorphism custom */
        .cinema-glass {
            background: rgba(13, 19, 33, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        /* Theater Dimmer Mode */
        .theater-dimmed #cinema-ambient,
        .theater-dimmed #cinema-details,
        .theater-dimmed #cinema-catalog,
        .theater-dimmed #cinema-header-meta,
        .theater-dimmed #cinema-footer {
            opacity: 0.08;
            pointer-events: none;
            transition: opacity 0.5s ease;
        }
        .theater-dimmed #cinema-player-container {
            transform: scale(1.02);
            transition: transform 0.5s ease;
        }
        /* Custom Scrollbar for Catalog */
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(6, 182, 212, 0.4);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(6, 182, 212, 0.7);
        }
    </style>
</head>
<body class="min-h-full bg-[#030611] text-slate-100 relative selection:bg-cyan-500 selection:text-slate-950 overflow-x-hidden transition-all duration-500">

    <!-- Ambient Cinema Lighting Glow Background -->
    <div id="cinema-ambient" class="fixed inset-0 pointer-events-none transition-opacity duration-500 overflow-hidden z-0">
        <div id="ambilight-aura" class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[450px] bg-gradient-to-b from-cyan-500/20 via-sky-600/10 to-transparent blur-3xl transition-colors duration-700"></div>
        <div class="absolute top-1/3 -left-32 w-80 h-80 bg-blue-700/10 blur-3xl rounded-full"></div>
        <div class="absolute bottom-10 -right-20 w-96 h-96 bg-purple-700/10 blur-3xl rounded-full"></div>
    </div>

    <!-- CAMOUFLAGE / BOSS KEY OVERLAY (Tekan F2 atau Tombol Panik) -->
    <div id="boss-camouflage" class="fixed inset-0 z-[999999] bg-slate-100 text-slate-800 p-8 hidden overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-sky-700 text-white font-bold flex items-center justify-center text-xs">RS</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">SIM-RSUD SIDAWANGI — MODUL PEMANTAUAN LOGISTIK &amp; ARMADA</h2>
                        <p class="text-xs text-slate-500">Audit Transaksi Rutin Kendaraan Dinas Operasional</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500">Mode Kerja Aktif</span>
                    <button type="button" onclick="toggleBossMode()" class="px-3 py-1.5 rounded bg-slate-800 text-white text-xs font-semibold hover:bg-slate-700">
                        Buka Kembali Bioskop (F2)
                    </button>
                </div>
            </div>
            <!-- Dummy Work Spreadsheet -->
            <div class="mt-6 bg-white border border-slate-200 rounded-lg shadow-xs overflow-hidden">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-100 text-slate-600 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="p-3">KODE REF</th>
                            <th class="p-3">TANGGAL</th>
                            <th class="p-3">ARMADA</th>
                            <th class="p-3">ODOMETER</th>
                            <th class="p-3">STATUS SERVIS</th>
                            <th class="p-3">VERIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                        <tr><td class="p-3">SPD/2026/09/014</td><td class="p-3">24-09-2026</td><td class="p-3">Innova Zenix (E 1001 YZ)</td><td class="p-3">42,500 KM</td><td class="p-3 text-emerald-600 font-semibold">Tervalidasi Sesuai</td><td class="p-3">Petugas Pool</td></tr>
                        <tr><td class="p-3">SPD/2026/09/015</td><td class="p-3">24-09-2026</td><td class="p-3">Avanza Veloz (E 1234 BZ)</td><td class="p-3">38,120 KM</td><td class="p-3 text-emerald-600 font-semibold">Tervalidasi Sesuai</td><td class="p-3">Petugas Pool</td></tr>
                        <tr><td class="p-3">SPD/2026/09/016</td><td class="p-3">24-09-2026</td><td class="p-3">Hiace Commuter (E 7788 AZ)</td><td class="p-3">55,400 KM</td><td class="p-3 text-amber-600 font-semibold">Jadwal Ganti Oli</td><td class="p-3">Kepala Garasi</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- LOCK SCREEN GATE (Tampil jika dibuka langsung tanpa passkey) -->
    <div id="gate-lockscreen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4 transition-all duration-500 hidden">
        <div class="max-w-md w-full p-8 rounded-3xl bg-slate-900/90 border border-cyan-500/40 text-center shadow-[0_0_60px_rgba(6,182,212,0.3)] relative overflow-hidden">
            <div class="absolute -top-20 -left-20 w-40 h-40 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="w-16 h-16 mx-auto rounded-2xl bg-cyan-950 border border-cyan-500/50 flex items-center justify-center text-3xl shadow-[0_0_30px_rgba(6,182,212,0.4)]">
                🎬
            </div>
            <h2 class="mt-5 text-2xl font-black text-cyan-400 text-glow-cyan font-mono-custom tracking-wider">who am i?</h2>
            <p class="text-xs text-slate-400 mt-2 font-mono-custom">Area Rahasia Tim IT RSUD Sidawangi. Identifikasi diri Anda.</p>

            <form onsubmit="unlockGate(event)" class="mt-6 space-y-4">
                <input type="password"
                       id="gate-input"
                       placeholder="ketik kata sandi agen..."
                       class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-cyan-500/50 text-cyan-300 text-center text-sm font-mono-custom tracking-widest focus:outline-none focus:ring-2 focus:ring-cyan-400 shadow-inner">
                <div id="gate-error" class="hidden text-xs text-rose-400 font-mono-custom"></div>
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-400 hover:to-teal-400 text-slate-950 font-bold text-xs font-mono-custom tracking-wider transition-all shadow-[0_0_25px_rgba(6,182,212,0.4)]">
                    VERIFIKASI IDENTITAS
                </button>
            </form>
            <div class="mt-5">
                <a href="{{ url('/') }}" class="text-xs text-slate-500 hover:text-slate-400 transition-colors">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <!-- MAIN CINEMA LOUNGE INTERFACE -->
    <div id="secret-content" class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 hidden">

        <!-- Top Navigation Bar (Header) -->
        <header id="cinema-header-meta" class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-800/80 text-xs">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-cyan-600 via-sky-500 to-teal-400 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-cyan-500/30">
                    🎬
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-black text-white text-lg tracking-tight">HANTU LAUT CINEMA</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono-custom font-extrabold bg-cyan-950/80 border border-cyan-500/50 text-cyan-300 shadow-xs">
                            VIP LEVEL 5
                        </span>
                    </div>
                    <p class="text-xs text-slate-400">Bioskop &amp; Ruang Rehat Rahasia Tim IT RSUD Sidawangi</p>
                </div>
            </div>

            <!-- Quick Action Toolbar -->
            <div class="flex items-center gap-2">
                <!-- Boss Key / Panic Button -->
                <button type="button"
                        onclick="toggleBossMode()"
                        class="px-3 py-2 rounded-xl bg-slate-800/90 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold transition-all flex items-center gap-1.5"
                        title="Tekan F2 jika ada orang lewat!">
                    <span>🚨</span>
                    <span class="hidden sm:inline">Boss Mode</span>
                    <span class="px-1.5 py-0.5 rounded bg-slate-900 text-[10px] text-slate-400 font-mono-custom">F2</span>
                </button>

                <!-- Theater Mode Toggle -->
                <button type="button"
                        onclick="toggleTheaterMode()"
                        id="btn-theater"
                        class="px-3.5 py-2 rounded-xl bg-cyan-950/40 hover:bg-cyan-900/60 border border-cyan-500/40 hover:border-cyan-400 text-cyan-300 text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                    <span id="theater-icon">💡</span>
                    <span id="theater-text">Matikan Lampu</span>
                </button>

                <!-- Reload Player -->
                <button type="button"
                        onclick="reloadPlayer()"
                        class="p-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-slate-300 border border-slate-800 hover:border-slate-700 transition-colors"
                        title="Segarkan Pemutar Video">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>

                <!-- Portal Pegawai Link -->
                <a href="{{ url('/portal') }}" class="px-3.5 py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold transition-colors flex items-center gap-1">
                    <span>Portal Pegawai →</span>
                </a>

                <!-- Lock Room -->
                <button type="button"
                        onclick="lockdownSecret()"
                        class="p-2 rounded-xl bg-rose-950/40 hover:bg-rose-900/60 text-rose-400 hover:text-rose-200 border border-rose-900/50 transition-colors"
                        title="Kunci Ruang Rahasia">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- CINEMA PLAYER STAGE -->
        <main class="mt-6">
            <!-- Screen Header Info Bar -->
            <div class="flex items-center justify-between pb-3 text-[11px] font-mono-custom text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="text-cyan-400 font-bold">SCREEN 01</span>
                    <span class="text-slate-600">•</span>
                    <span>DOLBY ATMOS &amp; VISION</span>
                    <span class="text-slate-600">•</span>
                    <span>1080P ULTRA HD</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-slate-500">Vidfast Embedded Cloud</span>
                    <button type="button" onclick="toggleTheaterMode()" class="text-cyan-400 hover:text-cyan-300 font-bold transition-colors">
                        [ ESC ] Keluar Mode Redup
                    </button>
                </div>
            </div>

            <!-- Video Player Box (Aspect Ratio 56.25% = 16:9) -->
            <div id="cinema-player-container" class="relative w-full rounded-2xl overflow-hidden bg-black border border-cyan-500/30 cinema-glow transition-all duration-500">
                <div class="relative w-full pt-[56.25%]">
                    <iframe
                        id="cinema-iframe"
                        src="https://vidfast.vc/movie/533535"
                        class="absolute top-0 left-0 w-full h-full"
                        frameborder="0"
                        allowfullscreen
                        allow="encrypted-media"
                    ></iframe>
                </div>
            </div>
        </main>

        <!-- NOW PLAYING METADATA & SEARCH CONTROLS -->
        <section id="cinema-details" class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6 transition-opacity duration-500">

            <!-- Active Movie Dossier (2 Cols) -->
            <div class="lg:col-span-2 p-6 rounded-3xl cinema-glass flex flex-col justify-between">
                <div>
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-800">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono-custom font-extrabold bg-cyan-950 text-cyan-400 border border-cyan-500/40">
                                    ▶ NOW STREAMING
                                </span>
                                <span id="movie-genre" class="text-xs text-slate-400 font-medium">Action • Comedy • Sci-Fi</span>
                            </div>
                            <h1 id="movie-title" class="text-2xl sm:text-3xl font-black text-white mt-1.5 tracking-tight">
                                Deadpool &amp; Wolverine (2024)
                            </h1>
                        </div>

                        <!-- Rating & Spec Badges -->
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <div class="flex items-center gap-1 justify-end text-amber-400 font-bold text-sm">
                                    <span>★</span>
                                    <span id="movie-rating">7.7</span>
                                    <span class="text-slate-500 text-xs font-normal">/10</span>
                                </div>
                                <span id="movie-year" class="text-[11px] text-slate-400 font-mono-custom">Rilis 2024</span>
                            </div>
                        </div>
                    </div>

                    <!-- Synopsis -->
                    <p id="movie-desc" class="text-xs sm:text-sm text-slate-300 mt-4 leading-relaxed line-clamp-3">
                        Wade Wilson yang lesu kembali terjun ke medan laga setelah Time Variance Authority (TVA) menariknya ke dalam misi penyelamatan multisemesta. Bersama sosok Wolverine yang enggan, keduanya beraksi dalam kegilaan komedi aksi tanpa batas.
                    </p>
                </div>

                <!-- Custom Movie Search / ID Changer -->
                <div class="mt-6 pt-4 border-t border-slate-800/80">
                    <form onsubmit="handleCustomInput(event)" class="flex flex-wrap items-center gap-2">
                        <div class="relative flex-1 min-w-[200px]">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 text-xs font-mono-custom">
                                TMDB ID:
                            </span>
                            <input type="text"
                                   id="custom-input-box"
                                   placeholder="contoh: 533535 atau URL vidfast..."
                                   class="w-full pl-20 pr-4 py-2.5 rounded-xl bg-slate-950/80 border border-slate-700 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-xs text-cyan-300 font-mono-custom placeholder:text-slate-600 focus:outline-none transition-all">
                        </div>

                        <!-- Type Selector: Movie / TV -->
                        <select id="stream-type-select" class="px-3 py-2.5 rounded-xl bg-slate-950/80 border border-slate-700 text-xs text-slate-300 font-mono-custom focus:outline-none focus:border-cyan-400">
                            <option value="movie">Film (Movie)</option>
                            <option value="tv">Serial (TV S1 E1)</option>
                        </select>

                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-400 hover:to-teal-400 text-slate-950 font-bold text-xs font-mono-custom tracking-wider transition-all shadow-[0_0_20px_rgba(6,182,212,0.35)] flex items-center gap-1.5">
                            <span>PUTAR FILM</span>
                        </button>
                    </form>
                    <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Cari ID Film di <a href="https://www.themoviedb.org" target="_blank" class="text-cyan-400 hover:underline">themoviedb.org</a></span>
                        <span class="font-mono-custom text-[10px]">Vidfast Cloud Engine</span>
                    </div>
                </div>
            </div>

            <!-- IT Snack Bar & Operational Dashboard (1 Col) -->
            <div class="p-6 rounded-3xl cinema-glass flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <h3 class="text-xs font-bold text-cyan-400 font-mono-custom tracking-wider flex items-center gap-1.5">
                            <span>☕</span> IT SNACK &amp; COFFEE BAR
                        </h3>
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>

                    <ul class="space-y-3 mt-4 text-xs text-slate-300">
                        <li class="flex items-center justify-between pb-2 border-b border-slate-800/60">
                            <span class="flex items-center gap-2 text-slate-400"><span>☕</span> Kopi Tubruk IT:</span>
                            <span class="text-emerald-400 font-mono-custom font-bold">READY (Panas)</span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-slate-800/60">
                            <span class="flex items-center gap-2 text-slate-400"><span>🍿</span> Popcorn &amp; Camilan:</span>
                            <span class="text-cyan-400 font-mono-custom font-bold">TERSEDIA</span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-slate-800/60">
                            <span class="flex items-center gap-2 text-slate-400"><span>🚗</span> Armada MAS PENDI:</span>
                            <span class="text-cyan-400 font-mono-custom font-bold">76/76 Tests Green</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-slate-400"><span>🛡️</span> Server RSUD:</span>
                            <span class="text-emerald-400 font-mono-custom font-bold">100% ONLINE</span>
                        </li>
                    </ul>
                </div>

                <!-- Interactive Coffee Refill Button -->
                <div class="mt-6 pt-4 border-t border-slate-800/80">
                    <button type="button"
                            onclick="refillCoffee()"
                            id="btn-refill-coffee"
                            class="w-full py-2.5 px-4 rounded-xl bg-slate-900/90 hover:bg-slate-800 border border-slate-700 hover:border-cyan-500/50 text-xs font-semibold text-slate-200 transition-all flex items-center justify-center gap-2 shadow-xs">
                        <span>☕ Seduh Tambah Kopi</span>
                        <span id="coffee-count" class="px-2 py-0.5 rounded-full bg-cyan-950 text-cyan-300 text-[10px] font-mono-custom font-bold">9,999 Cangkir</span>
                    </button>
                    <p class="text-[10px] text-slate-500 text-center mt-2">
                        Pereda kantuk saat giliran siaga server tengah malam.
                    </p>
                </div>
            </div>
        </section>

        <!-- CURATED MOVIE ROSTER / CATALOG -->
        <section id="cinema-catalog" class="mt-8 transition-opacity duration-500">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                        <span>🎬</span> DAFTAR FILM PILIHAN TIM IT
                    </h2>
                    <p class="text-xs text-slate-400">Klik poster untuk langsung memutar film di layar bioskop</p>
                </div>
                <span class="text-xs text-cyan-400 font-mono-custom hidden sm:inline">Pustaka Blockbuster Terverifikasi</span>
            </div>

            <!-- Movie Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-3 sm:gap-4">
                <!-- Movie 1: Deadpool & Wolverine -->
                <button type="button"
                        onclick="selectMovie('533535', 'Deadpool & Wolverine (2024)', '7.7', '2024', 'Action • Comedy • Sci-Fi', 'Wade Wilson yang lesu kembali terjun ke medan laga setelah TVA menariknya ke dalam misi penyelamatan multisemesta bersama Wolverine.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-cyan-500/60 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg"
                             alt="Deadpool & Wolverine"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=Deadpool+Wolverine';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 7.7
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">Deadpool &amp; Wolverine</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">2024 • Aksi</span>
                    </div>
                </button>

                <!-- Movie 2: Spider-Man Spider-Verse -->
                <button type="button"
                        onclick="selectMovie('569094', 'Spider-Man: Across the Spider-Verse (2023)', '8.4', '2023', 'Animation • Action • Sci-Fi', 'Miles Morales terlempar melintasi Multiverse, di mana ia bertemu tim Spider-People yang bertugas melindungi keberadaannya.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-slate-800 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/8Vt6mWEReuy4Of61Lnj5Xj704m8.jpg"
                             alt="Spider-Man: Across the Spider-Verse"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=Spider-Verse';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 8.4
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">Spider-Verse</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">2023 • Animasi</span>
                    </div>
                </button>

                <!-- Movie 3: The Batman -->
                <button type="button"
                        onclick="selectMovie('414906', 'The Batman (2022)', '7.7', '2022', 'Action • Crime • Drama', 'Dalam tahun keduanya memerangi kejahatan, Batman mengungkap korupsi di Gotham City yang terhubung dengan keluarganya sendiri saat memburu pembunuh berantai, Riddler.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-slate-800 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg"
                             alt="The Batman"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=The+Batman';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 7.7
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">The Batman</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">2022 • Aksi/Misteri</span>
                    </div>
                </button>

                <!-- Movie 4: Interstellar -->
                <button type="button"
                        onclick="selectMovie('157336', 'Interstellar (2014)', '8.7', '2014', 'Sci-Fi • Adventure • Drama', 'Sebuah tim penjelajah luar angkasa menembus lubang cacing (wormhole) dalam upaya mencari planet baru yang dapat menampung kelangsungan hidup umat manusia.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-slate-800 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg"
                             alt="Interstellar"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=Interstellar';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 8.7
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">Interstellar</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">2014 • Fiksi Ilmiah</span>
                    </div>
                </button>

                <!-- Movie 5: Oppenheimer -->
                <button type="button"
                        onclick="selectMovie('872585', 'Oppenheimer (2023)', '8.1', '2023', 'Biography • Drama • History', 'Kisah J. Robert Oppenheimer dan perannya dalam pengembangan bom atom di Manhattan Project selama Perang Dunia II.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-slate-800 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg"
                             alt="Oppenheimer"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=Oppenheimer';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 8.1
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">Oppenheimer</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">2023 • Sejarah</span>
                    </div>
                </button>

                <!-- Movie 6: Dune: Part Two -->
                <button type="button"
                        onclick="selectMovie('693134', 'Dune: Part Two (2024)', '8.2', '2024', 'Sci-Fi • Adventure', 'Paul Atreides bersatu dengan Chani dan suku Fremen saat mencari pembalasan terhadap para konspirator yang menghancurkan keluarganya.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-slate-800 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg"
                             alt="Dune: Part Two"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=Dune+2';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 8.2
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">Dune: Part Two</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">2024 • Fiksi Ilmiah</span>
                    </div>
                </button>

                <!-- Movie 7: The Matrix -->
                <button type="button"
                        onclick="selectMovie('603', 'The Matrix (1999)', '8.7', '1999', 'Sci-Fi • Action', 'Ketika seorang peretas komputer bernama Neo menyadari bahwa realitas dunia yang ditinggalinya adalah simulasi virtual yang dikendalikan oleh kecerdasan buatan.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-slate-800 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg"
                             alt="The Matrix"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=The+Matrix';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 8.7
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">The Matrix</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">1999 • Cyberpunk</span>
                    </div>
                </button>

                <!-- Movie 8: John Wick: Chapter 4 -->
                <button type="button"
                        onclick="selectMovie('603692', 'John Wick: Chapter 4 (2023)', '7.8', '2023', 'Action • Crime • Thriller', 'John Wick menemukan jalan untuk mengalahkan The High Table. Namun sebelum mendapatkan kebebasannya, ia harus menghadapi musuh baru dengan aliansi global.')"
                        class="movie-card group relative rounded-2xl overflow-hidden bg-slate-900/80 border border-slate-800 p-1.5 text-left transition-all hover:scale-105 hover:border-cyan-400 hover:shadow-xl hover:shadow-cyan-500/20 active:scale-95 focus:outline-none">
                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-950">
                        <img src="https://image.tmdb.org/t/p/w500/vZloFAK7NKnMGKEHvYcnEtmG0dJ.jpg"
                             alt="John Wick: Chapter 4"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600/0f172a/38bdf8?text=John+Wick+4';"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-black/80 backdrop-blur-xs text-[10px] font-bold text-amber-400 font-mono-custom">
                            ★ 7.8
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <span class="text-[11px] font-bold text-cyan-300 font-mono-custom">▶ PUTAR</span>
                        </div>
                    </div>
                    <div class="mt-2 px-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-cyan-300 transition-colors">John Wick 4</h4>
                        <span class="text-[10px] text-slate-400 font-mono-custom">2023 • Aksi</span>
                    </div>
                </button>
            </div>
        </section>

        <!-- Cinema Lounge Footer -->
        <footer id="cinema-footer" class="mt-12 pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 transition-opacity duration-500">
            <div class="flex items-center gap-2">
                <span>⚓ HANTU LAUT CINEMA</span>
                <span>•</span>
                <span>Khusus Tim IT RSUD Sidawangi Provinsi Jawa Barat</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="hover:text-cyan-400 transition-colors">Halaman Beranda</a>
                <span>•</span>
                <a href="{{ url('/portal') }}" class="hover:text-cyan-400 transition-colors">Portal Pegawai</a>
                <span>•</span>
                <button type="button" onclick="lockdownSecret()" class="text-rose-400 hover:text-rose-300 transition-colors">
                    🔒 Kunci Bioskop
                </button>
            </div>
        </footer>
    </div>

    <!-- CLIENT INTERACTIVE SCRIPTS -->
    <script>
        // Check unlock status on load
        document.addEventListener('DOMContentLoaded', () => {
            const isUnlocked = sessionStorage.getItem('hantu_laut_unlocked') === '1' || window.location.search.includes('unlocked=1');
            const lockscreen = document.getElementById('gate-lockscreen');
            const content = document.getElementById('secret-content');

            if (!isUnlocked) {
                lockscreen.classList.remove('hidden');
                document.getElementById('gate-input').focus();
            } else {
                content.classList.remove('hidden');
            }

            // Keyboard shortcut for Panic / Boss Mode (F2) & Theater (ESC)
            document.addEventListener('keydown', (e) => {
                if (e.key === 'F2') {
                    e.preventDefault();
                    toggleBossMode();
                }
                if (e.key === 'Escape') {
                    if (isTheaterDimmed) {
                        toggleTheaterMode();
                    }
                }
            });
        });

        // Gate unlock
        function unlockGate(e) {
            e.preventDefault();
            const input = document.getElementById('gate-input');
            const err = document.getElementById('gate-error');
            const val = (input.value || '').trim().toLowerCase();

            if (val === 'hantu laut') {
                sessionStorage.setItem('hantu_laut_unlocked', '1');
                document.getElementById('gate-lockscreen').classList.add('hidden');
                document.getElementById('secret-content').classList.remove('hidden');
            } else {
                err.classList.remove('hidden');
                err.innerText = 'Jawaban salah! Kamu bukan Hantu Laut.';
                input.select();
            }
        }

        function lockdownSecret() {
            sessionStorage.removeItem('hantu_laut_unlocked');
            window.location.href = "{{ url('/') }}";
        }

        // Select Movie from Cards
        function selectMovie(id, title, rating, year, genre, desc) {
            const iframe = document.getElementById('cinema-iframe');
            const titleEl = document.getElementById('movie-title');
            const ratingEl = document.getElementById('movie-rating');
            const yearEl = document.getElementById('movie-year');
            const genreEl = document.getElementById('movie-genre');
            const descEl = document.getElementById('movie-desc');

            iframe.src = `https://vidfast.vc/movie/${id}`;
            titleEl.innerText = title;
            ratingEl.innerText = rating;
            yearEl.innerText = `Rilis ${year}`;
            genreEl.innerText = genre;
            descEl.innerText = desc;

            // Highlight selected card
            document.querySelectorAll('.movie-card').forEach(c => {
                c.classList.remove('border-cyan-500/60');
                c.classList.add('border-slate-800');
            });
            if (event && event.currentTarget) {
                event.currentTarget.classList.remove('border-slate-800');
                event.currentTarget.classList.add('border-cyan-500/60');
            }

            // Scroll smoothly to player
            document.getElementById('cinema-player-container').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Custom Input Box (TMDB ID or Direct URL)
        function handleCustomInput(e) {
            e.preventDefault();
            const input = document.getElementById('custom-input-box');
            const type = document.getElementById('stream-type-select').value;
            const val = (input.value || '').trim();

            if (!val) return;

            const iframe = document.getElementById('cinema-iframe');
            let targetUrl = val;

            if (!val.startsWith('http://') && !val.startsWith('https://')) {
                if (type === 'tv') {
                    targetUrl = `https://vidfast.vc/tv/${val}/1/1`;
                } else {
                    targetUrl = `https://vidfast.vc/movie/${val}`;
                }
            }

            iframe.src = targetUrl;
            document.getElementById('movie-title').innerText = `Kustom Putar (ID: ${val})`;
            document.getElementById('movie-genre').innerText = type === 'tv' ? 'Serial TV' : 'Film Kustom';
            document.getElementById('movie-desc').innerText = `Memutar media dari server Vidfast dengan ID: ${val}`;
            input.value = '';

            document.getElementById('cinema-player-container').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Theater Dim Mode Toggle
        let isTheaterDimmed = false;
        function toggleTheaterMode() {
            isTheaterDimmed = !isTheaterDimmed;
            const body = document.body;
            const icon = document.getElementById('theater-icon');
            const text = document.getElementById('theater-text');

            if (isTheaterDimmed) {
                body.classList.add('theater-dimmed');
                icon.innerText = '✨';
                text.innerText = 'Nyalakan Lampu';
            } else {
                body.classList.remove('theater-dimmed');
                icon.innerText = '💡';
                text.innerText = 'Matikan Lampu';
            }
        }

        // Reload Player
        function reloadPlayer() {
            const iframe = document.getElementById('cinema-iframe');
            const currentSrc = iframe.src;
            iframe.src = 'about:blank';
            setTimeout(() => {
                iframe.src = currentSrc;
            }, 100);
        }

        // Boss Mode / Camouflage
        let isBossMode = false;
        function toggleBossMode() {
            isBossMode = !isBossMode;
            const overlay = document.getElementById('boss-camouflage');
            if (isBossMode) {
                overlay.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
            }
        }

        // Coffee Refill Fun Counter
        let coffeeCount = 9999;
        function refillCoffee() {
            coffeeCount++;
            document.getElementById('coffee-count').innerText = `${coffeeCount.toLocaleString()} Cangkir`;
            const btn = document.getElementById('btn-refill-coffee');
            btn.classList.add('scale-95');
            setTimeout(() => btn.classList.remove('scale-95'), 150);
        }
    </script>
</body>
</html>
