<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HandlesAlerts;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HandlesAlerts;

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.home');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function authenticate(LoginRequest $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $credentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $password]
            : ['username' => $login, 'password' => $password];

        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->hasAnyRole(['admin', 'teacher'])) {
                Auth::logout();
                $this->alertError('Hanya admin dan guru yang dapat mengakses halaman ini.', 'Akses Ditolak');

                return redirect()->route('auth.login');
            }

            session()->put('history', []);
            $this->alertSuccess("Selamat datang, {$user->name}!", 'Login Berhasil');

            return redirect()->intended(route('dashboard.home'));
        }

        $this->alertError('Email/username atau password salah, atau akun Anda tidak aktif.', 'Login Gagal');

        return back()->withInput($request->only('login'));
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $this->alertInfo('Anda telah keluar dari sistem.', 'Logout Berhasil');

        return redirect()->route('auth.login');
    }
}
