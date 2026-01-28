<?php

namespace App\Observers;

use App\Models\VerifikasiPembayaran;
use App\Models\ValidasiPenerimaan;

class VerifikasiPembayaranObserver
{
    public function created(VerifikasiPembayaran $verifikasiPembayaran): void
    {
        if ($verifikasiPembayaran->status === 'diterima') {
            ValidasiPenerimaan::create([
                'verifikasi_pembayaran_id' => $verifikasiPembayaran->id,
                'status' => 'belum_di_validasi',
            ]);
        }
    }

    public function updated(VerifikasiPembayaran $verifikasiPembayaran): void
    {
        if ($verifikasiPembayaran->status === 'diterima' && 
            !ValidasiPenerimaan::where('verifikasi_pembayaran_id', $verifikasiPembayaran->id)->exists()) {
            ValidasiPenerimaan::create([
                'verifikasi_pembayaran_id' => $verifikasiPembayaran->id,
                'status' => 'belum_di_validasi',
            ]);
        }
    }
}