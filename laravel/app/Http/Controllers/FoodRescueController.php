<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        // Add computed urgency level based on time remaining and extra fields for the UI
        $isPanti = Auth::check() && $this->isPantiUser(Auth::id());

        $foods = $foods->map(function ($food) {
            $now = Carbon::now();
            $expireTime = Carbon::parse($food->waktu_expired);
            $hoursRemaining = $now->diffInHours($expireTime, false);
            $secondsRemaining = $now->diffInSeconds($expireTime, false);

            if ($hoursRemaining < 2) {
                $food->urgency = 'critical';
            } elseif ($hoursRemaining < 6) {
                $food->urgency = 'urgent';
            } else {
                $food->urgency = 'normal';
            }

            // Get donor name
            $donatur = DB::table('donatur_profiles')->where('id_donatur', $food->id_donatur)->first();
            $food->donor_name = $donatur->nama_lengkap ?? 'Donatur';

            // Get distance (simulated for now)
            $distances = [2.1, 3.2, 4.5, 5.7, 6.8];
            $food->distance = $distances[array_rand($distances)];

            // Time remaining helpers for the UI
            $food->hours_remaining = max(0, $hoursRemaining);
            $food->countdown_seconds = max(0, $secondsRemaining);

            // Determine category based on food name
            $foodName = strtolower($food->nama_makanan ?? '');
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

            // Claimable flag for the UI
            $food->claimable = $food->status === 'available';

            return $food;
        });

        return view('food-rescue.index', compact('foods', 'isPanti'));
    }

    public function claim($id)
    {
        // Only orphanage accounts can claim food rescue items
        $userId = auth()->id();
        if (!$userId || !$this->isPantiUser($userId)) {
            return redirect()->route('food-rescue')->with('error', 'Hanya akun panti yang dapat mengklaim donasi makanan.');
        }

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

    /**
     * Check if the given user is a registered orphanage/recipient.
     */
    private function isPantiUser(int $userId): bool
    {
        return DB::table('panti_asuhan')->where('user_id', $userId)->exists()
            || DB::table('panti_profiles')->where('id_user', $userId)->exists();
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

    /**
     * Food Rescue dashboard for orphanage (panti) users.
     */
    public function pantiIndex(Request $request)
    {
        $userId = auth()->id();
        if (!$userId || !$this->isPantiUser($userId)) {
            return redirect()->route('home')->with('error', 'Hanya akun panti yang dapat mengakses halaman ini.');
        }

        $currentPanti = DB::table('panti_profiles')->where('id_user', $userId)->first();

        // Stats
        $stats = [
            'available' => DB::table('food_rescue')->where('status', 'available')->count(),
            'claimed_by_panti' => DB::table('food_rescue')->where('status', 'claimed')->where('id_claimer', $userId)->count(),
            'expire_today' => DB::table('food_rescue')
                ->where('status', 'available')
                ->whereDate('waktu_expired', Carbon::today())
                ->count(),
            'total_claimed_portion' => DB::table('food_rescue')
                ->where('id_claimer', $userId)
                ->sum('porsi'),
        ];

        // List foods: available to claim OR already claimed by this panti
        $foods = DB::table('food_rescue')
            ->whereIn('status', ['available', 'claimed'])
            ->orderBy('waktu_expired', 'asc')
            ->paginate(10);

        // Enrich foods
        $foods->getCollection()->transform(function ($food) use ($userId) {
            // Donor name
            $donatur = DB::table('donatur_profiles')->where('id_donatur', $food->id_donatur)->first();
            $food->donor_name = $donatur->nama_lengkap ?? 'Donatur';

            // Status label and badge class
            $statusMap = [
                'available' => ['label' => 'Tersedia', 'class' => 'success'],
                'claimed' => ['label' => 'Diklaim', 'class' => 'info'],
                'expired' => ['label' => 'Kadaluarsa', 'class' => 'secondary'],
                'pending' => ['label' => 'Menunggu Verifikasi', 'class' => 'warning text-dark'],
            ];
            $mapped = $statusMap[$food->status] ?? ['label' => ucfirst($food->status), 'class' => 'secondary'];
            $food->status_label = $mapped['label'];
            $food->status_class = $mapped['class'];

            // Claimable flag
            $food->claimable = $food->status === 'available';
            $food->claimed_by_me = $food->status === 'claimed' && $food->id_claimer === $userId;

            return $food;
        });

        return view('panti.food-rescue.index', compact('foods', 'stats', 'currentPanti'));
    }

    public function adminIndex(Request $request)
    {
        // Admin view shows ALL food items regardless of status
        $query = DB::table('food_rescue')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END") // Pending first
            ->orderBy('waktu_dibuat', 'desc'); // Newest first

        // Apply filters if provided
        if ($request->has('status') && $request->input('status') !== '' && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Get all foods with simple pagination
        $foods = $query->paginate(10);

        // Add computed data for each food item
        $foods->getCollection()->transform(function ($food) {
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
            if ($food->id_donatur) {
                $donatur = DB::table('donatur_profiles')->where('id_donatur', $food->id_donatur)->first();
                $food->donor_name = $donatur ? $donatur->nama_lengkap : 'Donatur Tidak Ditemukan';
                $food->donor_phone = $donatur ? $donatur->no_telp : '-';
            } else {
                $food->donor_name = 'Donatur Anonim';
                $food->donor_phone = '-';
            }

            // Get claimer name if claimed
            if ($food->id_claimer) {
                $claimer = DB::table('users')->where('id', $food->id_claimer)->first();
                $food->claimer_name = $claimer->nama ?? 'Unknown User';
            }

            // Get hours remaining
            $food->hours_remaining = max(0, $hoursRemaining);

            return $food;
        });

        $stats = [
            'total_saved_portions' => DB::table('food_rescue')
                ->whereIn('status', ['claimed'])
                ->sum('porsi'),
            'completed_rescue' => DB::table('food_rescue')->where('status', 'claimed')->count(),
            'active_rescue' => DB::table('food_rescue')->where('status', 'available')->count(),
            'pending_verification' => DB::table('food_rescue')->where('status', 'pending')->count(),
        ];

        return view('admin.food-rescue-admin.index', compact('foods', 'stats'));
    }

    public function destroy($id)
    {
        DB::table('food_rescue')->where('id_food', $id)->delete();
        return redirect()->back()->with('success', 'Food rescue item deleted successfully!');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'nama_makanan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'kadaluarsa' => 'required|date_format:Y-m-d\TH:i',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user = Auth::user();
            
            // Get donor profile if exists
            $donatur = DB::table('donatur_profiles')->where('id_user', $user->id)->first();
            $donator_id = $donatur ? $donatur->id_donatur : null;

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('foto')) {
                $imagePath = $request->file('foto')->store('food-rescue', 'public');
            }

            // Store the donation with pending status for admin verification
            // Note: food_rescue table does not have id_user; we track donor via id_donatur
            DB::table('food_rescue')->insert([
                'id_donatur' => $donator_id,
                'nama_makanan' => $request->nama_makanan,
                'porsi' => $request->jumlah,
                'waktu_dibuat' => now(),
                'waktu_expired' => $request->kadaluarsa,
                'foto' => $imagePath,
                'status' => 'pending', // Waiting for admin verification
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // If request expects JSON (AJAX), return JSON. Otherwise redirect back with flash message.
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Donasi Anda berhasil dikirim! Menunggu verifikasi admin.'
                ]);
            }

            return redirect()->route('food-rescue')->with('success', 'Donasi Anda berhasil dikirim! Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('food-rescue')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            DB::table('food_rescue')
                ->where('id_food', $id)
                ->update([
                    'status' => 'available',
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Donasi berhasil disetujui dan sekarang tersedia di Food Rescue!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            // Get the food item first to delete photo if exists
            $food = DB::table('food_rescue')->where('id_food', $id)->first();
            
            if ($food && $food->foto) {
                // Delete the photo file if exists
                $photoPath = storage_path('app/public/' . $food->foto);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            // Delete the record
            DB::table('food_rescue')->where('id_food', $id)->delete();

            return redirect()->back()->with('success', 'Donasi ditolak dan telah dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:available,claimed,expired'
        ]);

        try {
            DB::table('food_rescue')
                ->where('id_food', $id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Status berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}