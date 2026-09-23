@extends('layouts.app')

@section('content')
<div class="bg-[#0B0F19] text-gray-200 min-h-screen font-sans">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-10 py-5 bg-[#0B0F19] border-b border-gray-800/60 sticky top-0 z-50">
        <div class="flex items-center space-x-3">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-[#FF5500] rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-orange-500/20">P</div>
                <div>
                    <span class="text-lg font-bold tracking-wide text-white block leading-none">Pustaka V</span>
                    <span class="text-[10px] text-gray-500 font-medium tracking-widest uppercase">SMKN 5 SURAKARTA</span>
                </div>
            </a>
        </div>

        <div class="flex items-center space-x-8 text-sm font-medium text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <a href="#" class="hover:text-white transition">About</a>
            <a href="{{ route('catalog') }}" class="text-[#FF5500] font-semibold">Catalog</a>
            <a href="#" class="hover:text-white transition">Contact Us</a>
        </div>

        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="Search catalog..." class="bg-[#111827] text-xs text-gray-300 pl-9 pr-4 py-2 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] w-48 transition">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            @auth
                <a href="#" class="px-4 py-2 text-xs bg-[#111827] hover:bg-gray-800 text-white font-medium rounded-xl border border-gray-800 transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-xs bg-[#111827] hover:bg-gray-800 text-white font-medium rounded-xl border border-gray-800 transition">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-xs bg-[#FF5500] hover:bg-orange-600 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/20 transition">Sign Up</a>
            @endauth
        </div>
    </nav>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="max-w-6xl mx-auto px-6 py-8">

        <!-- Back Button -->
        <a href="{{ route('catalog') }}" class="inline-flex items-center text-xs font-medium text-gray-400 hover:text-white transition mb-8 gap-2">
            ← Back to Catalog
        </a>

        <!-- BOOK DETAIL SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">
            
            <!-- Left: Book Cover Large Card -->
            <div class="md:col-span-4">
                <div class="rounded-2xl overflow-hidden bg-[#111827] border border-gray-800 p-3 shadow-2xl">
                    <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&q=80' }}" alt="{{ $book->judul }}" class="w-full h-[450px] object-cover rounded-xl">
                </div>
            </div>

            <!-- Right: Book Information -->
            <div class="md:col-span-8 flex flex-col justify-between h-full">
                <div>
                    <!-- Badges -->
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2.5 py-0.5 rounded-md border border-orange-500/20">{{ $book->genre ?? 'Fiction' }}</span>
                        <span class="text-[10px] font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-md border border-emerald-500/20">Rak {{ $book->lokasi_rak ?? 'A-12' }}</span>
                    </div>

                    <!-- Title & Author -->
                    <h1 class="text-3xl font-extrabold text-white tracking-wide mb-1">{{ $book->judul }}</h1>
                    <p class="text-sm text-gray-400 mb-3">By {{ $book->penulis }}</p>

                    <!-- Rating -->
                    <div class="flex items-center space-x-2 mb-6 text-xs">
                        <div class="flex text-amber-400">
                            ★★★★★
                        </div>
                        <span class="font-bold text-white">4.9</span>
                        <span class="text-gray-500">(120 student reviews)</span>
                    </div>

                    <!-- Navigation Tabs (Synopsis, Details, Reviews) -->
                    <div x-data="{ tab: 'synopsis' }">
                        <div class="flex space-x-6 border-b border-gray-800 text-xs font-semibold pb-2 mb-4">
                            <button @click="tab = 'synopsis'" :class="tab === 'synopsis' ? 'text-[#FF5500] border-b-2 border-[#FF5500]' : 'text-gray-400 hover:text-white'" class="pb-2 transition">Synopsis</button>
                            <button @click="tab = 'details'" :class="tab === 'details' ? 'text-[#FF5500] border-b-2 border-[#FF5500]' : 'text-gray-400 hover:text-white'" class="pb-2 transition">Details</button>
                            <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'text-[#FF5500] border-b-2 border-[#FF5500]' : 'text-gray-400 hover:text-white'" class="pb-2 transition">Reviews</button>
                        </div>

                        <!-- Tab Contents -->
                        <div x-show="tab === 'synopsis'" class="text-xs text-gray-400 leading-relaxed mb-8">
                            {{ $book->deskripsi ?? 'Buku ini berisi pembahasan mendalam dan cerita menarik yang sangat direkomendasikan untuk dibaca oleh siswa dan guru SMKN 5 Surakarta.' }}
                        </div>

                        <div x-show="tab === 'details'" class="text-xs text-gray-400 space-y-2 mb-8" x-cloak>
                            <p><span class="text-gray-200 font-semibold">Penerbit:</span> {{ $book->penerbit ?? 'Gramedia' }}</p>
                            <p><span class="text-gray-200 font-semibold">Tahun Terbit:</span> {{ $book->tahun_terbit ?? '2023' }}</p>
                            <p><span class="text-gray-200 font-semibold">Stok Tersedia:</span> {{ $book->stok ?? 5 }} eksemplar</p>
                        </div>

                        <div x-show="tab === 'reviews'" class="text-xs text-gray-400 mb-8" x-cloak>
                            <p>Belum ada ulasan baru dari siswa.</p>
                        </div>
                    </div>
                </div>

                <!-- BORROW CARD / BOX ACTION -->
                <form action="{{ route('books.borrow', $book->id) }}" method="POST" class="bg-[#111827] border border-gray-800 p-5 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 mt-4">
                    @csrf
                    
                    <div>
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider block">AVAILABILITY</span>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-semibold text-white">Available in Library</span>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto">
                        <label class="text-[10px] text-gray-500 font-bold uppercase tracking-wider block mb-1">RENTAL DURATION</label>
                        <select name="duration" class="bg-[#182032] border border-gray-700/60 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#FF5500] w-full">
                            <option value="3">3 hari</option>
                            <option value="7">7 hari</option>
                            <option value="14">14 hari</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-auto">
                        <button type="submit" class="w-full bg-[#FF5500] hover:bg-orange-600 text-white font-semibold text-xs px-8 py-3 rounded-xl shadow-lg shadow-orange-500/20 transition duration-200">
                            Pinjam Buku
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- RELATED BOOKS SECTION -->
        <div class="mt-20">
            <h2 class="text-xl font-bold text-white mb-6">Related Books</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedBooks as $related)
                <a href="{{ route('books.show', $related->id) }}" class="bg-[#111827] border border-gray-800 rounded-2xl p-3 flex flex-col justify-between hover:border-[#FF5500]/50 transition group">
                    <div>
                        <div class="h-56 w-full rounded-xl overflow-hidden mb-3 bg-gray-900 relative">
                            <img src="{{ $related->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&q=80' }}" alt="{{ $related->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2 py-0.5 rounded border border-orange-500/20 inline-block mb-1.5">{{ $related->genre ?? 'General' }}</span>
                        <h3 class="font-bold text-white text-sm truncate group-hover:text-[#FF5500] transition">{{ $related->judul }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">By {{ $related->penulis }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="mt-16 border-t border-gray-800/80 bg-[#080C14] px-10 py-10">
        <div class="flex justify-between items-center text-[11px] text-gray-500">
            <p>© 2026 SMKN 5 Surakarta. Built with elegant Gen Z utility.</p>
        </div>
    </footer>

</div>
@endsection