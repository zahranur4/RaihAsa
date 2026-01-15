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
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // If registration is disabled via env flag, show closed page
        if (!env('REGISTRATION_ENABLED', true)) {
            return view('register.closed');
        }

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

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil. Selamat datang!');
    }

    // Mendaftar penerima/panti -> membuat record di tabel panti_asuhan
    public function registerRecipient(Request $request)
    {
        // Validate recipient + account details (require email + password so an account can be created)
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'kata_sandi' => ['required','string','min:8','regex:/^(?=.*[0-9])(?=.*[a-zA-Z]).{8,}$/'],
            'nomor_telepon' => ['nullable','string','regex:/^(\\+62|62|0)8[0-9]{7,11}$/'],
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'kapasitas' => 'nullable|integer|min:1',
            'nomor_legalitas' => 'nullable|string',
            'tanggal_berdiri' => 'nullable|date',
            'nama_penanggung_jawab' => 'nullable|string',
            'posisi_penanggung_jawab' => 'nullable|string',
            'nik' => ['required','string','size:16','regex:/^[0-9]{16}$/'],
            // file uploads (optional PDFs, max 5MB)
            'doc_akte' => 'nullable|file|mimes:pdf|max:5120',
            'doc_sk' => 'nullable|file|mimes:pdf|max:5120',
            'doc_npwp' => 'nullable|file|mimes:pdf|max:5120',
            'doc_other' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'kata_sandi.regex' => 'Password harus minimal 8 karakter dan mengandung huruf serta angka.',
            'nomor_telepon.regex' => 'Nomor telepon harus format Indonesia (contoh: 081234567890 atau +6281234567890).',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
            'doc_akte.mimes' => 'Akta harus berupa file PDF.',
            'doc_sk.mimes' => 'SK Kemenkumham harus berupa file PDF.',
            'doc_npwp.mimes' => 'NPWP harus berupa file PDF.',
            'doc_other.mimes' => 'Dokumen pendukung harus berupa file PDF.',
            'doc_akte.max' => 'Akta maksimal 5MB.',
            'doc_sk.max' => 'SK maksimal 5MB.',
            'doc_npwp.max' => 'NPWP maksimal 5MB.',
            'doc_other.max' => 'Dokumen pendukung maksimal 5MB.',
        ]);

        // Create a user account for the panti (so they can login)
        $userId = DB::table('users')->insertGetId([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => \Illuminate\Support\Facades\Hash::make($request->kata_sandi),
            'alamat' => $request->alamat ?? null,
            'nomor_telepon' => $request->nomor_telepon ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle document uploads (if provided) and store in public disk under panti_docs/{userId}
        $docAkte = null;
        $docSk = null;
        $docNpwp = null;
        $docOther = null;

        if ($request->hasFile('doc_akte')) {
            $docAkte = $request->file('doc_akte')->store("panti_docs/{$userId}", 'public');
        }
        if ($request->hasFile('doc_sk')) {
            $docSk = $request->file('doc_sk')->store("panti_docs/{$userId}", 'public');
        }
        if ($request->hasFile('doc_npwp')) {
            $docNpwp = $request->file('doc_npwp')->store("panti_docs/{$userId}", 'public');
        }
        if ($request->hasFile('doc_other')) {
            $docOther = $request->file('doc_other')->store("panti_docs/{$userId}", 'public');
        }

        // Insert panti_asuhan and link to user
        $data = [
            'user_id' => $userId,
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
            'doc_akte' => $docAkte,
            'doc_sk' => $docSk,
            'doc_npwp' => $docNpwp,
            'doc_other' => $docOther,
            'status_verifikasi_legalitas' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('panti_asuhan')->insert($data);

        // Create PantiProfile record with pending status
        \App\Models\PantiProfile::create([
            'id_user' => $userId,
            'nama_panti' => $request->nama,
            'alamat_lengkap' => $request->alamat ?? 'Alamat belum diisi',
            'kota' => $request->kota ?? 'Kota belum diisi',
            'no_sk' => $request->nomor_legalitas ?? null,
            'status_verif' => 'pending', // New panti accounts are unverified by default
        ]);

        // Auto-login as the newly created user
        $user = \App\Models\User::find($userId);
        Auth::login($user);

        // Redirect recipient (panti) to their dashboard
        return redirect()->route('panti.dashboard')->with('success', 'Pendaftaran panti/lembaga berhasil. Selamat datang! Akun Anda menunggu verifikasi dari admin.');
    }
}
