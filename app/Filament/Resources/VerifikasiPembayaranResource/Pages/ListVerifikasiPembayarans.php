<?php

namespace App\Filament\Resources\VerifikasiPembayaranResource\Pages;

use App\Filament\Resources\VerifikasiPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiPembayarans extends ListRecords
{
    protected static string $resource = VerifikasiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
        //    Actions\CreateAction::make()
        //    ->modalHeading('Tambah Penerima Baru')
        //        ->modalWidth('xl')
        ];
    }
}
