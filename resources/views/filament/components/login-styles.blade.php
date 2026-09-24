@if (request()->routeIs('filament.admin.auth.login'))
<style>
    /* Background aesthetic matching MAS PENDI Landing Page */
    body.fi-body.fi-panel-admin {
        background-color: #f8fafc;
        background-image: 
            radial-gradient(rgba(2, 132, 199, 0.12) 1px, transparent 1px),
            linear-gradient(180deg, #f0f9ff 0%, #ffffff 40%, #f8fafc 100%);
        background-size: 24px 24px, 100% 100%;
        background-repeat: repeat, no-repeat;
        min-height: 100vh;
    }

    .dark body.fi-body.fi-panel-admin {
        background-color: #0b1120;
        background-image: 
            radial-gradient(rgba(56, 189, 248, 0.1) 1px, transparent 1px),
            linear-gradient(180deg, #0f172a 0%, #020617 100%);
        background-size: 24px 24px, 100% 100%;
    }

    /* Elevate and polish the login container card */
    .fi-simple-main {
        background-color: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.95);
        box-shadow: 0 12px 30px -4px rgba(2, 132, 199, 0.1), 0 4px 12px -2px rgba(15, 23, 42, 0.05);
        border-radius: 1.5rem !important;
        padding: 2.5rem 2rem !important;
    }

    .dark .fi-simple-main {
        background-color: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(12px);
        border-color: rgba(51, 65, 85, 0.85);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
    }

    /* Header & Logo Spacing to strictly prevent any overlapping */
    .fi-simple-header {
        margin-bottom: 2rem !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
    }

    .fi-simple-header .fi-logo {
        height: auto !important;
        min-height: 44px !important;
        max-height: none !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        overflow: visible !important;
    }

    .fi-simple-header-heading {
        font-size: 1.25rem !important;
        font-weight: 800 !important;
        letter-spacing: -0.02em !important;
        color: #0f172a !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        line-height: 1.4 !important;
    }

    .dark .fi-simple-header-heading {
        color: #f8fafc !important;
    }

    /* Submit Button styling */
    .fi-btn.fi-color-primary {
        font-weight: 700 !important;
        letter-spacing: 0.01em !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25) !important;
        transition: all 0.2s ease !important;
    }

    .fi-btn.fi-color-primary:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35) !important;
    }
</style>
@endif
