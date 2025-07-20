<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Rekomendasi Wisata</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Pastikan ini sesuai dengan setup Vite/Mix Anda --}}
    {{-- Jika tidak menggunakan Vite/Mix untuk Alpine, Anda bisa memuatnya via CDN: --}}
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

    {{-- Styles tambahan jika ada --}}
    @stack('styles')

</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        
        {{-- Contoh Sederhana Navigasi --}}
        <nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex-shrink-0 text-xl font-bold text-blue-600 dark:text-blue-400">
                            {{-- Ganti dengan logo jika ada --}}
                            Mywis Explore Mojokerto
                        </a>
                    </div>
                    <div class="flex items-center">
                        {{-- Tombol Theme Switcher bisa diletakkan di sini atau di tempat lain yang lebih sesuai --}}
                        {{-- Contoh tombol theme switcher yang sama seperti di hasil.blade.php --}}
                        {{-- Jika Anda ingin tombol ini hanya ada di sini, hapus dari hasil.blade.php --}}
                        <button @click="darkMode = !darkMode"
                                class="ml-4 p-2 rounded-full text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none transition-colors duration-200"
                                aria-label="Toggle theme">
                            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>
                        
                        {{-- Link navigasi lain bisa ditambahkan di sini --}}
                        {{-- Contoh:
                        <a href="{{ route('preferensi') }}" class="ml-4 px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
                            Cari Rekomendasi
                        </a>
                        --}}
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- Contoh Sederhana Footer --}}
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'mywis') }}. All rights reserved.
                <p>Dibuat dengan <i class="fas fa-heart text-red-500"></i> oleh MyWis</p>
            </div>
        </footer>
    </div>

    {{-- Scripts tambahan jika ada --}}
    @stack('scripts')
</body>
</html>
