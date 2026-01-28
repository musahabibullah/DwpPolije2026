<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengambilanBarangResource\Pages;
use App\Models\PengambilanBarang;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengambilanBarangExport;
use Illuminate\Database\Eloquent\Collection;

class PengambilanBarangResource extends Resource
{
    protected static ?string $model = PengambilanBarang::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Pengambilan Barang';
    protected static ?string $pluralModelLabel = 'Pengambilan Barang';
    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                PengambilanBarang::query()
                    ->whereHas('validasiPenerimaan', function ($query) {
                        $query->where('status', 'berhasil_di_validasi');
                    })
                    ->with(['validasiPenerimaan.verifikasiPembayaran.penerima'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('validasiPenerimaan.verifikasiPembayaran.penerima.nik')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('validasiPenerimaan.verifikasiPembayaran.penerima.nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('validasiPenerimaan.verifikasiPembayaran.penerima.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('validasiPenerimaan.verifikasiPembayaran.penerima.no_telpon')
                    ->label('No. Telpon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('validasiPenerimaan.verifikasiPembayaran.penerima.jurusan.meja_pengambilan')
                    ->label("Meja")
                    ->searchable(),
                Tables\Columns\TextColumn::make('validasiPenerimaan.kode_unik')
                    ->label('Barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pengambilan')
                    ->label('Tanggal Pengambilan')
                    ->dateTime('d/m/Y H:i')
                    ->visible(function (?PengambilanBarang $record): bool {
                        return $record?->status === 'sudah_diambil';
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'barang_diantar' => 'success', // Warna hijau untuk "Barang Diantar"
                        'sudah_diambil' => 'success', // Warna hijau untuk "Sudah Diambil"
                        'belum_diambil' => 'warning',  // Warna kuning untuk "Belum Diambil"
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'barang_diantar' => 'Barang Diantar',
                        'sudah_diambil' => 'Sudah Diambil',
                        'belum_diambil' => 'Belum Diambil',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'barang_diantar' => 'Barang Diantar',
                        'sudah_diambil' => 'Sudah Diambil',
                        'belum_diambil' => 'Belum Diambil',
                    ])
                    ->label('Filter Berdasarkan Status')
                    ->placeholder('Semua Status')
            ])
            ->actions([
                Action::make('konfirmasi_pengambilan')
                    ->label(function (PengambilanBarang $record): string {
                        return $record->status === 'barang_diantar'
                            ? 'Barang Diantar'
                            : ($record->status === 'sudah_diambil' ? 'Sudah Diambil' : 'Konfirmasi');
                    })
                    ->color(function (PengambilanBarang $record): string {
                        return $record->status === 'barang_diantar' || $record->status === 'sudah_diambil'
                            ? 'success' // Warna hijau untuk "Barang Diantar" dan "Sudah Diambil"
                            : 'warning'; // Warna kuning untuk "Belum Diambil"
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pengambilan Barang')
                    ->modalDescription(function (PengambilanBarang $record): string {
                        if ($record->status === 'barang_diantar') {
                            return 'Barang telah diantar.';
                        }
                        if ($record->status === 'sudah_diambil') {
                            return 'Barang telah diambil.';
                        }
                        return 'Apakah Anda yakin ingin mengkonfirmasi pengambilan barang ini?';
                    })
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->disabled(function (PengambilanBarang $record): bool {
                        return $record->status === 'barang_diantar' || $record->status === 'sudah_diambil';
                    })
                    ->action(function (PengambilanBarang $record): void {
                        $record->update([
                            'status' => 'sudah_diambil',
                        ]);
                    }),
            ])
            ->headerActions([
                Action::make('exportAll')
                    ->label('Export ke Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return Excel::download(
                            new PengambilanBarangExport(),
                            'data-pengambilan-barang' . now()->format('Y-m-d') . '.xlsx'
                        );
                    })
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengambilanBarangs::route('/'),
        ];
    }
}