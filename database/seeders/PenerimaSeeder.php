<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PenerimaImport;

class PenerimaSeeder extends Seeder
{
    public function run(): void
    {
        // Lakukan impor
        Excel::import(new PenerimaImport, storage_path('app/public/penerima.csv'));
        
        // Hapus file setelah impor selesai
        $filePath = storage_path('app/public/penerima.csv');
        if (file_exists($filePath)) {
            unlink($filePath); // Menghapus file
        }
    }
}
