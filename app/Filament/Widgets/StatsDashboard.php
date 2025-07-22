<?php

namespace App\Filament\Widgets;

use App\Models\Wisata;
use App\Models\Kategori;
use App\Models\Kriteria;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        // Menghitung jumlah total data dari masing-masing tabel
        $countWisata = Wisata::count(); //
        $countKriteria = Kriteria::count();
        $countKategori = Kategori::count();

        return [
            // Membuat statistik untuk setiap data
            Stat::make('Jumlah Data Wisata', $countWisata . ' wisata'), //
            Stat::make('Jumlah Kriteria Penilaian', $countKriteria . ' kriteria'),
            Stat::make('Jumlah Kategori Wisata', $countKategori . ' kategori'),
        ];
    }
}