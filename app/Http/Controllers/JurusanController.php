<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    // Menampilkan semua data jurusan
    public function index()
    {
        $jurusans = Jurusan::with(['penerima', 'users'])->get();
        return response()->json($jurusans);
    }

    // Menyimpan data jurusan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'meja_pengambilan' => 'required|string|max:255',
        ]);

        $jurusan = Jurusan::create($request->only(['nama_jurusan', 'meja_pengambilan']));

        return response()->json(['message' => 'Jurusan berhasil ditambahkan', 'data' => $jurusan], 201);
    }

    // Menampilkan satu data jurusan berdasarkan ID
    public function show($id)
    {
        $jurusan = Jurusan::with(['penerima', 'users'])->findOrFail($id);
        return response()->json($jurusan);
    }

    // Mengupdate data jurusan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'meja_pengambilan' => 'required|string|max:255',
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->only(['nama_jurusan', 'meja_pengambilan']));

        return response()->json(['message' => 'Jurusan berhasil diperbarui', 'data' => $jurusan]);
    }

    // Menghapus data jurusan
    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return response()->json(['message' => 'Jurusan berhasil dihapus']);
    }
}
