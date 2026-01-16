<?php

namespace App\Http\Controllers;

use App\Models\RelawanProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show volunteer registration form
     */
    public function create()
    {
        // Check if user already registered as volunteer
        $existingProfile = RelawanProfile::where('id_user', Auth::id())->first();
        
        if ($existingProfile) {
            return redirect()->route('volunteer')->with('info', 'Anda sudah terdaftar sebagai relawan.');
        }

        return view('volunteer.register');
    }

    /**
     * Store volunteer registration
     */
    public function store(Request $request)
    {
        // Check if user already registered
        $existingProfile = RelawanProfile::where('id_user', Auth::id())->first();
        
        if ($existingProfile) {
            return redirect()->route('volunteer')->with('info', 'Anda sudah terdaftar sebagai relawan.');
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|max:16',
            'skill' => 'nullable|string|max:1000',
        ]);

        RelawanProfile::create([
            'id_user' => Auth::id(),
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'skill' => $validated['skill'],
            'status_verif' => 'pending',
        ]);

        return redirect()->route('volunteer')->with('success', 'Pendaftaran relawan berhasil! Akun Anda menunggu verifikasi dari admin.');
    }

    /**
     * Check volunteer registration status
     */
    public function status()
    {
        $profile = RelawanProfile::where('id_user', Auth::id())->first();
        
        return response()->json([
            'registered' => $profile ? true : false,
            'status' => $profile ? $profile->status_verif : null,
        ]);
    }
}
