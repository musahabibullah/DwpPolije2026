<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_pembayarans';

    protected $fillable = [
        'penerima_id',
        'bukti_pembayaran',
        'status',
        'alasan_ditolak'
    ];

    protected $casts = [
        'penerima_id' => 'integer',
        'status' => 'string',
    ];

    // Define the valid status values
    public const STATUS_BELUM_DIVERIFIKASI = 'belum_diverifikasi';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_DITOLAK = 'ditolak';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_BELUM_DIVERIFIKASI => 'Belum Diverifikasi',
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    protected static function booted(): void
    {

        static::deleting(function ($verifikasiPembayaran) {
            if ($verifikasiPembayaran->bukti_pembayaran) {
                try {
                    Storage::disk('private')->delete($verifikasiPembayaran->bukti_pembayaran);
                } catch (\Exception $e) {
                    \Log::error('Gagal menghapus file: ' . $e->getMessage());
                }
            }
        });

        // Set default status when creating
        static::creating(function (VerifikasiPembayaran $verifikasi) {
            if (!$verifikasi->status) {
                $verifikasi->status = self::STATUS_BELUM_DIVERIFIKASI;
            }
        });
    }

    // app/Models/VerifikasiPembayaran.php
    public function scopeForBendahara($query, User $user)
    {
        return $query->whereHas('penerima', function ($q) use ($user) {
            $q->whereHas('jurusan', function ($q) use ($user) {
                $q->whereIn('jurusans.id', $user->jurusans->pluck('id'));
            });
        });
    }

    public function penerima()
    {
        return $this->belongsTo(Penerima::class);
    }

    // Helper methods for status checking
    public function isBelumDiverifikasi(): bool
    {
        return $this->status === self::STATUS_BELUM_DIVERIFIKASI;
    }

    public function isDiterima(): bool
    {
        return $this->status === self::STATUS_DITERIMA;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }
}