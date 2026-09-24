<div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-800 text-center space-y-3">
    <a href="{{ url('/') }}"
       class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300/80 dark:border-slate-700 transition-all group shadow-xs">
        <svg class="w-4 h-4 text-slate-500 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Landing Page Awal</span>
    </a>

    <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
        <span>Bukan Administrator?</span>
        <a href="{{ url('/portal') }}" class="font-bold text-sky-600 hover:text-sky-500 dark:text-sky-400 underline">
            Masuk Portal Pegawai
        </a>
    </div>
</div>
