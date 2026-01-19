<?php

namespace App\Http\Controllers;

use App\Models\RelawanProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerRegistrationController extends Controller
{
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

        $categoryQuotas = $this->buildQuotaSnapshot();

        return view('volunteer.register', compact('categoryQuotas'));
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
            'kategori' => 'required|string',
        ]);

        if (!$this->isValidCategory($validated['kategori'])) {
            return redirect()->back()->withErrors(['kategori' => 'Kategori tidak valid.'])->withInput();
        }

        if (!$this->hasQuota($validated['kategori'])) {
            return redirect()->back()->withErrors(['kategori' => 'Kuota kategori ini sudah penuh.'])->withInput();
        }

        RelawanProfile::create([
            'id_user' => Auth::id(),
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'skill' => $validated['skill'],
            'kategori' => $validated['kategori'],
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

    private function categories(): array
    {
        return [
            'Edukasi & Literasi',
            'Kesehatan & Gizi',
            'Kreatif & Psikososial',
            'Kemanusiaan & Kebencanaan',
            'Dukungan Operasional & Lingkungan',
        ];
    }

    private function buildQuotaSnapshot(): array
    {
        $quotas = [];
        foreach ($this->categories() as $category) {
            $accepted = RelawanProfile::where('kategori', $category)
                ->where('status_verif', 'verified')
                ->count();

            $remaining = max(0, 20 - $accepted);
            $quotas[$category] = [
                'accepted' => $accepted,
                'remaining' => $remaining,
                'capacity' => 20,
            ];
        }
        return $quotas;
    }

    private function hasQuota(string $category): bool
    {
        $quota = $this->buildQuotaSnapshot();
        return isset($quota[$category]) && $quota[$category]['remaining'] > 0;
    }

    private function isValidCategory(string $category): bool
    {
        return in_array($category, $this->categories(), true);
    }
}
