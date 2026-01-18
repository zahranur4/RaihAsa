<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationMatchingController extends Controller
{
    /**
     * Show the matching form and get smart matches based on donor input.
     */
    public function findMatches(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $itemName = $request->input('item_name', '');
        $category = $request->input('category', '');
        $quantity = $request->input('quantity', 1);
        $matches = collect();
        $matchedWishlists = [];

        if ($itemName || $category) {
            // Find matching wishlists based on donor's item and quantity
            $query = Wishlist::where('status', 'open')->with('panti');

            // Search by item name similarity
            if ($itemName) {
                $query->whereRaw("LOWER(nama_barang) LIKE ?", ['%' . strtolower($itemName) . '%']);
            }

            // Filter by category if provided
            if ($category) {
                $query->where('kategori', $category);
            }

            $wishlists = $query->get();

            // Score and rank matches
            $matches = $wishlists->map(function ($wishlist) use ($itemName, $quantity) {
                $score = 0;

                // Exact or partial name match
                if ($itemName) {
                    $itemNameLower = strtolower($itemName);
                    $wishlistNameLower = strtolower($wishlist->nama_barang);
                    
                    if ($itemNameLower === $wishlistNameLower) {
                        $score += 10; // Exact match
                    } elseif (str_contains($wishlistNameLower, $itemNameLower)) {
                        $score += 6; // Partial match
                    }
                }

                // Quantity sufficiency bonus
                $quantityRatio = min($quantity / max(1, $wishlist->jumlah), 1);
                $score += (int)($quantityRatio * 5);

                // Urgency boost
                if ($wishlist->urgensi === 'mendesak') {
                    $score += 4;
                } elseif ($wishlist->urgensi === 'kesehatan') {
                    $score += 3;
                } elseif ($wishlist->urgensi === 'pendidikan') {
                    $score += 2;
                }

                // Recency bonus (newer wishlists get slight boost)
                if ($wishlist->created_at) {
                    $score += max(0, 2 - now()->diffInDays($wishlist->created_at));
                }

                $wishlist->match_score = $score;
                $wishlist->match_percentage = min(100, (int)($score / 20 * 100)); // Normalize to percentage
                $wishlist->quantity_fulfillment = min(100, (int)(($quantity / max(1, $wishlist->jumlah)) * 100));

                return $wishlist;
            })
            ->sortByDesc('match_score')
            ->values();

            $matchedWishlists = $matches->take(12);
        }

        $categories = $this->getWishlistCategories();

        return view('wishlist.matching', [
            'matches' => $matchedWishlists,
            'itemName' => $itemName,
            'category' => $category,
            'quantity' => $quantity,
            'categories' => $categories,
        ]);
    }

    /**
     * Get all available wishlist categories.
     */
    private function getWishlistCategories()
    {
        return DB::table('wishlists')
            ->where('status', 'open')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Fulfill a wishlist with donor's donation (create a pledge/reservation).
     */
    public function fulfillWishlist(Request $request, $wishlistId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlist = Wishlist::where('id_wishlist', $wishlistId)
            ->where('status', 'open')
            ->first();

        if (!$wishlist) {
            return response()->json(['error' => 'Wishlist not found'], 404);
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity_offered' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get or create donatur profile for current user
        $donaturProfile = DB::table('donatur_profiles')->where('id_user', Auth::id())->first();
        if (!$donaturProfile) {
            // Create a basic donatur profile if doesn't exist
            $donaturId = DB::table('donatur_profiles')->insertGetId([
                'id_user' => Auth::id(),
                'nama_lengkap' => Auth::user()->nama,
                'no_telp' => Auth::user()->nomor_telepon,
                'alamat_jemput' => Auth::user()->alamat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $donaturId = $donaturProfile->id_donatur;
        }

        // Record the pledge/fulfillment
        $pledgeId = DB::table('wishlist_pledges')->insertGetId([
            'id_wishlist' => $wishlistId,
            'id_donatur' => $donaturId,
            'item_offered' => $validated['item_name'],
            'quantity_offered' => $validated['quantity_offered'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending', // pending, confirmed, completed, cancelled
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('wishlist.pledge-detail', $pledgeId)
            ->with('success', 'Penawaran donasi berhasil dibuat! Tunggu konfirmasi dari panti.');
    }

    /**
     * Show pledge details.
     */
    public function showPledge($pledgeId)
    {
        $pledge = DB::table('wishlist_pledges')
            ->where('id_pledge', $pledgeId)
            ->first();

        if (!$pledge) {
            return redirect()->route('wishlist')->with('error', 'Pledge not found');
        }

        $wishlist = Wishlist::where('id_wishlist', $pledge->id_wishlist)->first();
        $panti = $wishlist ? $wishlist->panti : null;

        return view('wishlist.pledge-detail', compact('pledge', 'wishlist', 'panti'));
    }
}
