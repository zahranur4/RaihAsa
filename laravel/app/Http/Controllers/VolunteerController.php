<?php

namespace App\Http\Controllers;

use App\Models\RelawanProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VolunteerController extends Controller
{
    private const MAX_QUOTA = 20;

    private const CATEGORIES = [
        'Edukasi & Literasi',
        'Kesehatan & Gizi',
        'Kreatif & Psikososial',
        'Kemanusiaan & Kebencanaan',
        'Dukungan Operasional & Lingkungan',
    ];

    public function index()
    {
        // If user is logged in and is a verified volunteer, redirect to dashboard
        if (Auth::check()) {
            $volunteer = RelawanProfile::where('id_user', Auth::id())
                ->where('status_verif', 'verified')
                ->first();
            
            if ($volunteer) {
                return redirect()->route('volunteer.dashboard');
            }
        }

        $categoryQuotas = $this->buildQuotaSnapshot();

        return view('volunteer.index', [
            'categoryQuotas' => $categoryQuotas,
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Check if user is a verified volunteer
        $volunteer = RelawanProfile::where('id_user', $user->id)
            ->where('status_verif', 'verified')
            ->first();

        if (!$volunteer) {
            // Show volunteer page instead of redirect to avoid loop
            $categoryQuotas = $this->buildQuotaSnapshot();
            return view('volunteer.index', [
                'categoryQuotas' => $categoryQuotas,
                'message' => 'Anda belum terdaftar sebagai relawan. Silakan daftar terlebih dahulu.',
            ]);
        }

        // Get volunteer statistics
        $stats = [
            'registered_activities' => DB::table('activity_volunteer')
                ->where('id_user', $user->id)
                ->whereIn('status', ['registered', 'approved'])
                ->count(),
            'children_helped' => DB::table('activity_volunteer as av')
                ->join('volunteer_activities as va', 'av.id_activity', '=', 'va.id_activity')
                ->where('av.id_user', $user->id)
                ->where('av.status', 'completed')
                ->select('va.needed_volunteers')
                ->count() * 5, // Approximate: multiply by avg children per activity
            'certificates' => DB::table('activity_volunteer')
                ->where('id_user', $user->id)
                ->where('status', 'completed')
                ->count(),
        ];

        // Get recommended activities (active activities with remaining slots)
        $recommendedActivities = DB::table('volunteer_activities')
            ->where('volunteer_activities.status', 'active')
            ->where('volunteer_activities.activity_date', '>', now())
            ->leftJoin('activity_volunteer', 'volunteer_activities.id_activity', '=', 'activity_volunteer.id_activity')
            ->select(
                'volunteer_activities.id_activity',
                'volunteer_activities.title',
                'volunteer_activities.description',
                'volunteer_activities.location',
                'volunteer_activities.activity_date',
                'volunteer_activities.category',
                'volunteer_activities.needed_volunteers',
                DB::raw('COUNT(activity_volunteer.id) as registered_count')
            )
            ->groupBy('volunteer_activities.id_activity')
            ->having(DB::raw('registered_count'), '<', DB::raw('needed_volunteers'))
            ->limit(6)
            ->get();

        // Get user's activity history (all registrations)
        $userActivities = DB::table('activity_volunteer')
            ->join('volunteer_activities', 'activity_volunteer.id_activity', '=', 'volunteer_activities.id_activity')
            ->where('activity_volunteer.id_user', $user->id)
            ->select(
                'volunteer_activities.id_activity',
                'volunteer_activities.title',
                'volunteer_activities.location',
                'volunteer_activities.activity_date',
                'activity_volunteer.status',
                'activity_volunteer.created_at'
            )
            ->orderBy('activity_volunteer.created_at', 'desc')
            ->get();

        return view('volunteer-dashboard.index', [
            'volunteer' => $volunteer,
            'stats' => $stats,
            'recommendedActivities' => $recommendedActivities,
            'userActivities' => $userActivities,
        ]);
    }

    private function buildQuotaSnapshot(): array
    {
        $quotas = [];
        foreach (self::CATEGORIES as $category) {
            $accepted = RelawanProfile::where('kategori', $category)
                ->where('status_verif', 'verified')
                ->count();

            $remaining = max(0, self::MAX_QUOTA - $accepted);
            $quotas[$category] = [
                'accepted' => $accepted,
                'remaining' => $remaining,
                'capacity' => self::MAX_QUOTA,
            ];
        }

        return $quotas;
    }
}
