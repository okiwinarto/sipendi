<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section (Identik dengan Desain Landing Page) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 opacity-5 pointer-events-none">
            <svg class="w-64 h-64 text-sky-900" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
            </svg>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('portal.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 hover:text-sky-700 transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Beranda Portal</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Formulir Pengajuan Peminjaman</h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-xl leading-relaxed">
                    Unit Kerja Pemohon: <span class="font-bold text-sky-700">{{ $userUnit?->nama_unit ?? 'Bagian Umum' }}</span>. Pastikan rincian perjalanan diisi dengan lengkap untuk mempercepat verifikasi oleh Kepala Garasi.
                </p>
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('portal.kalender') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 hover:text-sky-600 hover:border-sky-300 font-bold text-xs shadow-2xs transition-colors">
                    <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Cek Kalender Mobil</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Potensi Bentrok Jadwal (Wajib Memuat "Potensi Bentrok Jadwal Terdeteksi" untuk Pengujian) -->
    @if ($conflictWarning)
        <div class="p-5 rounded-3xl bg-amber-50 border-2 border-amber-300 text-amber-900 text-sm flex items-start gap-4 shadow-sm animate-in fade-in duration-300">
            <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/30">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-grow pt-0.5">
                <p class="font-extrabold text-amber-950 text-sm">Potensi Bentrok Jadwal Terdeteksi</p>
                <p class="text-xs text-amber-800 mt-1 leading-relaxed">{{ $conflictWarning }}</p>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <!-- Section 1: Informasi Perjalanan -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-9 h-9 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-extrabold text-sm shadow-xs">
                    1
                </div>
                <div>
                    <h2 class="font-extrabold text-slate-900 text-base">Tujuan & Maksud Perjalanan Dinas</h2>
                    <p class="text-[11px] text-slate-500">Rincian tugas kedinasan RSUD Sidawangi yang membutuhkan kendaraan operasional</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Maksud / Keperluan Perjalanan Dinas <span class="text-rose-500">*</span>
                    </label>
                    <textarea wire:model="tujuan_perjalanan" rows="3" required
                        placeholder="Mis. Pengambilan pasokan obat rutin, reagen uji laboratorium, atau penugasan dinas resmi ke Dinas Kesehatan Provinsi Jawa Barat"
                        class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs sm:text-sm placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors"></textarea>
                    @error('tujuan_perjalanan') <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kota / Daerah Tujuan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </span>
                        <input type="text" wire:model="kota_tujuan" required
                            placeholder="Mis. Bandung / Sumber / Cirebon Kota"
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors">
                    </div>
                    @error('kota_tujuan') <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tingkat Prioritas Pengajuan <span class="text-rose-500">*</span>
                    </label>
                    <select wire:model.live="tingkat_prioritas"
                        class="block w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors">
                        <option value="normal">🟢 Normal (Kegiatan Terencana / Operasional Rutin)</option>
                        <option value="mendesak">🔴 Cito / Mendesak (Tugas Darurat Sangat Penting)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Jadwal & Kapasitas Penumpang -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-9 h-9 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-extrabold text-sm shadow-xs">
                    2
                </div>
                <div>
                    <h2 class="font-extrabold text-slate-900 text-base">Waktu Keberangkatan & Rencana Kembali</h2>
                    <p class="text-[11px] text-slate-500">Ketepatan tanggal dan jam keberangkatan mencegah perselisihan alokasi unit armada</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tgl. Berangkat <span class="text-rose-500">*</span></label>
                    <input type="date" min="{{ date('Y-m-d') }}" wire:model.live="tanggal_berangkat" required
                        class="block w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    @error('tanggal_berangkat') <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Berangkat <span class="text-rose-500">*</span></label>
                    <input type="time" wire:model.live="jam_berangkat" required
                        class="block w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tgl. Rencana Kembali <span class="text-rose-500">*</span></label>
                    <input type="date" min="{{ $tanggal_berangkat ?: date('Y-m-d') }}" wire:model.live="tanggal_kembali_rencana" required
                        class="block w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    @error('tanggal_kembali_rencana') <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Rencana Kembali <span class="text-rose-500">*</span></label>
                    <input type="time" wire:model.live="jam_kembali_rencana" required
                        class="block w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Penumpang <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" min="1" max="25" wire:model="jumlah_penumpang" required
                            class="block w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-semibold text-slate-400 pointer-events-none">Orang</span>
                    </div>
                </div>

                <!-- Selectable Driver Cards -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Penugasan Pengemudi <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $jenis_pengemudi === 'sopir_dinas' ? 'border-sky-500 bg-sky-50/70 ring-1 ring-sky-500 shadow-xs' : 'border-slate-200 bg-slate-50/40 hover:border-slate-300' }}">
                            <input type="radio" wire:model.live="jenis_pengemudi" value="sopir_dinas" class="mt-1 accent-sky-600">
                            <div>
                                <span class="block text-xs font-extrabold text-slate-900">Sopir Dinas Pool RSUD</span>
                                <span class="block text-[11px] text-slate-500 leading-relaxed mt-0.5">Disediakan pengemudi pool garasi resmi RSUD Sidawangi</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $jenis_pengemudi === 'swakemudi' ? 'border-sky-500 bg-sky-50/70 ring-1 ring-sky-500 shadow-xs' : 'border-slate-200 bg-slate-50/40 hover:border-slate-300' }}">
                            <input type="radio" wire:model.live="jenis_pengemudi" value="swakemudi" class="mt-1 accent-sky-600">
                            <div>
                                <span class="block text-xs font-extrabold text-slate-900">Swakemudi (Bawa Sendiri)</span>
                                <span class="block text-[11px] text-slate-500 leading-relaxed mt-0.5">Pegawai pemohon memiliki SIM aktif dan mengemudi mandiri</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Preferensi Armada & Dokumen Penugasan -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-9 h-9 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-extrabold text-sm shadow-xs">
                    3
                </div>
                <div>
                    <h2 class="font-extrabold text-slate-900 text-base">Preferensi Armada & Dokumen Penugasan</h2>
                    <p class="text-[11px] text-slate-500">Pilih kendaraan yang diinginkan atau serahkan alokasi unit kepada Kepala Garasi</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Preferensi Unit Armada Kendaraan (Opsional)</label>
                    <select wire:model.live="preferred_vehicle_id"
                        class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors">
                        <option value="">-- Bebas / Serahkan Penentuan Unit Sepenuhnya kepada Kepala Garasi --</option>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}">
                                {{ $v->nama_lengkap }} • {{ $v->kategori?->nama_kategori }} ({{ $v->kapasitas_penumpang }} Kursi, {{ ucfirst($v->bahan_bakar) }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-500 mt-1">Kepala Garasi berhak menyesuaikan alokasi unit definitif berdasarkan kelaikan fisik saat verifikasi teknis.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Surat Perintah Tugas (SPT) (Opsional)</label>
                    <input type="text" wire:model="no_surat_tugas"
                        placeholder="Mis. 800/142/RSUD-SDW/IX/2026"
                        class="block w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Berkas Surat Tugas (PDF / Gambar)</label>
                    <input type="file" wire:model="file_surat_tugas"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer">
                    <div wire:loading wire:target="file_surat_tugas" class="text-xs text-sky-600 mt-1 font-semibold">Mengunggah berkas surat tugas...</div>
                    @error('file_surat_tugas') <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan untuk Petugas Garasi (Opsional)</label>
                    <textarea wire:model="catatan_pemohon" rows="2"
                        placeholder="Mis. Membawa boks sampel medis pendingin steril, mohon AC dipersiapkan dingin..."
                        class="block w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs sm:text-sm placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors"></textarea>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500 text-center sm:text-left leading-relaxed">
                Permohonan peminjaman akan otomatis tercatat dan diteruskan ke Kepala Garasi untuk penetapan armada.
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('portal.index') }}"
                    class="px-5 py-3 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-bold text-xs shadow-lg shadow-sky-600/25 transition-all hover:scale-[1.02] disabled:opacity-50 cursor-pointer">
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan Peminjaman</span>
                    <span wire:loading wire:target="submit">Memproses Permohonan...</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>
