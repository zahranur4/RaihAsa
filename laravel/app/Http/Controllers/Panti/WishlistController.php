<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use App\Models\PantiProfile;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WishlistController extends Controller
{
    public function index()
    {
        $panti = $this->getOrSyncPantiProfile();
        if (!$panti) {
            return redirect()->route('panti.dashboard')->with('error', 'Profil panti tidak ditemukan.');
        }

        // Get wishlists for this panti
        $wishlists = Wishlist::where('id_panti', $panti->id_panti)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('panti.wishlist.index', compact('wishlists', 'panti'));
    }

    public function store(Request $request)
    {
        $panti = $this->getOrSyncPantiProfile();
        if (!$panti) {
            return redirect()->back()->with('error', 'Profil panti tidak ditemukan.');
        }

        // Check if panti is verified
        if ($panti->status_verif !== 'verified') {
            return redirect()->back()->with('error', 'Akun panti Anda belum terverifikasi. Silakan tunggu admin memverifikasi akun Anda terlebih dahulu.');
        }

        $data = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'urgensi' => 'required|in:mendesak,rutin,pendidikan,kesehatan',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data['id_panti'] = $panti->id_panti;
        $data['status'] = 'open';

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('wishlist/' . $panti->id_panti, 'public');
            $data['image'] = $path;
        }

        Wishlist::create($data);

        return redirect()->route('panti.wishlist')->with('success', 'Wishlist berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $panti = $this->getOrSyncPantiProfile();
        if (!$panti) {
            return redirect()->back()->with('error', 'Profil panti tidak ditemukan.');
        }

        // Check if panti is verified
        if ($panti->status_verif !== 'verified') {
            return redirect()->back()->with('error', 'Akun panti Anda belum terverifikasi. Silakan tunggu admin memverifikasi akun Anda terlebih dahulu.');
        }

        $wishlist = Wishlist::where('id_wishlist', $id)
            ->where('id_panti', $panti->id_panti)
            ->firstOrFail();

        $data = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'urgensi' => 'required|in:mendesak,rutin,pendidikan,kesehatan',
            'status' => 'required|in:open,fulfilled,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($wishlist->image) {
                Storage::disk('public')->delete($wishlist->image);
            }
            
            $image = $request->file('image');
            $path = $image->store('wishlist/' . $panti->id_panti, 'public');
            $data['image'] = $path;
        }

        $wishlist->update($data);

        return redirect()->route('panti.wishlist')->with('success', 'Wishlist berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $panti = $this->getOrSyncPantiProfile();
        if (!$panti) {
            return redirect()->back()->with('error', 'Profil panti tidak ditemukan.');
        }

        // Check if panti is verified
        if ($panti->status_verif !== 'verified') {
            return redirect()->back()->with('error', 'Akun panti Anda belum terverifikasi. Silakan tunggu admin memverifikasi akun Anda terlebih dahulu.');
        }

        $wishlist = Wishlist::where('id_wishlist', $id)
            ->where('id_panti', $panti->id_panti)
            ->firstOrFail();

        // Delete image if exists
        if ($wishlist->image) {
            Storage::disk('public')->delete($wishlist->image);
        }

        $wishlist->delete();

        return redirect()->route('panti.wishlist')->with('success', 'Wishlist berhasil dihapus!');
    }

    /**
     * Get the panti profile for the current user or sync from panti_asuhan if missing.
     */
    private function getOrSyncPantiProfile(): ?PantiProfile
    {
        $userId = Auth::id();

        // Try existing panti_profiles row
        $profile = PantiProfile::where('id_user', $userId)->first();
        if ($profile) {
            return $profile;
        }

        // Fallback: check legacy panti_asuhan table and create a profile stub
        $legacy = DB::table('panti_asuhan')->where('user_id', $userId)->first();
        if (!$legacy) {
            return null;
        }

        return PantiProfile::create([
            'id_user' => $userId,
            'nama_panti' => $legacy->nama ?? 'Panti',
            'alamat_lengkap' => $legacy->alamat ?? '',
            'kota' => $legacy->kota ?? ($legacy->alamat ? '' : ''),
            'latitude' => null,
            'longitude' => null,
            'no_sk' => $legacy->nomor_legalitas ?? null,
            'status_verif' => 'pending',
        ]);
    }
}

