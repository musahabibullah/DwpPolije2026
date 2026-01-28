<?php

namespace App\Filament\Resources\VerifikasiPembayaranResource\Pages;

use App\Filament\Resources\VerifikasiPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiPembayaran extends EditRecord
{
    protected static string $resource = VerifikasiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
