<?php

namespace App\Imports;

use App\Models\Penerima;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenerimaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Penerima([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'jurusan_id' => $row['jurusan_id'],
            'jabatan_id' => $row['jabatan_id'],
        ]);
    }
}
