<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PantiProfile;

class PantiProfileController extends Controller
{
    public function index()
    {
        // Use panti_profiles table with user relationship
        $pantis = PantiProfile::with('user')
            ->orderBy('created_at','desc')
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
            'nama_panti' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'no_sk' => 'nullable|string|max:100',
            'status_verif' => 'nullable|in:pending,verified,rejected',
        ]);

        PantiProfile::create($data);

        return redirect()->route('admin.recipients.index')->with('success','Panti ditambahkan.');
    }

    public function edit($id)
    {
        $panti = PantiProfile::with('user')->findOrFail($id);
        return view('admin.manajemen-penerima.edit', compact('panti'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_panti' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'no_sk' => 'nullable|string|max:100',
            'status_verif' => 'required|in:pending,verified,rejected',
        ]);

        $panti = PantiProfile::findOrFail($id);
        $panti->update($data);

        return redirect()->route('admin.recipients.index')->with('success','Panti diperbarui.');
    }

    public function destroy($id)
    {
        $panti = PantiProfile::findOrFail($id);
        $panti->delete();
        
        return redirect()->route('admin.recipients.index')->with('success','Panti dihapus.');
    }
}
