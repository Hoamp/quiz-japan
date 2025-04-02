<?php

namespace App\Http\Controllers;

use App\Models\Ordering;
use Illuminate\Http\Request;

class OrderingController extends Controller
{ 
    public function index()
    {
        $orderings = Ordering::all();
        return view('ordering.index', compact('orderings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        Ordering::create($request->all());

        return response()->json(['success' => 'Kuis berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $ordering = Ordering::findOrFail($id);
        $ordering->update($request->all());

        return response()->json(['success' => 'Kuis berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Ordering::findOrFail($id)->delete();
        return response()->json(['success' => 'Kuis berhasil dihapus']);
    }
}
