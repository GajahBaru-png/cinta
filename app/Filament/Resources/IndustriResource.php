<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndustriResource\Pages;
use App\Filament\Resources\IndustriResource\RelationManagers;
use App\Models\Industri;
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

class IndustriResource extends Resource
{
    protected static ?string $model = Industri::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Menu';

    public static function form(Form $form): Form
    {
         return $form
            ->schema([
                TextInput::make('nama')->required(),
                Textarea::make('bidang_usaha')->required(),
                Textarea::make('alamat')->required(),
                TextInput::make('kontak'),
                TextInput::make('email')->email(),
                Select::make('guru_pembimbing')
                    ->relationship('guru', 'nama')
                    ->label('Guru Pembimbing'),
            ]);

    }

    public static function table(Table $table): Table
    {
         return $table
            ->columns([
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('bidang_usaha')->label('Bidang')->limit(25),
                TextColumn::make('alamat')->label('Alamat')->limit(25),
                TextColumn::make('kontak')->label('Kontak'),
                TextColumn::make('email')->label('E-mail'),
                TextColumn::make('guru.nama')->label('Guru Pembimbing'),

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
            'index' => Pages\ListIndustris::route('/'),
            'create' => Pages\CreateIndustri::route('/create'),
            'edit' => Pages\EditIndustri::route('/{record}/edit'),
        ];
    }
}
