<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses submit form login
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Cek login pakai Email atau NIS
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nis';

        $loginData = [
            $fieldType => $request->login,
            'password' => $request->password,
        ];

        // 3. Coba Autentikasi & Redirect Berdasarkan Role
        if (Auth::attempt($loginData, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 👑 Jika akun yang login ber-role 'admin'
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // 🎓 Jika akun siswa biasa
            return redirect()->intended(route('user.dashboard'));
        }

        // 4. Jika gagal, kembalikan pesan error
        return back()->withErrors([
            'login' => 'Email/NIS atau password yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}