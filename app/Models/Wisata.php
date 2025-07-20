<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class Wisata extends Model
{
    use HasFactory; // Menggunakan trait HasFactory untuk memungkinkan pembuatan instance model dengan factory.

    // Mendefinisikan atribut yang bisa diisi (mass assignable) dalam model.
    protected $fillable = [
        'id_kategori',
        'name', // Nama wisata
        'coordinates', // Koordinat lokasi wisata
        'deskripsi', // Deskripsi wisata
        'image', // Path atau JSON berisi daftar gambar wisata
        'harga_tiket', // Rentang harga tiket masuk
        'jumlah_fasilitas', // Kategori jumlah fasilitas yang tersedia
        'ulasan',
        'waktu_operasional',
        'aksesibilitas', // Aksesibilitas wisata
        'link_gmaps', // Rentang ulasan berdasarkan rating
    ];

    /**
     * Accessor untuk atribut 'image'
     * 
     * Fungsi ini akan dijalankan saat kita mengambil nilai dari atribut 'image'.
     * Jika nilai tersimpan dalam database adalah string JSON, maka akan dikonversi menjadi array.
     * Jika nilai kosong (null), maka akan dikembalikan sebagai array kosong.
     */
    public function getImageAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Mutator untuk atribut 'image'
     * 
     * Fungsi ini akan dijalankan sebelum data disimpan ke dalam database.
     * Jika nilai yang diberikan berupa array, maka akan dikonversi menjadi string JSON sebelum disimpan.
     * Jika bukan array, maka nilai akan langsung disimpan sebagaimana adanya.
     */
    public function setImageAttribute($value)
    {
        $this->attributes['image'] = is_array($value) ? json_encode($value) : $value;
    }

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // In App\Models\Wisata.php
public function getLatitudeAttribute()
{
    $coordinates = explode(',', $this->coordinates);
    return (float)trim($coordinates[0]);
}

public function getLongitudeAttribute()
{
    $coordinates = explode(',', $this->coordinates);
    return (float)trim($coordinates[1]);
}
}
