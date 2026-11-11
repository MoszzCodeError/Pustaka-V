<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:siswa,admin,super_admin',
            'nis_nip'  => 'nullable|string|max:50|unique:users,nis_nip',
            'kelas'    => 'nullable|string|max:100',
        ], [
            'email.unique'   => 'Email tersebut sudah terdaftar.',
            'nis_nip.unique' => 'Nomor NIS / NIP tersebut sudah digunakan oleh akun lain.',
            'password.min'   => 'Password minimal harus 6 karakter.',
        ]);

        User::create([
            'name'     => trim($request->name),
            'email'    => trim($request->email),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'nis_nip'  => $request->filled('nis_nip') ? trim($request->nis_nip) : null,
            'kelas'    => $request->filled('kelas') ? trim($request->kelas) : null,
        ]);

        return redirect()->back()->with('success', 'Akun pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'    => 'required|in:siswa,admin,super_admin',
            'nis_nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'kelas'   => 'nullable|string|max:100',
        ], [
            'email.unique'   => 'Email tersebut sudah terdaftar.',
            'nis_nip.unique' => 'Nomor NIS / NIP tersebut sudah digunakan oleh akun lain.',
        ]);

        $data = [
            'name'    => trim($request->name),
            'email'   => trim($request->email),
            'role'    => $request->role,
            'nis_nip' => $request->filled('nis_nip') ? trim($request->nis_nip) : null,
            'kelas'   => $request->filled('kelas') ? trim($request->kelas) : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }
}