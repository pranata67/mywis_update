<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Wisata;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
// Hapus ViewField karena kita akan pakai View
// use Filament\Forms\Components\ViewField; 
use Filament\Forms\Components\View; // Gunakan ini
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\WisataResource\Pages;

class WisataResource extends Resource
{
    protected static ?string $model = Wisata::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationLabel = 'Data Wisata';
    protected static ?string $modelLabel = 'Data Wisata';
    protected static ?string $pluralModelLabel = 'Data Wisata'; // Menghindari "s"
    protected static ?string $slug = 'Data Wisata'; // Menghindari "s"

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Ganti ViewField menjadi View dan hubungkan dengan state 'coordinates'
            View::make('filament.components.mapadmin')
                ->label('Lokasi di Peta')
                // StatePath ini penting untuk menghubungkan view ke data field 'coordinates'
                ->statePath('coordinates')
                ->columnSpanFull(),

            TextInput::make('name')
                ->label('Nama Wisata')
                ->required(),

            Select::make('id_kategori')
                // ... (definisi select tetap sama)
                ->label('Kategori Wisata')
                // Manipulasi agar ketika options kategori muncul uppercase
                ->options(function () {
                    return Kategori::all()->mapWithKeys(function ($kategori) {
                        return [$kategori->id => ucfirst($kategori->nama_kategori)];
                    });
                })
                ->searchable()
                ->required()
                ->default(1)
                ->preload(),

            TextInput::make('coordinates')
                ->label('Koordinat')
                ->required()
                // Hapus ->id('coordinates_input'), sudah tidak diperlukan
                ->rule('regex:/^-?\d{1,2}(\.\d+)?,\s*-?\d{1,3}(\.\d+)?$/')
                ->helperText('Gunakan format: latitude, longitude (contoh: -7.12345, 110.98765)')
                ->columnSpanFull()
                // Tetap gunakan live() agar perubahan di text input langsung update peta
                ->live(),

            // ... (field lainnya tetap sama)
            TextInput::make('deskripsi')
                ->label('Deskripsi')
                ->nullable()
                ->columnSpanFull(6),

            FileUpload::make('image')
                ->label('Gambar')
                ->image()
                ->directory('wisata-images')
                ->columnSpanFull()
                ->multiple()
                ->preserveFilenames()
                ->imagePreviewHeight('250'),

            TextInput::make('harga_tiket')
                ->label('Harga Tiket Masuk')
                ->numeric()
                ->minValue(0)
                ->helperText('Gunakan harga tiket masuk dalam format angka: 10000')
                ->prefix('Rp.')
                ->required(),

            TextInput::make('jumlah_fasilitas')
                ->numeric()
                ->label('Jumlah Fasilitas')
                ->helperText('Masukan total jumlah fasilitas yang tersedia dalam angka (contoh: 5)')
                ->minValue(0)
                ->required(),

            TextInput::make('ulasan')
                ->label('Ulasan')
                ->numeric()
                ->step(0.1) // Memungkinkan input angka desimal
                ->minValue(0)
                ->maxValue(5)
                ->helperText('Masukan ulasan dalam skala 0-5 (contoh: 4,5)')
                ->required(),

            TextInput::make('waktu_operasional')
                ->label('Waktu Operasional')
                ->helperText('Masukan waktu operasional (contoh: 8 Jam)')
                ->numeric()
                ->suffix('Jam')
                ->minValue(1)
                ->maxValue(24)
                ->required(),

            Select::make('aksesibilitas')
                ->label('Aksesibilitas')
                // Sediakan daftar pilihan Anda di sini
                // Formatnya adalah [nilai_yang_disimpan => 'Teks Yang Ditampilkan']
                ->options([
                    5 => 'Bus',
                    4 => 'Minibus',
                    3 => 'Mobil',
                    2 => 'Sepeda Motor',
                    1 => 'Pejalan kaki',
                ])
                ->searchable() // Opsional, berguna jika daftar panjang
                ->required()
                ->helperText('Pilih tingkat aksesibilitas kendaraan yang paling sesuai.'),

            TextInput::make('link_gmaps')
                ->label('Link Google Maps')
                ->url()
                ->helperText('Masukan link Google Maps untuk lokasi wisata'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nama Wisata')->sortable()->searchable(),
            TextColumn::make('kategori.nama_kategori')->label('Kategori')->searchable()->sortable()
                ->formatStateUsing(function ($state) {
                    $mapping = [
                        'sejarah' => 'Sejarah',
                        'religi' => 'Religi',
                        'alam' => 'Alam',
                        'aksesbilitas' => 'Aksesibilitas',
                    ];
                    return $mapping[$state] ?? $state;
                }),
            TextColumn::make('coordinates')->label('Koordinat'),
            TextColumn::make('deskripsi')->label('Deskripsi')
                ->words(5),

            ImageColumn::make('image')
                ->label('Gambar')
                ->size(50),

            TextColumn::make('harga_tiket')->label('Harga Tiket Masuk')
                ->formatStateUsing(function ($state) {
                    return 'Rp. ' . number_format($state, 0, ',', '.');
                }),
            TextColumn::make('jumlah_fasilitas')->label('Fasilitas'),
            TextColumn::make('ulasan')->label('Ulasan'),
            TextColumn::make('waktu_operasional')->label('waktu operasional'),
            TextColumn::make('aksesibilitas')->label('Aksesibilitas')
                ->formatStateUsing(function (string $state): string {
                    $keterangan = [
                        5 => 'Bus',
                        4 => 'Minibus',
                        3 => 'Mobil',
                        2 => 'Sepeda Motor',
                        1 => 'Pejalan kaki',
                    ];

                    return $keterangan[$state] ?? $state;
                }),
            TextColumn::make('link_gmaps')->label('link gmaps'),


        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWisatas::route('/'),
            'create' => Pages\CreateWisata::route('/create'),
            'edit' => Pages\EditWisata::route('/{record}/edit'),
        ];
    }
}
