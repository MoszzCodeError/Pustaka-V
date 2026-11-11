@extends('layouts.app')

@section('content')
<div class="bg-[#0B0F19] text-gray-200 min-h-screen font-sans antialiased selection:bg-[#FF5500] selection:text-white" x-data="{ mobileMenuOpen: false }">

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

            <!-- DESKTOP MENU -->
            <div class="hidden md:flex items-center space-x-10 text-sm font-semibold text-gray-400">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Home</a>
                <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') || request()->routeIs('books.show') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Katalog Buku</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Tentang</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-[#FF5500] font-bold' : 'hover:text-white transition' }}">Kontak</a>
            </div>

            <!-- AUTH DESKTOP -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    @php
                        $userRole = strtolower(trim(auth()->user()->role ?? ''));
                        $dashboardRoute = in_array($userRole, ['admin', 'super_admin', 'superadmin', 'administrator']) ? route('admin.dashboard') : route('dashboard');
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
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari katalog..." class="w-full bg-[#111827] text-xs text-white px-4 py-2.5 pr-8 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                <button type="submit" class="absolute right-3 top-3 text-gray-400">🔍</button>
            </form>

            <div class="flex flex-col space-y-3 text-xs font-semibold">
                <a @click="mobileMenuOpen = false" href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Home</a>
                <a @click="mobileMenuOpen = false" href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') || request()->routeIs('books.show') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Katalog Buku</a>
                <a @click="mobileMenuOpen = false" href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Tentang</a>
                <a @click="mobileMenuOpen = false" href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-[#FF5500] font-bold' : 'text-gray-400 hover:text-white' }} py-1">Kontak</a>
            </div>

            <div class="pt-3 border-t border-gray-800 flex items-center justify-between">
                @auth
                    @php
                        $userRole = strtolower(trim(auth()->user()->role ?? ''));
                        $dashboardRoute = in_array($userRole, ['admin', 'super_admin', 'superadmin', 'administrator']) ? route('admin.dashboard') : route('dashboard');
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

    <!-- MAIN CONTENT -->
    <div class="px-4 md:px-10 py-8 max-w-7xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('catalog') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-[#FF5500] mb-6 transition group">
            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Katalog
        </a>

        <!-- Flash Session Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-semibold flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- Cover Sampul Buku -->
            <div class="lg:col-span-4">
                <div class="bg-[#111827] p-3 rounded-3xl border border-gray-800/80 shadow-2xl relative overflow-hidden group">
                    @php
                        $coverPath = $book->cover_image ?? $book->cover ?? null;
                    @endphp

                    @if($coverPath)
                        <img src="{{ Str::startsWith($coverPath, 'http') ? $coverPath : asset('storage/' . $coverPath) }}" 
                             alt="{{ $book->judul }}" 
                             class="w-full h-[450px] md:h-[500px] object-cover rounded-2xl shadow-inner group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-[450px] md:h-[500px] rounded-2xl bg-gradient-to-br from-gray-900 to-gray-800 flex flex-col items-center justify-center p-6 text-center border border-gray-800">
                            <svg class="w-16 h-16 text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <p class="text-sm font-semibold text-gray-500">Sampul Tidak Tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail & Informasi Buku -->
            <div class="lg:col-span-8 flex flex-col justify-between">
                <div>
                    <!-- Badges: Genre & Rak -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="text-[10px] font-extrabold text-[#FF5500] bg-orange-500/10 px-3 py-1 rounded-lg border border-orange-500/20 uppercase tracking-wider">
                            {{ $book->genre ?? 'Umum' }}
                        </span>
                        <span class="text-[10px] font-extrabold text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-lg border border-emerald-500/20 uppercase tracking-wider">
                            📍 {{ $book->nama_rak ?? $book->lokasi_rak ?? 'Belum Diatur' }}
                        </span>
                    </div>

                    <!-- Judul Buku & Penulis -->
                    <h1 class="text-2xl md:text-4xl font-black text-white mb-2 leading-tight">{{ $book->judul }}</h1>
                    <p class="text-gray-400 text-sm font-medium mb-4">Penulis: <span class="text-gray-200 font-semibold">{{ $book->penulis ?? 'Tidak Diketahui' }}</span></p>

                    <!-- Rating Static -->
                    <div class="flex items-center space-x-2 mb-6 bg-[#111827]/50 w-max px-3 py-1.5 rounded-xl border border-gray-800/80">
                        <div class="text-amber-400 text-xs">★★★★★</div>
                        <span class="text-xs font-bold text-white">4.9</span>
                        <span class="text-xs text-gray-500">(120 ulasan siswa)</span>
                    </div>

                    <!-- Metadata Grid Lengkap -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 p-4 rounded-2xl bg-[#111827] border border-gray-800/80 text-xs">
                        <div>
                            <span class="text-gray-500 block text-[10px] uppercase font-bold">Penerbit</span>
                            <span class="text-gray-200 font-medium truncate block mt-0.5">{{ $book->penerbit ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[10px] uppercase font-bold">Tahun Terbit</span>
                            <span class="text-gray-200 font-medium truncate block mt-0.5">{{ $book->tahun_terbit ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[10px] uppercase font-bold">ISBN</span>
                            <span class="text-gray-200 font-mono truncate block mt-0.5">{{ $book->isbn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[10px] uppercase font-bold">Jumlah Halaman</span>
                            <span class="text-gray-200 font-medium truncate block mt-0.5">{{ isset($book->jumlah_halaman) ? $book->jumlah_halaman . ' Halaman' : '-' }}</span>
                        </div>
                    </div>

                    <!-- Sinopsis / Deskripsi -->
                    <div class="mb-8">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sinopsis Buku</h3>
                        <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                            {{ $book->deskripsi ?? 'Belum ada deskripsi sinopsis yang tersedia untuk buku ini.' }}
                        </p>
                    </div>
                </div>

                <!-- Card Pinjam Buku -->
                <div class="bg-[#111827] p-5 md:p-6 rounded-2xl border border-gray-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Status Ketersediaan</span>
                        @if(($book->stok ?? 0) > 0)
                            <span class="inline-flex items-center text-xs font-bold text-emerald-400">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Tersedia di Perpustakaan (Stok: {{ $book->stok }})
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-bold text-red-400">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-2"></span> Sedang Tidak Tersedia / Habis
                            </span>
                        @endif
                    </div>

                    @auth
                        @php
                            $userRole = strtolower(trim(auth()->user()->role ?? ''));
                            $isAdmin = in_array($userRole, ['admin', 'super_admin', 'superadmin', 'administrator']);
                        @endphp

                        @if($isAdmin)
                            <div class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 text-xs font-bold text-center">
                                🔒 Akun Administrator tidak dapat meminjam buku.
                            </div>
                        @else
                            <form action="{{ route('books.borrow', $book->id) }}" method="POST" class="flex items-center gap-3 w-full sm:w-auto">
                                @csrf
                                <select name="durasi" class="bg-[#0B0F19] border border-gray-800 text-xs text-gray-300 px-3 py-3 rounded-xl focus:outline-none focus:border-[#FF5500]">
                                    <option value="3">Pinjam 3 Hari</option>
                                    <option value="7" selected>Pinjam 7 Hari</option>
                                    <option value="14">Pinjam 14 Hari</option>
                                </select>

                                <button type="submit" 
                                        class="w-full sm:w-auto px-6 py-3 bg-[#FF5500] hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-[#FF5500] whitespace-nowrap"
                                        @disabled(($book->stok ?? 0) < 1)>
                                    Pinjam Sekarang
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-[#FF5500] hover:bg-orange-600 text-white font-bold text-xs rounded-xl text-center shadow-lg shadow-orange-500/20 transition duration-200 whitespace-nowrap">
                            Masuk untuk Meminjam
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- RELATED BOOKS SECTION -->
        @if(isset($relatedBooks) && $relatedBooks->count() > 0)
            <div class="mt-16 border-t border-gray-800/80 pt-10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg md:text-xl font-bold text-white">Buku Terkait Lainnya</h2>
                    <a href="{{ route('catalog', ['genre' => $book->genre]) }}" class="text-xs font-semibold text-[#FF5500] hover:underline">Lihat Semua →</a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($relatedBooks as $rel)
                        @php
                            $relCover = $rel->cover_image ?? $rel->cover ?? null;
                        @endphp
                        <a href="{{ route('books.show', $rel->id) }}" class="bg-[#111827] border border-gray-800 rounded-2xl p-3 hover:border-[#FF5500]/60 transition duration-200 block group hover:-translate-y-1">
                            <div class="aspect-[3/4] rounded-xl overflow-hidden mb-3 bg-gray-900 border border-gray-800">
                                @if($relCover)
                                    <img src="{{ Str::startsWith($relCover, 'http') ? $relCover : asset('storage/' . $relCover) }}" 
                                         alt="{{ $rel->judul }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600 text-[10px]">No Cover</div>
                                @endif
                            </div>
                            <span class="text-[9px] font-bold text-[#FF5500] bg-orange-500/10 px-2 py-0.5 rounded border border-orange-500/20 inline-block mb-1">
                                {{ $rel->genre ?? 'Umum' }}
                            </span>
                            <h4 class="text-white font-bold text-xs truncate group-hover:text-[#FF5500] transition" title="{{ $rel->judul }}">{{ $rel->judul }}</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $rel->penulis ?? '-' }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- FOOTER -->
    <footer class="border-t border-gray-800/80 bg-[#080C14] px-4 md:px-10 py-8 mt-16">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-[#FF5500] rounded-lg flex items-center justify-center font-bold text-white text-xs">P</div>
                <span class="font-semibold text-gray-400">Pustaka V — SMKN 5 Surakarta</span>
            </div>
            <p>© {{ date('Y') }} Perpustakaan SMKN 5 Surakarta. All Rights Reserved.</p>
        </div>
    </footer>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection