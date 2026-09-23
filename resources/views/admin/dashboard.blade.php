@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<!-- STATS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 mb-8">
    <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800/80">
        <span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">TOTAL BUKU</span>
        <div class="text-2xl font-black text-white mt-2">{{ number_format($totalBuku) }} Buku</div>
        <p class="text-[11px] text-gray-500 mt-1">Tersimpan di database</p>
    </div>

    <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800/80">
        <span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">BUKU DIPINJAM</span>
        <div class="text-2xl font-black text-[#FF5500] mt-2">{{ number_format($bukuDipinjam) }} Active</div>
        <p class="text-[11px] text-gray-500 mt-1">Sedang dibaca siswa</p>
    </div>

    <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800/80">
        <span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">BUKU TERSEDIA</span>
        <div class="text-2xl font-black text-emerald-400 mt-2">{{ number_format($bukuTersedia) }} Ready</div>
        <p class="text-[11px] text-gray-500 mt-1">Siap dipinjam</p>
    </div>

    <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800/80">
        <span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">SISWA AKTIF</span>
        <div class="text-2xl font-black text-white mt-2">{{ number_format($siswaAktif) }} Siswa</div>
        <p class="text-[11px] text-gray-500 mt-1">Anggota perpustakaan</p>
    </div>
</div>

<!-- AKSI CEPAT ADMIN -->
<div class="mb-8">
    <h3 class="text-sm font-bold text-gray-300 mb-3">Aksi Cepat Admin</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.buku') }}" class="bg-[#FF5500] hover:bg-orange-600 p-4 rounded-xl font-bold text-white text-xs transition shadow-lg shadow-orange-500/20 block text-center">
            + Tambah Buku Baru
        </a>
        <a href="{{ route('admin.pinjaman') }}" class="bg-[#111827] hover:bg-gray-800 p-4 rounded-xl font-bold text-gray-300 text-xs transition border border-gray-800 block text-center">
            Proses Pengembalian
        </a>
        <a href="{{ route('admin.riwayat') }}" class="bg-[#111827] hover:bg-gray-800 p-4 rounded-xl font-bold text-gray-300 text-xs transition border border-gray-800 block text-center">
            Lihat Total Denda
        </a>
    </div>
</div>
@endsection