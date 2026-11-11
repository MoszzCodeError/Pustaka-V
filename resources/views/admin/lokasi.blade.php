@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 text-white bg-[#0B0F17] min-h-screen font-sans space-y-6" 
     x-data="{ 
        addRakModal: false,
        searchBookText: '',
        selectedGenre: '',
        searchRackText: '',
        sortRackBy: 'name_asc',
        searchInRackText: '',
        
        bookSelectOpen: false,
        selectedBookId: '',
        selectedBookTitle: '-- Pilih Buku --',
        bookSearchInput: '',

        racksData: {{ json_encode($racks) }},
        booksData: {{ json_encode($allBooks) }},

        get filteredRacks() {
            let racks = [...this.racksData];
            if (this.searchRackText.trim() !== '') {
                let q = this.searchRackText.toLowerCase();
                racks = racks.filter(r => 
                    (r.nama_rak && r.nama_rak.toLowerCase().includes(q)) || 
                    (r.kategori && r.kategori.toLowerCase().includes(q))
                );
            }
            if (this.sortRackBy === 'name_asc') {
                racks.sort((a, b) => (a.nama_rak || '').localeCompare(b.nama_rak || ''));
            } else if (this.sortRackBy === 'name_desc') {
                racks.sort((a, b) => (b.nama_rak || '').localeCompare(a.nama_rak || ''));
            } else if (this.sortRackBy === 'capacity_desc') {
                racks.sort((a, b) => (b.total_buku || 0) - (a.total_buku || 0));
            } else if (this.sortRackBy === 'capacity_asc') {
                racks.sort((a, b) => (a.total_buku || 0) - (b.total_buku || 0));
            }
            return racks;
        },

        get filteredSelectBooks() {
            let books = [...this.booksData];
            if (this.bookSearchInput.trim() !== '') {
                let q = this.bookSearchInput.toLowerCase();
                books = books.filter(b => b.judul && b.judul.toLowerCase().includes(q));
            }
            if (this.selectedGenre !== '') {
                books = books.filter(b => b.genre === this.selectedGenre);
            }
            return books;
        },

        selectBook(b) {
            this.selectedBookId = b.id;
            this.selectedBookTitle = b.judul + (b.genre ? ' [' + b.genre + ']' : '');
            this.bookSelectOpen = false;
        }
     }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white">Lokasi Buku & Rak</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-0.5">Visualisasikan denah penyimpanan buku, monitor kapasitas, dan permudah pencarian</p>
        </div>
        <button @click="addRakModal = true" class="w-full sm:w-auto px-4 py-2.5 bg-[#FF5500] hover:bg-orange-600 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2">
            <span>➕ Buat Rak Baru</span>
        </button>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="p-3.5 sm:p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs sm:text-sm flex justify-between items-center">
            <span>✨ {{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 sm:p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-xs sm:text-sm flex justify-between items-center">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif

    <!-- Denah Visual Kapasitas Rak Perpustakaan -->
    <div class="bg-slate-800/40 border border-slate-700/50 p-4 sm:p-5 rounded-2xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
            <h2 class="text-xs sm:text-sm font-semibold text-slate-300">Denah Visual Kapasitas Rak Perpustakaan</h2>
            
            <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                <input type="text" x-model="searchRackText" placeholder="Cari nama / kategori rak..." class="bg-slate-900/80 border border-slate-700 text-xs text-white px-3 py-2 rounded-xl focus:border-orange-500 outline-none w-full sm:w-48">
                
                <select x-model="sortRackBy" class="bg-slate-900/80 border border-slate-700 text-xs text-white px-3 py-2 rounded-xl focus:border-orange-500 outline-none w-full sm:w-auto">
                    <option value="name_asc">Sortir: Nama (A - Z)</option>
                    <option value="name_desc">Sortir: Nama (Z - A)</option>
                    <option value="capacity_desc">Kapasitas: Paling Penuh</option>
                    <option value="capacity_asc">Kapasitas: Paling Kosong</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
            <template x-for="rack in filteredRacks" :key="rack.id">
                <div class="relative group">
                    <a :href="'{{ route('admin.lokasi') }}?rack_id=' + rack.id" 
                       class="block p-3.5 sm:p-4 rounded-xl border transition-all duration-200"
                       :class="rack.id == {{ $selectedRackId ?? 0 }} ? 'border-orange-500 bg-slate-800 shadow-lg shadow-orange-500/10 ring-1 ring-orange-500' : 'border-slate-700/60 bg-slate-900/50 hover:border-slate-600'">
                        <div class="flex justify-between items-center mb-2 pr-6">
                            <h3 class="font-bold text-white text-sm sm:text-base" x-text="rack.nama_rak"></h3>
                            <span class="text-[11px] text-slate-400" x-show="rack.kategori" x-text="'(' + rack.kategori + ')'"></span>
                        </div>
                        
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-slate-400">Kapasitas Terisi</span>
                            <span class="font-bold" :class="Math.round(((rack.total_buku || 0) / 60) * 100) >= 100 ? 'text-red-400' : (rack.id == {{ $selectedRackId ?? 0 }} ? 'text-orange-400' : 'text-emerald-400')" x-text="(rack.total_buku || 0) + '/60 Buku'">
                            </span>
                        </div>

                        <div class="w-full bg-slate-700/60 h-2 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300" 
                                 :class="Math.round(((rack.total_buku || 0) / 60) * 100) >= 100 ? 'bg-red-500' : 'bg-gradient-to-r from-emerald-500 to-orange-500'" 
                                 :style="'width: ' + Math.min(100, Math.round(((rack.total_buku || 0) / 60) * 100)) + '%'"></div>
                        </div>
                    </a>

                    <form :action="'{{ url('admin/lokasi/rak') }}/' + rack.id" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rak ini?')" class="absolute top-3 right-3 sm:opacity-0 group-hover:opacity-100 transition">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Rak" class="text-gray-400 hover:text-red-400 p-1 rounded-lg hover:bg-slate-800 transition">
                            ✕
                        </button>
                    </form>
                </div>
            </template>

            <div x-show="filteredRacks.length === 0" class="col-span-full text-center py-6 text-slate-500 text-xs">
                Rak tidak ditemukan.
            </div>
        </div>
    </div>

    <!-- Main Content Section: Form & List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form Atur / Pindahkan Lokasi -->
        <div class="bg-slate-800/40 border border-slate-700/50 p-4 sm:p-5 rounded-2xl h-fit">
            <h3 class="font-bold text-base sm:text-lg text-white mb-4">Atur / Pindahkan Lokasi Buku</h3>
            <form action="{{ route('admin.lokasi.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="book_id" :value="selectedBookId" required>

                <!-- Custom Searchable Dropdown Buku -->
                <div class="relative" @click.outside="bookSelectOpen = false">
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Pilih Buku Target</label>
                    
                    <button type="button" 
                            @click="bookSelectOpen = !bookSelectOpen" 
                            class="w-full bg-slate-900/80 border border-slate-700 text-white rounded-xl p-3 text-xs sm:text-sm text-left flex justify-between items-center focus:border-orange-500 outline-none">
                        <span class="truncate" x-text="selectedBookTitle" :class="selectedBookId ? 'text-white font-semibold' : 'text-slate-400'"></span>
                        <span class="text-xs text-slate-400 ml-1">▼</span>
                    </button>

                    <div x-show="bookSelectOpen" 
                         x-cloak 
                         class="absolute z-50 left-0 right-0 mt-1 bg-[#111827] border border-slate-700 rounded-xl shadow-2xl overflow-hidden p-2">
                        
                        <input type="text" 
                               x-model="bookSearchInput" 
                               placeholder="🔍 Ketik judul buku..." 
                               class="w-full bg-slate-900 border border-slate-700 text-xs text-white p-2 rounded-lg outline-none focus:border-orange-500 mb-2">

                        <div class="max-h-48 overflow-y-auto space-y-1">
                            <template x-for="b in filteredSelectBooks" :key="b.id">
                                <div @click="selectBook(b)" 
                                     class="p-2 text-xs text-slate-200 hover:bg-orange-500 hover:text-white rounded-lg cursor-pointer transition flex justify-between items-center">
                                    <span class="font-medium truncate" x-text="b.judul"></span>
                                    <span class="text-[10px] opacity-75 ml-2" x-show="b.genre" x-text="b.genre"></span>
                                </div>
                            </template>
                            
                            <div x-show="filteredSelectBooks.length === 0" class="p-3 text-center text-xs text-slate-500">
                                Buku tidak ditemukan.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rak Tujuan -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Rak Tujuan</label>
                    <select name="rack_id" required class="w-full bg-slate-900/80 border border-slate-700 text-white rounded-xl p-3 text-xs sm:text-sm focus:border-orange-500 outline-none transition">
                        <option value="" disabled selected>-- Pilih Rak Tujuan --</option>
                        @foreach($racks as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_rak }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-4 rounded-xl transition shadow-lg shadow-orange-500/20 text-xs sm:text-sm">
                    Update Lokasi Buku
                </button>
            </form>
        </div>

        <!-- Daftar Buku Terdaftar di Rak Terpilih -->
        <div class="lg:col-span-2 bg-slate-800/40 border border-slate-700/50 p-4 sm:p-5 rounded-2xl">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <h3 class="font-bold text-base sm:text-lg text-white">
                    Buku Terdaftar di 
                    <span class="text-orange-400">
                        {{ $racks->firstWhere('id', $selectedRackId)->nama_rak ?? 'Rak' }}
                    </span>
                </h3>
                
                <input type="text" x-model="searchInRackText" placeholder="Cari buku di rak ini..." class="bg-slate-900/80 border border-slate-700 text-xs text-white px-3 py-2 rounded-xl focus:border-orange-500 outline-none w-full sm:w-48">
            </div>

            <div class="space-y-3">
                @forelse($booksInRack as $item)
                    <div x-show="searchInRackText.trim() === '' || '{{ strtolower(addslashes($item->judul)) }}'.includes(searchInRackText.toLowerCase()) || '{{ strtolower(addslashes($item->penulis ?? '')) }}'.includes(searchInRackText.toLowerCase())" 
                         class="flex items-center justify-between p-3 sm:p-3.5 bg-slate-900/60 rounded-xl border border-slate-800 gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-12 sm:w-10 sm:h-14 bg-slate-800 rounded-lg overflow-hidden flex-shrink-0 border border-slate-700">
                                @if($item->cover)
                                    <img src="{{ Str::startsWith($item->cover, 'http') ? $item->cover : asset('storage/' . $item->cover) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-500 text-[9px]">No Cover</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-white text-xs sm:text-sm truncate">{{ $item->judul }}</h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[11px] sm:text-xs text-slate-400 truncate">{{ $item->penulis ?? 'Tanpa Penulis' }}</p>
                                    @if(isset($item->genre))
                                        <span class="text-[8px] sm:text-[9px] font-bold text-orange-400 bg-orange-500/10 px-1.5 py-0.5 rounded border border-orange-500/20 uppercase flex-shrink-0">{{ $item->genre }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] sm:text-xs font-semibold px-2.5 py-1 bg-slate-800 text-slate-300 rounded-lg border border-slate-700 whitespace-nowrap">
                            Stok: {{ $item->stok }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500 text-xs sm:text-sm">
                        Belum ada buku yang tersimpan di rak ini.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- MODAL BUAT RAK BARU -->
    <div x-show="addRakModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-[#111827] border border-gray-800 rounded-3xl max-w-md w-full p-4 sm:p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-gray-800 pb-3">
                <h3 class="text-sm sm:text-base font-bold text-white">Buat Rak Baru</h3>
                <button @click="addRakModal = false" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('admin.lokasi.rak.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">Kode / Nama Rak</label>
                    <input type="text" name="nama_rak" placeholder="Contoh: Rak A1 / Rak RPL-01" required class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-3.5 py-2.5 sm:py-3 text-white focus:outline-none focus:border-[#FF5500]">
                </div>

                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">Kategori / Deskripsi Rak (Opsional)</label>
                    <input type="text" name="kategori" placeholder="Contoh: Pemrograman / Novel / Otomotif" class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-3.5 py-2.5 sm:py-3 text-white focus:outline-none focus:border-[#FF5500]">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="addRakModal = false" class="w-1/2 py-2.5 sm:py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 sm:py-3 bg-[#FF5500] hover:bg-orange-600 text-white rounded-xl font-bold transition shadow-lg shadow-orange-500/20">
                        Simpan Rak
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection