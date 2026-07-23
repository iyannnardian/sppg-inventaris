<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function authorizeAdmin()
    {
        if (!Auth::check() || strtolower(Auth::user()->role) !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk mengakses halaman manajemen pengguna.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->merge([
            'name' => trim($request->name ?? ''),
            'email' => strtolower(trim($request->email ?? '')),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar untuk pengguna lain.',
            'role.required' => 'Peran wajib dipilih.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
        ]);

        User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'role' => ucwords($request->role),
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil didaftarkan!');
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);

        $request->merge([
            'name' => trim($request->name ?? ''),
            'email' => strtolower(trim($request->email ?? '')),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',id_user',
            'role' => 'required|string',
            'password' => 'nullable|string|min:8',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar untuk pengguna lain.',
            'role.required' => 'Peran wajib dipilih.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
        ]);

        $userData = [
            'nama' => $request->name,
            'email' => $request->email,
            'role' => ucwords($request->role),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        if (Auth::user()->id_user == $id) {
            return redirect()->route('users.index')->with('error', 'Aksi ditolak! Anda tidak diperbolehkan menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus dari sistem!');
    }
}
