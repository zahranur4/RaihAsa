<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\PantiProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        // Get all wishlists with status 'open' and associated panti data
        $query = Wishlist::where('status', 'open')
            ->with('panti')
            ->orderBy('created_at', 'desc');

        // Filter by urgensi if requested
        $urgensi = $request->query('urgensi');
        if (in_array($urgensi, ['mendesak', 'rutin', 'pendidikan', 'kesehatan'])) {
            $query->where('urgensi', $urgensi);
        }

        // Filter by kategori if requested
        $kategori = $request->query('kategori');
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $wishlists = $query->paginate(12);

        // Add pledge progress to each wishlist
        $wishlists->getCollection()->transform(function ($wishlist) {
            $totalPledged = DB::table('wishlist_pledges')
                ->where('id_wishlist', $wishlist->id_wishlist)
                ->where('status', '!=', 'cancelled')
                ->sum('quantity_offered');
            
            $wishlist->quantity_pledged = $totalPledged ?? 0;
            $wishlist->progress_percentage = min(100, ($totalPledged / max(1, $wishlist->jumlah)) * 100);
            
            return $wishlist;
        });

        // Build personalized recommendations for logged-in donors
        $recommendations = collect();
        if (Auth::check()) {
            $donorProfile = DB::table('donatur_profiles')->where('id_user', Auth::id())->first();

            $donorCategories = [];
            if ($donorProfile) {
                $donorCategories = DB::table('donasi_barang')
                    ->where('id_donatur', $donorProfile->id_donatur)
                    ->pluck('kategori')
                    ->filter()
                    ->map(fn($c) => strtolower(trim($c)))
                    ->unique()
                    ->values()
                    ->all();
            }

            $donorCity = $this->extractCity(Auth::user()->alamat ?? null);

            $recommendations = Wishlist::where('status', 'open')
                ->with('panti')
                ->get()
                ->map(function ($wishlist) use ($donorCategories, $donorCity) {
                    $score = 0;

                    // Prioritize urgency
                    if ($wishlist->urgensi === 'mendesak') {
                        $score += 5;
                    } elseif ($wishlist->urgensi === 'kesehatan') {
                        $score += 4;
                    } elseif ($wishlist->urgensi === 'pendidikan') {
                        $score += 3;
                    } elseif ($wishlist->urgensi === 'rutin') {
                        $score += 2;
                    }

                    // Boost if donor previously donated in same category
                    if (!empty($donorCategories) && in_array(strtolower($wishlist->kategori), $donorCategories, true)) {
                        $score += 3;
                    }

                    // Light boost for same city match
                    if ($donorCity && $wishlist->panti && $wishlist->panti->kota && stripos($wishlist->panti->kota, $donorCity) !== false) {
                        $score += 2;
                    }

                    // Slight recency bonus
                    if ($wishlist->created_at) {
                        $score += max(0, 2 - now()->diffInDays($wishlist->created_at));
                    }

                    $wishlist->match_score = $score;
                    return $wishlist;
                })
                ->sortByDesc('match_score')
                ->take(6)
                ->map(function ($wishlist) {
                    $totalPledged = DB::table('wishlist_pledges')
                        ->where('id_wishlist', $wishlist->id_wishlist)
                        ->where('status', '!=', 'cancelled')
                        ->sum('quantity_offered');
                    
                    $wishlist->quantity_pledged = $totalPledged ?? 0;
                    $wishlist->progress_percentage = min(100, ($totalPledged / max(1, $wishlist->jumlah)) * 100);
                    
                    return $wishlist;
                })
                ->values();
        }

        // Check if current user is a panti/orphanage
        $isPanti = false;
        if (Auth::check()) {
            $isPanti = DB::table('panti_profiles')->where('id_user', Auth::id())->exists();
        }

        return view('wishlist.index', [
            'wishlists' => $wishlists,
            'recommendations' => $recommendations,
            'isPanti' => $isPanti,
        ]);
    }

    private function extractCity(?string $alamat): ?string
    {
        if (!$alamat) {
            return null;
        }

        // Simple heuristic: take segment after the first comma or the last word
        if (str_contains($alamat, ',')) {
            $parts = array_filter(array_map('trim', explode(',', $alamat)));
            return strtolower($parts[1] ?? $parts[0] ?? null);
        }

        $words = array_filter(explode(' ', trim($alamat)));
        return strtolower(end($words) ?: null);
    }
}
