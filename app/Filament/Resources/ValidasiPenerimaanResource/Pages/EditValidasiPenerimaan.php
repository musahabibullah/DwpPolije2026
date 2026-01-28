<?php

namespace App\Filament\Resources\ValidasiPenerimaanResource\Pages;

use App\Filament\Resources\ValidasiPenerimaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValidasiPenerimaan extends EditRecord
{
    protected static string $resource = ValidasiPenerimaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
