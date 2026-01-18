<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonasiMasukController extends Controller
{
    public function index(Request $request)
    {
        // Get current panti profile
        $currentPanti = DB::table('panti_profiles')
            ->where('id_user', Auth::id())
            ->first();

        if (!$currentPanti) {
            return redirect()->route('home')->with('error', 'Profil panti tidak ditemukan');
        }

        // Build query for pledges related to this panti's wishlists
        $query = DB::table('wishlist_pledges as wp')
            ->join('wishlists as w', 'wp.id_wishlist', '=', 'w.id_wishlist')
            ->join('donatur_profiles as dp', 'wp.id_donatur', '=', 'dp.id_donatur')
            ->join('users as u', 'dp.id_user', '=', 'u.id')
            ->where('w.id_panti', $currentPanti->id_panti)
            ->select(
                'wp.id_pledge',
                'wp.item_offered',
                'wp.quantity_offered',
                'wp.status',
                'wp.created_at',
                'wp.confirmed_at',
                'wp.completed_at',
                'wp.notes',
                'u.nama as donor_name',
                'u.email as donor_email',
                'u.nomor_telepon as donor_phone',
                'w.nama_barang as wishlist_item',
                'w.id_wishlist'
            );

        // Apply status filter if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('wp.status', $request->status);
        }

        // Apply date filter if provided
        if ($request->has('date') && $request->date !== '') {
            $query->whereDate('wp.created_at', $request->date);
        }

        // Get paginated results
        $donations = $query->orderBy('wp.created_at', 'desc')->paginate(10);

        // Calculate statistics
        $stats = [
            'total_donations' => DB::table('wishlist_pledges as wp')
                ->join('wishlists as w', 'wp.id_wishlist', '=', 'w.id_wishlist')
                ->where('w.id_panti', $currentPanti->id_panti)
                ->count(),
            
            'received_donations' => DB::table('wishlist_pledges as wp')
                ->join('wishlists as w', 'wp.id_wishlist', '=', 'w.id_wishlist')
                ->where('w.id_panti', $currentPanti->id_panti)
                ->where('wp.status', 'completed')
                ->count(),
            
            'pending_donations' => DB::table('wishlist_pledges as wp')
                ->join('wishlists as w', 'wp.id_wishlist', '=', 'w.id_wishlist')
                ->where('w.id_panti', $currentPanti->id_panti)
                ->where('wp.status', 'pending')
                ->count(),
            
            'total_items' => DB::table('wishlist_pledges as wp')
                ->join('wishlists as w', 'wp.id_wishlist', '=', 'w.id_wishlist')
                ->where('w.id_panti', $currentPanti->id_panti)
                ->sum('wp.quantity_offered'),
        ];

        return view('panti.donasi-masuk.index', compact('currentPanti', 'donations', 'stats'));
    }

    public function confirmReceipt(Request $request, $pledgeId)
    {
        $pledge = DB::table('wishlist_pledges')->where('id_pledge', $pledgeId)->first();

        if (!$pledge) {
            return redirect()->back()->with('error', 'Donasi tidak ditemukan');
        }

        // Update pledge status to completed
        DB::table('wishlist_pledges')
            ->where('id_pledge', $pledgeId)
            ->update([
                'status' => 'completed',
                'completed_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Penerimaan donasi berhasil dikonfirmasi!');
    }

    public function viewDetail($pledgeId)
    {
        // Get current panti profile
        $currentPanti = DB::table('panti_profiles')
            ->where('id_user', Auth::id())
            ->first();

        $pledge = DB::table('wishlist_pledges as wp')
            ->join('wishlists as w', 'wp.id_wishlist', '=', 'w.id_wishlist')
            ->join('donatur_profiles as dp', 'wp.id_donatur', '=', 'dp.id_donatur')
            ->join('users as u', 'dp.id_user', '=', 'u.id')
            ->where('wp.id_pledge', $pledgeId)
            ->where('w.id_panti', $currentPanti->id_panti)
            ->select(
                'wp.*',
                'u.nama as donor_name',
                'u.email as donor_email',
                'u.nomor_telepon as donor_phone',
                'u.alamat as donor_address',
                'w.nama_barang as wishlist_item',
                'w.kategori as wishlist_category',
                'w.jumlah as wishlist_quantity'
            )
            ->first();

        if (!$pledge) {
            return redirect()->route('panti.donasi-masuk')->with('error', 'Donasi tidak ditemukan');
        }

        return view('panti.donasi-masuk.detail', compact('currentPanti', 'pledge'));
    }
}
