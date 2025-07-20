<?php

/**
 * SOURCE CODE 6 KRITERIA MENGGUNAKAN NILAI AKTUAL
 * SOURCE CODE YANG DIOPTIMISASI
 * Fokus pada:
 * 1. Fleksibilitas -> Tidak lagi terikat pada nama kriteria, kini sepenuhnya dinamis.
 * 2. Keterbacaan -> Menggunakan metode koleksi Laravel (map, pluck, etc.) agar lebih ringkas.
 * 3. Robustness -> Penanganan edge case yang lebih baik dan kode yang lebih mudah dipelihara.
 */

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Collection; // Import Collection untuk type-hinting yang lebih baik

class CalculateController extends Controller
{
    /**
     * Menangani & memvalidasi input, lalu memicu perhitungan SAW.
     */
    public function calculate(Request $request)
    {
        // Validasi data input dari form dengan aturan yang lebih spesifik
        $data = $request->validate([
            'koordinat' => ['required', 'string', 'regex:/^-?\d{1,2}(\.\d+)?,\s*-?\d{1,3}(\.\d+)?$/'],
            'kategori' => 'required|exists:kategoris,id',
            'kriteria_order' => 'required|string',
            'weights' => 'required|array'
        ]);

        // Konversi bobot ke float
        $weights = array_map('floatval', $data['weights']);

        // Validasi total bobot (harus 100%). Menggunakan abs() dan toleransi 0.01 aman untuk float.
        if (abs(array_sum($weights) - 100) > 0.01) {
            return back()->withErrors(['weights' => 'Total bobot dari semua kriteria harus 100%.'])->withInput();
        }

        // --- Memproses Input ---
        $weights_decimal = array_map(fn($w) => $w / 100, $weights);
        $userCoords = explode(',', $data['koordinat']);
        $kriteria_order = array_map('intval', explode(',', $data['kriteria_order']));

        // Validasi kesesuaian jumlah kriteria yang dipilih dengan jumlah bobot yang diinput
        if (count($kriteria_order) !== count($weights_decimal)) {
            return back()->withErrors(['weights' => 'Jumlah kriteria dan bobot yang dimasukkan tidak sesuai.'])->withInput();
        }

        // Panggil fungsi inti SAW dengan data yang sudah bersih
        $recommendations = $this->calculateSAW(
            (float)trim($userCoords[0]),
            (float)trim($userCoords[1]),
            $data['kategori'],
            $kriteria_order,
            array_values($weights_decimal)
        );

        // Arahkan ke halaman hasil jika ada rekomendasi, atau kembali dengan pesan error jika tidak ada.
        return empty($recommendations)
            ? back()->with('error', 'Tidak ada tempat wisata yang ditemukan untuk kategori yang dipilih.')->withInput()
            : redirect()->route('hasil')->with('recommendations', $recommendations);
    }

    /**
     * Implementasi inti dari perhitungan Simple Additive Weighting (SAW).
     */
    private function calculateSAW(float $userLat, float $userLng, int $kategoriId, array $kriteria_order, array $weights): array
    {
        // 1. Ambil semua data wisata yang sesuai dengan kategori yang dipilih.
        $wisatas = Wisata::where('id_kategori', $kategoriId)->get();
        if ($wisatas->isEmpty()) {
            return []; // Jika tidak ada wisata, hentikan proses.
        }

        // 2. Ambil data kriteria berdasarkan ID, lalu urutkan sesuai `kriteria_order` dari user.
        // Pendekatan ini lebih efisien daripada `orderByRaw` jika dilakukan di sisi koleksi.
        $kriterias = Kriteria::whereIn('id', $kriteria_order)
            ->get()
            ->sortBy(fn($model) => array_search($model->id, $kriteria_order));

        // 3. Buat Matriks Keputusan secara dinamis.
        $matrix = $this->buildDecisionMatrix($wisatas, $kriterias, $userLat, $userLng);

        // 4. Normalisasi Matriks Keputusan
        $normalizedMatrix = $this->normalizeMatrix($matrix, $kriterias);

        // 5. Hitung Skor Akhir (Perankingan)
        $results = $this->calculateFinalScores($normalizedMatrix, $kriterias, $weights);

        // 6. Urutkan hasil berdasarkan skor dari yang tertinggi ke terendah dan kembalikan sebagai array.
        return $results->sortByDesc('score')->values()->all();
    }

