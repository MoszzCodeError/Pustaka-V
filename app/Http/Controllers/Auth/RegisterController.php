<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nis' => 'required|string|max:20|unique:users,nis_nip', // Cek ke nis_nip
            'class' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted'
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'nis.unique' => 'NIS/NIP ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted' => 'Kamu harus menyetujui syarat & ketentuan perpustakaan.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nis_nip' => $request->nis,     // Disimpan ke kolom nis_nip
            'kelas' => $request->class,     // Disimpan ke kolom kelas
            'password' => Hash::make($request->password),
            'role' => 'siswa'               // Sesuaikan dengan enum ('siswa')
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }
}