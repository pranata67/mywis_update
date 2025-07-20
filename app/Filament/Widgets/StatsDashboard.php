<?php

namespace App\Filament\Widgets;

use App\Models\Wisata;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $countWisata = Wisata::count(); // Menghitung jumlah total data wisata dari tabel 'wisatas'
        return [
            Stat::make('Jumlah Data Wisata', $countWisata . ' wisata'), // Membuat statistik jumlah wisata dengan format "XX wisata"
            
        ];
    }
}
