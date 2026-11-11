@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 text-white bg-[#0B0F17] min-h-screen font-sans" x-data="{
    isEdit: false,
    formTitle: 'Detail / Tambah Buku Baru',
    formAction: '{{ route('admin.buku.store') }}',
    book: { id: '', judul: '', penulis: '', penerbit: '', tahun_terbit: '', genre: '', isbn: '', jumlah_halaman: '', stok: 1, rack_id: '', deskripsi: '' },
    showGenreModal: false,
    
    resetForm() {
        this.isEdit = false;
        this.formTitle = 'Detail / Tambah Buku Baru';
        this.formAction = '{{ route('admin.buku.store') }}';
        this.book = { id: '', judul: '', penulis: '', penerbit: '', tahun_terbit: '', genre: '', isbn: '', jumlah_halaman: '', stok: 1, rack_id: '', deskripsi: '' };
    },
    editBook(b) {
        this.isEdit = true;
        this.formTitle = 'Edit Data Buku';
        this.formAction = '{{ url('/admin/buku') }}/' + b.id;
        this.book = { 
            id: b.id, 
            judul: b.judul || '', 
            penulis: b.penulis || '', 
            penerbit: b.penerbit || '', 
            tahun_terbit: b.tahun_terbit || '', 
            genre: b.genre || '', 
            isbn: b.isbn || '', 
            jumlah_halaman: b.jumlah_halaman || '', 
            stok: b.stok || 1, 
            rack_id: b.rack_id || '', 
            deskripsi: b.deskripsi || '' 
        };
    }
}">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">Kelola Buku</h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Sistem Informasi Manajemen Perpustakaan SMKN 5 Surakarta</p>
        </div>
        <div class="flex items-center gap-2.5 w-full sm:w-auto">
            <button @click="showGenreModal = true" class="flex-1 sm:flex-none text-center bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-gray-700 transition">
                ⚙️ Genre
            </button>
            <button @click="resetForm()" class="flex-1 sm:flex-none text-center bg-[#FF5500] hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-lg shadow-orange-500/20">
                + Tambah Buku
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.buku') }}" method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis, atau ISBN..." class="w-full bg-[#151C28] text-xs px-4 py-2.5 pl-10 rounded-xl border border-gray-800 focus:outline-none focus:border-orange-500 text-gray-200">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <select name="genre" onchange="this.form.submit()" class="bg-[#151C28] text-xs px-4 py-2.5 rounded-xl border border-gray-800 focus:outline-none text-gray-300">
            <option value="">Genre: Semua</option>
            @foreach($genres as $g)
                <option value="{{ $g->nama_genre }}" {{ request('genre') == $g->nama_genre ? 'selected' : '' }}>{{ $g->nama_genre }}</option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Tabel Katalog Buku -->
        <div class="lg:col-span-7 bg-[#151C28] border border-gray-800 rounded-xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden">
            <div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[600px]">
                        <thead class="bg-[#0D121D] text-gray-400 uppercase border-b border-gray-800">
                            <tr>
                                <th class="py-3 px-3">Cover</th>
                                <th class="py-3 px-3">Judul & Penulis</th>
                                <th class="py-3 px-3">Genre</th>
                                <th class="py-3 px-3">ISBN</th>
                                <th class="py-3 px-3">Stok</th>
                                <th class="py-3 px-3">Lokasi Rak</th>
                                <th class="py-3 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/60">
                            @forelse($books as $b)
                            <tr class="hover:bg-[#1E293B]/40 transition">
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    @if(isset($b->cover) && $b->cover)
                                        <img src="{{ asset('storage/' . $b->cover) }}" class="w-9 h-12 sm:w-10 sm:h-14 object-cover rounded shadow">
                                    @else
                                        <div class="w-9 h-12 sm:w-10 sm:h-14 bg-gray-900 rounded flex items-center justify-center text-[9px] text-gray-500 border border-gray-800">No Cover</div>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="font-semibold text-white text-xs max-w-[130px] sm:max-w-[160px] truncate" title="{{ $b->judul }}">{{ $b->judul }}</div>
                                    <div class="text-[10px] text-gray-400 max-w-[130px] sm:max-w-[160px] truncate">{{ $b->penulis ?? '-' }}</div>
                                </td>
                                <td class="py-2.5 px-3 text-gray-300 whitespace-nowrap">{{ $b->genre ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-400 font-mono whitespace-nowrap">{{ $b->isbn ?? '-' }}</td>
                                <td class="py-2.5 px-3 font-semibold text-emerald-400 whitespace-nowrap">{{ $b->stok ?? 0 }}</td>
                                <td class="py-2.5 px-3 font-medium text-emerald-500 whitespace-nowrap">{{ $b->nama_rak ?? 'Belum Diatur' }}</td>
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button @click="editBook({{ json_encode($b) }})" class="p-1.5 bg-[#FF5500]/10 hover:bg-[#FF5500] text-[#FF5500] hover:text-white rounded-lg transition border border-[#FF5500]/30" title="Edit Buku">
                                            ✏️
                                        </button>
                                        <form action="{{ route('admin.buku.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 rounded-lg transition border border-rose-500/20" title="Hapus Buku">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400 text-xs">
                                    Belum ada buku terdaftar. Silakan tambah melalui form di sebelah kanan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAGINATION RAPI (TIDAK NEMBUS & COMPACT) -->
            @if($books->hasPages())
                <div class="mt-5 pt-4 border-t border-gray-800/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <div class="text-gray-400 text-[11px] whitespace-nowrap">
                        Menampilkan <span class="font-bold text-white">{{ $books->firstItem() ?? 0 }}</span> - <span class="font-bold text-white">{{ $books->lastItem() ?? 0 }}</span> dari <span class="font-bold text-white">{{ $books->total() }}</span> total buku
                    </div>
                    
                    <div class="overflow-x-auto max-w-full py-1">
                        <div class="flex items-center gap-1 shrink-0">
                            {{-- Tombol Previous --}}
                            @if ($books->onFirstPage())
                                <span class="px-2.5 py-1 bg-gray-900 text-gray-600 rounded-lg border border-gray-800 cursor-not-allowed text-[11px] font-semibold">
                                    &laquo; Prev
                                </span>
                            @else
                                <a href="{{ $books->previousPageUrl() }}" class="px-2.5 py-1 bg-[#0D121D] hover:bg-[#FF5500] text-gray-300 hover:text-white rounded-lg border border-gray-800 transition text-[11px] font-semibold">
                                    &laquo; Prev
                                </a>
                            @endif

                            @php
                                $currentPage = $books->currentPage();
                                $lastPage = $books->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);
                            @endphp

                            @if($start > 1)
                                <a href="{{ $books->url(1) }}" class="px-2.5 py-1 bg-[#0D121D] hover:bg-gray-800 text-gray-400 hover:text-white rounded-lg border border-gray-800 transition text-[11px]">1</a>
                                @if($start > 2)
                                    <span class="px-1 text-gray-600">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $currentPage)
                                    <span class="px-2.5 py-1 bg-[#FF5500] text-white font-bold rounded-lg text-[11px] shadow-md shadow-orange-500/20">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $books->url($page) }}" class="px-2.5 py-1 bg-[#0D121D] hover:bg-gray-800 text-gray-400 hover:text-white rounded-lg border border-gray-800 transition text-[11px]">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <span class="px-1 text-gray-600">...</span>
                                @endif
                                <a href="{{ $books->url($lastPage) }}" class="px-2.5 py-1 bg-[#0D121D] hover:bg-gray-800 text-gray-400 hover:text-white rounded-lg border border-gray-800 transition text-[11px]">{{ $lastPage }}</a>
                            @endif

                            {{-- Tombol Next --}}
                            @if ($books->hasMorePages())
                                <a href="{{ $books->nextPageUrl() }}" class="px-2.5 py-1 bg-[#0D121D] hover:bg-[#FF5500] text-gray-300 hover:text-white rounded-lg border border-gray-800 transition text-[11px] font-semibold">
                                    Next &raquo;
                                </a>
                            @else
                                <span class="px-2.5 py-1 bg-gray-900 text-gray-600 rounded-lg border border-gray-800 cursor-not-allowed text-[11px] font-semibold">
                                    Next &raquo;
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Form Detail / Tambah & Edit Buku -->
        <div class="lg:col-span-5 bg-[#151C28] border border-gray-800 rounded-xl p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xs sm:text-sm font-bold text-white" x-text="formTitle"></h2>
                <button type="button" x-show="isEdit" @click="resetForm()" class="text-xs text-[#FF5500] hover:underline font-semibold">
                    + Reset Form
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-500/20 border border-emerald-500 text-emerald-400 text-xs rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form :action="formAction" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">SAMPUL BUKU</label>
                    <input type="file" name="cover" class="block w-full text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#0D121D] file:text-[#FF5500] hover:file:bg-gray-800 bg-[#0D121D] border border-gray-800 rounded-xl">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">JUDUL BUKU</label>
                    <input type="text" name="judul" x-model="book.judul" placeholder="Masukkan judul buku..." required class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">PENULIS</label>
                        <input type="text" name="penulis" x-model="book.penulis" placeholder="Nama penulis" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">GENRE</label>
                        <select name="genre" x-model="book.genre" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-300 focus:outline-none focus:border-orange-500">
                            <option value="">Pilih genre</option>
                            @foreach($genres as $g)
                                <option value="{{ $g->nama_genre }}">{{ $g->nama_genre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">PENERBIT</label>
                        <input type="text" name="penerbit" x-model="book.penerbit" placeholder="Penerbit" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">TAHUN TERBIT</label>
                        <input type="number" name="tahun_terbit" x-model="book.tahun_terbit" placeholder="Contoh: 2024" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">ISBN</label>
                        <input type="text" name="isbn" x-model="book.isbn" placeholder="978-..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">HALAMAN</label>
                        <input type="number" name="jumlah_halaman" x-model="book.jumlah_halaman" placeholder="350" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">STOK</label>
                        <input type="number" name="stok" x-model="book.stok" min="0" required class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">LOKASI RAK</label>
                    <select name="rack_id" x-model="book.rack_id" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-300 focus:outline-none focus:border-orange-500">
                        <option value="">Pilih rak</option>
                        @foreach($racks as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_rak }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">DESKRIPSI BUKU</label>
                    <textarea name="deskripsi" x-model="book.deskripsi" rows="3" placeholder="Sinopsis ringkas..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-orange-500"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" x-show="isEdit" @click="resetForm()" class="w-1/3 bg-gray-800 hover:bg-gray-700 text-gray-300 font-semibold py-2.5 rounded-lg transition text-xs">
                        Batal
                    </button>
                    <button type="submit" :class="isEdit ? 'w-2/3' : 'w-full'" class="bg-[#FF5500] hover:bg-orange-600 text-white font-bold py-2.5 rounded-lg transition text-xs shadow-lg shadow-orange-500/20">
                        <span x-text="isEdit ? 'Update Data Buku' : 'Simpan Buku Baru'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KELOLA GENRE -->
    <div x-show="showGenreModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-[#151C28] border border-gray-800 rounded-2xl max-w-md w-full p-4 sm:p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-gray-800 pb-3">
                <h3 class="text-xs sm:text-sm font-bold text-white">⚙️ Kelola List Genre</h3>
                <button @click="showGenreModal = false" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <!-- Form Tambah Genre -->
            <form action="{{ route('admin.genre.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="nama_genre" placeholder="Nama genre baru..." required class="flex-1 bg-[#0D121D] border border-gray-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#FF5500]">
                <button type="submit" class="px-3.5 py-2 bg-[#FF5500] hover:bg-orange-600 text-white text-xs font-bold rounded-xl transition shadow">
                    + Tambah
                </button>
            </form>

            <!-- List Genre -->
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                @forelse($genres as $g)
                    <div class="flex justify-between items-center bg-[#0D121D] p-2.5 rounded-xl border border-gray-800 text-xs">
                        <span class="text-gray-200 font-medium">{{ $g->nama_genre }}</span>
                        <form action="{{ route('admin.genre.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus genre ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 text-[11px] font-bold">✕ Hapus</button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-xs text-gray-500 py-3">Belum ada genre kustom.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection