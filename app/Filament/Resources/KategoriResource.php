<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Illuminate\Database\Eloquent\Model; // <-- PENTING: Import Model
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Pest\Laravel\options;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Kategori';
    protected static ?string $modelLabel = 'Data Kategori';
    protected static ?string $pluralModelLabel = 'Data Kategori';
    protected static ?string $slug = 'Data Kategori';

    /**
     * Menonaktifkan fungsionalitas 'Create' secara global.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Menonaktifkan fungsionalitas 'Edit' secara global.
     * INI ADALAH SOLUSI UTAMA untuk masalah klik dua kali.
     */
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    /**
     * (Rekomendasi) Menonaktifkan fungsionalitas 'Delete' secara global.
     */
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->options([
                        'sejarah' => 'Sejarah',
                        'religi' => 'Religi',
                        'alam' => 'Alam',
                        'keluarga' => 'Keluarga',
                    ])
                    ->required()
                    ->unique(ignoreRecord: true) // Mencegah duplikat
                    ->validationMessages(['unique' => 'Kategori ini sudah ada.']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kategori')->label('Nama Kategori')
                    ->formatStateUsing(function ($state) {
                        $mapping = [
                            'sejarah' => 'Sejarah',
                            'religi' => 'Religi',
                            'alam' => 'Alam',
                            'keluarga' => 'Keluarga',
                        ];
                        return $mapping[$state] ?? $state;
                    }),
            ])
            // Mengosongkan action secara eksplisit, karena sudah diatur oleh canEdit dan canDelete
            ->actions([])
            ->bulkActions([]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoris::route('/'),
            // Halaman create dan edit tidak akan bisa diakses karena ada canCreate() dan canEdit()
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
}