<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ValidasiPenerimaan extends Model
{
    protected $fillable = [
        'verifikasi_pembayaran_id',
        'kode_unik',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate kode unik
            $model->kode_unik = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Auto-validasi untuk meja 5
            $meja = $model->verifikasiPembayaran?->penerima?->jurusan?->meja_pengambilan;
            $model->status = ($meja == 5) ? 'berhasil_di_validasi' : 'belum_di_validasi';
        });

        static::created(function ($model) {
            if ($model->status === 'berhasil_di_validasi') {
                // Ambil data meja lagi untuk memastikan
                $meja = $model->verifikasiPembayaran?->penerima?->jurusan?->meja_pengambilan;
                
                PengambilanBarang::create([
                    'validasi_penerimaan_id' => $model->id,
                    'status' => ($meja == 5) ? 'barang_diantar' : 'belum_diambil',
                    'tanggal_pengambilan' => ($meja == 5) ? now() : null
                ]);
            }
        });

        static::updated(function ($model) {
            if ($model->status === 'berhasil_di_validasi' && !$model->pengambilanBarang) {
                $meja = $model->verifikasiPembayaran?->penerima?->jurusan?->meja_pengambilan;
                
                PengambilanBarang::create([
                    'validasi_penerimaan_id' => $model->id,
                    'status' => ($meja == 5) ? 'barang_diantar' : 'belum_diambil',
                    'tanggal_pengambilan' => ($meja == 5) ? now() : null
                ]);
            }
        });
    }

    public function verifikasiPembayaran()
    {
        return $this->belongsTo(VerifikasiPembayaran::class);
    }

    public function pengambilanBarang(): HasOne
    {
        return $this->hasOne(PengambilanBarang::class);
    }
}