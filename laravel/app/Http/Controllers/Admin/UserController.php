<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar pengguna
    public function index()
    {
        $users = User::orderBy('id','desc')->paginate(20);
        return view('admin.manajemen-pengguna.index', compact('users'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.manajemen-pengguna.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nomor_telepon' => ['nullable','regex:/^(\\+62|62|0)8[0-9]{7,11}$/'],
            'alamat' => 'nullable|string',
            'kata_sandi' => ['nullable','string','min:8','regex:/^(?=.*[0-9])(?=.*[a-zA-Z]).{8,}$/'],
        ], [
            'kata_sandi.regex' => 'Password harus minimal 8 karakter dan mengandung huruf serta angka.',
            'nomor_telepon.regex' => 'Nomor telepon harus format Indonesia.'
        ]);

        $user->nama = $data['nama'];
        $user->email = $data['email'];
        $user->nomor_telepon = $data['nomor_telepon'] ?? $user->nomor_telepon;
        $user->alamat = $data['alamat'] ?? $user->alamat;
        if (!empty($data['kata_sandi'])) {
            $user->kata_sandi = Hash::make($data['kata_sandi']);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna diperbarui.');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna dihapus.');
    }
}
