<?php

namespace App\Exports;

use App\Models\PengambilanBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class PengambilanBarangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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

        $query = PengambilanBarang::query()
            ->whereHas('validasiPenerimaan', function ($query) {
                $query->where('status', 'berhasil_di_validasi');
            })
            ->with(['validasiPenerimaan.verifikasiPembayaran.penerima']);

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
            'Kode Unik',
            'Status',
            'Tanggal Pengambilan',
            'Tanggal Validasi'
        ];
    }

    public function map($row): array
    {
        $statusText = match ($row->status) {
            'sudah_diambil' => 'Sudah Diambil',
            'belum_diambil' => 'Belum Diambil',
            'barang_diantar' => 'Sudah Diantar',
            default => $row->status,
        };

        $tanggalPengambilan = $row->tanggal_pengambilan 
            ? Carbon::parse($row->tanggal_pengambilan)->format('d/m/Y H:i')
            : '-';

        $tanggalValidasi = $row->validasiPenerimaan->updated_at 
            ? Carbon::parse($row->validasiPenerimaan->updated_at)->format('d/m/Y H:i')
            : '-';

        return [
            $row->id,
            $row->validasiPenerimaan->verifikasiPembayaran->penerima->nik ?? '-',
            $row->validasiPenerimaan->verifikasiPembayaran->penerima->nama ?? '-',
            $row->validasiPenerimaan->verifikasiPembayaran->penerima->email ?? '-',
            $row->validasiPenerimaan->verifikasiPembayaran->penerima->no_telpon ?? '-',
            $row->validasiPenerimaan->verifikasiPembayaran->penerima->jurusan->meja_pengambilan ?? '-',
            $row->validasiPenerimaan->kode_unik ?? '-',
            $statusText,
            $tanggalPengambilan,
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