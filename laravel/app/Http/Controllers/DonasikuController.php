<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonasikuController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get donatur profile for the user
        $donatur = DB::table('donatur_profiles')
            ->where('id_user', $user->id)
            ->first();

        if (!$donatur) {
            // If user doesn't have donatur profile, redirect to donor profile
            return redirect()->route('donor-profile');
        }

        // Get total donations (food items)
        $totalDonasi = DB::table('food_rescue')
            ->where('id_donatur', $donatur->id_donatur)
            ->count();

        // Get total food weight (porsi)
        $totalBeratMakanan = DB::table('food_rescue')
            ->where('id_donatur', $donatur->id_donatur)
            ->sum('porsi');

        // Get unique orphanages (panti) that received donations
        $pantiTerbantui = DB::table('food_rescue')
            ->join('donatur_profiles', 'food_rescue.id_donatur', '=', 'donatur_profiles.id_donatur')
            ->where('food_rescue.id_donatur', $donatur->id_donatur)
            ->distinct()
            ->count(DB::raw('DISTINCT food_rescue.id_food'));

        // For now, set sertifikat to 0 (this would need a certificates table)
        $sertifikatDidapat = 0;

        // Get donation history (all food items donated by this user)
        $donationHistory = DB::table('food_rescue')
            ->where('id_donatur', $donatur->id_donatur)
            ->orderBy('waktu_dibuat', 'desc')
            ->get();

        return view('donasiku.index', [
            'user' => $user,
            'totalDonasi' => $totalDonasi,
            'totalBeratMakanan' => $totalBeratMakanan,
            'pantiTerbantui' => $pantiTerbantui,
            'sertifikatDidapat' => $sertifikatDidapat,
            'donationHistory' => $donationHistory,
        ]);
    }
}
