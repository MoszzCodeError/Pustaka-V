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
            <form action="{{ route('catalog') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search catalog..." class="bg-[#111827] text-xs text-gray-300 pl-9 pr-4 py-2 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] w-48 transition">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
            
            @auth
                <a href="#" class="px-4 py-2 text-xs bg-[#111827] hover:bg-gray-800 text-white font-medium rounded-xl border border-gray-800 transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-xs bg-[#111827] hover:bg-gray-800 text-white font-medium rounded-xl border border-gray-800 transition">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-xs bg-[#FF5500] hover:bg-orange-600 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/20 transition">Sign Up</a>
            @endauth
        </div>
    </nav>

    <!-- HEADER TITLE & FILTER -->
    <div class="px-10 py-8">
        <h1 class="text-3xl font-extrabold text-white tracking-wide">Library Catalog</h1>
        <p class="text-xs text-gray-400 mt-1">Explore and discover thousands of books available in SMKN 5 Surakarta library.</p>

        <!-- Genre Filter Bar -->
        <div class="flex items-center gap-2 mt-6 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('catalog') }}" class="px-4 py-1.5 rounded-full text-xs font-medium transition whitespace-nowrap {{ !request('genre') ? 'bg-[#FF5500] text-white' : 'bg-[#111827] text-gray-400 border border-gray-800 hover:text-white' }}">All Genres</a>
            @foreach(['Fiction', 'Adventure', 'History', 'Self-Help', 'Mystery', 'Technology', 'Science', 'Romance'] as $g)
                <a href="{{ route('catalog', ['genre' => $g]) }}" class="px-4 py-1.5 rounded-full text-xs font-medium transition whitespace-nowrap {{ request('genre') == $g ? 'bg-[#FF5500] text-white' : 'bg-[#111827] text-gray-400 border border-gray-800 hover:text-white' }}">{{ $g }}</a>
            @endforeach
        </div>
    </div>

    <!-- BOOK LIST GRID (4 Ke Samping & Mengalir Ke Bawah) -->
    <div class="px-10 pb-16">
        @if($books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($books as $book)
                <a href="{{ route('books.show', $book->id) }}" class="bg-[#111827] border border-gray-800/80 rounded-2xl p-3.5 flex flex-col justify-between hover:border-[#FF5500]/60 transition group hover:-translate-y-1 duration-200">
                    <div>
                        <div class="h-64 w-full rounded-xl overflow-hidden mb-3 bg-gray-900 border border-gray-800/50 relative">
                            <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&q=80' }}" alt="{{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <span class="text-[10px] font-semibold text-[#FF5500] bg-orange-500/10 px-2.5 py-0.5 rounded-md border border-orange-500/20 inline-block mb-2">{{ $book->genre ?? 'General' }}</span>
                        <h3 class="font-bold text-white text-sm truncate group-hover:text-[#FF5500] transition">{{ $book->judul }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5 truncate">By {{ $book->penulis }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $books->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-[#111827] rounded-3xl border border-gray-800/80">
                <p class="text-gray-400 text-sm">Tidak ada buku yang ditemukan.</p>
            </div>
        @endif
    </div>

    <!-- FOOTER -->
    <footer class="border-t border-gray-800/80 bg-[#080C14] px-10 py-10">
        <div class="flex justify-between items-center text-[11px] text-gray-500">
            <p>© 2026 SMKN 5 Surakarta. Built with elegant Gen Z utility.</p>
        </div>
    </footer>

</div>
@endsection