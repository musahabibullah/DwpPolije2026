<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    // Menampilkan semua data jabatan
    public function index()
    {
        $jabatans = Jabatan::all();
        return response()->json($jabatans);
    }

    // Menyimpan data jabatan baru
    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan = Jabatan::create([
            'jabatan' => $request->jabatan,
        ]);

        return response()->json(['message' => 'Jabatan berhasil ditambahkan', 'data' => $jabatan], 201);
    }

    // Menampilkan satu data jabatan berdasarkan ID
    public function show($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json($jabatan);
    }

    // Mengupdate data jabatan
    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'jabatan' => $request->jabatan,
        ]);

        return response()->json(['message' => 'Jabatan berhasil diperbarui', 'data' => $jabatan]);
    }

    // Menghapus data jabatan
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return response()->json(['message' => 'Jabatan berhasil dihapus']);
    }
}
