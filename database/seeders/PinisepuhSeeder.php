<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VerifikasiPembayaran;

class PinisepuhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk setiap penerima dengan ID user 1008, 1009, dan 1010
        $data = [
            ['user_id' => 1008, 'name' => 'Ibu Soetrisno Widjaja'],
            ['user_id' => 1009, 'name' => 'Soehardjo Widodo'],
            ['user_id' => 1010, 'name' => 'Ibu Asmuji'],
        ];

        // Isi data ke dalam tabel verifikasi_pembayarans
        foreach ($data as $item) {
            VerifikasiPembayaran::create([
                'penerima_id' => $item['user_id'], // Menggunakan user_id yang sesuai
                'status' => VerifikasiPembayaran::STATUS_DITERIMA, // Status diterima
                'bukti_pembayaran' => 'BuktiPembayaran' . rand(1000, 9999), // String acak (misalnya "BuktiPembayaran1234")
            ]);
        }
    }
}
