<?php

namespace App\Exports;

use App\Models\ValidasiPenerimaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class ValidasiPenerimaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $records;
    protected $status;

    public function __construct($records = null, $status = null)
    {
        $this->records = $records;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->records) {
            return $this->records;
        }

        $query = ValidasiPenerimaan::query()
            ->whereHas('verifikasiPembayaran', function ($query) {
                $query->where('status', 'diterima');
            })
            ->with(['verifikasiPembayaran.penerima']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No.',
            'NIK',
            'Nama',
            'Email',
            'No. Telpon',
            'Meja Pengambilan',
            'Barcode/Kode Unik',
            'Status',
            'Tanggal Validasi'
        ];
    }

    public function map($row): array
    {
        $statusText = match ($row->status) {
            'berhasil_di_validasi' => 'Berhasil di validasi',
            'belum_di_validasi' => 'Belum di validasi',
            default => $row->status,
        };

        $tanggalValidasi = $row->updated_at 
            ? Carbon::parse($row->updated_at)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i')
            : '-';

        return [
            $row->id,
            $row->verifikasiPembayaran->penerima->nik ?? '-',
            $row->verifikasiPembayaran->penerima->nama ?? '-',
            $row->verifikasiPembayaran->penerima->email ?? '-',
            $row->verifikasiPembayaran->penerima->no_telpon ?? '-',
            $row->verifikasiPembayaran->penerima->jurusan->meja_pengambilan ?? '-',
            $row->kode_unik ?? '-',
            $statusText,
            $tanggalValidasi,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (heading) as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}