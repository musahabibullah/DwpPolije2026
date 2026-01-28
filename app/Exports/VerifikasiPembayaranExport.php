<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Penerima;
use App\Models\VerifikasiPembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VerifikasiPembayaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;
    protected $user;
    
    public function __construct(array $filters = [], User $user = null)
    {
        $this->filters = $filters;
        $this->user = $user ?? auth()->user();
    }
    
    public function collection()
    {
        if (isset($this->filters['status']) && $this->filters['status'] === 'belum_bayar') {
            return Penerima::with(['jurusan', 'jabatan'])
                ->where(function($query) {
                    $query->whereDoesntHave('verifikasiPembayarans', function ($q) {
                        $q->where('status', '!=', 'ditolak');
                    })
                    ->orWhereHas('verifikasiPembayarans', function ($q) {
                        $q->where('status', 'ditolak');
                    });
                })
                ->when($this->user->hasRole('bendahara'), function($q) {
                    $q->whereHas('jurusan', function($q) {
                        $q->whereIn('id', $this->user->jurusans->pluck('id'));
                    });
                })
                ->get();
        }

        $query = VerifikasiPembayaran::with(['penerima.jurusan', 'penerima.jabatan'])
            ->when($this->user->hasRole('bendahara'), function($q) {
                $q->whereHas('penerima.jurusan', function($q) {
                    $q->whereIn('id', $this->user->jurusans->pluck('id'));
                });
            });

        if (isset($this->filters['status']) && $this->filters['status'] !== 'semua') {
            $query->where('status', $this->filters['status']);
        }

        return $query->get();
    }
    
    public function headings(): array
    {
        $baseHeaders = [
            'NIK',
            'Nama',
            'Email',
            'No. Telepon',
            'Jurusan',
            'Jabatan',
            'Status Verifikasi',
        ];

        // Tambahkan kolom tanggal hanya untuk status yang relevan
        if ($this->shouldShowTanggalColumn()) {
            $baseHeaders[] = 'Tanggal Verifikasi';
        }

        if ($this->filters['status'] === 'belum_bayar') {
            $baseHeaders[6] = 'Status';
            return $baseHeaders;
        }

        return $baseHeaders;
    }

    private function shouldShowTanggalColumn(): bool
    {
        $status = $this->filters['status'] ?? 'semua';
        
        // Tampilkan kolom tanggal hanya untuk status: diterima, ditolak, atau semua
        return in_array($status, ['diterima', 'ditolak', 'semua']) && $status !== 'belum_bayar';
    }
    
    public function map($row): array
    {
        $data = [
            $this->getNik($row),
            $this->getNama($row),
            $this->getEmail($row),
            $this->getNoTelpon($row),
            $this->getJurusan($row),
            $this->getJabatan($row),
            $this->getStatusVerifikasi($row),
        ];

        if ($this->shouldShowTanggalColumn()) {
            $data[] = $this->getTanggalVerifikasi($row);
        }

        return $data;
    }

    private function getNik($row)
    {
        return $row->nik ?? $row->penerima->nik ?? '-';
    }

    private function getNama($row)
    {
        return $row->nama ?? $row->penerima->nama ?? '-';
    }

    private function getEmail($row)
    {
        return $row->email ?? $row->penerima->email ?? '-';
    }

    private function getNoTelpon($row)
    {
        return $row->no_telpon ?? $row->penerima->no_telpon ?? '-';
    }

    private function getJurusan($row)
    {
        return $row->jurusan->nama_jurusan ?? $row->penerima->jurusan->nama_jurusan ?? '-';
    }

    private function getJabatan($row)
    {
        return $row->jabatan->jabatan ?? $row->penerima->jabatan->jabatan ?? '-';
    }

    private function getStatusVerifikasi($row)
    {
        if ($this->filters['status'] === 'belum_bayar') {
            return 'Belum Melakukan Pembayaran';
        }

        $status = $row->status ?? $row->status ?? null;
        return match ($status) {
            'belum_diverifikasi' => 'Belum Diverifikasi',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            default => $status ?? '-',
        };
    }

    private function getTanggalVerifikasi($row)
    {
        $status = $row->status ?? null;
        $date = $row->updated_at ?? null;

        // Hanya tampilkan tanggal jika status diterima/ditolak
        if (in_array($status, ['diterima', 'ditolak'])) {
            return $date ? $date->format('d-m-Y H:i') : '-';
        }

        return '-';
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}