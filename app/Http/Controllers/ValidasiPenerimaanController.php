<?php

namespace App\Http\Controllers;

use App\Models\ValidasiPenerimaan;
use App\Models\PengambilanBarang;
use Illuminate\Http\Request;

class ValidasiPenerimaanController extends Controller
{
    // Tampilkan semua data validasi penerimaans dengan detail yang diperlukan
    public function index()
    {
        $data = ValidasiPenerimaan::with('verifikasiPembayaran.penerima.jurusan')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nik' => $item->verifikasiPembayaran->penerima->nik,
                    'nama' => $item->verifikasiPembayaran->penerima->nama,
                    'email' => $item->verifikasiPembayaran->penerima->email,
                    'no_telpon' => $item->verifikasiPembayaran->penerima->no_telpon,
                    'meja' => $item->verifikasiPembayaran->penerima->jurusan->meja_pengambilan,
                    'barcode' => $item->kode_unik,
                    'status' => $item->status,
                ];
            });

        return response()->json($data);
    }

    // Konfirmasi validasi penerimaan berdasarkan ID
    public function konfirmasi($id)
    {
        $validasi = ValidasiPenerimaan::find($id);

        if (!$validasi) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($validasi->status === 'berhasil_di_validasi') {
            return response()->json(['message' => 'Validasi sudah dilakukan sebelumnya'], 400);
        }

        $validasi->status = 'berhasil_di_validasi';
        $validasi->save();

        if (!$validasi->pengambilanBarang) {
            $meja = $validasi->verifikasiPembayaran->penerima->jurusan->meja_pengambilan;

            PengambilanBarang::create([
                'validasi_penerimaan_id' => $validasi->id,
                'status' => ($meja == 5) ? 'barang_diantar' : 'belum_diambil',
                'tanggal_pengambilan' => ($meja == 5) ? now() : null,
            ]);
        }

        return response()->json(['message' => 'Validasi berhasil dikonfirmasi']);
    }
}