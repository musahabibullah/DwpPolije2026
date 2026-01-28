<?php

namespace App\Filament\Resources\PengambilanBarangResource\Pages;

use App\Filament\Resources\PengambilanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengambilanBarang extends EditRecord
{
    protected static string $resource = PengambilanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
