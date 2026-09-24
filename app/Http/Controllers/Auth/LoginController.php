<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login portal pegawai.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Proses otentikasi login pengguna.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->authenticatedRedirect(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Keluar dari sesi login.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->forget('url.intended');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Arahkan pengguna sesuai peran setelah berhasil login.
     */
    protected function authenticatedRedirect($user)
    {
        $intended = session()->get('url.intended');

        // Validasi url.intended: Jika tersimpan URL yang mengarah ke /admin,
        // namun pengguna yang login TIDAK memiliki izin akses ke panel admin,
        // hapus intended URL tersebut agar pengguna tidak diarahkan ke /admin (yang memicu 403).
        if ($intended && str_contains($intended, '/admin')) {
            $panel = \Filament\Facades\Filament::getCurrentOrDefaultPanel();
            if (! $user->canAccessPanel($panel)) {
                session()->forget('url.intended');
            }
        }

        if ($user->hasRole('admin_it')) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/portal');
    }
}
