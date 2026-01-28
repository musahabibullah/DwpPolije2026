<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Closure;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Akun';
    protected static ?string $pluralModelLabel = 'Akun';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label("Nama Pengguna")
                    ->required()
                    ->maxLength(255)
                    ->rules([
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                // Normalisasi: hapus semua karakter non-alphanumeric + spasi + lowercase
                                $normalizedName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $value));

                                $query = User::query()
                                    ->whereRaw('LOWER(REGEXP_REPLACE(name, "[^a-zA-Z0-9]", "")) = ?', [$normalizedName]);

                                if ($component->getRecord()) {
                                    $query->where('id', '!=', $component->getRecord()->id);
                                }

                                if ($query->exists()) {
                                    $fail('Nama sudah digunakan (termasuk versi dengan spasi/tanda baca/case berbeda)');
                                }
                            };
                        },
                    ]),

                Forms\Components\TextInput::make('email')
                    ->label("Email")
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->rules([
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                $normalizedEmail = strtolower(trim($value));

                                $query = User::query()
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

                Forms\Components\TextInput::make('password')
                    ->label("Password")
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255),

                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
            ])
            ->columns(1);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()
                    ->label("Nama Pengguna")
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()
                    ->label("Email")
                    ->searchable(),
                Tables\Columns\TagsColumn::make('roles.name')
                    ->label("Peran / Akses")
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Ditambahkan pada")
                    ->dateTime('d-M-Y')
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
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            //'create' => Pages\CreateUser::route('/create'),
            //'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'akun';
    }

    // app/Filament/Resources/UserResource.php
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')->required(),
            // ... field lain
            Forms\Components\Select::make('jurusans')
                ->multiple()
                ->relationship('jurusans', 'nama_jurusan')
                ->hidden(fn(): bool => !auth()->user()->hasRole('super_admin')),
        ];
    }

}