<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PKLResource\Pages;
use App\Filament\Resources\PKLResource\RelationManagers;
use App\Models\PKL;
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
use Filament\Forms\Components\DatePicker;

class PKLResource extends Resource
{
    protected static ?string $model = PKL::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('siswa_id')
                    ->relationship('siswa', 'nama')
                    ->label('Nama Siswa')
                    ->required(),
                Select::make('industri_id')
                    ->relationship('industri', 'nama')
                    ->required(),
                Select::make('guru_id')
                    ->relationship('guru', 'nama')
                    ->label('Nama Guru Pembimbing')
                    ->required(),
                DatePicker::make('mulai')->required()->label('Tanggal Mulai'),
                DatePicker::make('selesai')->label('Tanggal Selesai'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')->label('Nama'),
                TextColumn::make('industri.nama')->label('Industri'),
                TextColumn::make('guru.nama')->label('Guru Pembimbing'),
                TextColumn::make('mulai')->label('Tanggal Mulai'),
                TextColumn::make('selesai')->label('Tanggal Selesai'),


                //
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
            'index' => Pages\ListPKLS::route('/'),
            'create' => Pages\CreatePKL::route('/create'),
            'edit' => Pages\EditPKL::route('/{record}/edit'),
        ];
    }
}
