<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required(),
                TextInput::make('nip')->required()->unique()->label('NIP'),
                Select::make('gender')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                    ->required(),
                Textarea::make('alamat')->required(),
                TextInput::make('kontak')
                ->formatStateUsing(function ($state) {
                        if (!$state) return null;

                        // Jika sudah ada +62, kembalikan seperti semula
                        if (str_starts_with($state, '+62')) {
                            return $state;
                        }

                        // Jika dimulai dengan 0, hapus 0 dan tambah +62
                        if (str_starts_with($state, '0')) {
                            return '+62' . substr($state, 1);
                        }

                        // Jika tidak dimulai dengan 0 atau +62, tambah +62
                        return '+62' . $state;
                    })
                    ->dehydrateStateUsing(function ($state) {
                        if (!$state) return null;

                        // Jika sudah ada +62, kembalikan seperti semula
                        if (str_starts_with($state, '+62')) {
                            return $state;
                        }

                        // Jika dimulai dengan 0, hapus 0 dan tambah +62
                        if (str_starts_with($state, '0')) {
                            return '+62' . substr($state, 1);
                        }

                        // Jika tidak dimulai dengan 0 atau +62, tambah +62
                        return '+62' . $state;
                    }),
                TextInput::make('email')->email(),
            ]);
            

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('nip')->label('NIP'),
                TextColumn::make('alamat')->label('Alamat')->limit(25),
                TextColumn::make('kontak')->label('Kontak')
                ->formatStateUsing(function ($state) {
                        if (!$state) return null;

                        // Tampilkan format yang lebih readable
                        if (str_starts_with($state, '+62')) {
                            // Ubah +62812345678 menjadi +62 812-345-678
                            return $state;
                        }

                        // Jika dimulai dengan 0, hapus 0 dan tambah +62
                        if (str_starts_with($state, '0')) {
                            return '+62' . substr($state, 1);
                        }

                        // Jika tidak dimulai dengan 0 atau +62, tambah +62
                        return '+62' . $state;
                    }),
                TextColumn::make('email')->label('E-mail'),
                TextColumn::make('gender')
                    ->label('Kelamin')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => 'Tidak Diketahui',
                    }),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}