    /**
     * Membangun matriks keputusan dari data wisata dan kriteria.
     * Dibuat menjadi fungsi terpisah untuk kebersihan kode (Single Responsibility).
     */
    private function buildDecisionMatrix(Collection $wisatas, Collection $kriterias, float $userLat, float $userLng): Collection
    {
        return $wisatas->map(function ($wisata) use ($kriterias, $userLat, $userLng) {
            $values = [];
            // Loop pada kriteria yang dipilih pengguna untuk mengisi nilai secara dinamis
            foreach ($kriterias as $kriteria) {
                // Ambil nama kolom dari model Kriteria (e.g., 'harga_tiket', 'ulasan')
                $columnName = $kriteria->nama_kriteria;
                // Ambil nilai dari model Wisata. Jika kriteria 'jarak', hitung dulu.
                $values[$columnName] = ($columnName === 'jarak')
                    ? $this->calculateDistance($userLat, $userLng, $wisata->latitude, $wisata->longitude)
                    : $wisata->{$columnName};
            }
            return ['wisata' => $wisata, 'values' => $values];
        });
    }

    /**
     * Menormalisasi matriks keputusan.
     */
    private function normalizeMatrix(Collection $matrix, Collection $kriterias): Collection
    {
        // 1. Cari nilai MIN dan MAX untuk setiap kriteria menggunakan `pluck` agar efisien.
        $minMax = $kriterias->mapWithKeys(function ($kriteria) use ($matrix) {
            $columnName = $kriteria->nama_kriteria;
            $values = $matrix->pluck('values.' . $columnName);

            return [$columnName => [
                'min' => $values->min(),
                'max' => $values->max(),
                'type' => $kriteria->tipe,
                // Cari nilai minimum yang bukan nol untuk kriteria cost (menghindari pembagian dengan nol)
                'non_zero_min' => $values->filter(fn($v) => $v > 0)->min(),
            ]];
        });

        // 2. Lakukan proses normalisasi untuk setiap nilai dalam matriks.
        return $matrix->map(function ($item) use ($kriterias, $minMax) {
            $item['normalized'] = [];
            foreach ($kriterias as $kriteria) {
                $columnName = $kriteria->nama_kriteria;
                $value = $item['values'][$columnName];
                $stats = $minMax[$columnName];

                if ($stats['type'] === 'cost') {
                    // Normalisasi untuk kriteria tipe 'cost' (semakin kecil semakin baik)
                    $minToUse = $stats['non_zero_min'] ?? $stats['min'];
                    // Jika nilai adalah 0 (misal: tiket gratis), skornya 1 (terbaik).
                    // Jika tidak, hitung dengan rumus min/value.
                    $normVal = ($value == 0) ? 1 : ($minToUse / $value);
                } else {
                    // Normalisasi untuk kriteria tipe 'benefit' (semakin besar semakin baik)
                    $normVal = ($stats['max'] != 0) ? $value / $stats['max'] : 0;
                }
                $item['normalized'][$columnName] = $normVal;
            }
            return $item;
        });
    }

    /**
     * Menghitung skor akhir dari matriks yang sudah dinormalisasi.
     * Dibuat menjadi fungsi terpisah untuk kebersihan kode.
     */
    private function calculateFinalScores(Collection $normalizedMatrix, Collection $kriterias, array $weights): Collection
    {
        return $normalizedMatrix->map(function ($item) use ($kriterias, $weights) {
            $score = 0;
            // `values()` memastikan kita mendapatkan koleksi dengan index numerik (0, 1, 2, ...)
            foreach ($kriterias->values() as $idx => $kriteria) {
                $columnName = $kriteria->nama_kriteria;
                // Akumulasi skor: (Nilai Normalisasi * Bobot)
                $score += $item['normalized'][$columnName] * $weights[$idx];
            }
            // Kembalikan data lengkap
            return ['wisata' => $item['wisata'], 'score' => $score, 'details' => $item['values']];
        });
    }

    /**
     * Menghitung jarak antara dua titik koordinat menggunakan formula Haversine.
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Jari-jari bumi dalam km
        $latDiff = deg2rad($lat2 - $lat1);
        $lngDiff = deg2rad($lng2 - $lng1);
        $a = sin($latDiff / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDiff / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    /**
     * Menampilkan halaman hasil.
     */
    public function hasil()
    {
        return view('hasil');
    }
}
