<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RelawanProfile;

class RelawanProfileController extends Controller
{
    public function index()
    {
        $relawans = RelawanProfile::with('user')->orderBy('id_relawan','desc')->paginate(20);
        return view('admin.manajemen-relawan.index', compact('relawans'));
    }

    public function create()
    {
        return view('admin.manajemen-relawan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|max:32',
            'skill' => 'nullable|string',
            'status_verif' => 'nullable|in:pending,verified,rejected',
        ]);

        RelawanProfile::create($data);
        return redirect()->route('admin.volunteers.index')->with('success','Relawan ditambahkan.');
    }

    public function edit($id)
    {
        $relawan = RelawanProfile::with('user')->findOrFail($id);
        return view('admin.manajemen-relawan.edit', compact('relawan'));
    }

    public function update(Request $request, $id)
    {
        $relawan = RelawanProfile::findOrFail($id);
        $data = $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|max:32',
            'skill' => 'nullable|string',
            'status_verif' => 'nullable|in:pending,verified,rejected',
        ]);

        $relawan->update($data);
        return redirect()->route('admin.volunteers.index')->with('success','Relawan diperbarui.');
    }

    public function destroy($id)
    {
        $relawan = RelawanProfile::findOrFail($id);
        $relawan->delete();
        return redirect()->route('admin.volunteers.index')->with('success','Relawan dihapus.');
    }
}
