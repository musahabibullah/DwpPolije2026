<?php

// database/migrations/2025_02_17_create_validasi_penerimaans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validasi_penerimaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verifikasi_pembayaran_id')->constrained()->cascadeOnDelete();
            $table->string('kode_unik', 6);
            $table->enum('status', ['belum_di_validasi', 'berhasil_di_validasi'])->default('belum_di_validasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validasi_penerimaans');
    }
};