@extends('layouts.app-daisy')
@section('title', 'Hasil Rekomendasi Wisata')

@section('content')

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h2 class="text-3xl font-bold text-center mb-10">Hasil Rekomendasi Wisata</h2>

        @if(session('recommendations') && count(session('recommendations')) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(session('recommendations') as $index => $item)
                {{-- Card per item --}}
                <div x-data="{ openDetail: false }" class="card bg-base-100 shadow-xl flex flex-col h-full">
                    
                    {{-- Gambar --}}
                    <figure>
                        @php
                            $imageUrl = null;
                            if (is_string($item['wisata']->image) && !empty($item['wisata']->image)) {
                                $imageUrl = asset('storage/' . $item['wisata']->image);
                            } elseif (is_array($item['wisata']->image) && count($item['wisata']->image) > 0 && !empty($item['wisata']->image[0])) {
                                $imageUrl = asset('storage/' . $item['wisata']->image[0]);
                            }
                        @endphp

                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" 
                                 class="w-full h-52 object-cover" 
                                 alt="Gambar Wisata {{ $item['wisata']->name }}">
                        @else
                            <div class="w-full h-52 bg-base-200 text-base-content flex items-center justify-center text-center p-4">
                                Tidak ada gambar
                            </div>
                        @endif
                    </figure>

                    {{-- Konten Card --}}
                    <div class="card-body p-6 flex flex-col flex-grow">
                        <h2 class="card-title">#{{ $index+1 }}: {{ $item['wisata']->name }}</h2>

                        @if($item['wisata']->link_gmaps)
                            <a href="{{ $item['wisata']->link_gmaps }}" target="_blank" 
                               class="link link-info inline-flex items-center text-sm mb-3">
                                <i class="fas fa-map-marker-alt mr-2"></i> Lihat di Maps
                            </a>
                        @endif

                        <ul class="space-y-1 mb-4 text-sm">
                            <li><span class="font-semibold">Skor:</span> {{ number_format($item['score'], 3) }}</li>
                            <li><span class="font-semibold">Rating:</span> {{ $item['details']['ulasan'] ?? 'N/A' }}/5</li>
                            <li><span class="font-semibold">Jarak:</span> {{ number_format($item['details']['jarak'] ?? 0, 2) }} km</li>
                        </ul>
                        
                        {{-- Collapsible Detail Content --}}
                        <div x-show="openDetail" x-collapse class="mt-4 bg-base-200/50 rounded-md p-4 text-sm">
                            <p class="mb-3"><span class="font-semibold">Deskripsi:</span> {{ $item['wisata']->deskripsi }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                <p class="flex items-center space-x-2"><i class="fas fa-ticket-alt w-4 text-center"></i> <span>Tiket: Rp{{ number_format($item['details']['harga_tiket'] ?? 0) }}</span></p>
                                <p class="flex items-center space-x-2"><i class="fas fa-road w-4 text-center"></i> <span>Akses: {{ number_format($item['details']['aksesibilitas'] ?? 'N/A') }}</span></p>
                                <p class="flex items-center space-x-2"><i class="fas fa-restroom w-4 text-center"></i> <span>Fasilitas: {{ $item['details']['jumlah_fasilitas'] ?? 'N/A' }}</span></p>
                                <p class="flex items-center space-x-2"><i class="fas fa-clock w-4 text-center"></i> <span>Operasional: {{ $item['details']['waktu_operasional'] ?? 'N/A' }} jam</span></p>
                            </div>
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div class="card-actions justify-end items-center mt-auto pt-4 space-x-2">
                            {{-- TOMBOL BARU: LIHAT PROFIL --}}
                            @if(isset($item['wisata']->link_profil) && !empty($item['wisata']->link_profil))
                                <a href="{{ $item['wisata']->link_profil }}" target="_blank" class="btn btn-secondary flex-1">
                                    <i class="fas fa-external-link-alt mr-2"></i>Lihat Profil
                                </a>
                            @else
                                {{-- Jika tidak ada link_profil, arahkan ke halaman detail kustom --}}
                                <a href="{{ route('wisata.show', ['wisata' => $item['wisata']->id]) }}" class="btn btn-secondary flex-1">
                                    <i class="fas fa-info-circle mr-2"></i>Lihat Profil
                                </a>
                            @endif

                            <button @click="openDetail = !openDetail" 
                                    class="btn btn-outline btn-primary">
                                <span x-show="!openDetail"><i class="fas fa-chevron-down"></i></span>
                                <span x-show="openDetail"><i class="fas fa-chevron-up"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- Alert jika tidak ada rekomendasi --}}
            <div role="alert" class="alert alert-warning shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h3 class="font-bold">Perhatian!</h3>
                    <div class="text-xs">
                        Tidak ditemukan rekomendasi wisata yang sesuai.
                        @if(session('error'))
                            <br>Error: {{ session('error') }}
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Tombol kembali --}}
        <div class="mt-12 text-center">
            <a href="{{ route('preferensi')}}" class="btn btn-primary">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pencarian
            </a>
        </div>
    </div>
</div>
@endsection