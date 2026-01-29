<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerima;
use App\Models\VerifikasiPembayaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use FPDF;

class PembayaranController extends Controller
{
    public function submitPembayaran(Request $request)
    {
        try {
            Log::info('Menerima permintaan pembayaran', $request->all());

            // Validasi input
            $request->validate([
                'nik' => 'required|exists:penerimas,nik',
                'hp' => 'required|string',
                'email' => 'required|email',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Cek penerima berdasarkan NIK
            $penerima = Penerima::where('nik', $request->nik)->first();
            if (!$penerima) {
                Log::error('NIK tidak ditemukan: ' . $request->nik);
                return response()->json(['error' => 'NIK tidak ditemukan!'], 404);
            }

            // Ambil kunci rahasia JWT dari .env
            $jwtSecret = config('jwt.secret');
            $jwtExpiration = config('jwt.expiration');

            // Simpan bukti pembayaran
            $filename = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();
            $filePath = $request->file('bukti_pembayaran')->storeAs('', $filename, 'private');
            $buktiPathPublic = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

            Log::info('Bukti pembayaran berhasil disimpan', [
                'private_path' => $filePath,
                'public_path' => $buktiPathPublic
            ]);

            // Buat token JWT
            $tokenPayload = [
                'nik' => $request->nik,
                'email' => $request->email,
                'hp' => $request->hp,
                'bukti_pembayaran' => $filePath,
                'exp' => time() + $jwtExpiration
            ];
            $token = JWT::encode($tokenPayload, $jwtSecret, 'HS256');

            Log::info('JWT Token berhasil dibuat: ' . $token);

            // Perbarui data penerima
            $penerima->update([
                'no_telpon' => $request->hp,
                'email' => $request->email,
            ]);

            // Simpan atau perbarui verifikasi pembayaran
            VerifikasiPembayaran::updateOrCreate(
                ['penerima_id' => $penerima->id],
                [
                    'bukti_pembayaran' => $filename,
                    'status' => 'belum_diverifikasi',
                ]
            );

             // Generate nomor transaksi acak antara 1000000 hingga 9999999
             $transactionCode = rand(1000000, 9999999);

             // Ambil data tambahan
             $jurusan = $penerima->jurusan ? $penerima->jurusan->nama_jurusan : 'N/A'; // Mengambil nama jurusan dari kolom 'nama_jurusan'
             $currentTime = now()->format('d-m-Y H:i:s'); // Waktu transaksi

             if (empty($penerima->nama)) {
                Log::error('Nama penerima tidak ditemukan.');
                return response()->json(['error' => 'Nama penerima tidak ditemukan.'], 400);
            }

            // Buat PDF menggunakan FPDF
            $pdfFilename = 'invoice_' . $penerima->nik . '_' . time() . '.pdf';
            $pdfFolder = 'invoices';

            // Buat direktori dengan Storage facade
            Storage::disk('public')->makeDirectory($pdfFolder);

            // Path untuk menyimpan PDF
            $pdfPath = storage_path('app/public/' . $pdfFolder . '/' . $pdfFilename);

            // Pastikan direktori ada
            $directory = dirname($pdfPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

                        // Buat PDF menggunakan FPDF
            $pdfFilename = 'invoice_' . $penerima->nik . '_' . time() . '.pdf';
            $pdfFolder = 'invoices';

            // Buat direktori jika belum ada
            if (!file_exists(public_path($pdfFolder))) {
                mkdir(public_path($pdfFolder), 0755, true);
            }

            // Path untuk menyimpan PDF
            $pdfPath = public_path($pdfFolder . '/' . $pdfFilename);

            // Buat PDF dengan FPDF
            $pdf = new FPDF();
            $pdf->AddPage();

            $backgroundPath = public_path('images/buktipembayaran.png');
            if (file_exists($backgroundPath)) {
                $pdf->Image($backgroundPath, 0, 0, 210, 297);
            } else {
                Log::error("Background image tidak ditemukan di: " . $backgroundPath);
            }

            $marginLeft = 20;

            // Judul dokumen
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, '', 0, 1, 'C');
            $pdf->Ln(65);

            // Informasi transaksi
            $pdf->SetX($marginLeft);
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Cell(50, 10, 'Nomor Transaksi:', 0);
            $pdf->SetFont('Arial', '', 15);
            $pdf->Cell(0, 10, $transactionCode, 0, 1);

            $pdf->SetX($marginLeft);
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Cell(50, 10, 'Waktu Transaksi:', 0);
            $pdf->SetFont('Arial', '', 15);
            $pdf->Cell(0, 10, $currentTime, 0, 1);

            $pdf->SetX($marginLeft);
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Cell(50, 10, 'Nama:', 0);
            $pdf->SetFont('Arial', '', 15);
            $pdf->Cell(0, 10, $penerima->nama, 0, 1);

            $pdf->SetX($marginLeft);
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Cell(50, 10, 'Jurusan:', 0);
            $pdf->SetFont('Arial', '', 15);
            $pdf->Cell(0, 10, $jurusan, 0, 1);

            $pdf->Ln(10);

            // Simpan PDF
            $pdf->Output('F', $pdfPath);

            if (!file_exists($pdfPath)) {
                Log::error('Gagal membuat PDF: ' . $pdfPath);
                return response()->json(['error' => 'Gagal membuat PDF'], 500);
            }

            Log::info('PDF berhasil dibuat di: ' . $pdfPath);

            // URL PDF publik
            $pdfUrl = url("{$pdfFolder}/{$pdfFilename}");

            // Data untuk email
            $data = [
                'nama' => $penerima->nama,
                'jurusan' => $jurusan,
                'waktu_transaksi' => $currentTime,
                'nomor_transaksi' => $transactionCode,
            ];

            // Kirim Email
            try {
                Mail::send('emails.invoice', $data, function ($message) use ($request, $pdfPath) {
                    $message->to($request->email)
                            ->subject('Konfirmasi Pembayaran')
                            ->attach($pdfPath);
                });

                Log::info('Email berhasil dikirim ke: ' . $request->email);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email: ' . $e->getMessage());
                // Lanjutkan eksekusi meskipun email gagal
            }

            // Data untuk pesan WhatsApp
            $pesanWhatsApp = "pesan tidak perlu dibalas (non reply chat)\nPembayaran Anda telah berhasil dikirim. Menunggu verifikasi dari admin,\nsilahkan cek email anda jika tidak ada silahkan cek di folder spam\n\npengambilan barang untuk PSDKU dapat di ambil di *GOR DJOEANG 45 POLITEKNIK NEGERI JEMBER pada hari Minggu 8 Maret 2026*\n\nDetail Transaksi:\nNama: {$penerima->nama}\nJurusan: {$jurusan}\nNomor Transaksi: {$transactionCode}\nWaktu: {$currentTime}\n\nBukti pembayaran: {$pdfUrl}";

            // Kirim WhatsApp dengan bukti PDF
            $this->kirimWhatsApp($request->hp, $pesanWhatsApp);

            return response()->json([
                'success' => 'Pembayaran berhasil dikirim, email dan pesan WhatsApp telah dikirim!',
                'pdf_url' => $pdfUrl,
                'jwt_token' => $token // Kirim token sebagai response
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan dalam submitPembayaran: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

/**
 * Fungsi untuk mengirim pesan WhatsApp menggunakan Fonnte
 */
private function kirimWhatsApp($nomor, $pesan)
{
    try {
        // Ambil token dari .env
        $token = env('FONNTE_TOKEN');

        if (empty($token)) {
            Log::warning('Konfigurasi Fonnte tidak lengkap. Pengiriman WhatsApp dilewati.');
            return [
                'success' => false,
                'message' => 'Token tidak tersedia'
            ];
        }

        // Format nomor tetap menggunakan 62...
        $nomor = $this->formatNomor($nomor);

        // Menggunakan Laravel Http Client (Wrapper dari Guzzle/cURL)
        $response = Http::withHeaders([
            'Authorization' => $token, // Sesuai dokumentasi: 'Authorization: TOKEN'
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target'      => $nomor,
            'message'     => $pesan,
            'countryCode' => '62', // Default Indonesia
            'delay'       => '2',  // Jeda pengiriman (opsional)
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

    /**
     * Format nomor telepon
     */
    private function formatNomor($nomor)
    {
        if (substr($nomor, 0, 1) === "0") {
            $nomor = "62" . substr($nomor, 1);
        }
        return $nomor;
    }
}
