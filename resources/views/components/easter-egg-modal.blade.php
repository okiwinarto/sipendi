<!-- Easter Egg Secret Modal: "who am i?" -->
<div id="easter-egg-modal" class="fixed inset-0 z-[999999] hidden items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-all duration-300">
    <div id="easter-egg-card" class="relative w-full max-w-md bg-gradient-to-b from-slate-900/95 via-slate-900/95 to-slate-950/98 border border-cyan-500/40 rounded-2xl shadow-[0_0_50px_rgba(6,182,212,0.25)] p-6 text-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <!-- Ambient Glowing Radar Line -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Terminal Window Bar -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-800 text-xs font-mono text-slate-400">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-500/80 inline-block shadow-sm"></span>
                <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block shadow-sm"></span>
                <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block shadow-sm"></span>
                <span class="ml-2 text-cyan-400 font-semibold tracking-wider flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                    SECURITY CLEARANCE
                </span>
            </div>
            <button type="button" onclick="closeEasterEggModal()" class="text-slate-500 hover:text-slate-300 p-1 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Terminal Content -->
        <div class="mt-6 text-center">
            <!-- Glowing Emblem -->
            <div class="w-16 h-16 mx-auto rounded-2xl bg-cyan-950/70 border border-cyan-500/40 flex items-center justify-center text-3xl shadow-[0_0_25px_rgba(6,182,212,0.35)] relative group">
                <span class="relative z-10">⚓</span>
                <div class="absolute inset-0 rounded-2xl bg-cyan-400/10 animate-pulse"></div>
            </div>

            <!-- "who am i?" Heading -->
            <h3 class="mt-4 font-mono font-black text-3xl tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-300 to-teal-300 drop-shadow-[0_0_12px_rgba(6,182,212,0.6)]">
                who am i?
            </h3>
            <p class="text-xs text-slate-400 font-mono mt-1.5 leading-relaxed">
                Identitas terproteksi // Masukkan kode sandi agen untuk mengakses ruang rahasia IT RSUD Sidawangi.
            </p>

            <!-- Password Challenge Form -->
            <form id="easter-egg-form" onsubmit="submitEasterEgg(event)" class="mt-6 space-y-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyan-400/70 font-mono text-sm">
                        &gt;_
                    </div>
                    <input type="password"
                           id="easter-egg-input"
                           autocomplete="off"
                           spellcheck="false"
                           placeholder="ketik kata sandi rahasia..."
                           class="w-full pl-9 pr-10 py-3 bg-slate-950/80 border border-cyan-500/40 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/40 rounded-xl text-center font-mono text-cyan-300 text-sm tracking-wider placeholder:text-slate-600 focus:outline-none transition-all shadow-inner">
                    <button type="button"
                            onclick="toggleEasterEggPassword()"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-cyan-400 transition-colors"
                            title="Tampilkan / Sembunyikan">
                        <svg id="eye-icon-open" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-icon-closed" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>

                <!-- Error & Success Feedback -->
                <div id="easter-egg-feedback" class="hidden text-xs font-mono py-2 px-3 rounded-lg border transition-all text-center"></div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 pt-2">
                    <button type="button"
                            onclick="closeEasterEggModal()"
                            class="w-1/3 py-2.5 px-3 rounded-xl border border-slate-700 hover:border-slate-600 bg-slate-800/60 hover:bg-slate-800 text-slate-400 hover:text-slate-200 text-xs font-mono transition-all">
                        Batal [ESC]
                    </button>
                    <button type="submit"
                            id="easter-egg-submit"
                            class="w-2/3 py-2.5 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-400 hover:to-teal-400 text-slate-950 font-mono font-bold text-xs tracking-wider transition-all shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_25px_rgba(6,182,212,0.6)] flex items-center justify-center gap-1.5">
                        <span>[ ENTER ] Verifikasi</span>
                    </button>
                </div>
            </form>

            <div class="mt-5 text-[10px] text-slate-600 font-mono">
                Hint: Sang penguasa kedalaman samudra 🌊
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes easterEggShake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-6px); }
        40%, 80% { transform: translateX(6px); }
    }
    .easter-egg-shake {
        animation: easterEggShake 0.4s ease-in-out;
    }
</style>

