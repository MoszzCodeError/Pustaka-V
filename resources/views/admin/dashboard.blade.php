@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="p-4 sm:p-6 bg-[#0B0F17] min-h-screen text-white font-sans">

    <!-- HEADER WELCOME -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-2xl font-bold">Dashboard Admin Overview</h1>
        <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Ringkasan statistik perpustakaan Pustaka V dan akses cepat transaksi</p>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5 mb-6 sm:mb-8">
        <div class="bg-[#111827] p-4 sm:p-5 rounded-2xl border border-gray-800/80 flex justify-between items-center sm:block">
            <div>
                <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-wider uppercase">TOTAL BUKU</span>
                <div class="text-xl sm:text-2xl font-black text-white mt-1 sm:mt-2">{{ number_format($totalBuku) }} Buku</div>
                <p class="text-[10px] sm:text-[11px] text-gray-500 mt-0.5">Tersimpan di database</p>
            </div>
            <div class="w-10 h-10 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-[#FF5500] sm:hidden text-lg">📖</div>
        </div>

        <div class="bg-[#111827] p-4 sm:p-5 rounded-2xl border border-gray-800/80 flex justify-between items-center sm:block">
            <div>
                <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-wider uppercase">BUKU DIPINJAM</span>
                <div class="text-xl sm:text-2xl font-black text-[#FF5500] mt-1 sm:mt-2">{{ number_format($bukuDipinjam) }} Active</div>
                <p class="text-[10px] sm:text-[11px] text-gray-500 mt-0.5">Sedang dibaca siswa</p>
            </div>
            <div class="w-10 h-10 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center justify-center text-amber-500 sm:hidden text-lg">⚙️</div>
        </div>

        <div class="bg-[#111827] p-4 sm:p-5 rounded-2xl border border-gray-800/80 flex justify-between items-center sm:block">
            <div>
                <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-wider uppercase">BUKU TERSEDIA</span>
                <div class="text-xl sm:text-2xl font-black text-emerald-400 mt-1 sm:mt-2">{{ number_format($bukuTersedia) }} Ready</div>
                <p class="text-[10px] sm:text-[11px] text-gray-500 mt-0.5">Siap dipinjam</p>
            </div>
            <div class="w-10 h-10 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-500 sm:hidden text-lg">✔</div>
        </div>

        <div class="bg-[#111827] p-4 sm:p-5 rounded-2xl border border-gray-800/80 flex justify-between items-center sm:block">
            <div>
                <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-wider uppercase">SISWA AKTIF</span>
                <div class="text-xl sm:text-2xl font-black text-white mt-1 sm:mt-2">{{ number_format($siswaAktif) }} Siswa</div>
                <p class="text-[10px] sm:text-[11px] text-gray-500 mt-0.5">Anggota perpustakaan</p>
            </div>
            <div class="w-10 h-10 bg-sky-500/10 border border-sky-500/20 rounded-xl flex items-center justify-center text-sky-500 sm:hidden text-lg">👥</div>
        </div>
    </div>

    <!-- AKSI CEPAT ADMIN -->
    <div class="mb-6 sm:mb-8">
        <h3 class="text-xs sm:text-sm font-bold text-gray-300 mb-3">Aksi Cepat Admin</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <a href="{{ route('admin.buku') }}" class="bg-[#FF5500] hover:bg-orange-600 p-3.5 sm:p-4 rounded-xl font-bold text-white text-xs transition shadow-lg shadow-orange-500/20 block text-center">
                + Tambah Buku Baru
            </a>
            <a href="{{ route('admin.pinjaman') }}" class="bg-[#111827] hover:bg-gray-800 p-3.5 sm:p-4 rounded-xl font-bold text-cyan-400 text-xs transition border border-gray-800 block text-center">
                📲 Verifikasi QRIS & Kode
            </a>
            <a href="{{ route('admin.pinjaman') }}" class="bg-[#111827] hover:bg-gray-800 p-3.5 sm:p-4 rounded-xl font-bold text-emerald-400 text-xs transition border border-gray-800 block text-center">
                🔄 Proses Pengembalian
            </a>
            <a href="{{ route('admin.riwayat') }}" class="bg-[#111827] hover:bg-gray-800 p-3.5 sm:p-4 rounded-xl font-bold text-yellow-400 text-xs transition border border-gray-800 block text-center">
                💵 Lihat Total Denda
            </a>
        </div>
    </div>

    <!-- PENGUMUMAN / STATUS QRIS BRIEF -->
    <div class="bg-[#111827] border border-gray-800 rounded-2xl p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500/10 border border-orange-500/20 text-[#FF5500] rounded-xl flex items-center justify-center text-lg sm:text-xl flex-shrink-0">
                ⚙️
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-white">Kelola Banner & QRIS Pembayaran</h4>
                <p class="text-[11px] sm:text-xs text-gray-400 mt-0.5">Atur slider promosi, berita terbaru, dan unggah gambar QRIS pelunasan denda.</p>
            </div>
        </div>
        <a href="{{ route('admin.banner') }}" class="w-full sm:w-auto text-center px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-white font-semibold text-xs rounded-xl border border-gray-700 transition cursor-pointer whitespace-nowrap">
            Buka Pengaturan Konten →
        </a>
    </div>

</div>
@endsection