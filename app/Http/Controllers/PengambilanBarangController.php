<?php

namespace App\Http\Controllers;

use App\Models\PengambilanBarang;
use Illuminate\Http\Request;

class PengambilanBarangController extends Controller
{
    public function index()
    {
        $data = PengambilanBarang::with([
            'validasiPenerimaan.verifikasiPembayaran.penerima.jurusan'
        ])->get()->map(function ($pb) {
            return [
                'id' => $pb->id, // Tambahkan id di sini untuk referensi di frontend
                'nik' => $pb->validasiPenerimaan->verifikasiPembayaran->penerima->nik,
                'nama' => $pb->validasiPenerimaan->verifikasiPembayaran->penerima->nama,
                'email' => $pb->validasiPenerimaan->verifikasiPembayaran->penerima->email,
                'no_telpon' => $pb->validasiPenerimaan->verifikasiPembayaran->penerima->no_telpon,
                'meja' => $pb->validasiPenerimaan->verifikasiPembayaran->penerima->jurusan->meja_pengambilan,
                'barcode' => $pb->validasiPenerimaan->kode_unik,
                'status_pengambilan' => $pb->status,
                'tanggal_pengambilan' => $pb->tanggal_pengambilan,
            ];
        });

        return response()->json($data);
    }

    public function konfirmasi($id)
    {
        $pengambilan = PengambilanBarang::find($id);

        if (!$pengambilan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Misal fungsi konfirmasi di model mengembalikan false jika sudah dikonfirmasi
        if (!$pengambilan->konfirmasi()) {
            return response()->json(['message' => 'Barang sudah diambil sebelumnya'], 400);
        }

        return response()->json(['message' => 'Status berhasil dikonfirmasi']);
    }
}