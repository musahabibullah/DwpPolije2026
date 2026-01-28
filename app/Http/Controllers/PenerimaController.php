<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerima;
use App\Models\Jurusan;

class PenerimaController extends Controller
{

    public function index()
    {
        $penerimas = Penerima::with(['jurusan', 'jabatan'])
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nik' => $p->nik,
                    'nama' => $p->nama,
                    'email' => $p->email,
                    'no_telpon' => $p->no_telpon,
                    'jurusan_id' => $p->jurusan_id,
                    'jabatan_id' => $p->jabatan_id,
                    'nama_jurusan' => $p->jurusan->nama_jurusan,
                    'jabatan' => $p->jabatan->jabatan,
                ];
            });

        return response()->json($penerimas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:20|unique:penerimas,nik',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:penerimas,email',
            'no_telpon' => 'nullable|string|max:15',
            'jurusan_id' => 'required|exists:jurusans,id',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        $penerima = Penerima::create($validated);

        return response()->json([
            'message' => 'Data berhasil ditambahkan.',
            'data' => $penerima
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $penerima = Penerima::find($id);

        if (!$penerima) {
            return response()->json([
                'message' => 'Data penerima tidak ditemukan.'
            ], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'nik' => 'sometimes|required|string|max:20|unique:penerimas,nik,' . $penerima->id,
            'nama' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:penerimas,email,' . $penerima->id,
            'no_telpon' => 'nullable|string|max:15',
            'jurusan_id' => 'required|exists:jurusans,id',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        // Update data
        $penerima->update($validated);

        return response()->json([
            'message' => 'Data berhasil diperbarui.',
            'data' => $penerima
        ]);
    }


    public function destroy($id)
    {
        $penerima = Penerima::find($id);

        if (!$penerima) {
            return response()->json([
                'message' => 'Data penerima tidak ditemukan.'
            ], 404);
        }

        $penerima->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus.'
        ]);
    }


    public function show($nik)
    {
        $penerima = Penerima::where('nik', $nik)->first();

        if (!$penerima) {
            return response()->json([
                'message' => 'NIK tidak terdaftar!',
                'rekening' => 'XXXX-XXXX-XXXXX',
                'nama_rekening' => 'DWP Polije'
            ], 404);
        }

        // Default rekening dan nama rekening
        $rekening = 'XXXX-XXXX-XXXXX';
        $nama_rekening = 'DWP Polije';

        // Tentukan nomor rekening berdasarkan jurusan_id
        if (in_array($penerima->jurusan_id, [1, 2, 3, 4, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17])) {
            $rekening = 'Mandiri: 1430030816746';
            $nama_rekening = 'Yeni Ida Kurniawati';
        } elseif (in_array($penerima->jurusan_id, [6, 16, 18, 21])) {
            $rekening = 'Mandiri: 1430030816720';
            $nama_rekening = 'Yeni Ida Kurniawati';
        } elseif (in_array($penerima->jurusan_id, [5, 19, 20, 22, 23, 24, 25, 26, 27])) {
            $rekening = 'Mandiri: 1430029952551';
            $nama_rekening = 'Yeni Ida Kurniawati';
        }

        return response()->json([
            'nama' => $penerima->nama,
            'jurusan' => $penerima->jurusan->nama_jurusan ?? 'Tidak Ada',
            'jabatan' => $penerima->jabatan->jabatan ?? 'Tidak Ada',
            'rekening' => $rekening,
            'nama_rekening' => $nama_rekening
        ]);
    }
}