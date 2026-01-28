<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValidasiPenerimaanResource\Pages;
use App\Models\ValidasiPenerimaan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Models\Jabatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ValidasiPenerimaanExport;
use Illuminate\Database\Eloquent\Collection;

class ValidasiPenerimaanResource extends Resource
{
    protected static ?string $model = ValidasiPenerimaan::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Validasi Penerima';
    protected static ?string $pluralModelLabel = 'Validasi Penerima';
    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                ValidasiPenerimaan::query()
                    ->whereHas('verifikasiPembayaran', function (Builder $query) {
                        $query->where('status', 'diterima');
                    })
                    ->with(['verifikasiPembayaran.penerima'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('verifikasiPembayaran.penerima.nik')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('verifikasiPembayaran.penerima.nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('verifikasiPembayaran.penerima.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('verifikasiPembayaran.penerima.no_telpon')
                    ->label('No. Telpon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('verifikasiPembayaran.penerima.jurusan.meja_pengambilan')
                    ->label("Meja")
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_unik')
                    ->label('Barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'berhasil_di_validasi' => 'success',
                        'belum_di_validasi' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'berhasil_di_validasi' => 'Berhasil di validasi',
                        'belum_di_validasi' => 'Belum di validasi',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'berhasil_di_validasi' => 'Berhasil di validasi',
                        'belum_di_validasi' => 'Belum di validasi',
                    ])
                    ->label('Filter Berdasarkan Status')
                    ->placeholder('Semua Status')
            ])
            ->actions([
                Action::make('konfirmasi')
                    ->label(
                        fn(ValidasiPenerimaan $record): string =>
                        $record->status === 'berhasil_di_validasi'
                        ? 'Terverifikasi'
                        : 'Konfirmasi'
                    )
                    ->color(
                        fn(ValidasiPenerimaan $record): string =>
                        $record->status === 'berhasil_di_validasi'
                        ? 'success'
                        : 'warning'
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Validasi')
                    ->modalDescription(
                        fn(ValidasiPenerimaan $record): string =>
                        $record->status === 'berhasil_di_validasi'
                        ? "Penerimaan telah divalidasi. Silahkan ke meja " .
                        $record->verifikasiPembayaran->penerima->jabatan->meja_pengambilan
                        : 'Apakah Anda yakin ingin mengkonfirmasi penerimaan ini?'
                    )
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->disabled(
                        fn(ValidasiPenerimaan $record): bool =>
                        $record->status === 'berhasil_di_validasi'
                    )
                    ->action(function (ValidasiPenerimaan $record): void {
                        $record->update([
                            'status' => 'berhasil_di_validasi',
                        ]);
                    }),
            ])
            ->headerActions([
                Action::make('exportAll')
                    ->label('Export ke Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return Excel::download(
                            new ValidasiPenerimaanExport(), 
                            'semua-validasi-penerimaan-' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d') . '.xlsx'
                        );
                    })
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListValidasiPenerimaans::route('/'),
        ];
    }
}