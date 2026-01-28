<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            'Administrasi',
            'Teknisi',
            'Dosen',
            'Satpam/Waker',
            'Pramubakti Kebersihan',
            'Satuan Pengamanan',
            'Pramubakti',
            'Satpam',
            'Paramedis',
            'Pramu Sarana',
            'Sopir',
            'Tenaga Parkir',
            'Pinisepuh',
        ];

        foreach ($jabatans as $jabatan) {
            DB::table('jabatans')->insert([
                'jabatan' => $jabatan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}