<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodRescue;

class FoodRescueController extends Controller
{
    public function index()
    {
        $items = FoodRescue::orderBy('id_food','desc')->paginate(20);
        return view('admin.food-rescue-admin.index', compact('items'));
    }

    public function create()
    {
        return view('admin.food-rescue-admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_donatur' => 'required|exists:donatur_profiles,id_donatur',
            'nama_makanan' => 'required|string|max:255',
            'porsi' => 'required|integer|min:1',
            'waktu_dibuat' => 'required|date',
            'waktu_expired' => 'nullable|date',
            'status' => 'nullable|in:available,claimed,expired',
            'id_claimer' => 'nullable|exists:users,id',
        ]);

        FoodRescue::create($data);

        return redirect()->route('admin.food-rescue.index')->with('success','Food rescue item created.');
    }

    public function edit($id)
    {
        $item = FoodRescue::findOrFail($id);
        return view('admin.food-rescue-admin.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = FoodRescue::findOrFail($id);
        $data = $request->validate([
            'id_donatur' => 'required|exists:donatur_profiles,id_donatur',
            'nama_makanan' => 'required|string|max:255',
            'porsi' => 'required|integer|min:1',
            'waktu_dibuat' => 'required|date',
            'waktu_expired' => 'nullable|date',
            'status' => 'nullable|in:available,claimed,expired',
            'id_claimer' => 'nullable|exists:users,id',
        ]);

        $item->update($data);
        return redirect()->route('admin.food-rescue.index')->with('success','Food rescue item updated.');
    }

    public function destroy($id)
    {
        $item = FoodRescue::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.food-rescue.index')->with('success','Item deleted.');
    }
}
