<?php

namespace App\Http\Controllers;

use App\Models\RelawanProfile;
use Illuminate\Support\Facades\Auth;

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
        $categoryQuotas = $this->buildQuotaSnapshot();

        return view('volunteer.index', [
            'categoryQuotas' => $categoryQuotas,
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
