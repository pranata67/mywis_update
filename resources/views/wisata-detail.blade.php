<!DOCTYPE html>
<html lang="id"
      x-data="{ theme: localStorage.getItem('theme') || 'emerald' }"
      x-init="$watch('theme', val => localStorage.setItem('theme', val))"
      :data-theme="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Wisata: {{ $wisata->name }}</title>

    {{-- CDN for Tailwind CSS & Daisy UI --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- CDN for Font Awesome (for icons) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        .carousel-item img {
            width: 100%;
            height: 60vh;
            object-fit: cover;
        }
    </style>

    @php
        // Logika untuk menyiapkan gambar untuk carousel
        $imageArray = [];
        if (is_string($wisata->image) && !empty($wisata->image)) {
            $imageArray[] = $wisata->image;
        } elseif (is_array($wisata->image)) {
            $imageArray = array_filter($wisata->image); // Filter out empty values
        }

        // Logika untuk teks aksesibilitas
        $aksesibilitasText = '';
        switch ($wisata->aksesibilitas) {
            case 5:
                $aksesibilitasText = 'Bus';
                break;
            case 4:
                $aksesibilitasText = 'Minibus';
                break;
            case 3:
                $aksesibilitasText = 'Mobil';
                break;
            default:
                $aksesibilitasText = $wisata->aksesibilitas . ' / 5';
                break;
        }
    @endphp
</head>
<body class="antialiased bg-base-200">

    {{-- 1) (BARU) THEME TOGGLE --}}
    <div class="fixed top-4 right-4 z-50">
        <label class="swap swap-rotate btn btn-ghost btn-circle bg-base-100/50 backdrop-blur-sm">
            <input type="checkbox" @click="theme = (theme === 'emerald' ? 'night' : 'emerald')" :checked="theme === 'night'" />
            {{-- Sun icon --}}
            <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29l.71-.71A1,1,0,0,0,6.34,4.93l-.71.71A1,1,0,0,0,5.64,7.05ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM20,12a1,1,0,0,0-1-1H18a1,1,0,0,0,0,2h1A1,1,0,0,0,20,12ZM17,5.64a1,1,0,0,0,.71-.29l.71-.71a1,1,0,1,0-1.41-1.41l-.71.71A1,1,0,0,0,17,5.64ZM12,15a3,3,0,1,0-3-3A3,3,0,0,0,12,15Zm0,2a5,5,0,1,0-5-5A5,5,0,0,0,12,17Z"/></svg>
            {{-- Moon icon --}}
            <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22a10.14,10.14,0,0,0,12.79,12.79A8.14,8.14,0,0,1,12.14,19.69Z"/></svg>
        </label>
    </div>

    {{-- 2) (DIUBAH) HERO SECTION: NAMA WISATA TANPA BACKGROUND GAMBAR --}}
    <div class="hero min-h-[60vh] bg-gradient-to-br from-primary to-secondary text-primary-content">
        <div class="hero-content text-center">
            <div class="max-w-2xl">
                <h1 class="mb-5 text-5xl md:text-7xl font-bold">{{ $wisata->name }}</h1>
                <p class="text-lg md:text-xl">Detail Informasi Destinasi Wisata</p>
            </div>
        </div>
    </div>

    {{-- CAROUSEL GAMBAR --}}
    @if(count($imageArray) > 0)
    <section class="py-16 md:py-20 bg-base-100">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-10">Galeri Wisata</h2>
            <div class="carousel w-full rounded-box shadow-xl">
                @foreach($imageArray as $index => $image)
                <div id="slide{{ $index + 1 }}" class="carousel-item relative w-full justify-center">
                    <img src="{{ asset('storage/' . $image) }}" class="max-w-4xl" alt="Gambar Wisata {{ $wisata->name }} {{ $index + 1 }}" />
                    @if(count($imageArray) > 1)
                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                        <a href="#slide{{ $index == 0 ? count($imageArray) : $index }}" class="btn btn-circle">❮</a> 
                        <a href="#slide{{ $index + 1 == count($imageArray) ? 1 : $index + 2 }}" class="btn btn-circle">❯</a>
                    </div>
                    @endif
                </div> 
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
    {{-- FEATURE CARD SECTION --}}
    <main class="container mx-auto px-4 py-16 md:py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
            <div class="card bg-base-100 shadow-xl text-center"><div class="card-body items-center"><div class="text-primary text-4xl mb-4"><i class="fas fa-ticket-alt"></i></div><h3 class="card-title">Harga Tiket</h3><p class="text-lg font-semibold">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</p></div></div>
            <div class="card bg-base-100 shadow-xl text-center"><div class="card-body items-center"><div class="text-primary text-4xl mb-4"><i class="fas fa-star"></i></div><h3 class="card-title">Ulasan</h3><p class="text-lg font-semibold">{{ $wisata->ulasan }} / 5</p></div></div>
            <div class="card bg-base-100 shadow-xl text-center"><div class="card-body items-center"><div class="text-primary text-4xl mb-4"><i class="fas fa-road"></i></div><h3 class="card-title">Aksesibilitas</h3><p class="text-lg font-semibold">{{ $aksesibilitasText }}</p></div></div>
            <div class="card bg-base-100 shadow-xl text-center"><div class="card-body items-center"><div class="text-primary text-4xl mb-4"><i class="fas fa-restroom"></i></div><h3 class="card-title">Fasilitas</h3><p class="text-lg font-semibold">{{ $wisata->jumlah_fasilitas }}</p></div></div>
            <div class="card bg-base-100 shadow-xl text-center"><div class="card-body items-center"><div class="text-primary text-4xl mb-4"><i class="fas fa-clock"></i></div><h3 class="card-title">Operasional</h3><p class="text-lg font-semibold">{{ $wisata->waktu_operasional }} jam</p></div></div>
        </div>
    </main>

    {{-- HERO SECTION: DESKRIPSI WISATA --}}
    <section class="hero bg-base-100 rounded-box mx-4">
        <div class="hero-content text-center p-8 md:p-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold">Deskripsi Wisata</h2>
                <p class="py-6 max-w-3xl mx-auto text-base-content/80">{{ $wisata->deskripsi ?? 'Deskripsi untuk wisata ini belum tersedia.' }}</p>
                <div class="flex flex-wrap gap-4 justify-center mt-4">
                     @if($wisata->link_gmaps)
                        <a href="{{ $wisata->link_gmaps }}" target="_blank" class="btn btn-info"><i class="fas fa-map-marked-alt mr-2"></i> Buka di Google Maps</a>
                    @endif
                     <a href="{{ url()->previous(route('hasil')) }}" class="btn btn-outline btn-neutral"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer footer-center p-10 bg-base-300 text-base-content mt-24">
        <aside>
            <p>&copy; 2025 Mywis Explore Mojokerto. All rights reserved.</p>
            <p>Dibuat dengan <span class="text-red-500">&hearts;</span> oleh MyWis</p>
        </aside>
    </footer>

    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>

</body>
</html>