<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
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
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('nama')->required(),
            TextInput::make('nis')->required()->unique(),
            Select::make('gender')
                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                ->required(),
            Textarea::make('alamat')->required(),
            TextInput::make('kontak')->required()
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
            TextInput::make('email')->email()->required()->unique(),
            Toggle::make('status_pkl')
                // ->options([0 => 'Belum diterima PKL', 1 => 'Sudah diterima PKL'])
                ->required(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('nis')->label('NIS'),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => 'Tidak Diketahui',
                    }),
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
                ToggleColumn::make('status_pkl')
                    ->label('Status PKL')
                    ->sortable()
                    // ->formatStateUsing(fn ($state) => $state == 1 ? 'Terverifikasi' : 'Belum Terverifikasi'),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('deleteSelected')
                    ->label('Hapus yang Belum PKL')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $recordsToDelete = $records->filter(function ($record) {
                            return $record->status_pkl == 0;
                        });

                        $recordsToDelete->each->delete();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
