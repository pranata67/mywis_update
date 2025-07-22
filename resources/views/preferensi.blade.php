@extends('layouts.app-daisy')

@section('content')

{{-- Container utama --}}
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
    <form action="{{ route('calculate') }}" method="POST" id="preferenceForm" class="space-y-8">
        @csrf
        {{-- Judul --}}
        <h2 class="text-3xl font-bold text-center mb-10">
            Sesuaikan Preferensi Wisata Anda
        </h2>

        {{-- BAGIAN PETA - Menggunakan warna tema dasar --}}
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <label class="block text-xl font-medium text-center mb-3">Lihat Lokasi Anda Melalui Peta</label>
                <div id="map" class="w-full max-w-4xl h-72 md:h-96 mx-auto rounded-lg shadow-2xl border-2 border-base-300"></div>
            </div>
        </div>

        {{-- Card untuk input koordinat --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <label for="coordinates_input" class="label">
                    <span class="label-text text-lg">Koordinat Anda</span>
                </label>
                <input type="text" 
                       id="coordinates_input" 
                       name="koordinat" 
                       required 
                       readonly 
                       placeholder="Pilih lokasi pada peta..."
                       class="input input-bordered w-full" />
            </div>
        </div>

        {{-- Card untuk pilihan kategori --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <label for="kategori_select" class="label">
                    <span class="label-text text-lg">Pilih Kategori Tempat Wisata</span>
                </label>
                <select id="kategori_select" name="kategori" class="select select-bordered w-full">
                    <option disabled selected>Pilih salah satu...</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- (BARU) Card untuk menampilkan daftar wisata sesuai kategori --}}
        <div id="wisata-list-container" class="card bg-base-100 shadow-xl hidden">
            <div class="card-body">
                <label class="label">
                    <span class="label-text text-lg">Daftar Wisata dalam Kategori Terpilih</span>
                </label>
                <div class="overflow-x-auto max-h-60">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-200">
                                <th>Nama Tempat Wisata</th>
                            </tr>
                        </thead>
                        <tbody id="wisata-list-body">
                            {{-- Konten akan diisi oleh JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card untuk tabel bobot kriteria --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                 <label class="label">
                    <span class="label-text text-lg">Masukkan Persentase Bobot Prioritas Kriteria</span>
                </label>
                <p class="text-sm opacity-70 mb-4">Total bobot dari semua kriteria harus 100%.</p>
                <input type="hidden" name="kriteria_order" id="kriteria_order">
                
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-base-200">
                                <th>Kriteria</th>
                                <th>Bobot (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kriterias as $kriteria)
                            <tr data-id="{{ $kriteria->id }}">
                                <td>{{ ucwords(str_replace('_', ' ', $kriteria->nama_kriteria)) }}</td>
                                <td>
                                     <input type="number"
                                           class="input input-bordered w-28 weight-input"
                                           name="weights[{{ $kriteria->id }}]"
                                           min="1"
                                           max="100"
                                           step="1"
                                           required
                                           placeholder="0-100">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pesan error validasi bobot (JavaScript) --}}
        <div id="form-error-message" role="alert" class="alert alert-error shadow-lg hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span></span>
        </div>

        {{-- Tombol Submit --}}
        <div class="pt-5 text-center">
            <button type="submit" class="btn btn-success w-full sm:w-auto">
                <i class="fas fa-search mr-2"></i>
                Cari Rekomendasi Wisata
            </button>
        </div>
    </form>

    {{-- Menampilkan Error Validasi Laravel --}}
    @if ($errors->any())
        <div role="alert" class="alert alert-error mt-8 shadow-lg">
             <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <h3 class="font-bold">Oops! Ada beberapa kesalahan:</h3>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>


{{-- Dependencies dan Scripts --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

@php
// $wisatas sudah di-pass dari controller
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    const preferenceForm = document.getElementById('preferenceForm');
    const formErrorMessageDiv = document.getElementById('form-error-message');
    const formErrorSpan = formErrorMessageDiv.querySelector('span');
    
    // (BARU) Variabel untuk fungsionalitas kategori dan list wisata
    const allWisatas = @json($wisatas);
    const kategoriSelect = document.getElementById('kategori_select');
    const wisataListContainer = document.getElementById('wisata-list-container');
    const wisataListBody = document.getElementById('wisata-list-body');

    // (BARU) Fungsi untuk memperbarui daftar wisata berdasarkan kategori
    function updateWisataList(selectedCategoryId) {
        // Kosongkan tabel
        wisataListBody.innerHTML = '';

        if (!selectedCategoryId) {
            wisataListContainer.classList.add('hidden');
            return;
        }

        // Filter wisata berdasarkan id_kategori
        const filteredWisatas = allWisatas.filter(wisata => wisata.id_kategori == selectedCategoryId);

        if (filteredWisatas.length > 0) {
            // Isi tabel dengan data yang difilter
            filteredWisatas.forEach(wisata => {
                const row = `<tr><td>${wisata.name}</td></tr>`;
                wisataListBody.innerHTML += row;
            });
            // Tampilkan container tabel
            wisataListContainer.classList.remove('hidden');
        } else {
            // Sembunyikan jika tidak ada wisata di kategori tsb
            wisataListContainer.classList.add('hidden');
        }
    }

    // (BARU) Event listener saat pilihan kategori diubah
    kategoriSelect.addEventListener('change', function() {
        updateWisataList(this.value);
    });

    // (BARU) Cek parameter URL saat halaman dimuat
    const urlParams = new URLSearchParams(window.location.search);
    const kategoriIdFromUrl = urlParams.get('kategori_id');

    if (kategoriIdFromUrl) {
        // Set nilai dropdown sesuai parameter URL
        kategoriSelect.value = kategoriIdFromUrl;
        
        // Picu event 'change' secara manual untuk memuat daftar wisata
        kategoriSelect.dispatchEvent(new Event('change'));
    }


    // Validasi form bobot
    if (preferenceForm) {
        preferenceForm.addEventListener('submit', function(e) {
            let totalWeight = 0;
            document.querySelectorAll('.weight-input').forEach(input => {
                totalWeight += parseFloat(input.value) || 0;
            });

            formErrorMessageDiv.classList.add('hidden');
            formErrorSpan.textContent = '';

            if (Math.abs(totalWeight - 100) > 0.01) {
                e.preventDefault(); 
                formErrorSpan.textContent = 'Total persentase bobot untuk semua kriteria harus tepat 100%. Mohon periksa kembali.';
                formErrorMessageDiv.classList.remove('hidden');
                formErrorMessageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }

            const kriteriaIdsInOrder = @json($kriterias->pluck('id')->all());
            const kriteriaOrderInput = document.querySelector('#kriteria_order');
            if (kriteriaOrderInput) {
                kriteriaOrderInput.value = kriteriaIdsInOrder.join(',');
            }
        });
    }

    // Inisialisasi Peta Leaflet (kode peta tidak diubah)
    var wisataLocations = @json($wisatas->map(function($wisata) {
        return [
            'coordinates' => $wisata->coordinates,
            'name' => $wisata->name
        ];
    }));

    var map;
    var defaultLat = -7.490675772242737;
    var defaultLng = 112.44445853750588; // Koreksi longitude agar di sekitar Mojokerto
    var userLocationMarker;

    function fetchLocationByIp(redIcon) {
        fetch('https://ip-api.com/json')
            .then(response => response.json())
            .then(data => {
                if (data && data.status === 'success') {
                    var ipLat = data.lat;
                    var ipLng = data.lon;
                    map.setView([ipLat, ipLng], 12);
                    userLocationMarker.setLatLng([ipLat, ipLng]);
                    userLocationMarker.bindPopup('Mohon izinkan akses GPS pada browser Anda. (Lokasi saat ini terdeteksi melalui IP Address Anda)').openPopup();
                    $('#coordinates_input').val(ipLat.toFixed(6) + ', ' + ipLng.toFixed(6));
                } else {
                    userLocationMarker.bindPopup('Lokasi tidak terdeteksi, menggunakan lokasi default.').openPopup();
                }
            })
            .catch(error => {
                console.error('Error saat fetch IP Geolocation:', error);
                userLocationMarker.bindPopup('Lokasi tidak terdeteksi, menggunakan lokasi default.').openPopup();
            });
    }

    function initMap() {
        if (typeof L === 'undefined') {
            console.error('Leaflet library is not loaded. Map cannot be initialized.');
            const mapDiv = document.getElementById('map');
            if(mapDiv) {
                mapDiv.innerHTML = `<div class="alert alert-error"><p>Pustaka peta (Leaflet) gagal dimuat. Silakan coba muat ulang halaman.</p></div>`;
            }
            return;
        }

        map = L.map('map').setView([defaultLat, defaultLng], 10);

        L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        }).addTo(map);

        wisataLocations.forEach(function(location) {
            try {
                var coords = location.coordinates.split(/,\s*/);
                if (coords.length === 2 && !isNaN(parseFloat(coords[0])) && !isNaN(parseFloat(coords[1]))) {
                    L.marker([parseFloat(coords[0]), parseFloat(coords[1])])
                        .addTo(map)
                        .bindPopup(L.popup({maxWidth: 200}).setContent(location.name));
                } else {
                    console.warn('Invalid coordinates for wisata:', location.name, location.coordinates);
                }
            } catch (error) {
                console.error('Error processing wisata location:', location.name, error);
            }
        });
        
        var redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        userLocationMarker = L.marker([defaultLat, defaultLng], { icon: redIcon })
            .addTo(map)
            .bindPopup('Mencari lokasi Anda...')
            .openPopup();
        
        $('#coordinates_input').val(defaultLat.toFixed(6) + ', ' + defaultLng.toFixed(6));

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;
                    map.setView([userLat, userLng], 13);
                    userLocationMarker.setLatLng([userLat, userLng]);
                    userLocationMarker.bindPopup('Lokasi Anda Saat Ini').openPopup();
                    $('#coordinates_input').val(userLat.toFixed(6) + ', ' + userLng.toFixed(6));
                },
                function(error) {
                    console.warn('Error getting user location:', error.message);
                    fetchLocationByIp(redIcon);
                },
                { timeout: 10000, enableHighAccuracy: true }
            );
        } else {
            console.warn('Geolocation is not supported by this browser.');
            fetchLocationByIp(redIcon);
        }
        $(window).on('resize', function() {
            setTimeout(function() { map.invalidateSize(); }, 100);
        }).trigger('resize');
    }

    if (typeof L !== 'undefined') {
        initMap();
    } else {
        console.warn('Leaflet not immediately available, will try to init map shortly...');
        setTimeout(function() {
            if (typeof L !== 'undefined') {
                initMap();
            } else {
                 console.error('Leaflet failed to load. Map cannot be initialized.');
                 const mapDiv = document.getElementById('map');
                 if(mapDiv) {
                     mapDiv.innerHTML = `<div class="alert alert-error"><p>Map gagal dimuat. Periksa koneksi internet atau coba muat ulang halaman.</p></div>`;
                 }
            }
        }, 1000);
    }
});
</script>

@endsection