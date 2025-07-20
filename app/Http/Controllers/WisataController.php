<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    /**
     * Menampilkan halaman detail untuk satu tempat wisata.
     *
     * @param  \App\Models\Wisata  $wisata
     * @return \Illuminate\View\View
     */
    public function show(Wisata $wisata)
    {
        // Mengirim data wisata yang ditemukan ke view 'wisata-detail'
        return view('wisata-detail', compact('wisata'));
    }
}