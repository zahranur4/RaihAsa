<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerActivityController extends Controller
{
    public function index(Request $request)
    {
        // Get all volunteer activity registrations with related data
        $activities = DB::table('activity_volunteer as av')
            ->join('volunteer_activities as va', 'av.id_activity', '=', 'va.id_activity')
            ->join('users as u', 'av.id_user', '=', 'u.id')
            ->select(
                'av.id',
                'av.id_activity',
                'av.id_user',
                'av.status',
                'av.motivation',
                'av.emergency_contact_name',
                'av.emergency_contact_phone',
                'av.transportation',
                'av.created_at',
                'va.title as activity_name',
                'va.location',
                'va.activity_date',
                'u.nama as volunteer_name',
                'u.email as volunteer_email',
                'u.nomor_telepon as volunteer_phone'
            )
            ->orderBy('av.created_at', 'desc');

        // Apply status filter if provided
        if ($request->has('status') && $request->status !== '') {
            $activities = $activities->where('av.status', $request->status);
        }

        $activities = $activities->paginate(10);

        return view('admin.manajemen-kegiatan-relawan.index', compact('activities'));
    }

    public function storeActivity(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'volunteer_id' => 'required|exists:users,id',
            'activity_id' => 'required|exists:volunteer_activities,id_activity',
            'motivation' => 'required|string|min:10',
            'emergency_name' => 'required|string',
            'emergency_phone' => 'required|string',
            'transportation' => 'required|in:motor,mobil,ojek,umum',
            'commitment' => 'required'
        ]);

        try {
            // Check if already registered for this activity
            $existing = DB::table('activity_volunteer')
                ->where('id_user', $request->volunteer_id)
                ->where('id_activity', $request->activity_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar untuk kegiatan ini'
                ], 422);
            }

            // Store the activity registration
            DB::table('activity_volunteer')->insert([
                'id_activity' => $request->activity_id,
                'id_user' => $request->volunteer_id,
                'status' => 'registered',
                'motivation' => $request->motivation,
                'emergency_contact_name' => $request->emergency_name,
                'emergency_contact_phone' => $request->emergency_phone,
                'transportation' => $request->transportation,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran kegiatan berhasil disimpan. Menunggu persetujuan admin.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approve($id)
    {
        DB::table('activity_volunteer')
            ->where('id', $id)
            ->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Pendaftaran kegiatan relawan berhasil disetujui!');
    }

    public function reject($id)
    {
        DB::table('activity_volunteer')
            ->where('id', $id)
            ->update([
                'status' => 'rejected',
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Pendaftaran kegiatan relawan berhasil ditolak!');
    }

    public function complete($id)
    {
        DB::table('activity_volunteer')
            ->where('id', $id)
            ->update([
                'status' => 'completed',
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Kegiatan relawan berhasil diselesaikan!');
    }
}
