<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Tampilkan form pendaftaran (GET /register)
    public function showRegister()
    {
        return view('register.index');
    }

    // Mendaftar donor -> membuat record di tabel users
    public function registerDonor(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'kata_sandi' => ['required','string','min:8','regex:/^(?=.*[0-9])(?=.*[a-zA-Z]).{8,}$/'],
            'nomor_telepon' => ['nullable','string','regex:/^(\\+62|62|0)8[0-9]{7,11}$/'],
            'alamat' => 'nullable|string',
        ], [
            'kata_sandi.regex' => 'Password harus minimal 8 karakter dan mengandung huruf serta angka.',
            'nomor_telepon.regex' => 'Nomor telepon harus format Indonesia (contoh: 081234567890 atau +6281234567890).'
        ]);

        $id = DB::table('users')->insertGetId([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Otomatis login setelah registrasi
        $user = \App\Models\User::find($id);
        Auth::login($user);

        return redirect('/home')->with('success', 'Pendaftaran berhasil. Selamat datang!');
    }

    // Mendaftar penerima/panti -> membuat record di tabel panti_asuhan
    public function registerRecipient(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'kata_sandi' => ['nullable','string','min:8','regex:/^(?=.*[0-9])(?=.*[a-zA-Z]).{8,}$/'],
            'nomor_telepon' => ['nullable','string','regex:/^(\\+62|62|0)8[0-9]{7,11}$/'],
            'kode_pos' => 'nullable|string|max:10',
            'kapasitas' => 'nullable|integer|min:1',
            'nomor_legalitas' => 'nullable|string',
            'tanggal_berdiri' => 'nullable|date',
            'nama_penanggung_jawab' => 'nullable|string',
            'posisi_penanggung_jawab' => 'nullable|string',
            'nik' => ['nullable','string','size:16','regex:/^[0-9]{16}$/'],
        ], [
            'kata_sandi.regex' => 'Password harus minimal 8 karakter dan mengandung huruf serta angka.',
            'nomor_telepon.regex' => 'Nomor telepon harus format Indonesia (contoh: 081234567890 atau +6281234567890).',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.regex' => 'NIK harus berupa 16 digit angka.'
        ]);

        $data = [
            'nama' => $request->nama,
            'jenis' => $request->jenis ?? $request->type ?? null,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'kode_pos' => $request->kode_pos,
            'kapasitas' => $request->kapasitas,
            'nomor_legalitas' => $request->nomor_legalitas,
            'tanggal_berdiri' => $request->tanggal_berdiri,
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'posisi_penanggung_jawab' => $request->posisi_penanggung_jawab,
            'nik' => $request->nik,
            'status_verifikasi_legalitas' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('panti_asuhan')->insert($data);

        return redirect('/home')->with('success', 'Pendaftaran panti/lembaga berhasil dikirim dan menunggu verifikasi.');
    }
}
