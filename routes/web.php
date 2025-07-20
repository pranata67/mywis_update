<?php
use App\Filament\Pages\HomePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferensiController;
use App\Http\Controllers\CalculateController;
use App\Http\Controllers\WisataController;

Route::get('/', function () {
    return view('landing');
});

Route::get('home', HomePage::class)->name('home');
Route::get('/preferensi', [PreferensiController::class, 'index'])->name('preferensi');
Route::post('/calculate', [CalculateController::class, 'calculate'])->name('calculate');
Route::get('/hasil', [CalculateController::class, 'hasil'])->name('hasil');
Route::get('/wisata/{wisata}', [WisataController::class, 'show'])->name('wisata.show');
