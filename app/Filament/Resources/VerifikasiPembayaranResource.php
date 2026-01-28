<?php

namespace App\Filament\Resources;

use App\Exports\VerifikasiPembayaranExport;
use App\Filament\Resources\VerifikasiPembayaranResource\Pages;
use App\Models\Penerima;
use App\Models\VerifikasiPembayaran;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Http\Request;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\VerifikasiPembayaranController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
//use App\Filament\Resources\Storage;
use Illuminate\Support\Facades\Storage;
use FPDF;

class VerifikasiPembayaranResource extends Resource
{
    protected static ?string $model = VerifikasiPembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Verifikasi Pembayaran';
    protected static ?string $pluralModelLabel = 'Verifikasi Pembayaran';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('penerima_id')
                    ->label('Penerima')
                    ->relationship(
                        name: 'penerima',
                        titleAttribute: 'nama',
                        modifyQueryUsing: fn(Builder $query) => $query
                            ->whereDoesntHave('verifikasiPembayarans', function ($query) {
                                $query->where('status', '!=', 'ditolak');
                            })
                            ->orWhereHas('verifikasiPembayarans', function ($query) {
                                $query->where('status', 'ditolak');
                            })
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\FileUpload::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->required()
                    ->disk('private'),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'belum_diverifikasi' => 'Belum Diverifikasi',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
                    ->default('belum_diverifikasi')
                    ->required(),

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('penerima.nik')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penerima.nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penerima.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penerima.no_telpon')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'belum_diverifikasi' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'belum_diverifikasi' => 'Belum Diverifikasi',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        default => $state,
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'belum_diverifikasi' => 'Belum Diverifikasi',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
                    ->label('Status Verifikasi')
                    ->placeholder('Filter by Status')
            ])
            ->actions([
                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-m-eye')
                    ->modalWidth('xl')
                    ->modalHeading('Verifikasi Pembayaran')
                    ->visible(fn(VerifikasiPembayaran $record): bool => $record->status === 'belum_diverifikasi')
                    ->form([
                        Section::make()
                            ->schema([
                                Placeholder::make('bukti_pembayaran')
                                    ->label('Bukti Pembayaran')
                                    ->content(function (VerifikasiPembayaran $record): HtmlString {
                                        $imageUrl = route('private.file.show', ['filename' => $record->bukti_pembayaran]);
                                        $downloadUrl = route('private.file.download', ['filename' => $record->bukti_pembayaran]);

                                        return new HtmlString('
                                            <div class="space-y-4">
                                                <div class="flex justify-center">
                                                    <img 
                                                        src="' . $imageUrl . '" 
                                                        alt="Bukti Pembayaran" 
                                                        class="max-w-full h-auto rounded-lg shadow-lg" 
                                                        style="max-height: 400px;"
                                                    />
                                                </div>
                                                <div class="flex justify-center">
                                                    <a 
                                                        href="' . $downloadUrl . '" 
                                                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                                    >
                                                        Download Bukti Pembayaran
                                                    </a>
                                                </div>
                                            </div>
                                        ');
                                    }),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'diterima' => 'Diterima',
                                        'ditolak' => 'Ditolak',
                                    ])
                                    ->required()
                                    ->live(),
                                    Forms\Components\Textarea::make('alasan_ditolak')
                                    ->label('Alasan Ditolak')
                                    ->placeholder('Masukkan alasan mengapa pembayaran ditolak')
                                    ->helperText('Alasan ini akan dikirimkan ke pengguna melalui WhatsApp')
                                    ->required(fn (Get $get): bool => $get('status') === 'ditolak')
                                    ->visible(fn (Get $get): bool => $get('status') === 'ditolak'),
                            ]),
                    ])

                    ->action(function (VerifikasiPembayaran $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                            'alasan_ditolak' => $data['status'] === 'ditolak' ? $data['alasan_ditolak'] : null,
                        ]);

                        if ($data['status'] === 'diterima') {
                            // Kirim email dan WhatsApp untuk konfirmasi pembayaran diterima
                            $controller = new VerifikasiPembayaranController();
                            $controller->kirimVerifikasi(new Request(), $record);
                        } else if ($data['status'] === 'ditolak') {
                            // Kirim WhatsApp untuk notifikasi penolakan
                            $controller = new VerifikasiPembayaranController();
                            $controller->kirimPenolakan(new Request(), $record, $data['alasan_ditolak']);
                        }
                    }),

                Tables\Actions\Action::make('lihat_bukti')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('xl')
                    ->modalHeading('Bukti Pembayaran')
                    ->visible(fn(VerifikasiPembayaran $record): bool => $record->status !== 'belum_diverifikasi')
                    ->modalContent(function (VerifikasiPembayaran $record): HtmlString {
                        $imageUrl = route('private.file.show', ['filename' => $record->bukti_pembayaran]);
                        $downloadUrl = route('private.file.download', ['filename' => $record->bukti_pembayaran]);

                        return new HtmlString('
                            <div class="space-y-4">
                                <div class="flex justify-center">
                                    <img 
                                        src="' . $imageUrl . '" 
                                        alt="Bukti Pembayaran" 
                                        class="max-w-full h-auto rounded-lg shadow-lg" 
                                        style="max-height: 400px;"
                                    />
                                </div>
                                <div class="flex justify-center">
                                    <a 
                                        href="' . $downloadUrl . '" 
                                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                    >
                                        Download Bukti Pembayaran
                                    </a>
                                </div>
                            </div>
                        ');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                    Tables\Actions\Action::make('download_pdf')
                        ->label('Download Invoice')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('primary')
                        ->action(function (VerifikasiPembayaran $record): BinaryFileResponse {
                            // Generate PDF filename and path
                            $pdfFilename = 'invoice_' . $record->penerima->nik . '_' . now()->timestamp . '.pdf';
                            $pdfFolder = 'invoices';
                            
                            // Ensure the directory exists
                            Storage::disk('public')->makeDirectory($pdfFolder);
                            
                            // Path for saving PDF
                            $pdfPath = storage_path('app/public/' . $pdfFolder . '/' . $pdfFilename);
                            
                            // Set up data variables
                            $jurusan = $record->penerima->jurusan ? $record->penerima->jurusan->nama_jurusan : 'N/A';
                            $currentTime = now()->format('d-m-Y H:i:s');
                            $transactionCode = $record->kode_transaksi ?? rand(1000000, 9999999);
                                
                            // Create PDF with FPDF
                            $pdf = new FPDF();
                            $pdf->AddPage();

                            // Add background image
                            $backgroundPath = public_path('images/buktipembayaran.png');
                            if (file_exists($backgroundPath)) {
                                $pdf->Image($backgroundPath, 0, 0, 210, 297);
                            } else {
                                \Log::error("Background image tidak ditemukan di: " . $backgroundPath);
                            }

                            $marginLeft = 20;
                            
                            // Document title
                            $pdf->SetFont('Arial', 'B', 16);
                            $pdf->Cell(0, 10, '', 0, 1, 'C');
                            $pdf->Ln(65);
                            
                            // Transaction information
                            $pdf->SetX($marginLeft);
                            $pdf->SetFont('Arial', 'B', 15);
                            $pdf->Cell(50, 10, 'Nomor Transaksi:', 0);
                            $pdf->SetFont('Arial', '', 15);
                            $pdf->Cell(0, 10, $transactionCode, 0, 1);
                            
                            $pdf->SetX($marginLeft);
                            $pdf->SetFont('Arial', 'B', 15);
                            $pdf->Cell(50, 10, 'Waktu Transaksi:', 0);
                            $pdf->SetFont('Arial', '', 15);
                            $pdf->Cell(0, 10, $currentTime, 0, 1);
                            
                            $pdf->SetX($marginLeft);
                            $pdf->SetFont('Arial', 'B', 15);
                            $pdf->Cell(50, 10, 'Nama:', 0);
                            $pdf->SetFont('Arial', '', 15);
                            $pdf->Cell(0, 10, $record->penerima->nama, 0, 1);
                            
                            $pdf->SetX($marginLeft);
                            $pdf->SetFont('Arial', 'B', 15);
                            $pdf->Cell(50, 10, 'Jurusan:', 0);
                            $pdf->SetFont('Arial', '', 15);
                            $pdf->Cell(0, 10, $jurusan, 0, 1);

                            $pdf->Ln(10);
                            
                            // Save PDF
                            $pdf->Output('F', $pdfPath);

                            // Check if file was created successfully
                            if (!file_exists($pdfPath)) {
                                \Log::error('Gagal membuat PDF: ' . $pdfPath);
                                throw new \Exception('Gagal membuat PDF');
                            }

                            // Return the PDF for download
                            return response()->download($pdfPath, $pdfFilename, [
                                'Content-Type' => 'application/pdf',
                            ]);
                        }),

                Tables\Actions\DeleteAction::make()
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export ke Excel')
                    ->color('warning')
                    ->icon('heroicon-o-document-arrow-down')
                    // Di headerActions()
                    ->action(function (array $data) {
                        $filename = 'rekap-verifikasi-pembayaran.xlsx';

                        if ($data['status'] === 'belum_bayar') {
                            $filename = 'belum-melakukan-pembayaran.xlsx';
                        }

                        return Excel::download(
                            new VerifikasiPembayaranExport(
                                ['status' => $data['status']],
                                auth()->user() // Kirim user yang login
                            ),
                            $filename
                        );
                    })
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status Verifikasi')
                            ->options([
                                'belum_bayar' => 'Belum melakukan pembayaran',
                                'belum_diverifikasi' => 'Belum di verifikasi',
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerifikasiPembayarans::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('bendahara')) {
            $query->forBendahara(auth()->user());
        }

        return $query;
    }
}