@extends('layouts.admin')
@section('title', 'Kelola Pinjaman')

@section('content')
<div class="bg-[#111827] p-6 rounded-2xl border border-gray-800/80 mb-8">
    <h3 class="text-sm font-bold text-white mb-2">Verifikasi Kode Tukar Buku</h3>
    <div class="flex gap-3">
        <input type="text" placeholder="Masukkan 5-karakter kode unik peminjaman" class="flex-1 bg-[#0B0F19] text-xs text-white p-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
        <button class="bg-[#FF5500] text-white px-6 py-3 rounded-xl font-bold text-xs">Verifikasi</button>
    </div>
</div>
@endsection