<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PantiProfile;

class PantiProfileController extends Controller
{
    public function index()
    {
        // Use the main orphanage table (panti_asuhan) so admin sees real registrations
        $pantis = \Illuminate\Support\Facades\DB::table('panti_asuhan')
            ->orderBy('id','desc')
            ->paginate(20);

        return view('admin.manajemen-penerima.index', compact('pantis'));
    }

    public function create()
    {
        return view('admin.manajemen-penerima.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'nomor_telepon' => 'nullable|string',
            'kode_pos' => 'nullable|string|max:10',
            'kapasitas' => 'nullable|integer|min:0',
            'status_verifikasi_legalitas' => 'nullable|in:pending,terverifikasi,ditolak',
        ]);

        \Illuminate\Support\Facades\DB::table('panti_asuhan')->insert(array_merge($data, ['created_at' => now(), 'updated_at' => now()]));

        return redirect()->route('admin.recipients.index')->with('success','Panti ditambahkan.');
    }

    public function edit($id)
    {
        $panti = \Illuminate\Support\Facades\DB::table('panti_asuhan')->where('id', $id)->first();
        if (! $panti) abort(404);
        return view('admin.manajemen-penerima.edit', compact('panti'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'nomor_telepon' => 'nullable|string',
            'kode_pos' => 'nullable|string|max:10',
            'kapasitas' => 'nullable|integer|min:0',
            'status_verifikasi_legalitas' => 'nullable|in:pending,terverifikasi,ditolak',
        ]);

        \Illuminate\Support\Facades\DB::table('panti_asuhan')->where('id', $id)->update(array_merge($data, ['updated_at' => now()]));

        return redirect()->route('admin.recipients.index')->with('success','Panti diperbarui.');
    }

    public function destroy($id)
    {
        \Illuminate\Support\Facades\DB::table('panti_asuhan')->where('id', $id)->delete();
        return redirect()->route('admin.recipients.index')->with('success','Panti dihapus.');
    }
}
