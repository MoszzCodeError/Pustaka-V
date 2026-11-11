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
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-xs bg-[#111827] hover:bg-gray-800 text-white font-semibold rounded-xl border border-gray-800 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-xs bg-[#FF5500] hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition">Daftar</a>
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

        <!-- MAIN CONTAINER TENTANG -->
        <main class="px-4 sm:px-6 md:px-16 py-8 sm:py-12 max-w-5xl mx-auto w-full">
            <div class="text-center max-w-4xl mx-auto mb-8 sm:mb-10">
                <span class="text-[10px] sm:text-[11px] font-extrabold text-[#FF5500] bg-orange-500/10 px-3.5 py-1.5 rounded-full border border-orange-500/20 uppercase tracking-widest inline-block mb-3">
                    TENTANG PUSTAKA V
                </span>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight mb-3">
                    Pusat Literasi Digital <span class="text-[#FF5500]">SMK Negeri 5 Surakarta</span>
                </h1>
                <p class="text-xs sm:text-sm md:text-base text-gray-400 leading-relaxed max-w-3xl mx-auto">
                    Pustaka V merupakan platform perpustakaan modern berbasis web yang dirancang khusus untuk mempercepat akses referensi akademik, ilmu kejuruan, dan sirkulasi peminjaman buku bagi seluruh civitas akademika.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-[#111827] p-6 sm:p-8 rounded-3xl border border-gray-800 hover:border-[#FF5500]/40 transition duration-300 shadow-xl relative overflow-hidden group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500/10 text-[#FF5500] rounded-2xl flex items-center justify-center mb-3 sm:mb-4 border border-orange-500/20 text-lg sm:text-xl font-bold">🎯</div>
                    <h3 class="text-base sm:text-lg font-extrabold text-white mb-2">Visi Perpustakaan</h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed">
                        Menjadi pusat penyedia informasi dan literasi kejuruan yang modern, adaptif, serta inklusif guna mencetak generasi siswa SMK Negeri 5 Surakarta yang unggul dalam riset dan teknologi.
                    </p>
                </div>

                <div class="bg-[#111827] p-6 sm:p-8 rounded-3xl border border-gray-800 hover:border-[#FF5500]/40 transition duration-300 shadow-xl relative overflow-hidden group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500/10 text-[#FF5500] rounded-2xl flex items-center justify-center mb-3 sm:mb-4 border border-orange-500/20 text-lg sm:text-xl font-bold">⚡</div>
                    <h3 class="text-base sm:text-lg font-extrabold text-white mb-2">Misi Utama</h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed">
                        Menyelenggarakan sistem manajemen sirkulasi peminjaman mandiri, transaksi pelunasan denda transparan via QRIS, serta memfasilitasi akses modul pembelajaran lintas kompetensi keahlian secara cepat.
                    </p>
                </div>
            </div>
        </main>
    </div>

    <footer class="border-t border-gray-800/80 bg-[#080C14] px-4 sm:px-6 md:px-16 py-6 sm:py-8 mt-12 sm:mt-16">
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