<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PantiProfile;

class PantiProfileController extends Controller
{
    public function index()
    {
        $pantis = PantiProfile::orderBy('id_panti','desc')->paginate(20);
        return view('admin.manajemen-penerima.index', compact('pantis'));
    }

    public function create()
    {
        return view('admin.manajemen-penerima.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama_panti' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'no_sk' => 'nullable|string',
            'status_verif' => 'nullable|in:pending,verified,rejected',
        ]);

        PantiProfile::create($data);
        return redirect()->route('admin.recipients.index')->with('success','Panti ditambahkan.');
    }

    public function edit($id)
    {
        $panti = PantiProfile::findOrFail($id);
        return view('admin.manajemen-penerima.edit', compact('panti'));
    }

    public function update(Request $request, $id)
    {
        $panti = PantiProfile::findOrFail($id);
        $data = $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama_panti' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'no_sk' => 'nullable|string',
            'status_verif' => 'nullable|in:pending,verified,rejected',
        ]);

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
