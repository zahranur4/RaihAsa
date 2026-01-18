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

        // If user has a donatur profile, show their donation dashboard
        if ($donatur) {
            // Get total donations (food items)
            $totalDonasi = DB::table('food_rescue')
                ->where('id_donatur', $donatur->id_donatur)
                ->count();

            // Get total food weight (porsi)
            $totalBeratMakanan = DB::table('food_rescue')
                ->where('id_donatur', $donatur->id_donatur)
                ->sum('porsi');

            // Get count of donations (as panti terbantui for now)
            $pantiTerbantui = DB::table('food_rescue')
                ->where('id_donatur', $donatur->id_donatur)
                ->count();

            // For now, set sertifikat to 0 (this would need a certificates table)
            $sertifikatDidapat = 0;

            // Get donation history (all food items donated by this user)
            $donationHistory = DB::table('food_rescue')
                ->where('id_donatur', $donatur->id_donatur)
                ->orderBy('waktu_dibuat', 'desc')
                ->get();

            // Get wishlist pledge history (smart matching donations)
            $wishlistPledges = DB::table('wishlist_pledges as wp')
                ->join('wishlists as w', 'wp.id_wishlist', '=', 'w.id_wishlist')
                ->join('panti_profiles as p', 'w.id_panti', '=', 'p.id_panti')
                ->where('wp.id_donatur', $donatur->id_donatur)
                ->select(
                    'wp.id_pledge',
                    'wp.item_offered',
                    'wp.quantity_offered',
                    'wp.status',
                    'wp.created_at',
                    'wp.confirmed_at',
                    'wp.completed_at',
                    'w.nama_barang',
                    'w.kategori',
                    'p.nama_panti'
                )
                ->orderBy('wp.created_at', 'desc')
                ->get();

            return view('donasiku.index', [
                'user' => $user,
                'totalDonasi' => $totalDonasi,
                'totalBeratMakanan' => $totalBeratMakanan,
                'pantiTerbantui' => $pantiTerbantui,
                'sertifikatDidapat' => $sertifikatDidapat,
                'donationHistory' => $donationHistory,
                'wishlistPledges' => $wishlistPledges,
            ]);
        }

        // For non-donor users (volunteers, etc), show their contribution summary
        $totalPledges = DB::table('wishlist_pledges')
            ->where('id_donatur', function ($query) use ($user) {
                $query->select('id_donatur')
                    ->from('donatur_profiles')
                    ->where('id_user', $user->id);
            })
            ->count();

        $totalWishlistContributions = DB::table('wishlist_pledges')
            ->where('id_donatur', function ($query) use ($user) {
                $query->select('id_donatur')
                    ->from('donatur_profiles')
                    ->where('id_user', $user->id);
            })
            ->sum('quantity_offered');

        return view('donasiku.index', [
            'user' => $user,
            'totalDonasi' => 0,
            'totalBeratMakanan' => 0,
            'pantiTerbantui' => 0,
            'sertifikatDidapat' => 0,
            'donationHistory' => collect(),
            'totalPledges' => $totalPledges ?? 0,
            'totalWishlistContributions' => $totalWishlistContributions ?? 0,
        ]);
    }
}
