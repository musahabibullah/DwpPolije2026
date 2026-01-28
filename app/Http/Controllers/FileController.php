<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show($filename)
    {
        return $this->handleFile($filename, false);
    }

    public function download($filename)
    {
        return $this->handleFile($filename, true);
    }

    private function handleFile($filename, $download)
    {
        // Middleware sudah menangani auth, tidak perlu pengecekan lagi
        if (!Storage::disk('private')->exists($filename)) {
            return redirect('/');
        }

        if ($download) {
            return Storage::disk('private')->download($filename);
        }

        return Storage::disk('private')->response($filename);
    }
}