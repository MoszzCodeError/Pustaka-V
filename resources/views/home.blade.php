@extends('layouts.app')

@section('content')
<div class="bg-[#0B0F19] text-gray-200 min-h-screen font-sans">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-10 py-5 bg-[#0B0F19] border-b border-gray-800/60 sticky top-0 z-50">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-[#FF5500] rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-orange-500/20">P</div>
            <div>
                <span class="text-lg font-bold tracking-wide text-white block leading-none">Pustaka V</span>
                <span class="text-[10px] text-gray-500 font-medium tracking-widest uppercase">SMKN 5 SURAKARTA</span>
            </div>
        </div>

        <div class="flex items-center space-x-8 text-sm font-medium text-gray-400">
            <a href="{{ route('home') }}" class="text-[#FF5500] font-semibold">Home</a>
            <a href="#" class="hover:text-white transition">About</a>
            <a href="{{ route('catalog') }}" class="hover:text-white transition">Catalog</a>
            <a href="{{ route('contact') }}" class="hover:text-white transition">Contact Us</a>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Form Pencarian -->
            <div class="relative hidden md:block">
                <input type="text" placeholder="Search catalog..." class="bg-[#111827] text-xs text-white px-4 py-2 rounded-xl border border-gray-800 focus:outline-none w-48">
            </div>

            <!-- Pengecekan Status Login -->
            @auth
                <!-- Jika USER SUDAH LOGIN: Tampilkan Nama User & Tombol Dashboard -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 bg-[#111827] hover:bg-gray-800 text-white font-medium text-xs px-4 py-2 rounded-xl border border-gray-700 transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="bg-[#FF5500] hover:bg-orange-600 text-white font-medium text-xs px-4 py-2 rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Dashboard
                    </a>
                </div>
            @else
                <!-- Jika USER BELUM LOGIN: Tampilkan Login & Sign Up (Style Awal) -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-300 hover:text-white px-3 py-2 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-[#FF5500] hover:bg-orange-600 text-white font-medium text-xs px-4 py-2 rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Sign Up
                    </a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- HERO BANNER SECTION (AUTO SLIDE 5 DETIK) -->
    <div class="px-10 py-6" 
         x-data="{
            activeSlide: 0,
            slides: [
                {
                    tag: 'NEWS & EVENTS',
                    title: 'Empowering SMKN 5 Creative Creators',
                    desc: 'Explore the redesigned digital catalog! Access 15k+ modern journals, vocational databases, and trending book collections.',
                    image: 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=1200&q=80'
                },
                {
                    tag: 'NEW ARRIVALS',
                    title: 'Koleksi Modul Kejuruan Terbaru 2026',
                    desc: 'Akses cepat ribuan referensi RPL, DKV, TKJ, dan Teknik Otomotif untuk mengasah keahlian kamu.',
                    image: 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1200&q=80'
                },
                {
                    tag: 'DIGITAL HUB',
                    title: 'Layanan Perpustakaan Digital SMKN 5',
                    desc: 'Reservasi dan pinjam buku favoritmu secara praktis dari mana saja dan kapan saja.',
                    image: 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1200&q=80'
                }
            ],
            next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length }
        }"
        x-init="setInterval(() => next(), 5000)">
        
        <div class="relative w-full h-[380px] rounded-3xl bg-cover bg-center overflow-hidden border border-gray-800/80 flex items-end p-10 shadow-2xl transition-all duration-700 ease-in-out"
             :style="`background-image: linear-gradient(to top, rgba(11,15,25,0.95), rgba(11,15,25,0.3)), url('${slides[activeSlide].image}')`">
            
            <div class="max-w-2xl z-10">
                <span class="text-[10px] font-bold text-[#FF5500] tracking-widest uppercase bg-orange-500/10 px-3 py-1 rounded-full border border-orange-500/20" x-text="slides[activeSlide].tag"></span>
                <h1 class="text-4xl font-extrabold text-white mt-3 mb-2 leading-tight transition-all duration-300" x-text="slides[activeSlide].title"></h1>
                <p class="text-gray-400 text-sm leading-relaxed" x-text="slides[activeSlide].desc"></p>
            </div>

            <!-- Slider Controls -->
            <div class="absolute right-8 bottom-8 flex items-center space-x-3 z-10">
                <button @click="prev()" class="w-10 h-10 bg-black/40 hover:bg-black/80 text-white rounded-full flex items-center justify-center border border-gray-700/60 backdrop-blur-md transition cursor-pointer">‹</button>
                <button @click="next()" class="w-10 h-10 bg-[#FF5500] hover:bg-orange-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30 transition cursor-pointer">›</button>
            </div>
        </div>
    </div>

    <!-- TRENDING BOOKS SECTION -->
    <div class="px-10 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white tracking-wide">Trending Books</h2>
            <a href="{{ route('catalog') }}" class="text-xs text-[#FF5500] hover:underline font-semibold flex items-center gap-1">View All Books →</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
            <div class="bg-[#111827] border border-gray-800/80 rounded-2xl p-3 flex flex-col justify-between hover:border-[#FF5500]/50 transition group">
                <div>
                    <div class="h-56 w-full rounded-xl overflow-hidden mb-3 bg-gray-900 border border-gray-800/50 relative">
                        <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2.5 py-0.5 rounded-md border border-orange-500/20 inline-block mb-1.5">Technology</span>
                    <h3 class="font-bold text-white text-sm truncate">Pemrograman Web Modern</h3>
                    <p class="text-xs text-gray-400 mt-0.5">By Tim RPL</p>
                </div>
            </div>
            <div class="bg-[#111827] border border-gray-800/80 rounded-2xl p-3 flex flex-col justify-between hover:border-[#FF5500]/50 transition group">
                <div>
                    <div class="h-56 w-full rounded-xl overflow-hidden mb-3 bg-gray-900 border border-gray-800/50 relative">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2.5 py-0.5 rounded-md border border-orange-500/20 inline-block mb-1.5">Design</span>
                    <h3 class="font-bold text-white text-sm truncate">Desain Grafis DKV</h3>
                    <p class="text-xs text-gray-400 mt-0.5">By Tim DKV</p>
                </div>
            </div>
            <div class="bg-[#111827] border border-gray-800/80 rounded-2xl p-3 flex flex-col justify-between hover:border-[#FF5500]/50 transition group">
                <div>
                    <div class="h-56 w-full rounded-xl overflow-hidden mb-3 bg-gray-900 border border-gray-800/50 relative">
                        <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2.5 py-0.5 rounded-md border border-orange-500/20 inline-block mb-1.5">Network</span>
                    <h3 class="font-bold text-white text-sm truncate">Jaringan Komputer TKJ</h3>
                    <p class="text-xs text-gray-400 mt-0.5">By Tim TKJ</p>
                </div>
            </div>
            <div class="bg-[#111827] border border-gray-800/80 rounded-2xl p-3 flex flex-col justify-between hover:border-[#FF5500]/50 transition group">
                <div>
                    <div class="h-56 w-full rounded-xl overflow-hidden mb-3 bg-gray-900 border border-gray-800/50 relative">
                        <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2.5 py-0.5 rounded-md border border-orange-500/20 inline-block mb-1.5">Automotive</span>
                    <h3 class="font-bold text-white text-sm truncate">Teknik Otomotif Dasar</h3>
                    <p class="text-xs text-gray-400 mt-0.5">By Tim Otomotif</p>
                </div>
            </div>
            <div class="bg-[#111827] border border-gray-800/80 rounded-2xl p-3 flex flex-col justify-between hover:border-[#FF5500]/50 transition group">
                <div>
                    <div class="h-56 w-full rounded-xl overflow-hidden mb-3 bg-gray-900 border border-gray-800/50 relative">
                        <img src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2.5 py-0.5 rounded-md border border-orange-500/20 inline-block mb-1.5">General</span>
                    <h3 class="font-bold text-white text-sm truncate">Kewirausahaan Muda</h3>
                    <p class="text-xs text-gray-400 mt-0.5">By Tim Bisnis</p>
                </div>
            </div>
        </div>
    </div>

    <!-- BROWSE BY GENRE -->
    <div class="px-10 py-6">
        <h2 class="text-xl font-bold text-white mb-4">Browse by Genre</h2>
        <div class="flex flex-wrap gap-3">
            <button class="px-5 py-2 rounded-full bg-[#111827] text-xs font-medium text-gray-300 border border-gray-800 hover:border-[#FF5500] hover:text-white transition flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span> Adventure
            </button>
            <button class="px-5 py-2 rounded-full bg-[#111827] text-xs font-medium text-gray-300 border border-gray-800 hover:border-[#FF5500] hover:text-white transition flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span> Biography
            </button>
            <button class="px-5 py-2 rounded-full bg-[#111827] text-xs font-medium text-gray-300 border border-gray-800 hover:border-[#FF5500] hover:text-white transition flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span> Fiction
            </button>
            <button class="px-5 py-2 rounded-full bg-[#111827] text-xs font-medium text-gray-300 border border-gray-800 hover:border-[#FF5500] hover:text-white transition flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span> Romance
            </button>
            <button class="px-5 py-2 rounded-full bg-[#111827] text-xs font-medium text-gray-300 border border-gray-800 hover:border-[#FF5500] hover:text-white transition flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span> Mystery
            </button>
            <button class="px-5 py-2 rounded-full bg-[#111827] text-xs font-medium text-gray-300 border border-gray-800 hover:border-[#FF5500] hover:text-white transition flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span> History
            </button>
        </div>
    </div>

    <!-- PUSTAKA V PREMIUM UTILITY -->
    <div class="px-10 py-8">
        <span class="text-[10px] font-bold text-[#FF5500] tracking-widest uppercase">FEATURES</span>
        <h2 class="text-2xl font-bold text-white mt-1 mb-6">Pustaka V Premium Utility</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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

    <!-- FULL FOOTER LENGKAP -->
    <footer class="mt-16 border-t border-gray-800/80 bg-[#080C14] px-10 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
            <!-- Brand Column -->
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

            <!-- Quick Links -->
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Quick Links</h4>
                <ul class="space-y-2.5 text-xs text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-[#FF5500] transition">Home</a></li>
                    <li><a href="{{ route('catalog') }}" class="hover:text-[#FF5500] transition">Catalog Directory</a></li>
                    <li><a href="#" class="hover:text-[#FF5500] transition">E-Books Collection</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#FF5500] transition">Contact Support</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Jurusan / Kategori</h4>
                <ul class="space-y-2.5 text-xs text-gray-400">
                    <li><a href="#" class="hover:text-[#FF5500] transition">Rekayasa Perangkat Lunak</a></li>
                    <li><a href="#" class="hover:text-[#FF5500] transition">Desain Komunikasi Visual</a></li>
                    <li><a href="#" class="hover:text-[#FF5500] transition">Teknik Komputer & Jaringan</a></li>
                    <li><a href="#" class="hover:text-[#FF5500] transition">Teknik Otomotif</a></li>
                </ul>
            </div>

            <!-- Contact & Hours -->
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Informasi</h4>
                <ul class="space-y-2.5 text-xs text-gray-400">
                    <li class="flex items-center gap-2">📍 Surakarta, Jawa Tengah</li>
                    <li class="flex items-center gap-2">✉️ perpustakaan@smkn5surakarta.sch.id</li>
                    <li class="flex items-center gap-2">🕒 Senin - Jumat: 07:00 - 15:30 WIB</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800/60 pt-6 flex flex-col md:flex-row justify-between items-center text-[11px] text-gray-500 gap-4">
            <p>© 2026 SMKN 5 Surakarta. All rights reserved.</p>
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