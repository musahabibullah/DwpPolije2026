<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengambilanBarang extends Model
{
    protected $fillable = [
        'validasi_penerimaan_id',
        'status',
        'tanggal_pengambilan',
    ];

    protected $casts = [
        'tanggal_pengambilan' => 'datetime',
    ];

    public function validasiPenerimaan(): BelongsTo
    {
        return $this->belongsTo(ValidasiPenerimaan::class);
    }
    // App/Models/PengambilanBarang.php
    public function isDiantar(): bool
    {
        return $this->validasiPenerimaan->verifikasiPembayaran->penerima->jurusan->meja_pengambilan == 5;
    }
}