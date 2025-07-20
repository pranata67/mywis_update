<?php

namespace App\Models;
use App\Models\Wisata;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
        protected $fillable = [
        'nama_kategori',
    ];

    public function wisata()
{
    return $this->hasMany(Wisata::class, 'id_kategori');
}
}


