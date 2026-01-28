<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengambilan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validasi_penerimaan_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['belum_diambil', 'sudah_diambil', 'barang_diantar'])->default('belum_diambil');
            $table->timestamp('tanggal_pengambilan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengambilan_barangs');
    }
};
