<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = [
            ['nama_jurusan' => 'KEUANGAN DAN UMUM', 'meja_pengambilan' => 1],
            ['nama_jurusan' => 'AKPK', 'meja_pengambilan' => 1],
            ['nama_jurusan' => 'P3M', 'meja_pengambilan' => 1],
            ['nama_jurusan' => 'P4M', 'meja_pengambilan' => 1],
            ['nama_jurusan' => 'JURUSAN TEKNOLOGI PERTANIAN', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'JURUSAN TEKNOLOGI INFORMASI', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA PERPUS', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA BAHASA', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA LUK', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA BIOSAINS', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA PERMESINAN', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA TERPADU', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA PANGAN', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA KWU', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'UPA TIK', 'meja_pengambilan' => 2],
            ['nama_jurusan' => 'JURUSAN PRODUKSI PERTANIAN', 'meja_pengambilan' => 3],
            ['nama_jurusan' => 'JURUSAN BAHASA, KOMUNIKASI DAN PARIWISATA', 'meja_pengambilan' => 3],
            ['nama_jurusan' => 'JURUSAN KESEHATAN', 'meja_pengambilan' => 3],
            ['nama_jurusan' => 'JURUSAN PETERNAKAN', 'meja_pengambilan' => 4],
            ['nama_jurusan' => 'JURUSAN MANAJEMEN AGRIBISNIS', 'meja_pengambilan' => 4],
            ['nama_jurusan' => 'JURUSAN TEKNIK', 'meja_pengambilan' => 4],
            ['nama_jurusan' => 'JURUSAN BISNIS', 'meja_pengambilan' => 4],
            ['nama_jurusan' => 'PSDKU BONDOWOSO', 'meja_pengambilan' => 5],
            ['nama_jurusan' => 'PSDKU NGANJUK', 'meja_pengambilan' => 5],
            ['nama_jurusan' => 'PSDKU SIDOARJO', 'meja_pengambilan' => 5],
            ['nama_jurusan' => 'PSDKU NGAWI', 'meja_pengambilan' => 5],
            ['nama_jurusan' => 'PINISEPUH', 'meja_pengambilan' => 5],
        ];

        foreach ($jurusans as &$jurusan) {
            $jurusan['created_at'] = now();
            $jurusan['updated_at'] = now();
        }

        DB::table('jurusans')->insert($jurusans);
    }
}
