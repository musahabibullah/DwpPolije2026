<?php
use App\Http\Controllers\PengambilanBarangController;
use Firebase\JWT\JWT;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\ValidasiPenerimaanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JurusanController;


// Route untuk generate token
Route::post('/auth/token', function (Request $request) {
    $request->validate([
        'nik' => 'required',
        'email' => 'required|email',
        'hp' => 'required', // Tambahkan validasi nomor HP
        'bukti_pembayaran' => 'required' // Tambahkan validasi bukti pembayaran
    ]);

    $payload = [
        'nik' => $request->nik,
        'email' => $request->email,
        'hp' => $request->hp, // Tambahkan nomor HP ke payload
        'bukti_pembayaran' => $request->bukti_pembayaran, // Tambahkan bukti pembayaran ke payload
        'iat' => time(),
        'exp' => time() + 3600, // Token berlaku selama 1 jam
    ];

    $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

    return response()->json(['token' => $token]);
});

// Route yang membutuhkan token
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/pembayaran/cek', function (Request $request) {
        return response()->json(['message' => 'Token valid!', 'data' => $request->jwt_data]);
    });
});


Route::post('/login', [AuthController::class, 'login']);

// api penerima
Route::resource('penerimas', PenerimaController::class);

Route::put('/penerimas/{id}', [PenerimaController::class, 'update']);

Route::resource('jurusan', JurusanController::class);

Route::resource('jabatan', JabatanController::class);

// api validasi penerima

Route::get('/validasi-penerimaans', [ValidasiPenerimaanController::class, 'index']);
Route::post('/validasi-penerimaans/konfirmasi/{id}', [ValidasiPenerimaanController::class, 'konfirmasi']);


// api pengambilan barang
Route::get('/pengambilan-barang', [PengambilanBarangController::class, 'index']);
Route::post('/pengambilan-barang/konfirmasi/{id}', [PengambilanBarangController::class, 'konfirmasi']);