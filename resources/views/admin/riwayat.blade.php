@extends('layouts.admin')
@section('title', 'Riwayat Pinjaman Lengkap')

@section('content')
<div class="bg-[#111827] p-6 rounded-2xl border border-gray-800 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-sm font-bold text-white">Console Filter Riwayat</h3>
        <button class="bg-[#FF5500] text-xs font-bold text-white px-4 py-2 rounded-xl">Export Excel (.xlsx)</button>
    </div>
    <table class="w-full text-left text-xs text-gray-400">
        <thead class="bg-[#0B0F19] text-gray-300">
            <tr>
                <th class="p-3">Nama Siswa</th>
                <th class="p-3">Buku</th>
                <th class="p-3">Denda</th>
                <th class="p-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $r)
            <tr class="border-b border-gray-800">
                <td class="p-3 text-white">{{ $r->user_id }}</td>
                <td class="p-3">{{ $r->book_id }}</td>
                <td class="p-3 text-[#FF5500]">Rp {{ number_format($r->denda ?? 0) }}</td>
                <td class="p-3">{{ $r->status }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada riwayat transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection