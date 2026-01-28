<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Storage;

class Penerima extends Model
{
    use HasFactory;

    protected $table = 'penerimas';

    protected $fillable = [
        'nik',
        'nama',
        'email',
        'no_telpon',
        'jurusan_id',
        'jabatan_id'
    ];

    protected $casts = [
        'jurusan_id' => 'integer',
        'jabatan_id' => 'integer',
    ];

    protected static function booted(): void
    {

        static::deleting(function ($penerima) {
            $verifikasiPembayarans = $penerima->verifikasiPembayarans;

            foreach ($verifikasiPembayarans as $verifikasi) {
                if ($verifikasi->bukti_pembayaran) {
                    Storage::disk('private')->delete($verifikasi->bukti_pembayaran);
                }
            }
        });
    }
    // app/Models/Penerima.php
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function verifikasiPembayarans(): HasMany
    {
        return $this->hasMany(VerifikasiPembayaran::class, 'penerima_id');
    }

    public function hasActiveVerification(): bool
    {
        return $this->verifikasiPembayarans()
            ->where('status', '!=', 'ditolak')
            ->exists();
    }
}