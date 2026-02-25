<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Penerima;

class ImportPenerimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('penerimas2026.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("File not found: $csvFile");
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip header row: nik,nama,jurusan_id,jabatan_id

        $count = 0;
        while (($data = fgetcsv($file)) !== false) {
            // Map columns based on CSV structure:
            // 0: nik
            // 1: nama
            // 2: jurusan_id
            // 3: jabatan_id

            // Basic validation to ensure row has enough columns
            if (count($data) < 4) {
                continue;
            }

            try {
                Penerima::updateOrCreate(
                    ['nik' => $data[0]], // Unique key to avoid duplicates
                    [
                        'nama' => $data[1],
                        'jurusan_id' => $data[2],
                        'jabatan_id' => $data[3],
                        // email and no_telpon will use default values from migration if creating
                    ]
                );
                $count++;
            } catch (\Exception $e) {
                $this->command->warn("Failed to import NIK {$data[0]}: " . $e->getMessage());
            }
        }

        fclose($file);
        $this->command->info("Successfully imported/updated $count penerima records.");
    }
}
