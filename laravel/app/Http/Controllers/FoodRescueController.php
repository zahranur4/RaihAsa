<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FoodRescueController extends Controller
{
    public function index(Request $request)
    {
        // Get filter category from request
        $category = $request->input('category', 'all');

        // Build query for available foods only
        $query = DB::table('food_rescue')
            ->where('status', 'available')
            ->orderBy('waktu_expired', 'asc');

        // Get all foods for the page
        $foods = $query->get();

        // Add computed urgency level based on time remaining
        $foods = $foods->map(function ($food) {
            $now = Carbon::now();
            $expireTime = Carbon::parse($food->waktu_expired);
            $hoursRemaining = $now->diffInHours($expireTime, false);

            if ($hoursRemaining < 2) {
                $food->urgency = 'critical';
            } elseif ($hoursRemaining < 6) {
                $food->urgency = 'urgent';
            } else {
                $food->urgency = 'normal';
            }

            // Get donor name
            $donatur = DB::table('donatur_profiles')->where('id_donatur', $food->id_donatur)->first();
            $food->donor_name = $donatur->nama_lengkap ?? 'Unknown Donor';

            // Get distance (simulated for now)
            $distances = [2.1, 3.2, 4.5, 5.7, 6.8];
            $food->distance = $distances[array_rand($distances)];

            // Get hours remaining
            $food->hours_remaining = max(0, $hoursRemaining);

            // Determine category based on food name
            $foodName = strtolower($food->nama_makanan);
            if (strpos($foodName, 'nasi') !== false || strpos($foodName, 'mie') !== false || 
                strpos($foodName, 'bakso') !== false || strpos($foodName, 'lumpia') !== false ||
                strpos($foodName, 'soto') !== false || strpos($foodName, 'masak') !== false) {
                $food->category = 'makanan-basah';
            } elseif (strpos($foodName, 'roti') !== false || strpos($foodName, 'kopi') !== false ||
                     strpos($foodName, 'perkedel') !== false) {
                $food->category = 'makanan-kering';
            } elseif (strpos($foodName, 'buah') !== false) {
                $food->category = 'buah';
            } elseif (strpos($foodName, 'sayur') !== false || strpos($foodName, 'gado') !== false) {
                $food->category = 'sayur';
            } elseif (strpos($foodName, 'minuman') !== false || strpos($foodName, 'kopi') !== false) {
                $food->category = 'minuman';
            } else {
                $food->category = 'makanan-basah';
            }

            return $food;
        });

        return view('food-rescue.index', compact('foods'));
    }

    public function claim($id)
    {
        $food = DB::table('food_rescue')->where('id_food', $id)->first();

        if (!$food) {
            return redirect()->route('food-rescue')->with('error', 'Makanan tidak ditemukan');
        }

        if ($food->status !== 'available') {
            return redirect()->route('food-rescue')->with('error', 'Makanan sudah diklaim atau kadaluwarsa');
        }

        // Update food status to claimed
        DB::table('food_rescue')
            ->where('id_food', $id)
            ->update([
                'id_claimer' => auth()->id(),
                'status' => 'claimed',
                'updated_at' => now(),
            ]);

        return redirect()->route('food-rescue')->with('success', 'Makanan berhasil diklaim!');
    }

    public function detail($id)
    {
        $food = DB::table('food_rescue')->where('id_food', $id)->first();

        if (!$food) {
            return redirect()->route('food-rescue')->with('error', 'Makanan tidak ditemukan');
        }

        // Get donor info
        $donatur = DB::table('donatur_profiles')->where('id_donatur', $food->id_donatur)->first();
        
        return view('food-rescue.detail', compact('food', 'donatur'));
    }
}
