<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KriteriaResource\Pages;
use App\Filament\Resources\KriteriaResource\RelationManagers;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KriteriaResource extends Resource
{
    protected static ?string $model = Kriteria::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Kriteria';
    protected static ?string $modelLabel = 'Data Kriteria';
    protected static ?string $pluralModelLabel = 'Data Kriteria'; // Menghindari "Posts"
    protected static ?string $slug = 'Data Kriteria'; // Menghindari "posts"

    /**
     * Metode ini akan menonaktifkan tombol dan halaman "Create".
     */
    public static function canCreate(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('nama_kriteria')
                    ->label('Nama Kriteria')
                    ->options([
                        'jarak' => 'Jarak',
                        'harga_tiket' => 'Harga Tiket Masuk',
                        'jumlah_fasilitas' => 'Jumlah fasilitas',
                        'ulasan' => 'Ulasan',
                        'waktu_operasional' => 'Durasi Operasional',
                        'aksesibilitas' => 'Aksesibilitas',

                    ])
                    ->required()
                    ->unique(ignoreRecord: true) // Mencegah duplikat
                    ->validationMessages(['unique' => 'Kategori ini sudah ada.']),


                Select::make('tipe') // 'type' is the database column name
                    ->label('tipe kriteria')
                    ->options([
                        'cost' => 'Cost',
                        'benefit' => 'Benefit',
                    ])
                    ->default('cost') // Optional default value
                    ->searchable() // Optional
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kriteria')
                    ->label('Nama Kriteria')
                    ->formatStateUsing(function ($state) {
                        $mapping = [
                            'jarak' => 'Jarak',
                            'harga_tiket' => 'Harga Tiket Masuk',
                            'jumlah_fasilitas' => 'Jumlah Fasilitas',
                            'ulasan' => 'Ulasan',
                            'waktu_operasional' => 'Durasi Operasional',
                            'aksesibilitas' => 'Aksesibilitas',
                        ];
                        return $mapping[$state] ?? $state;
                    }),

                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // ])
                // ->bulkActions([
                //     Tables\Actions\BulkActionGroup::make([
                //         Tables\Actions\DeleteBulkAction::make(),
                //     ]),
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
            'index' => Pages\ListKriterias::route('/'),
            'create' => Pages\CreateKriteria::route('/create'),
            'edit' => Pages\EditKriteria::route('/{record}/edit'),
        ];
    }
}
