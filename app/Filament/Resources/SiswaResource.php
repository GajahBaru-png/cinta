<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
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
            TextInput::make('kontak')->required(),
            TextInput::make('email')->email()->required()->unique(),
            Select::make('status_pkl')
                ->options([0 => 'Belum diterima PKL', 1 => 'Sudah diterima PKL'])
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
                TextColumn::make('kontak')->label('Kontak'),
                TextColumn::make('email')->label('E-mail'),
                TextColumn::make('status_pkl')
                    ->label('Status PKL')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Terverifikasi' : 'Belum Terverifikasi'),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
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
