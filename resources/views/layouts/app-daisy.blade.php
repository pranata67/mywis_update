<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="icon" type="image/png" href="{{ asset('pine.png') }}">

    {{-- Font & Icons --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- DaisyUI & Tailwind CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js CDN (Tambahkan ini) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Konfigurasi DaisyUI --}}
    <script>
      tailwind.config = {
        daisyui: {
          themes: ["emerald", "night"],
        },
      }
    </script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col bg-base-200">
        
        {{-- Navbar --}}
        <nav class="navbar bg-base-100 shadow-md sticky top-0 z-50">
            <div class="navbar-start">
                <a href="{{ url('/') }}" class="btn btn-ghost text-xl text-primary">
                    Mywis Explore Mojokerto
                </a>
            </div>
            <div class="navbar-end">
                {{-- Theme Controller --}}
                <label class="flex cursor-pointer gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                  <input type="checkbox" value="emerald" class="toggle theme-controller" checked/>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/></svg>


                </label>
            </div>
        </nav>

        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="footer footer-center p-4 bg-base-300 text-base-content mt-auto">
          <aside>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'mywis') }}. All rights reserved.</p>
            <p>Dibuat dengan <i class="fas fa-heart text-red-500"></i> oleh MyWis</p>
          </aside>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>