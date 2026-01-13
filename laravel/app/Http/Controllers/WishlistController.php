<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\PantiProfile;
use Illuminate\Http\Request;

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
        if ($urgensi === 'high') {
            $query->where('urgensi', 'high');
        } elseif ($urgensi === 'medium') {
            $query->where('urgensi', 'medium');
        } elseif ($urgensi === 'low') {
            $query->where('urgensi', 'low');
        }

        // Filter by kategori if requested
        $kategori = $request->query('kategori');
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $wishlists = $query->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }
}
