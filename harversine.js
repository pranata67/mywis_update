//Harversine Formula dalam node js

// Fungsi untuk mengubah derajat ke radian
function toRadians(degrees) {
    return degrees * Math.PI / 180;
  }
  
  // Fungsi Haversine
  function haversine(lat1, lon1, lat2, lon2) {
    const R = 6371; // Jari-jari bumi dalam km
    const dLat = toRadians(lat2 - lat1);
    const dLon = toRadians(lon2 - lon1);
  
    const a = Math.sin(dLat / 2) ** 2 +
              Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
              Math.sin(dLon / 2) ** 2;
  
    const c = 2 * Math.asin(Math.sqrt(a));
    return R * c; // Hasil jarak dalam kilometer
  }
  
  // Contoh pemakaian
  const latUser = -7.5513718778876475;
  const lonUser = 112.38215676475455;
  const latWisata = -7.542446255965994;
  const lonWisata = 112.37443836472607;
  
  const jarak = haversine(latUser, lonUser, latWisata, lonWisata);
  console.log(`Jarak dari user ke tempat wisata: ${jarak.toFixed(2)} km`);
  