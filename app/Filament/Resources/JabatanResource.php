<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JabatanResource\Pages;
use App\Models\Jabatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Closure;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Jabatan';
    protected static ?string $pluralModelLabel = 'Jabatan';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jabatan')
                    ->label("Nama Jabatan")
                    ->required()
                    ->rules([
                        'required',
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                $normalizedValue = strtolower(str_replace(' ', '', $value));

                                $query = Jabatan::query()
                                    ->whereRaw('LOWER(REPLACE(jabatan, " ", "")) = ?', [$normalizedValue]);

                                if ($component->getRecord()) {
                                    $query->where('id', '!=', $component->getRecord()->id);
                                }

                                if ($query->exists()) {
                                    $fail('Jabatan ini sudah terdaftar');
                                }
                            };
                        },
                    ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jabatan')
                    ->label("Nama Jabatan")
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
            'index' => Pages\ListJabatans::route('/'),
            //'create' => Pages\CreateJabatan::route('/create'),
            //'edit' => Pages\EditJabatan::route('/{record}/edit'),
        ];
    }
}