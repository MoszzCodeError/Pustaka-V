@extends('layouts.app')

@section('content')
<div class="bg-[#0B0F19] text-gray-200 min-h-screen font-sans antialiased selection:bg-[#FF5500] selection:text-white flex flex-col justify-between" x-data="{ mobileMenuOpen: false }">

    <div>
        <!-- NAVBAR -->
        <nav class="bg-[#0B0F19]/90 backdrop-blur-md border-b border-gray-800/60 sticky top-0 z-50">
            <div class="flex items-center justify-between px-4 sm:px-6 md:px-16 py-4">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-tr from-[#FF5500] to-orange-400 rounded-xl flex items-center justify-center font-black text-white shadow-lg shadow-orange-500/20 group-hover:scale-105 transition">P</div>
                        <div>
                            <span class="text-base sm:text-xl font-black tracking-wide text-white block leading-none">Pustaka V</span>
                            <span class="text-[9px] sm:text-[10px] text-gray-500 font-bold tracking-widest uppercase mt-0.5 sm:mt-1 block">SMKN 5 SURAKARTA</span>
                        </div>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-10 text-sm font-semibold text-gray-400">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Home</a>
                    <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Katalog Buku</a>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Tentang</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Kontak</a>
                </div>

                <div class="hidden md:flex items-center space-x-3">
                    @auth
                        @php
                            $dashboardRoute = auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' ? route('admin.dashboard') : route('dashboard');
                        @endphp
                        <a href="{{ $dashboardRoute }}" class="px-5 py-2.5 text-xs bg-[#111827] hover:bg-gray-800 text-white font-semibold rounded-xl border border-gray-800 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-xs bg-[#111827] hover:bg-gray-800 text-white font-semibold rounded-xl border border-gray-800 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-xs bg-[#FF5500] hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- HAMBURGER BUTTON (MOBILE) -->
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

            <!-- MOBILE MENU DROPDOWN -->
            <div x-show="mobileMenuOpen" 
                 @click.outside="mobileMenuOpen = false"
                 style="display: none;" 
                 class="md:hidden bg-[#0B0F19] border-b border-gray-800 px-4 py-5 space-y-4">
                
                <form action="{{ route('catalog') }}" method="GET" class="relative w-full">
                    @if(request('genre'))
                        <input type="hidden" name="genre" value="{{ request('genre') }}">
                    @endif
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari katalog..." class="w-full bg-[#111827] text-xs text-white px-4 py-2.5 pr-8 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                    <button type="submit" class="absolute right-3 top-3 text-gray-400">🔍</button>
                </form>

                <div class="flex flex-col space-y-3 text-xs font-semibold">
                    <a @click="mobileMenuOpen = false" href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Home</a>
                    <a @click="mobileMenuOpen = false" href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Katalog Buku</a>
                    <a @click="mobileMenuOpen = false" href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Tentang</a>
                    <a @click="mobileMenuOpen = false" href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Kontak</a>
                </div>

                <div class="pt-3 border-t border-gray-800 flex items-center justify-between">
                    @auth
                        @php
                            $dashboardRoute = auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' ? route('admin.dashboard') : route('dashboard');
                        @endphp
                        <span class="text-xs text-gray-400 truncate max-w-[140px]">Hi, {{ Auth::user()->name }}</span>
                        <a href="{{ $dashboardRoute }}" class="bg-[#FF5500] text-white text-xs px-4 py-2 rounded-xl font-bold shadow-lg shadow-orange-500/20">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs text-gray-300 font-semibold">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-[#FF5500] text-white text-xs px-4 py-2 rounded-xl font-bold shadow-lg shadow-orange-500/20">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- MAIN CONTAINER -->
        <main class="px-4 sm:px-6 md:px-16 py-6 sm:py-10 max-w-[1600px] mx-auto w-full">
            
            <!-- HEADER & SEARCH BAR -->
            <div class="mb-6 sm:mb-10">
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight mb-2">Katalog Buku Perpustakaan</h1>
                <p class="text-xs sm:text-base text-gray-400 mb-6">Jelajahi dan pinjam buku favoritmu dengan mudah dari koleksi kami.</p>

                <!-- Search Form -->
                <form action="{{ route('catalog') }}" method="GET" class="flex flex-col sm:flex-row gap-3 max-w-3xl">
                    @if(request('genre'))
                        <input type="hidden" name="genre" value="{{ request('genre') }}">
                    @endif
                    <div class="relative flex-1">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Cari judul buku, penulis, atau penerbit..." 
                               class="w-full bg-[#111827] border border-gray-800 text-xs sm:text-sm text-white placeholder-gray-500 px-4 py-3 pl-10 sm:pl-12 rounded-xl sm:rounded-2xl focus:outline-none focus:border-[#FF5500] transition">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500 absolute left-3.5 sm:left-4 top-3.5 sm:top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="px-6 py-3 sm:py-4 bg-[#FF5500] hover:bg-orange-600 text-white text-xs sm:text-sm font-bold rounded-xl sm:rounded-2xl transition shadow-lg shadow-orange-500/20">
                        Cari Buku
                    </button>
                </form>
            </div>

            <!-- GENRE TABS -->
            <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto pb-3 mb-6 sm:mb-10 scrollbar-none text-xs font-medium">
                @php
                    $allGenreParams = request()->except(['genre', 'page']);
                @endphp
                <a href="{{ route('catalog', $allGenreParams) }}" 
                   class="px-4 py-2 rounded-xl whitespace-nowrap border transition {{ !request('genre') ? 'bg-[#FF5500] text-white border-[#FF5500] font-bold shadow-lg shadow-orange-500/20' : 'bg-[#111827] text-gray-400 border-gray-800 hover:text-white' }}">
                    Semua Genre
                </a>

                @if(isset($genres) && count($genres) > 0)
                    @foreach($genres as $g)
                        @php
                            $genreName = is_object($g) ? ($g->nama_genre ?? $g->nama ?? '') : $g;
                            $genreParams = array_merge(request()->except(['genre', 'page']), ['genre' => $genreName]);
                        @endphp
                        @if(!empty($genreName))
                            <a href="{{ route('catalog', $genreParams) }}" 
                               class="px-4 py-2 rounded-xl whitespace-nowrap border transition {{ request('genre') == $genreName ? 'bg-[#FF5500] text-white border-[#FF5500] font-bold shadow-lg shadow-orange-500/20' : 'bg-[#111827] text-gray-400 border-gray-800 hover:text-white' }}">
                                {{ $genreName }}
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>

            <!-- CONTAINER CARD BUKU -->
            @if(isset($books) && $books->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5 sm:gap-6">
                    @foreach($books as $book)
                        @php
                            $coverPath = $book->cover_image ?? $book->cover ?? null;
                            $stok = $book->stok ?? 0;
                        @endphp

                        <a href="{{ route('books.show', $book->id) }}" 
                           class="bg-[#111827] border border-gray-800/80 hover:border-[#FF5500] rounded-2xl p-2.5 sm:p-3.5 transition duration-300 group flex flex-col justify-between shadow-xl">
                            
                            <div>
                                <div class="aspect-[3/4] w-full rounded-xl overflow-hidden mb-2 sm:mb-3 bg-gray-900 border border-gray-800/80 relative">
                                    <div class="absolute top-1.5 right-1.5 z-10">
                                        @if($stok > 0)
                                            <span class="bg-emerald-500/90 backdrop-blur-md text-white text-[8px] sm:text-[10px] font-black px-1.5 py-0.5 rounded shadow">Tersedia</span>
                                        @else
                                            <span class="bg-red-500/90 backdrop-blur-md text-white text-[8px] sm:text-[10px] font-black px-1.5 py-0.5 rounded shadow">Habis</span>
                                        @endif
                                    </div>

                                    @if($coverPath)
                                        <img src="{{ Str::startsWith($coverPath, 'http') ? $coverPath : asset('storage/' . $coverPath) }}" 
                                             alt="{{ $book->judul }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center p-2 text-center bg-gray-900 text-gray-500">
                                            <span class="text-[10px] font-semibold opacity-60 line-clamp-3">{{ $book->judul }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-2">
                                    <span class="text-[8px] sm:text-[10px] font-extrabold text-[#FF5500] bg-orange-500/10 px-1.5 py-0.5 rounded border border-orange-500/20 uppercase truncate">
                                        {{ $book->genre ?? 'Umum' }}
                                    </span>
                                    <span class="text-[9px] sm:text-xs text-gray-400">Stok: {{ $stok }}</span>
                                </div>

                                <h3 class="text-white font-bold text-xs sm:text-sm leading-snug truncate group-hover:text-[#FF5500] transition" title="{{ $book->judul }}">
                                    {{ $book->judul }}
                                </h3>
                                <p class="text-gray-400 text-[10px] sm:text-xs truncate mt-0.5">Oleh: {{ $book->penulis ?? 'Tidak diketahui' }}</p>
                            </div>

                        </a>
                    @endforeach
                </div>

                <!-- KOMPONEN PAGINASI TAILWIND CLEAN -->
                @if(method_exists($books, 'hasPages') && $books->hasPages())
                    <div class="mt-10 pt-6 border-t border-gray-800/80 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-gray-400">
                            Menampilkan <span class="font-bold text-white">{{ $books->firstItem() }}</span> sampai <span class="font-bold text-white">{{ $books->lastItem() }}</span> dari <span class="font-bold text-white">{{ $books->total() }}</span> buku
                        </div>

                        <div class="flex items-center space-x-2">
                            {{-- Tombol Sebelumnya --}}
                            @if ($books->onFirstPage())
                                <span class="px-4 py-2 text-xs font-semibold text-gray-600 bg-[#111827] border border-gray-800 rounded-xl cursor-not-allowed">
                                    &laquo; Sebelumnya
                                </span>
                            @else
                                <a href="{{ $books->previousPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-white bg-[#111827] hover:bg-gray-800 border border-gray-800 hover:border-[#FF5500] rounded-xl transition">
                                    &laquo; Sebelumnya
                                </a>
                            @endif

                            {{-- Tombol Berikutnya --}}
                            @if ($books->hasMorePages())
                                <a href="{{ $books->nextPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-white bg-[#111827] hover:bg-gray-800 border border-gray-800 hover:border-[#FF5500] rounded-xl transition">
                                    Berikutnya &raquo;
                                </a>
                            @else
                                <span class="px-4 py-2 text-xs font-semibold text-gray-600 bg-[#111827] border border-gray-800 rounded-xl cursor-not-allowed">
                                    Berikutnya &raquo;
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-16 bg-[#111827] rounded-3xl border border-gray-800">
                    <p class="text-gray-400 text-xs sm:text-sm">Buku tidak ditemukan.</p>
                </div>
            @endif
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="border-t border-gray-800/80 bg-[#080C14] px-4 sm:px-6 md:px-16 py-6 sm:py-8 mt-12 sm:mt-20">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-gray-500 max-w-[1600px] mx-auto text-center sm:text-left">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-[#FF5500] rounded-lg flex items-center justify-center font-bold text-white text-xs shadow">P</div>
                <span class="font-bold text-gray-300">Pustaka V — SMKN 5 Surakarta</span>
            </div>
            <p>© {{ date('Y') }} Perpustakaan SMKN 5 Surakarta. All Rights Reserved.</p>
        </div>
    </footer>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection