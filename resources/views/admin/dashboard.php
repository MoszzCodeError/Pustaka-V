@extends('layouts.admin')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-white">Dashboard Overview</h1>
        <p class="text-xs text-gray-400">Sistem Informasi Manajemen Perpustakaan SMKN 5 Surakarta</p>
    </div>

    <!-- CARDS STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-[#111827] border border-gray-800 rounded-2xl p-4 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL BUKU</p>
                <p class="text-2xl font-extrabold text-white mt-1">{{ number_format($totalBuku) }} Buku</p>
                <p class="text-[10px] text-gray-500 mt-1">Tersimpan di database</p>
            </div>
            <div class="w-10 h-10 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-[#FF5500]">📖</div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-2xl p-4 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">BUKU DIPINJAM</p>
                <p class="text-2xl font-extrabold text-white mt-1">{{ number_format($bukuDipinjam) }} Active</p>
                <p class="text-[10px] text-gray-500 mt-1">Sedang dibaca siswa</p>
            </div>
            <div class="w-10 h-10 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center justify-center text-amber-500">⚙️</div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-2xl p-4 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">BUKU TERSEDIA</p>
                <p class="text-2xl font-extrabold text-white mt-1">{{ number_format($bukuTersedia) }} Ready</p>
                <p class="text-[10px] text-gray-500 mt-1">Siap dipinjam</p>
            </div>
            <div class="w-10 h-10 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-500">✔</div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-2xl p-4 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">SISWA AKTIF</p>
                <p class="text-2xl font-extrabold text-white mt-1">{{ number_format($siswaAktif) }} Siswa</p>
                <p class="text-[10px] text-gray-500 mt-1">Anggota perpustakaan</p>
            </div>
            <div class="w-10 h-10 bg-sky-500/10 border border-sky-500/20 rounded-xl flex items-center justify-center text-sky-500">👥</div>
        </div>
    </div>

    <!-- AKSI CEPAT ADMIN -->
    <div class="mb-8">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Aksi Cepat Admin</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.books.index') }}" class="bg-[#FF5500] hover:bg-orange-600 p-4 rounded-2xl text-white font-bold text-sm shadow-lg shadow-orange-500/20 transition flex flex-col justify-between h-24">
                <span>+ Tambah Buku Baru</span>
                <span class="text-[10px] opacity-80 font-normal">Input database katalog</span>
            </a>
        </div>
    </div>
</div>
@endsection