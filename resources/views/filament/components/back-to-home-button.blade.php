<div class="sipendi-login-footer" style="margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200, #e2e8f0); display: flex; flex-direction: column; gap: 0.875rem;">
    <!-- Tombol Utama: Kembali ke Landing Page Awal -->
    <a href="{{ url('/') }}"
       class="sipendi-btn-home"
       style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.6875rem 1rem; border-radius: 0.75rem; font-size: 0.8125rem; font-weight: 700; text-decoration: none; color: #1e293b; background-color: #f1f5f9; border: 1px solid #cbd5e1; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;"
       onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.borderColor='#94a3b8'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.08)';"
       onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0, 0, 0, 0.05)';"
    >
        <svg style="width: 1rem; height: 1rem; color: #64748b; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Landing Page Awal</span>
    </a>

    <!-- Card Pembantu: Beralih ke Portal Pegawai jika bukan admin -->
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0.75rem 1rem; border-radius: 0.75rem; background: rgba(2, 132, 199, 0.05); border: 1px dashed rgba(2, 132, 199, 0.25); text-align: center; gap: 0.375rem;">
        <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">
            Bukan Administrator IT?
        </span>
        <a href="{{ url('/portal') }}"
           style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8125rem; font-weight: 700; color: #0284c7; text-decoration: none; transition: color 0.15s ease;"
           onmouseover="this.style.color='#0369a1'; this.style.textDecoration='underline';"
           onmouseout="this.style.color='#0284c7'; this.style.textDecoration='none';"
        >
            <span>Masuk Portal Pegawai (User / Garasi / Pimpinan)</span>
            <svg style="width: 0.875rem; height: 0.875rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
</div>

<style>
    /* Dark mode adjustments for Filament */
    .dark .sipendi-login-footer {
        border-top-color: var(--gray-800, #1e293b) !important;
    }
    .dark .sipendi-btn-home {
        color: #f1f5f9 !important;
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    .dark .sipendi-btn-home:hover {
        background-color: #334155 !important;
        border-color: #475569 !important;
    }
    .dark .sipendi-btn-home svg {
        color: #94a3b8 !important;
    }
</style>
