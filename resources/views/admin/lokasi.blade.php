@extends('layouts.admin') {{-- Pastikan meng-extends layout admin project kamu --}}

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">Lokasi Buku & Rak</h1>
        <p class="text-slate-400 text-sm">Visualisasikan denah penyimpanan buku, monitor kapasitas, dan permudah pencarian</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Denah Visual Kapasitas Rak Perpustakaan -->
    <div class="bg-slate-800/40 border border-slate-700/50 p-5 rounded-2xl">
        <h2 class="text-sm font-semibold text-slate-300 mb-4">Denah Visual Kapasitas Rak Perpustakaan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($racks as $rack)
                @php
                    $kapasitasMaks = 60;
                    $persen = min(100, round(($rack->total_buku / $kapasitasMaks) * 100));
                    $isSelected = $rack->id == $selectedRackId;
                @endphp
                <a href="{{ route('admin.lokasi', ['rack_id' => $rack->id]) }}" 
                   class="block p-4 rounded-xl border transition-all duration-200 {{ $isSelected ? 'border-orange-500 bg-slate-800 shadow-lg shadow-orange-500/10 ring-1 ring-orange-500' : 'border-slate-700/60 bg-slate-900/50 hover:border-slate-600' }}">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-bold text-white text-base">{{ $rack->nama_rak }}</h3>
                        @if(isset($rack->kategori))
                            <span class="text-xs text-slate-400">({{ $rack->kategori }})</span>
                        @endif
                    </div>
                    
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-slate-400">Kapasitas Terisi</span>
                        <span class="font-bold {{ $persen >= 100 ? 'text-red-400' : ($isSelected ? 'text-orange-400' : 'text-emerald-400') }}">
                            {{ $rack->total_buku }}/{{ $kapasitasMaks }} Buku
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-700/60 h-2 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-300 {{ $persen >= 100 ? 'bg-red-500' : 'bg-gradient-to-r from-emerald-500 to-orange-500' }}" 
                             style="width: {{ $persen }}%"></div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Main Content Section: Form & List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form Atur / Pindahkan Lokasi -->
        <div class="bg-slate-800/40 border border-slate-700/50 p-5 rounded-2xl h-fit">
            <h3 class="font-bold text-lg text-white mb-4">Atur / Pindahkan Lokasi Buku</h3>
            <form action="{{ route('admin.lokasi.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Pilih Buku</label>
                    <select name="book_id" required class="w-full bg-slate-900/80 border border-slate-700 text-white rounded-xl p-3 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition">
                        <option value="" disabled selected>-- Pilih Buku --</option>
                        @foreach($allBooks as $b)
                            <option value="{{ $b->id }}">{{ $b->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Rak Tujuan</label>
                    <select name="rack_id" required class="w-full bg-slate-900/80 border border-slate-700 text-white rounded-xl p-3 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition">
                        <option value="" disabled selected>-- Pilih Rak Tujuan --</option>
                        @foreach($racks as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_rak }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-4 rounded-xl transition shadow-lg shadow-orange-500/20 text-sm">
                    Update Lokasi Buku
                </button>
            </form>
        </div>

        <!-- Daftar Buku Terdaftar di Rak Terpilih -->
        <div class="lg:col-span-2 bg-slate-800/40 border border-slate-700/50 p-5 rounded-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-white">
                    Buku Terdaftar di 
                    <span class="text-orange-400">
                        {{ $racks->firstWhere('id', $selectedRackId)->nama_rak ?? 'Rak' }}
                    </span>
                </h3>
            </div>

            <div class="space-y-3">
                @forelse($booksInRack as $item)
                    <div class="flex items-center justify-between p-3.5 bg-slate-900/60 rounded-xl border border-slate-800">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-14 bg-slate-800 rounded-lg overflow-hidden flex-shrink-0 border border-slate-700">
                                @if($item->cover)
                                    <img src="{{ asset('storage/' . $item->cover) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-500 text-[10px]">No Cover</div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-sm">{{ $item->judul }}</h4>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $item->penulis ?? 'Tanpa Penulis' }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 bg-slate-800 text-slate-300 rounded-lg border border-slate-700">
                            Stok: {{ $item->stok }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500 text-sm">
                        Belum ada buku yang tersimpan di rak ini.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection