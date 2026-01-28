<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurusanResource\Pages;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Closure; // Pastikan ini di-import

class JurusanResource extends Resource
{
    protected static ?string $model = Jurusan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Unit / Jurusan';
    protected static ?string $pluralModelLabel = 'Unit / Jurusan';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_jurusan')
                    ->label("Nama Unit / Jurusan")
                    ->required()
                    ->rules([
                        'required',
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                $normalizedValue = strtolower(str_replace(' ', '', $value));

                                $query = Jurusan::query()
                                    ->whereRaw('LOWER(REPLACE(nama_jurusan, " ", "")) = ?', [$normalizedValue]);

                                if ($component->getRecord()) {
                                    $query->where('id', '!=', $component->getRecord()->id);
                                }

                                if ($query->exists()) {
                                    $fail('Jurusan/Unit ini sudah terdaftar');
                                }
                            };
                        },
                    ]),

                Forms\Components\Select::make('meja_pengambilan')
                    ->label("Meja Pengambilan")
                    ->options([
                        '1' => 'Meja 1',
                        '2' => 'Meja 2',
                        '3' => 'Meja 3',
                        '4' => 'Meja 4',
                    ])
                    ->required(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_jurusan')
                    ->label("Nama Unit / Jurusan")
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
            'index' => Pages\ListJurusans::route('/'),
            //'create' => Pages\CreateJurusan::route('/create'),
            //'edit' => Pages\EditJurusan::route('/{record}/edit'),
        ];
    }
}