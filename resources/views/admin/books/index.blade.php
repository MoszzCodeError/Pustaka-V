@extends('layouts.admin')

@section('content')
<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Kelola Buku</h1>
            <p class="text-xs text-gray-400">Sistem Informasi Manajemen Perpustakaan SMKN 5 Surakarta</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- LEFT: TABEL DAFTAR BUKU -->
        <div class="lg:col-span-7 bg-[#111827] border border-gray-800 rounded-2xl p-5">
            <h2 class="text-sm font-bold text-white mb-4">Daftar Katalog Buku</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-400">
                            <th class="pb-3 font-semibold">Cover</th>
                            <th class="pb-3 font-semibold">Judul & Penulis</th>
                            <th class="pb-3 font-semibold">Genre</th>
                            <th class="pb-3 font-semibold">Stok</th>
                            <th class="pb-3 font-semibold">Rak</th>
                            <th class="pb-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @forelse($books as $book)
                        <tr>
                            <td class="py-3">
                                <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=100&q=80' }}" class="w-9 h-12 object-cover rounded-md border border-gray-800">
                            </td>
                            <td class="py-3">
                                <p class="font-bold text-white truncate max-w-[120px]">{{ $book->judul }}</p>
                                <p class="text-[10px] text-gray-500 truncate max-w-[120px]">{{ $book->penulis }}</p>
                            </td>
                            <td class="py-3">
                                <span class="text-[10px] text-[#FF5500] bg-orange-500/10 px-2 py-0.5 rounded border border-orange-500/20">{{ $book->genre }}</span>
                            </td>
                            <td class="py-3 text-gray-300 font-medium">{{ $book->stok }}</td>
                            <td class="py-3">
                                <span class="text-[10px] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">{{ $book->lokasi_rak }}</span>
                            </td>
                            <td class="py-3 text-right">
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Yakin hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-400 text-[11px]">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                Belum ada buku di database (0 Data). Tambahkan melalui form di samping!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $books->links() }}
            </div>
        </div>

        <!-- RIGHT: FORM TAMBAH BUKU BARU -->
        <div class="lg:col-span-5 bg-[#111827] border border-gray-800 rounded-2xl p-5">
            <h2 class="text-sm font-bold text-white mb-4">Detail / Tambah Buku Baru</h2>

            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">SAMPUL BUKU</label>
                    <input type="file" name="cover_image" class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#182032] file:text-[#FF5500] hover:file:bg-gray-800 bg-[#0B0F19] border border-gray-800 rounded-xl">
                </div>

                <div>
                    <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">JUDUL BUKU</label>
                    <input type="text" name="judul" required placeholder="Masukkan judul buku lengkap" class="w-full bg-[#0B0F19] text-xs text-gray-200 px-3 py-2.5 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">PENULIS</label>
                        <input type="text" name="penulis" required placeholder="Nama penulis" class="w-full bg-[#0B0F19] text-xs text-gray-200 px-3 py-2.5 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">GENRE</label>
                        <select name="genre" required class="w-full bg-[#0B0F19] text-xs text-gray-200 px-3 py-2.5 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                            <option value="Fiction">Fiction</option>
                            <option value="Adventure">Adventure</option>
                            <option value="History">History</option>
                            <option value="Self-Help">Self-Help</option>
                            <option value="Mystery">Mystery</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">ISBN</label>
                        <input type="text" name="isbn" placeholder="978-..." class="w-full bg-[#0B0F19] text-xs text-gray-200 px-3 py-2.5 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">JUMLAH STOK</label>
                        <input type="number" name="stok" required value="1" min="1" class="w-full bg-[#0B0F19] text-xs text-gray-200 px-3 py-2.5 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">LOKASI RAK</label>
                    <select name="lokasi_rak" required class="w-full bg-[#0B0F19] text-xs text-gray-200 px-3 py-2.5 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]">
                        <option value="Rak A-12">Rak A-12</option>
                        <option value="Rak B-02">Rak B-02</option>
                        <option value="Rak C-05">Rak C-05</option>
                        <option value="Rak D-01">Rak D-01</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">SINOPSIS / DESKRIPSI</label>
                    <textarea name="deskripsi" rows="3" placeholder="Tulis sinopsis ringkas..." class="w-full bg-[#0B0F19] text-xs text-gray-200 px-3 py-2.5 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500]"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-2">
                    <button type="reset" class="px-4 py-2 text-xs bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-6 py-2 text-xs bg-[#FF5500] hover:bg-orange-600 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/20 transition">Simpan Buku</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection