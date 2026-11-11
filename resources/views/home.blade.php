@extends('layouts.app')

@section('content')
<div class="bg-[#0B0F19] text-gray-200 min-h-screen font-sans" x-data="{ mobileMenuOpen: false }">

    <!-- NAVBAR -->
    <nav class="bg-[#0B0F19] border-b border-gray-800/60 sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 md:px-10 py-5">
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-[#FF5500] rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-orange-500/20">P</div>
                    <div>
                        <span class="text-lg font-bold tracking-wide text-white block leading-none">Pustaka V</span>
                        <span class="text-[10px] text-gray-500 font-medium tracking-widest uppercase">SMKN 5 SURAKARTA</span>
                    </div>
                </a>
            </div>

            <!-- DESKTOP MENU (TIDAK BERUBAH) -->
            <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-400">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#FF5500] font-semibold' : 'hover:text-white transition' }}">Home</a>
                <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-[#FF5500] font-semibold' : 'hover:text-white transition' }}">Katalog Buku</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#FF5500] font-semibold' : 'hover:text-white transition' }}">Tentang</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-[#FF5500] font-semibold' : 'hover:text-white transition' }}">Kontak</a>
            </div>

            <!-- SEARCH & AUTH DESKTOP (TIDAK BERUBAH) -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Form Pencarian -->
                <form action="{{ route('catalog') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search catalog..." class="bg-[#111827] text-xs text-white px-4 py-2 pr-8 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] w-48 transition">
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-white text-xs">🔍</button>
                </form>

                <!-- Status Login -->
                @auth
                    @php
                        $dashboardRoute = auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' ? route('admin.dashboard') : route('dashboard');
                    @endphp
                    <div class="flex items-center space-x-3">
                        <a href="{{ $dashboardRoute }}" class="flex items-center gap-2 bg-[#111827] hover:bg-gray-800 text-white font-medium text-xs px-4 py-2 rounded-xl border border-gray-700 transition">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                        </a>
                        <a href="{{ $dashboardRoute }}" class="bg-[#FF5500] hover:bg-orange-600 text-white font-medium text-xs px-4 py-2 rounded-xl shadow-lg shadow-orange-500/20 transition">
                            Dashboard
                        </a>
                    </div>
                @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-300 hover:text-white px-3 py-2 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-[#FF5500] hover:bg-orange-600 text-white font-medium text-xs px-4 py-2 rounded-xl shadow-lg shadow-orange-500/20 transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- HAMBURGER BUTTON (HANYA MUNCUL DI MOBILE/HP) -->
            <div class="flex items-center md:hidden">
                <button @click.stop="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="text-gray-300 hover:text-white p-2 rounded-xl bg-[#111827] border border-gray-800 focus:outline-none transition active:scale-95">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6 text-[#FF5500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- MOBILE MENU DROPDOWN (KHUSUS MOBILE) -->
        <div x-show="mobileMenuOpen" 
             @click.outside="mobileMenuOpen = false"
             style="display: none;" 
             class="md:hidden bg-[#0B0F19] border-b border-gray-800 px-6 py-5 space-y-4">
            
            <form action="{{ route('catalog') }}" method="GET" class="relative w-full">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search catalog..." class="w-full bg-[#111827] text-xs text-white px-4 py-2.5 pr-8 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                <button type="submit" class="absolute right-3 top-3 text-gray-400">🔍</button>
            </form>

            <div class="flex flex-col space-y-3 text-sm font-semibold">
                <a @click="mobileMenuOpen = false" href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#FF5500]' : 'text-gray-400 hover:text-white' }}">Home</a>
                <a @click="mobileMenuOpen = false" href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-[#FF5500]' : 'text-gray-400 hover:text-white' }}">Katalog Buku</a>
                <a @click="mobileMenuOpen = false" href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#FF5500]' : 'text-gray-400 hover:text-white' }}">Tentang</a>
                <a @click="mobileMenuOpen = false" href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-[#FF5500]' : 'text-gray-400 hover:text-white' }}">Kontak</a>
            </div>

            <div class="pt-3 border-t border-gray-800 flex items-center justify-between">
                @auth
                    @php
                        $dashboardRoute = auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' ? route('admin.dashboard') : route('dashboard');
                    @endphp
                    <span class="text-xs text-gray-400 truncate max-w-[140px]">Hi, {{ Auth::user()->name }}</span>
                    <a href="{{ $dashboardRoute }}" class="bg-[#FF5500] text-white text-xs px-4 py-2 rounded-xl font-bold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-xs text-gray-300 font-semibold">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-[#FF5500] text-white text-xs px-4 py-2 rounded-xl font-bold">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO BANNER SECTION (KODE ASLI KAMU) -->
    @php
        $defaultSlides = [
            [
                'tag' => 'NEWS & EVENTS',
                'title' => 'Empowering SMKN 5 Creative Creators',
                'desc' => 'Explore the redesigned digital catalog! Access modern journals, vocational databases, and trending book collections.',
                'image' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=1200&q=80'
            ],
            [
                'tag' => 'NEW ARRIVALS',
                'title' => 'Koleksi Modul Kejuruan Terbaru 2026',
                'desc' => 'Akses cepat ribuan referensi RPL, DKV, TKJ, dan Teknik Otomotif untuk mengasah keahlian kamu.',
                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1200&q=80'
            ],
            [
                'tag' => 'DIGITAL HUB',
                'title' => 'Layanan Perpustakaan Digital SMKN 5',
                'desc' => 'Reservasi dan pinjam buku favoritmu secara praktis dari mana saja dan kapan saja.',
                'image' => 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1200&q=80'
            ]
        ];
        
        $slidesData = (isset($banners) && count($banners) > 0) ? $banners : $defaultSlides;
    @endphp

    <div class="px-6 md:px-10 py-6" 
         x-data="{
            activeSlide: 0,
            slides: {{ json_encode($slidesData) }},
            next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length }
         }"
         x-init="setInterval(() => next(), 5000)">
        
        <div class="relative w-full h-[380px] rounded-3xl bg-cover bg-center overflow-hidden border border-gray-800/80 flex items-end p-6 md:p-10 shadow-2xl transition-all duration-700 ease-in-out"
             :style="`background-image: linear-gradient(to top, rgba(11,15,25,0.95), rgba(11,15,25,0.3)), url('${slides[activeSlide].image}')`">
            
            <div class="max-w-2xl z-10">
                <span class="text-[10px] font-bold text-[#FF5500] tracking-widest uppercase bg-orange-500/10 px-3 py-1 rounded-full border border-orange-500/20" x-text="slides[activeSlide].tag || 'ANNOUNCEMENT'"></span>
                <h1 class="text-2xl md:text-4xl font-extrabold text-white mt-3 mb-2 leading-tight transition-all duration-300" x-text="slides[activeSlide].title"></h1>
                <p class="text-gray-400 text-xs md:text-sm leading-relaxed line-clamp-2 md:line-clamp-none" x-text="slides[activeSlide].desc || slides[activeSlide].description"></p>
            </div>

            <!-- Slider Controls -->
            <div class="absolute right-6 md:right-8 bottom-6 md:bottom-8 flex items-center space-x-3 z-10">
                <button @click="prev()" class="w-10 h-10 bg-black/40 hover:bg-black/80 text-white rounded-full flex items-center justify-center border border-gray-700/60 backdrop-blur-md transition cursor-pointer">‹</button>
                <button @click="next()" class="w-10 h-10 bg-[#FF5500] hover:bg-orange-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30 transition cursor-pointer">›</button>
            </div>
        </div>
    </div>

    <!-- TRENDING BOOKS SECTION (KODE ASLI KAMU - 7 KOLOM) -->
    <div class="px-6 md:px-10 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white tracking-wide">Trending Books</h2>
            <a href="{{ route('catalog') }}" class="text-xs text-[#FF5500] hover:underline font-semibold flex items-center gap-1">View All Books →</a>
        </div>

        <!-- GRID 7 KOLOM (KODE ASLI KAMU) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3 w-full">
            @forelse($books ?? [] as $book)
                <a href="{{ route('books.show', $book->id) }}" class="bg-[#111827] border border-gray-800/80 rounded-2xl p-2.5 flex flex-col justify-between hover:border-[#FF5500]/50 transition group">
                    <div>
                        <!-- Sampul Kecil Aspect 3/4 -->
                        <div class="aspect-[3/4] w-full rounded-xl overflow-hidden mb-2 bg-gray-900 border border-gray-800/50 relative">
                            @if(!empty($book->cover))
                                <img src="{{ Str::startsWith($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&q=80" alt="{{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @endif

                            <span class="absolute top-1.5 right-1.5 bg-emerald-500/90 backdrop-blur-sm text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full shadow">
                                Tersedia
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-1 mb-1">
                            <span class="text-[8px] font-bold text-[#FF5500] bg-orange-500/10 px-1.5 py-0.5 rounded border border-orange-500/20 uppercase truncate max-w-[65px]">
                                {{ $book->genre ?? $book->kategori ?? 'Umum' }}
                            </span>
                            <span class="text-[8px] text-gray-400">
                                Stok: <strong class="text-white">{{ $book->stok ?? 1 }}</strong>
                            </span>
                        </div>

                        <h3 class="font-bold text-white text-[11px] truncate leading-tight" title="{{ $book->judul }}">{{ $book->judul }}</h3>
                        <p class="text-[9px] text-gray-400 mt-0.5 truncate">Oleh: {{ $book->penulis ?? $book->pengarang ?? 'Penulis' }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500 text-xs">
                    Belum ada koleksi buku yang tersedia di perpustakaan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- BROWSE BY GENRE (KODE ASLI KAMU) -->
    <div class="px-6 md:px-10 py-6">
        <h2 class="text-xl font-bold text-white mb-4">Browse by Genre</h2>
        <div class="flex flex-wrap gap-3">
            @php
                $genreList = (isset($categories) && count($categories) > 0) 
                    ? $categories 
                    : ['Adventure', 'Biography', 'Fiction', 'Romance', 'Mystery', 'History', 'Technology', 'Automotive'];
            @endphp

            @foreach ($genreList as $item)
                @php
                    $genreName = is_object($item) ? ($item->nama_genre ?? $item->nama ?? $item) : $item;
                @endphp
                <a href="{{ route('catalog', ['genre' => $genreName]) }}" 
                   class="px-5 py-2 rounded-full bg-[#111827] text-xs font-medium text-gray-300 border border-gray-800 hover:border-[#FF5500] hover:text-white transition flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span> 
                    {{ $genreName }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- PUSTAKA V PREMIUM UTILITY (KODE ASLI KAMU) -->
    <div class="px-6 md:px-10 py-8">
        <span class="text-[10px] font-bold text-[#FF5500] tracking-widest uppercase">FEATURES</span>
        <h2 class="text-2xl font-bold text-white mt-1 mb-6">Pustaka V Premium Utility</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800/80 hover:border-gray-700 transition">
                <div class="w-10 h-10 bg-orange-500/10 text-[#FF5500] rounded-xl flex items-center justify-center mb-4 border border-orange-500/20 text-lg">📱</div>
                <h3 class="font-bold text-white text-sm mb-1">Online Borrowing</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Reserve & secure books instantly via client app.</p>
            </div>
            <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800/80 hover:border-gray-700 transition">
                <div class="w-10 h-10 bg-orange-500/10 text-[#FF5500] rounded-xl flex items-center justify-center mb-4 border border-orange-500/20 text-lg">🔄</div>
                <h3 class="font-bold text-white text-sm mb-1">Returns & Exchanges</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Automated drops & scan locations at SMKN 5.</p>
            </div>
            <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800/80 hover:border-gray-700 transition">
                <div class="w-10 h-10 bg-orange-500/10 text-[#FF5500] rounded-xl flex items-center justify-center mb-4 border border-orange-500/20 text-lg">⏰</div>
                <h3 class="font-bold text-white text-sm mb-1">24-Hour Access</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Browse catalog and digital resources anytime.</p>
            </div>
            <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800/80 hover:border-gray-700 transition">
                <div class="w-10 h-10 bg-orange-500/10 text-[#FF5500] rounded-xl flex items-center justify-center mb-4 border border-orange-500/20 text-lg">📄</div>
                <h3 class="font-bold text-white text-sm mb-1">Digital Catalog</h3>
                <p class="text-xs text-gray-400 leading-relaxed">E-books and modern PDF reading portals.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER (KODE ASLI KAMU) -->
    <footer class="mt-16 border-t border-gray-800/80 bg-[#080C14] px-6 md:px-10 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-[#FF5500] rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-orange-500/20">P</div>
                    <div>
                        <span class="text-lg font-bold tracking-wide text-white block leading-none">Pustaka V</span>
                        <span class="text-[10px] text-gray-500 font-medium tracking-widest uppercase">SMKN 5 SURAKARTA</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Platform Layanan Perpustakaan Digital Resmi SMKN 5 Surakarta. Menghubungkan siswa dengan ribuan koleksi buku & referensi ilmu kejuruan secara modern.
                </p>
            </div>

            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">QUICK LINKS</h4>
                <ul class="space-y-2.5 text-xs text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-[#FF5500] transition">Home</a></li>
                    <li><a href="{{ route('catalog') }}" class="hover:text-[#FF5500] transition">Katalog Buku</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#FF5500] transition">Tentang</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#FF5500] transition">Kontak</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">INFORMASI</h4>
                <ul class="space-y-2.5 text-xs text-gray-400">
                    <li class="flex items-center gap-2">📍 Surakarta, Jawa Tengah</li>
                    <li class="flex items-center gap-2">📞 +62 812-2593-1965</li>
                    <li class="flex items-center gap-2">✉️ lapor@smkn5surakarta.sch.id</li>
                    <li class="flex items-center gap-2">🕒 Senin - Jumat: 07:00 - 15:30 WIB</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800/60 pt-6 flex flex-col md:flex-row justify-between items-center text-[11px] text-gray-500 gap-4">
            <p>© {{ date('Y') }} SMKN 5 Surakarta. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-gray-300 transition">Privacy Policy</a>
                <a href="#" class="hover:text-gray-300 transition">Terms of Service</a>
            </div>
        </div>
    </footer>

</div>

<!-- CDN Alpine JS -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection