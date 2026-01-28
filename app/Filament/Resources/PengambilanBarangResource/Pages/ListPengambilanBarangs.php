<?php

namespace App\Filament\Resources\PengambilanBarangResource\Pages;

use App\Filament\Resources\PengambilanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengambilanBarangs extends ListRecords
{
    protected static string $resource = PengambilanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
