<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CLASSIFIED // HANTU LAUT CINEMA — Bioskop Rahasia Tim IT RSUD Sidawangi</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            box-shadow: 0 0 60px -10px rgba(6, 182, 212, 0.35), 0 25px 50px -12px rgba(0, 0, 0, 0.9);
        }
        .text-glow-cyan {
            text-shadow: 0 0 12px rgba(6, 182, 212, 0.7);
        }
        /* Theater Dimmer Mode */
        .theater-dimmed #cinema-ambient,
        .theater-dimmed #cinema-details,
        .theater-dimmed #cinema-header-meta {
            opacity: 0.15;
            transition: opacity 0.4s ease;
        }
        .theater-dimmed #cinema-ambient:hover,
        .theater-dimmed #cinema-details:hover,
        .theater-dimmed #cinema-header-meta:hover {
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-full bg-[#050811] text-slate-200 relative selection:bg-cyan-500 selection:text-slate-950 overflow-x-hidden transition-colors duration-500">

    <!-- Ambient Cinema Background Gradient -->
    <div id="cinema-ambient" class="fixed inset-0 pointer-events-none transition-opacity duration-500 overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-b from-cyan-600/15 via-blue-600/10 to-transparent blur-3xl"></div>
        <div class="absolute bottom-0 right-10 w-96 h-96 bg-purple-600/10 blur-3xl rounded-full"></div>
    </div>

    <!-- LOCK SCREEN GATE (Tampil jika dibuka langsung tanpa passkey) -->
    <div id="gate-lockscreen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950 p-4 transition-all duration-500 hidden">
        <div class="max-w-md w-full p-8 rounded-3xl bg-slate-900/90 border border-cyan-500/40 text-center shadow-[0_0_50px_rgba(6,182,212,0.25)]">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-cyan-950 border border-cyan-500/50 flex items-center justify-center text-3xl shadow-[0_0_30px_rgba(6,182,212,0.4)]">
                🎬
            </div>
            <h2 class="mt-5 text-2xl font-black text-cyan-400 text-glow-cyan font-mono-custom tracking-wider">RESTRICTED CINEMA</h2>
            <p class="text-xs text-slate-400 mt-2 font-mono-custom">Area Rahasia Tim IT RSUD Sidawangi. Identifikasi diri Anda.</p>

            <form onsubmit="unlockGate(event)" class="mt-6 space-y-4">
                <input type="password"
                       id="gate-input"
                       placeholder="who am i? (ketik kode sandi...)"
                       class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-cyan-500/50 text-cyan-300 text-center text-sm font-mono-custom tracking-widest focus:outline-none focus:ring-2 focus:ring-cyan-400">
                <div id="gate-error" class="hidden text-xs text-rose-400 font-mono-custom"></div>
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs font-mono-custom tracking-wider transition-all shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                    VERIFIKASI AKSES
                </button>
            </form>
            <div class="mt-5">
                <a href="{{ url('/') }}" class="text-xs text-slate-500 hover:text-slate-400 transition-colors">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <!-- MAIN CINEMA LOUNGE -->
    <div id="secret-content" class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 hidden">

        <!-- Top Bar Navigasi -->
        <header class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-800/80 text-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-600 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-cyan-500/20">
                    🎬
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-white text-base tracking-tight">HANTU LAUT CINEMA</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono-custom font-bold bg-cyan-950 border border-cyan-500/40 text-cyan-300">VIP LOUNGE</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Bioskop & Ruang Rehat Rahasia Tim IT RSUD Sidawangi</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <!-- Dim Lights Button -->
                <button type="button"
                        onclick="toggleTheaterMode()"
                        id="btn-theater"
                        class="px-3 py-1.5 rounded-xl border border-slate-700 hover:border-slate-500 bg-slate-900/80 hover:bg-slate-800 text-slate-300 text-xs font-medium transition-all flex items-center gap-1.5">
                    <span id="theater-icon">💡</span>
                    <span id="theater-text">Matikan Lampu</span>
                </button>

                <a href="{{ url('/portal') }}" class="px-3 py-1.5 rounded-xl border border-cyan-500/30 hover:border-cyan-400 bg-cyan-950/40 hover:bg-cyan-900/50 text-cyan-300 text-xs font-semibold transition-all">
                    Portal Pegawai →
                </a>

                <button type="button"
                        onclick="lockdownSecret()"
                        class="p-1.5 rounded-xl border border-rose-900/40 hover:border-rose-700 bg-rose-950/30 hover:bg-rose-900/40 text-rose-400 hover:text-rose-200 transition-colors"
                        title="Kunci Ruang Rahasia">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Movie Player Section -->
        <main class="mt-6">
            <div class="relative w-full rounded-2xl overflow-hidden bg-black border border-slate-800/80 cinema-glow">
                <!-- User's Requested Aspect Ratio Container (56.25% = 16:9) -->
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

        <!-- Cinema Details & Controls -->
        <div id="cinema-details" class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6 transition-opacity duration-500">

            <!-- Movie Info & Preset Selector (2 Cols) -->
            <div class="lg:col-span-2 p-5 rounded-2xl bg-slate-900/70 border border-slate-800 backdrop-blur-md">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-800">
                    <div>
                        <div class="text-[11px] font-mono-custom text-cyan-400 font-semibold tracking-wider flex items-center gap-1.5">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            NOW STREAMING
                        </div>
                        <h1 id="movie-title" class="text-xl font-bold text-white mt-1">Deadpool &amp; Wolverine (2024)</h1>
                        <p class="text-xs text-slate-400 mt-0.5">Server Vidfast • HD 1080p • Audio Multi-Subtitle</p>
                    </div>

                    <!-- Custom TMDB / ID Changer -->
                    <div class="flex items-center gap-2">
                        <input type="text"
                               id="custom-movie-id"
                               placeholder="ID / TMDB Movie..."
                               class="w-36 px-3 py-1.5 rounded-lg bg-slate-950 border border-slate-700 text-xs text-cyan-300 font-mono-custom focus:outline-none focus:border-cyan-500">
                        <button type="button"
                                onclick="changeMovieCustom()"
                                class="px-3 py-1.5 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs transition-colors">
                            Putar
                        </button>
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="mt-4">
                    <span class="text-xs font-semibold text-slate-400 block mb-2.5">Pilihan Film Favorit Tim IT:</span>
                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                                onclick="changeMovie('533535', 'Deadpool & Wolverine (2024)')"
                                class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-cyan-950 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/50 text-xs font-medium text-slate-300 transition-all">
                            🦸 Deadpool &amp; Wolverine
                        </button>
                        <button type="button"
                                onclick="changeMovie('569094', 'Spider-Man: Across the Spider-Verse (2023)')"
                                class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-cyan-950 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/50 text-xs font-medium text-slate-300 transition-all">
                            🕷️ Spider-Man: Spider-Verse
                        </button>
                        <button type="button"
                                onclick="changeMovie('157336', 'Interstellar (2014)')"
                                class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-cyan-950 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/50 text-xs font-medium text-slate-300 transition-all">
                            🚀 Interstellar
                        </button>
                        <button type="button"
                                onclick="changeMovie('414906', 'The Batman (2022)')"
                                class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-cyan-950 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/50 text-xs font-medium text-slate-300 transition-all">
                            🦇 The Batman
                        </button>
                        <button type="button"
                                onclick="changeMovie('603', 'The Matrix (1999)')"
                                class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-cyan-950 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/50 text-xs font-medium text-slate-300 transition-all">
                            🕶️ The Matrix
                        </button>
                    </div>
                </div>
            </div>

            <!-- IT Snack & Lounge Card (1 Col) -->
            <div class="p-5 rounded-2xl bg-slate-900/70 border border-slate-800 backdrop-blur-md flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-cyan-400 font-mono-custom tracking-wider flex items-center gap-1.5 mb-3">
                        <span>☕</span> IT SNACK &amp; COFFEE BAR
                    </h3>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li class="flex items-center justify-between pb-2 border-b border-slate-800">
                            <span class="flex items-center gap-2"><span>☕</span> Kopi Hitam Tubruk:</span>
                            <span class="text-emerald-400 font-mono-custom font-bold">READY (Panas)</span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-slate-800">
                            <span class="flex items-center gap-2"><span>🍿</span> Popcorn &amp; Camilan:</span>
                            <span class="text-cyan-400 font-mono-custom font-bold">TERSEDIA</span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-slate-800">
                            <span class="flex items-center gap-2"><span>🚗</span> Armada MAS PENDI:</span>
                            <span class="text-cyan-400 font-mono-custom font-bold">76/76 Tests Green</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span>🛡️</span> Server RSUD:</span>
                            <span class="text-emerald-400 font-mono-custom font-bold">ONLINE 100%</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-5 p-3 rounded-xl bg-cyan-950/40 border border-cyan-500/30 text-[11px] text-cyan-200">
                    Selamat beristirahat sejenak! Saat darurat server berdering, segera kembali ke pos operasional.
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <footer class="mt-8 pt-4 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div>
                © {{ date('Y') }} Tim IT RSUD Sidawangi. Hantu Laut Secret Cinema.
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="hover:text-slate-400 transition-colors">Beranda</a>
                <span>•</span>
                <a href="{{ url('/portal') }}" class="hover:text-slate-400 transition-colors">Portal</a>
                <span>•</span>
                <button type="button" onclick="lockdownSecret()" class="text-rose-400 hover:text-rose-300">
                    Kunci Bioskop
                </button>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Gate check on page load
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
        });

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

        // Change Movie
        function changeMovie(id, title) {
            const iframe = document.getElementById('cinema-iframe');
            const titleEl = document.getElementById('movie-title');

            iframe.src = `https://vidfast.vc/movie/${id}`;
            if (title) {
                titleEl.innerText = title;
            }
        }

        function changeMovieCustom() {
            const input = document.getElementById('custom-movie-id');
            const val = (input.value || '').trim();
            if (!val) return;

            let url = val;
            if (!val.startsWith('http://') && !val.startsWith('https://')) {
                url = `https://vidfast.vc/movie/${val}`;
            }

            const iframe = document.getElementById('cinema-iframe');
            iframe.src = url;
            document.getElementById('movie-title').innerText = `Kustom Film (ID: ${val})`;
            input.value = '';
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
    </script>
</body>
</html>
