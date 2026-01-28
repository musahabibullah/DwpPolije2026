<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenerimaResource\Pages;
use App\Models\Penerima;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Closure;


class PenerimaResource extends Resource
{
    protected static ?string $model = Penerima::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Daftar Penerima';
    protected static ?string $pluralModelLabel = 'Daftar Penerima';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nik')
                ->label("NIP/NI PPPK/NIK")
                    ->required()
                    ->rules([
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                $normalizedNik = preg_replace('/\s+/', '', $value);

                                $query = Penerima::query()
                                    ->whereRaw('REPLACE(nik, " ", "") = ?', [$normalizedNik]);

                                if ($component->getRecord()) {
                                    $query->where('id', '!=', $component->getRecord()->id);
                                }

                                if ($query->exists()) {
                                    $fail('NIK sudah terdaftar');
                                }
                            };
                        },
                    ]),

                Forms\Components\TextInput::make('nama')
                ->label("Nama")
                    ->required()
                    ->rules([
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                // Normalisasi: hapus semua karakter non-alphanumeric + spasi + lowercase
                                $normalizedNama = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $value));

                                $query = Penerima::query()
                                    ->whereRaw('LOWER(REGEXP_REPLACE(nama, "[^a-zA-Z0-9]", "")) = ?', [$normalizedNama]);

                                if ($component->getRecord()) {
                                    $query->where('id', '!=', $component->getRecord()->id);
                                }

                                if ($query->exists()) {
                                    $fail('Nama sudah terdaftar (termasuk versi dengan spasi/tanda baca/case berbeda)');
                                }
                            };
                        },
                    ]),

                Forms\Components\TextInput::make('email')
                ->label("Email")
                    ->email()
                    ->required()
                    ->rules([
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                $normalizedEmail = strtolower(trim($value));

                                $query = Penerima::query()
                                    ->whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail]);

                                if ($component->getRecord()) {
                                    $query->where('id', '!=', $component->getRecord()->id);
                                }

                                if ($query->exists()) {
                                    $fail('Email sudah terdaftar');
                                }
                            };
                        },
                    ]),

                Forms\Components\TextInput::make('no_telpon')
                ->label("Nomor Telepon")
                    ->tel()
                    ->required()
                    ->rules([
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                $normalizedTelpon = preg_replace('/\D/', '', $value);

                                $query = Penerima::query()
                                    ->whereRaw('REPLACE(no_telpon, " ", "") = ?', [$normalizedTelpon]);

                                if ($component->getRecord()) {
                                    $query->where('id', '!=', $component->getRecord()->id);
                                }

                                if ($query->exists()) {
                                    $fail('Nomor telepon sudah terdaftar');
                                }
                            };
                        },
                    ]),
                Forms\Components\Select::make('jurusan_id')
                    ->relationship('jurusan', 'nama_jurusan')
                    ->label("Unit / Jurusan")
                    ->required(),
                Forms\Components\Select::make('jabatan_id')
                    ->relationship('jabatan', 'jabatan')
                    ->label("Jabatan")
                    ->required()
            ])
            ->columns(1);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                ->label("NIP/NI PPPK/NIK")
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                ->label("Nama")
                    ->searchable(),
                Tables\Columns\TextColumn::make('jurusan.nama_jurusan')
                ->label("Unit / Jurusan")
                ->searchable(),
                Tables\Columns\TextColumn::make('jabatan.jabatan')
                ->label("Jabatan")
                ->searchable(),

            ])
            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenerimas::route('/'),
           // 'create' => Pages\CreatePenerima::route('/create'),
            //'edit' => Pages\EditPenerima::route('/{record}/edit'),
        ];
    }
}