<script>
    // Audio synthesizer with Web Audio API for immersive SFX
    const EasterEggAudio = {
        ctx: null,
        init() {
            if (!this.ctx && (window.AudioContext || window.webkitAudioContext)) {
                this.ctx = new (window.AudioContext || window.webkitAudioContext)();
            }
        },
        playOpen() {
            try {
                this.init();
                if (!this.ctx) return;
                const now = this.ctx.currentTime;
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(659.25, now); // E5
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.15); // A5
                gain.gain.setValueAtTime(0.08, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                osc.start(now);
                osc.stop(now + 0.25);
            } catch(e) {}
        },
        playError() {
            try {
                this.init();
                if (!this.ctx) return;
                const now = this.ctx.currentTime;
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(180, now);
                osc.frequency.linearRampToValueAtTime(110, now + 0.2);
                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                osc.start(now);
                osc.stop(now + 0.25);
            } catch(e) {}
        },
        playSuccess() {
            try {
                this.init();
                if (!this.ctx) return;
                const now = this.ctx.currentTime;
                // Double sonar ping
                [0, 0.18, 0.4].forEach((delay, idx) => {
                    const osc = this.ctx.createOscillator();
                    const gain = this.ctx.createGain();
                    osc.type = 'sine';
                    const freq = idx === 2 ? 1760 : (idx === 1 ? 1318.5 : 880);
                    osc.frequency.setValueAtTime(freq, now + delay);
                    gain.gain.setValueAtTime(0.15, now + delay);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + delay + 0.6);
                    osc.connect(gain);
                    gain.connect(this.ctx.destination);
                    osc.start(now + delay);
                    osc.stop(now + delay + 0.6);
                });
            } catch(e) {}
        }
    };

    function openEasterEggModal() {
        const modal = document.getElementById('easter-egg-modal');
        const card = document.getElementById('easter-egg-card');
        const input = document.getElementById('easter-egg-input');
        const feedback = document.getElementById('easter-egg-feedback');

        if (!modal || !card) return;

        feedback.className = 'hidden text-xs font-mono py-2 px-3 rounded-lg border transition-all text-center';
        feedback.innerHTML = '';
        input.value = '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
            input.focus();
        }, 20);

        EasterEggAudio.playOpen();
    }

    function closeEasterEggModal() {
        const modal = document.getElementById('easter-egg-modal');
        const card = document.getElementById('easter-egg-card');

        if (!modal || !card) return;

        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 250);
    }

    function toggleEasterEggPassword() {
        const input = document.getElementById('easter-egg-input');
        const iconOpen = document.getElementById('eye-icon-open');
        const iconClosed = document.getElementById('eye-icon-closed');

        if (input.type === 'password') {
            input.type = 'text';
            iconOpen.classList.remove('hidden');
            iconClosed.classList.add('hidden');
        } else {
            input.type = 'password';
            iconOpen.classList.add('hidden');
            iconClosed.classList.remove('hidden');
        }
    }

    function submitEasterEgg(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('easter-egg-input');
        const card = document.getElementById('easter-egg-card');
        const feedback = document.getElementById('easter-egg-feedback');
        const submitBtn = document.getElementById('easter-egg-submit');

        const answer = (input.value || '').trim().toLowerCase();

        if (answer === 'hantu laut') {
            // Correct password!
            EasterEggAudio.playSuccess();
            feedback.className = 'block text-xs font-mono py-2 px-3 rounded-lg border bg-emerald-950/80 border-emerald-500/60 text-emerald-300 text-center shadow-[0_0_15px_rgba(16,185,129,0.3)] animate-pulse';
            feedback.innerHTML = '⚓ [IDENTITY VERIFIED: HANTU LAUT]<br><span class="text-[10px] text-emerald-400">Clearance Level-5 Diberikan. Membuka markas rahasia...</span>';

            sessionStorage.setItem('hantu_laut_unlocked', '1');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Membuka Gerbang...</span>';

            setTimeout(() => {
                window.location.href = "{{ url('/secret') }}";
            }, 1200);
        } else {
            // Wrong password!
            EasterEggAudio.playError();
            card.classList.remove('easter-egg-shake');
            void card.offsetWidth; // Trigger reflow
            card.classList.add('easter-egg-shake');

            feedback.className = 'block text-xs font-mono py-2 px-3 rounded-lg border bg-rose-950/80 border-rose-500/60 text-rose-300 text-center shadow-[0_0_15px_rgba(244,63,94,0.3)]';
            feedback.innerHTML = '🚫 [ACCESS DENIED]<br><span class="text-[10px] text-rose-400">Identitas tidak dikenali. Kamu bukan Hantu Laut!</span>';

            input.select();
            input.focus();
        }
    }

    // Attach listeners on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        // Any click on elements with class "easter-egg-trigger"
        document.querySelectorAll('.easter-egg-trigger').forEach(el => {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                openEasterEggModal();
            });
        });

        // Close on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('easter-egg-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeEasterEggModal();
                }
            }
        });

        // Close when clicking modal backdrop
        const modal = document.getElementById('easter-egg-modal');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeEasterEggModal();
                }
            });
        }
    });
</script>
