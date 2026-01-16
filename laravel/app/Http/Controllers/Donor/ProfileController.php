<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('donor-profile.index', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
        ]);

        $user->update($data);

        return redirect()->route('donor-profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->kata_sandi)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        // Update password
        $user->update([
            'kata_sandi' => Hash::make($validated['password']),
        ]);

        return redirect()->route('donor-profile')->with('password-success', 'Password berhasil diubah!');
    }
}
