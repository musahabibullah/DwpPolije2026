<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\VerifikasiPembayaranController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\PembayaranController;

use App\Models\Penerima;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


// ----- ROUTE POST -----

Route::post('/search-nik', function (Request $request) {
    $nik = $request->input('nik');

    if (!$nik) {
        return response()->json(['status' => 'error', 'message' => 'NIK tidak boleh kosong'], 400);
    }

    // Cari penerima berdasarkan NIK
    $penerima = Penerima::where('nik', $nik)->first();

    if (!$penerima) {
        return response()->json(['status' => 'error', 'message' => 'Anda Tidak Terdaftar Dalam Data Penerima Bazar.']);
    }

    // Cek apakah penerima sudah mengisi form pembayaran
    if ($penerima->hasActiveVerification()) {
        return response()->json([
            'status' => 'exists',
            'message' => 'Anda sudah mengisi formulir ini. Kalau ada perubahan, Hubungi Admin'
        ]);
    }

    // Simpan session agar hanya bisa mengakses payment-user button melalui search
    Session::put('payment_access', true);

    return response()->json([
        'status' => 'success',
        'message' => 'Anda Terdaftar Dalam Data Penerima Bazar. Silakan lanjutkan ke halaman pembayaran.'
    ]);
});

// METHOD POST UNTUK KIRIM KE DB
Route::post('/submit-pembayaran', [PembayaranController::class, 'submitPembayaran'])->name('submit.pembayaran');

Route::post('/verifikasi/{verifikasiPembayaran}/kirim', [VerifikasiPembayaranController::class, 'kirimVerifikasi']);

// ----- END ROUTE POST -----




// ----- ROUTE GET -----

// METHOD GET UNTUK AMBIL DARI DB
Route::get('/penerima/{nik}', [PenerimaController::class, 'show']);

// HALAMAN UTAMA
Route::get('/', function () {
    return view('layouts.main'); // Halaman Landing Page
})->name('home');

// HALAMAN FORM PEMBAYARAN 
Route::get('/payment-user', function () {
    // Cek apakah session payment_access ada
    if (!Session::has('payment_access')) {
        return redirect('/')->with('error', 'Akses ditolak. Harap gunakan tombol Search.');
    }
    // Hapus session agar akses hanya bisa sekali per pencarian
    Session::forget('payment_access');

    return view('payment-user');
});

// DOWNLOAD INVOICE KETIKA SUBMIT
Route::get('/download-invoice', [PembayaranController::class, 'downloadInvoice'])->name('download.invoice');

// REKENING
Route::get('/get-rekening/{nik}', [PenerimaController::class, 'show']);

Route::get('/private-file/{filename}', [FileController::class, 'show'])
    ->name('private.file.show')
    ->middleware('auth');

Route::get('/download-private-file/{filename}', [FileController::class, 'download'])
    ->name('private.file.download')
    ->middleware('auth');

Route::get('auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// ----- END ROUTE GET -----