<!DOCTYPE html>
<html lang="id"
      x-data="{ theme: localStorage.getItem('theme') || 'emerald' }"
      x-init="$watch('theme', val => localStorage.setItem('theme', val))"
      :data-theme="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mywis Explore Mojokerto - Rekomendasi Wisata</title>
    <link rel="icon" type="image/png" href="{{ asset('pine.png') }}">

    {{-- CDN for Tailwind CSS & Daisy UI --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    {{-- (BARU) CDN for Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        /* Menggunakan font Figtree sebagai font utama dari body */
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="antialiased">

    {{-- Navbar --}}
    <div class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
        <div class="navbar-start">
            <a class="btn btn-ghost text-xl text-primary">
                🌄 Mywis Explore Mojokerto
            </a>
        </div>
        <div class="navbar-end">
            {{-- Theme Toggle --}}
            <label class="swap swap-rotate btn btn-ghost btn-circle">
                <input type="checkbox" @click="theme = (theme === 'emerald' ? 'night' : 'emerald')" :checked="theme === 'night'" />
                <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29l.71-.71A1,1,0,0,0,6.34,4.93l-.71.71A1,1,0,0,0,5.64,7.05ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM20,12a1,1,0,0,0-1-1H18a1,1,0,0,0,0,2h1A1,1,0,0,0,20,12ZM17,5.64a1,1,0,0,0,.71-.29l.71-.71a1,1,0,1,0-1.41-1.41l-.71.71A1,1,0,0,0,17,5.64ZM12,15a3,3,0,1,0-3-3A3,3,0,0,0,12,15Zm0,2a5,5,0,1,0-5-5A5,5,0,0,0,12,17Z"/></svg>
                <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22a10.14,10.14,0,0,0,12.79,12.79A8.14,8.14,0,0,1,12.14,19.69Z"/></svg>
            </label>
        </div>
    </div>

    {{-- Hero Section --}}
    <div class="hero min-h-[60vh] bg-gradient-to-br from-primary to-secondary text-primary-content">
        <div class="hero-content text-center">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Temukan Wisata Terbaik di Mojokerto</h1>
                <p class="text-lg md:text-xl mb-10">Dapatkan rekomendasi wisata yang dipersonalisasi sesuai dengan preferensi Anda.</p>
                <a href="{{ route('preferensi') }}" class="btn btn-neutral btn-lg shadow-lg transform hover:scale-105 transition-transform duration-300">
                    Mulai Sekarang &gt;&gt;
                </a>
            </div>
        </div>
    </div>

    <main class="container mx-auto">
        {{-- Features Section --}}
        <section class="py-16 md:py-24 px-4">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold">Kenapa Harus Menggunakan Sistem Ini?</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card bg-base-100 shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body items-center text-center">
                        <div class="text-primary text-4xl mb-4">📊</div>
                        <h3 class="card-title">Kriteria Komprehensif</h3>
                        <p>Pemeringkatan berdasarkan 6 faktor penting untuk rekomendasi akurat.</p>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body items-center text-center">
                        <div class="text-primary text-4xl mb-4">⏱️</div>
                        <h3 class="card-title">Menghemat Waktu</h3>
                        <p>Cari wisata yang paling sesuai preferensi Anda secara cepat dan mudah.</p>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body items-center text-center">
                        <div class="text-primary text-4xl mb-4">⭐</div>
                        <h3 class="card-title">Info Akurat</h3>
                        <p>Update data terbaru dari berbagai sumber terpercaya untuk informasi relevan.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Categories Section --}}
        <section class="py-16 md:py-24 bg-base-200 rounded-box px-4 mx-4">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold">Kategori Wisata</h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- DIUBAH: Menambahkan parameter kategori_id=1 pada URL --}}
                <div class="card bg-base-100 shadow-xl image-full transition-all duration-300 hover:shadow-2xl">
                    <figure><img src="https://static.promediateknologi.id/crop/0x0:0x0/0x0/webp/photo/p2/229/2024/10/31/candi-bajang-ratu-2709528309.png" alt="Sejarah"/></figure>
                    <div class="card-body">
                        <h3 class="card-title text-2xl">Sejarah</h3>
                        <p>Jelajahi peninggalan sejarah dan kekayaan budaya Mojokerto.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('preferensi', ['kategori_id' => 1]) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                {{-- DIUBAH: Menambahkan parameter kategori_id=2 pada URL --}}
                <div class="card bg-base-100 shadow-xl image-full transition-all duration-300 hover:shadow-2xl">
                    <figure><img src="https://static.promediateknologi.id/crop/0x585:1080x1616/0x0/webp/photo/p2/74/2024/09/15/Screenshot_20240915_192755-181566920.jpg" alt="Alam"/></figure>
                    <div class="card-body">
                        <h3 class="card-title text-2xl">Alam</h3>
                        <p>Temukan keindahan alam Mojokerto yang mempesona.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('preferensi', ['kategori_id' => 2]) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                {{-- DIUBAH: Menambahkan parameter kategori_id=3 pada URL --}}
                <div class="card bg-base-100 shadow-xl image-full transition-all duration-300 hover:shadow-2xl">
                    <figure><img src="https://awsimages.detik.net.id/community/media/visual/2022/05/01/masjid-di-mojokerto_43.jpeg?w=480" alt="Religi"/></figure>
                    <div class="card-body">
                        <h3 class="card-title text-2xl">Religi</h3>
                        <p>Kunjungi destinasi wisata religi yang menenangkan jiwa.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('preferensi', ['kategori_id' => 3]) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                {{-- DIUBAH: Menambahkan parameter kategori_id=4 pada URL --}}
                <div class="card bg-base-100 shadow-xl image-full transition-all duration-300 hover:shadow-2xl">
                    <figure><img src="https://faktualnews.co/images/2022/05/liburan-keluarga.jpg" alt="Wisata Keluarga"/></figure>
                    <div class="card-body">
                        <h3 class="card-title text-2xl">Wisata Keluarga</h3>
                        <p>Ciptakan momen seru bersama keluarga di destinasi ramah anak.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('preferensi', ['kategori_id' => 4]) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 px-4">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold">Jelajahi Pariwisata Mojokerto Lewat Data</h2>
                <p class="mt-4 max-w-3xl mx-auto text-lg text-base-content/80">
                    Mywis telah membantu ribuan pengguna menemukan tempat wisata favorit mereka, dari tempat bersantai hingga tempat mengenal sejarah.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Feature Card 1 --}}
                <div class="card bg-base-100 shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body items-center text-center">
                        <div class="bg-primary/10 text-primary rounded-full p-4 mb-4">
                            <i data-feather="map-pin" class="w-10 h-10"></i>
                        </div>
                        <p class="text-4xl font-bold">40 +</p>
                        <p class="mt-2 text-base-content/80">Tempat wisata terdaftar</p>
                    </div>
                </div>
                {{-- Feature Card 2 --}}
                <div class="card bg-base-100 shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body items-center text-center">
                        <div class="bg-primary/10 text-primary rounded-full p-4 mb-4">
                           <i data-feather="users" class="w-10 h-10"></i>
                        </div>
                        <p class="text-4xl font-bold">1500 +</p>
                        <p class="mt-2 text-base-content/80">Pengunjung</p>
                    </div>
                </div>
                {{-- Feature Card 3 --}}
                <div class="card bg-base-100 shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body items-center text-center">
                        <div class="bg-primary/10 text-primary rounded-full p-4 mb-4">
                           <i data-feather="check-square" class="w-10 h-10"></i>
                        </div>
                        <p class="text-4xl font-bold">6 Kriteria</p>
                        <p class="mt-2 text-base-content/80">Untuk penilaian objektif</p>
                    </div>
                </div>
            </div>
        </section>
        
    </main>

    {{-- Footer --}}
    <footer class="footer footer-center p-10 bg-base-300 text-base-content mt-24">
        <aside>
            <p>&copy; 2025 Mywis Explore Mojokerto. All rights reserved.</p>
            <p>Dibuat dengan <span class="text-red-500">&hearts;</span> oleh MyWis</p>
        </aside>
    </footer>

    {{-- Alpine.js for interactivity --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- (BARU) Feather Icons Initializer --}}
    <script>
        feather.replace();
    </script>

</body>
</html>