<?php

namespace App\Filament\Resources\ValidasiPenerimaanResource\Pages;

use App\Filament\Resources\ValidasiPenerimaanResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListValidasiPenerimaans extends ListRecords
{
    protected static string $resource = ValidasiPenerimaanResource::class;

    public function konfirmasi($recordId)
    {
        $record = \App\Models\ValidasiPenerimaan::find($recordId);
        
        if (!$record) {
            return;
        }

        $mejaNumber = $record->verifikasiPembayaran->penerima->jabatan->meja_pengambilan;
        
        $record->update([
            'status' => 'berhasil_di_validasi'
        ]);

        Notification::make()
            ->success()
            ->title('Berhasil divalidasi')
            ->body("Silahkan menuju meja {$mejaNumber}")
            ->send();
    }
}