<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonasiBarang;
use Illuminate\Support\Facades\DB;

class DonasiController extends Controller
{
    public function index()
    {
        $donations = DonasiBarang::orderBy('id_donasi','desc')->paginate(20);

        $stats = [
            'total_donations' => DonasiBarang::count(),
            'completed_donations' => DonasiBarang::where('status', 'delivered')->count(),
            'active_donations' => DonasiBarang::whereIn('status', ['pending', 'accepted'])->count(),
        ];

        return view('admin.manajemen-donasi.index', compact('donations', 'stats'));
    }

    public function create()
    {
        return view('admin.manajemen-donasi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_donatur' => 'required|exists:donatur_profiles,id_donatur',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'status' => 'nullable|in:pending,accepted,delivered,cancelled',
            'id_panti' => 'nullable|exists:panti_profiles,id_panti',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('donasi_photos','public');
            $data['foto'] = $path;
        }

        DonasiBarang::create($data);
        return redirect()->route('admin.donations.index')->with('success','Donasi berhasil dibuat.');
    }

    public function edit($id)
    {
        $donation = DonasiBarang::findOrFail($id);
        return view('admin.manajemen-donasi.edit', compact('donation'));
    }

    public function update(Request $request, $id)
    {
        $donation = DonasiBarang::findOrFail($id);
        $data = $request->validate([
            'id_donatur' => 'required|exists:donatur_profiles,id_donatur',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'status' => 'nullable|in:pending,accepted,delivered,cancelled',
            'id_panti' => 'nullable|exists:panti_profiles,id_panti',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('donasi_photos','public');
            $data['foto'] = $path;
        }

        $donation->update($data);
        return redirect()->route('admin.donations.index')->with('success','Donasi diperbarui.');
    }

    public function destroy($id)
    {
        $donation = DonasiBarang::findOrFail($id);
        $donation->delete();
        return redirect()->route('admin.donations.index')->with('success','Donasi dihapus.');
    }
}
