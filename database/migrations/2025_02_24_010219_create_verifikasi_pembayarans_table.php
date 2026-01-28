<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerima_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('bukti_pembayaran');
            $table->enum('status', ['belum_diverifikasi', 'diterima', 'ditolak'])->default('belum_diverifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_pembayarans');
    }
};