<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class FilamentLogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        // Pastikan url.intended dibersihkan sepenuhnya saat logout
        // sehingga tidak ada riwayat URL /admin yang tertinggal untuk login pengguna berikutnya
        session()->forget('url.intended');

        return redirect()->to('/admin/login');
    }
}
