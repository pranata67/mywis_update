<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class seederwisata extends Seeder
{
    public function run()
    {
        $wisatas = [
            [
                'name' => 'Museum Trowulan',
                'id_kategori' => 1,
                'latitude' => -7.55990424156045,
                'longitude' => 112.380699630662,
                'harga_tiket' => 7000,
                'jumlah_fasilitas' => 7,
                'ulasan' => 4.5,
                'waktu_operasional' => 7,
            ],
            [
                'name' => 'Kolam Segaran',
                'id_kategori' => 1,
                'latitude' => -7.55795153666898,
                'longitude' => 112.38296367102,
                'harga_tiket' => 0,
                'jumlah_fasilitas' => 4,
                'ulasan' => 4.5,
                'waktu_operasional' => 24,
            ],
            [
                'name' => 'Candi Brahu',
                'id_kategori' => 1,
                'latitude' => -7.54288824955043,
                'longitude' => 112.374552368817,
                'harga_tiket' => 4000,
                'jumlah_fasilitas' => 6,
                'ulasan' => 4.6,
                'waktu_operasional' => 9,
            ],
            [
                'name' => 'Candi Bajang Ratu',
                'id_kategori' => 1,
                'latitude' => -7.56736738915906,
                'longitude' => 112.399733019601,
                'harga_tiket' => 4000,
                'jumlah_fasilitas' => 6,
                'ulasan' => 4.6,
                'waktu_operasional' => 7,
            ],
            [
                'name' => 'Candi Tikus',
                'id_kategori' => 1,
                'latitude' => -7.57173180576305,
                'longitude' => 112.403721520671,
                'harga_tiket' => 5000,
                'jumlah_fasilitas' => 6,
                'ulasan' => 4.5,
                'waktu_operasional' => 8,
            ],
            [
                'name' => 'Sumur Upas',
                'id_kategori' => 1,
                'latitude' => -7.57054731022423,
                'longitude' => 112.379591783453,
                'harga_tiket' => 0,
                'jumlah_fasilitas' => 6,
                'ulasan' => 4.4,
                'waktu_operasional' => 7,
            ],
            [
                'name' => 'Candi Gentong',
                'id_kategori' => 1,
                'latitude' => -7.54374264152844,
                'longitude' => 112.378049098055,
                'harga_tiket' => 0,
                'jumlah_fasilitas' => 6,
                'ulasan' => 4.5,
                'waktu_operasional' => 8,
            ],
            [
                'name' => 'Candi Minak Jinggo',
                'id_kategori' => 1,
                'latitude' => -7.55829151872238,
                'longitude' => 112.386540084907,
                'harga_tiket' => 0,
                'jumlah_fasilitas' => 3,
                'ulasan' => 4.2,
                'waktu_operasional' => 8,
            ],
            [
                'name' => 'Gapura Candi Wringin Lawang',
                'id_kategori' => 1,
                'latitude' => -7.54194466410788,
                'longitude' => 112.39101915102,
                'harga_tiket' => 4000,
                'jumlah_fasilitas' => 5,
                'ulasan' => 4.6,
                'waktu_operasional' => 8,
            ],
            [
                'name' => 'Situs Lantai Segi Enam',
                'id_kategori' => 1,
                'latitude' => -7.57114802621215,
                'longitude' => 112.379908603856,
                'harga_tiket' => 0,
                'jumlah_fasilitas' => 5,
                'ulasan' => 4.6,
                'waktu_operasional' => 9,
            ],
        ];

        DB::table('wisatas')->insert($wisatas);
    }
}
