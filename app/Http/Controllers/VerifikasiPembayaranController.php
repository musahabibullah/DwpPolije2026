<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiPembayaran;
use App\Models\Penerima;
use App\Models\ValidasiPenerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Filament\Resources\Storage;
use FPDF;
use Milon\Barcode\DNS1D;

class VerifikasiPembayaranController extends Controller
{
    public function kirimVerifikasi(Request $request, VerifikasiPembayaran $verifikasiPembayaran)
{
    try {
        if ($verifikasiPembayaran->status !== 'diterima') {
            return response()->json(['error' => 'Verifikasi belum diterima'], 400);
        }

        $penerima = $verifikasiPembayaran->penerima;
        $validasiPenerimaan = ValidasiPenerimaan::where('verifikasi_pembayaran_id', $verifikasiPembayaran->id)->first();
        $kodeUnik = $validasiPenerimaan->kode_unik ?? '000000';
        $nomorTransaksi = random_int(100000, 999999);
        $tanggalPembayaran = now()->format('Y-m-d');

        // Get meja_pengambilan from jurusan
        $mejaPengambilan = $penerima->jurusan->meja_pengambilan ?? 'N/A';

        $data = [
            'nomor_transaksi' => $nomorTransaksi,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'nik' => $penerima->nik,
            'nama' => $penerima->nama,
            'jurusan' => $penerima->jurusan->nama_jurusan ?? 'N/A',
            'email' => $penerima->email,
            'no_telpon' => $penerima->no_telpon,
            'meja' => $mejaPengambilan, // Added meja pengambilan
            'kode_unik' => $kodeUnik
        ];

        // Change the storage path from storage_path to public_path
        $pdfFileName = 'invoice_' . $penerima->nik . '.pdf';
        $pdfPath = public_path('invoices/' . $pdfFileName);

        // Make sure the directory exists
        if (!file_exists(public_path('invoices'))) {
            mkdir(public_path('invoices'), 0755, true);
        }

        $this->generatePDF($data, $pdfPath);

        // Generate public URL for the PDF
        $pdfUrl = url('invoices/' . $pdfFileName);

        try {
            Mail::send('emails.pembayaran_confirmation', $data, function ($message) use ($penerima, $pdfPath) {
                $message->to($penerima->email)
                        ->subject('Konfirmasi Pembayaran')
                        ->attach($pdfPath);
            });
            Log::info('Email berhasil dikirim ke: ' . $penerima->email);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengirim email'], 500);
        }

        $this->kirimWhatsApp($penerima->no_telpon, "Terima kasih sudah melakukan pembayaran. Anda dapat mengakses invoice di: " . $pdfUrl . "\n\n*NOTE :* untuk dapat mengakses link bukti pembayaran silahkan untuk menyimpan nomor ini sebagai kontak anda agar dapat melihat link nya");

        return response()->json([
            'success' => 'Pembayaran berhasil dikirim, email dan pesan WhatsApp telah dikirim!',
            'invoice_url' => $pdfUrl
        ]);
    } catch (\Exception $e) {
        Log::error('Kesalahan dalam pengiriman verifikasi pembayaran: ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan dalam pengiriman verifikasi pembayaran.'], 500);
    }
}

    // Menambahkan method baru untuk kirimPenolakan
    public function kirimPenolakan(Request $request, VerifikasiPembayaran $verifikasiPembayaran, $alasanDitolak)
    {
        try {
            if ($verifikasiPembayaran->status !== 'ditolak') {
                return response()->json(['error' => 'Verifikasi tidak ditolak'], 400);
            }

            $penerima = $verifikasiPembayaran->penerima;

            // Menyiapkan pesan WhatsApp yang informatif
            $pesan = "Halo {$penerima->nama},\n\n".
                     "Mohon maaf, pembayaran Anda dengan NIK {$penerima->nik} ditolak dengan alasan:\n\n" .
                     "*{$alasanDitolak}*\n\n" .
                     "Silakan lakukan pembayaran ulang dengan bukti yang valid. Untuk informasi lebih lanjut, hubungi admin kami.\n\n" .
                     "Terima kasih.";

            // Kirim pesan WhatsApp
            $this->kirimWhatsApp($penerima->no_telpon, $pesan);

            Log::info('Pesan penolakan berhasil dikirim ke: ' . $penerima->no_telpon);
            return response()->json(['success' => 'Pesan penolakan berhasil dikirim melalui WhatsApp!']);

        } catch (\Exception $e) {
            Log::error('Kesalahan dalam pengiriman pesan penolakan: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan dalam pengiriman pesan penolakan.'], 500);
        }
    }

    private function generatePDF($data, $pdfPath, $positionY = 70) {
        $pdf = new FPDF();
        $pdf->AddPage();

        // Add Background
        $backgroundPath = public_path('images/barcode.png');
        if (file_exists($backgroundPath)) {
            $pdf->Image($backgroundPath, 0, 0, 210, 297);
        } else {
            Log::error("Background image tidak ditemukan di: " . $backgroundPath);
        }

        // Set initial position
        $pdf->SetY($positionY);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, '', 0, 1, 'C');
        $pdf->Ln(5);

        // Transaction details in a better format
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, 'Nomor Transaksi:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $data['nomor_transaksi'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, 'Tanggal Pembayaran:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $data['tanggal_pembayaran'], 0, 1, 'L');

        $pdf->Ln(5);

        // Personal Information section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'INFORMASI PENERIMA', 0, 1, 'L');
        $pdf->Ln(2);

        // Set up consistent formatting for personal details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 8, 'NIK:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, $data['nik'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 8, 'Nama:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, $data['nama'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 8, 'Jurusan:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, $data['jurusan'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 8, 'Email:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, $data['email'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 8, 'No. Telepon:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, $data['no_telpon'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 8, 'Meja Pengambilan:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, $data['meja'], 0, 1, 'L');

        $pdf->Ln(10);

        // Barcode section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, '', 0, 1, 'C');

        // Generate barcode
        $barcode = new DNS1D();
        $barcodeData = $barcode->getBarcodePNG($data['kode_unik'], 'C128');

        if ($barcodeData) {
            // Save barcode to public directory
            $barcodeFileName = 'barcode_' . $data['kode_unik'] . '.png';
            $barcodePath = public_path('barcodes/' . $barcodeFileName);

            // Make sure the directory exists
            if (!file_exists(public_path('barcodes'))) {
                mkdir(public_path('barcodes'), 0755, true);
            }

            file_put_contents($barcodePath, base64_decode($barcodeData));

            // Display barcode
            $pdf->Image($barcodePath, ($pdf->GetPageWidth() - 80) / 2, $pdf->GetY() +30, 80, 20);

            // Display unique code under the barcode
            $pdf->SetY($pdf->GetY() + 52);
            $pdf->Cell(0, 10, $data['kode_unik'], 0, 1, 'C');
        } else {
            Log::error("Gagal membuat barcode untuk kode unik: " . $data['kode_unik']);
        }

        $pdf->Ln(8); // Add spacing after barcode section

        // Set normal font for the rest of the data
        $pdf->SetFont('Arial', '', 20);
        // Output PDF to the specified path
        $pdf->Output('F', $pdfPath);
    }

private function kirimWhatsApp($nomor, $pesan)
{
    try {
        $token = env('FONNTE_TOKEN');

        if (empty($token)) {
            Log::warning('Konfigurasi Fonnte tidak ditemukan. Pengiriman WhatsApp dilewati.');
            return [
                'success' => false,
                'message' => 'Token tidak tersedia'
            ];
        }

        $nomor = $this->formatNomor($nomor);

        // Menyesuaikan dengan dokumentasi Fonnte
        $response = Http::withHeaders([
            'Authorization' => $token, // Sesuai 'Authorization: TOKEN'
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target'      => $nomor,
            'message'     => $pesan,
            'countryCode' => '62',
            'delay'       => '2',
        ]);

        Log::info('Respon Fonnte WhatsApp:', $response->json());

        return $response->json();
    } catch (\Exception $e) {
        Log::error('Gagal mengirim WhatsApp via Fonnte: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

    private function formatNomor($nomor)
    {
        if (substr($nomor, 0, 1) === "0") {
            $nomor = "62" . substr($nomor, 1);
        }
    return $nomor;
    }
}
