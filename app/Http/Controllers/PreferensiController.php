<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Kriteria;
use App\Models\Wisata;

class PreferensiController extends Controller
{
     public function index()
    {
        $kategoris = Kategori::all();
        
        // Ambil kriteria dan beri bobot tetap
        $kriterias = Kriteria::all();
        $wisatas = Wisata::all();
        
        // ->map(function($item, $index) {
        //     // Assign bobot tetap sesuai urutan
        //     $fixedWeights = [35, 25, 15, 15, 10];
        //     $item->bobot = $fixedWeights[$index] ?? 0;
        //     return $item;
        // });

        return view('preferensi', compact('kategoris', 'kriterias', 'wisatas'));
    }

    public function hasil(Request $request)
    {
        return view('hasil', [
            'koordinat' => $request->koordinat,
            'kategori' => $request->kategori,
            'kriterias' => $request->kriteria_order
        ]);
    }
}