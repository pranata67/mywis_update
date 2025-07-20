<?php
// Periksa apakah request adalah POST (form disubmit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Konfigurasi koneksi database MySQL
    $servername = "localhost"; // Nama server
    $username = "root"; // Username database
    $password = ""; // Password database
    $dbname = "tourism_recommendation"; // Nama database

    // Buat koneksi ke database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Hentikan eksekusi jika koneksi gagal
    }

    // Ambil input pengguna dari form
    $category = $_POST['category']; // Kategori wisata (Alam, Religi, Sejarah)
    $w_distance = $_POST['w_distance'] / 100; // Bobot jarak (konversi ke desimal)
    $w_price = $_POST['w_price'] / 100; // Bobot harga tiket
    $w_rating = $_POST['w_rating'] / 100; // Bobot rating
    $w_facilities = $_POST['w_facilities'] / 100; // Bobot fasilitas
    $w_hours = $_POST['w_hours'] / 100; // Bobot durasi operasional

    // Query untuk mengambil data tempat wisata berdasarkan kategori
    $sql = "SELECT * FROM tourism_places WHERE category = ?";
    $stmt = $conn->prepare($sql); // Siapkan query untuk keamanan
    $stmt->bind_param("s", $category); // Bind parameter kategori
    $stmt->execute();
    $result = $stmt->get_result();

    // Simpan data tempat wisata ke array
    $places = [];
    while ($row = $result->fetch_assoc()) {
        $places[] = $row;
    }

    // Cari nilai max dan min untuk normalisasi
    $max_distance = max(array_column($places, 'distance')); // Jarak terbesar
    $min_distance = min(array_column($places, 'distance')); // Jarak terkecil
    // Filter harga tiket non-zero untuk menghitung min dan max
    $non_zero_prices = array_filter(array_column($places, 'ticket_price'), function($price) {
        return $price > 0;
    });
    $max_price = !empty($non_zero_prices) ? max($non_zero_prices) : 0; // Harga maksimum non-zero
    $min_price = !empty($non_zero_prices) ? min($non_zero_prices) : 0; // Harga minimum non-zero
    $max_rating = max(array_column($places, 'rating')); // Rating tertinggi
    $min_rating = min(array_column($places, 'rating')); // Rating terendah
    $max_facilities = max(array_column($places, 'facilities')); // Fasilitas terbanyak
    $min_facilities = min(array_column($places, 'facilities')); // Fasilitas tersedikit
    $max_hours = max(array_column($places, 'operational_hours')); // Durasi terlama
    $min_hours = min(array_column($places, 'operational_hours')); // Durasi terpendek

    // Hitung skor SAW untuk setiap tempat wisata
    $scores = [];
    foreach ($places as $place) {
        // Normalisasi kriteria (cost: jarak, harga; benefit: rating, fasilitas, jam)
        $norm_distance = ($place['distance'] != 0 && $max_distance != 0) ? ($min_distance / $place['distance']) : 1; // Normalisasi jarak
        $norm_price = ($place['ticket_price'] != 0 && $min_price != 0) ? ($min_price / $place['ticket_price']) : 1; // Normalisasi harga, handle tiket gratis
        $norm_rating = $max_rating ? ($place['rating'] / $max_rating) : 0; // Normalisasi rating
        $norm_facilities = $max_facilities ? ($place['facilities'] / $max_facilities) : 0; // Normalisasi fasilitas
        $norm_hours = $max_hours ? ($place['operational_hours'] / $max_hours) : 0; // Normalisasi durasi

        // Hitung skor SAW
        $score = (
            $w_distance * $norm_distance +
            $w_price * $norm_price +
            $w_rating * $norm_rating +
            $w_facilities * $norm_facilities +
            $w_hours * $norm_hours
        );
        $scores[] = [
            'name' => $place['name'],
            'score' => $score,
            'distance' => $place['distance'],
            'ticket_price' => $place['ticket_price'],
            'rating' => $place['rating'],
            'facilities' => $place['facilities'],
            'operational_hours' => $place['operational_hours']
        ];
    }

    // Urutkan berdasarkan skor (descending)
    usort($scores, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    // Tampilkan hasil dalam tabel
    if (!empty($scores)) {
        echo "<h2>Hasil Rekomendasi ($category)</h2>";
        echo "<table>";
        echo "<tr><th>Nama Tempat</th><th>Skor</th><th>Jarak (km)</th><th>Harga Tiket (Rp)</th><th>Rating</th><th>Fasilitas</th><th>Jam Operasional</th></tr>";
        foreach ($scores as $place) {
            $ticket_price = $place['ticket_price'] == 0 ? 'Gratis' : number_format($place['ticket_price']);
            echo "<tr>";
            echo "<td>{$place['name']}</td>";
            echo "<td>" . number_format($place['score'], 3) . "</td>";
            echo "<td>{$place['distance']}</td>";
            echo "<td>$ticket_price</td>";
            echo "<td>{$place['rating']}</td>";
            echo "<td>{$place['facilities']}</td>";
            echo "<td>{$place['operational_hours']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada tempat wisata ditemukan untuk kategori ini.</p>";
    }

    // Tutup koneksi database
    $stmt->close();
    $conn->close();
}
?>

<!-- JavaScript untuk validasi bobot -->
<script>
    function validateWeights() {
        const weights = [
            parseFloat(document.getElementById('w_distance').value),
            parseFloat(document.getElementById('w_price').value),
            parseFloat(document.getElementById('w_rating').value),
            parseFloat(document.getElementById('w_facilities').value),
            parseFloat(document.getElementById('w_hours').value)
        ];
        const total = weights.reduce((sum, val) => sum + (val || 0), 0);
        const errorDiv = document.getElementById('error');
        if (Math.abs(total - 100) > 0.01) {
            errorDiv.textContent = 'Total bobot harus sama dengan 100%';
            return false;
        }
        errorDiv.textContent = '';
        return true;
    }
</script>
</body>
</html>