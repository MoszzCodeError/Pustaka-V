@extends('layouts.admin')

@section('content')
<div class="p-6 text-white bg-[#0B0F17] min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Kelola Buku</h1>
            <p class="text-sm text-gray-400">Sistem Informasi Manajemen Perpustakaan SMKN 5 Surakarta</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="bg-[#FF5500] hover:bg-orange-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2">
                <span>+</span> Tambah Buku Baru
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="flex gap-4 mb-6">
        <div class="relative flex-1">
            <input type="text" placeholder="Cari judul, penulis, atau ISBN..." class="w-full bg-[#151C28] text-sm px-4 py-2.5 pl-10 rounded-lg border border-gray-800 focus:outline-none focus:border-orange-500 text-gray-200">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <select class="bg-[#151C28] text-sm px-4 py-2.5 rounded-lg border border-gray-800 focus:outline-none text-gray-300">
            <option value="">Genre: Semua</option>
        </select>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Tabel Katalog Buku -->
        <div class="lg:col-span-7 bg-[#151C28] border border-gray-800 rounded-xl p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#0D121D] text-gray-400 text-xs uppercase border-b border-gray-800">
                        <tr>
                            <th class="py-3 px-3">Cover</th>
                            <th class="py-3 px-3">Judul & Penulis</th>
                            <th class="py-3 px-3">Genre</th>
                            <th class="py-3 px-3">ISBN</th>
                            <th class="py-3 px-3">Stok</th>
                            <th class="py-3 px-3">Lokasi Rak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @forelse($books as $b)
                        <tr class="hover:bg-[#1E293B]/40 transition">
                            <td class="py-3 px-3">
                                @if(isset($b->cover) && $b->cover)
                                    <img src="{{ asset('storage/' . $b->cover) }}" class="w-10 h-14 object-cover rounded shadow">
                                @else
                                    <div class="w-10 h-14 bg-gray-900 rounded flex items-center justify-center text-[10px] text-gray-500 border border-gray-800">No Cover</div>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-semibold text-white text-sm">{{ $b->judul }}</div>
                                <div class="text-xs text-gray-400">{{ $b->penulis ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-3 text-xs text-gray-300">{{ $b->genre ?? '-' }}</td>
                            <td class="py-3 px-3 text-xs text-gray-400 font-mono">{{ $b->isbn ?? '-' }}</td>
                            <td class="py-3 px-3 text-xs font-semibold text-emerald-400">{{ $b->stok ?? 0 }}</td>
                            <td class="py-3 px-3 text-xs font-medium text-emerald-500">{{ $b->nama_rak ?? 'Belum Diatur' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-400 text-sm">
                                Belum ada buku terdaftar. Silakan tambah melalui form di sebelah kanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Detail / Tambah Buku Baru -->
        <div class="lg:col-span-5 bg-[#151C28] border border-gray-800 rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4 text-white">Detail / Tambah Buku Baru</h2>

            <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Unggah Sampul Gambar -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SAMPUL BUKU</label>
                    <div class="border-2 border-dashed border-gray-800 hover:border-orange-500/50 bg-[#0D121D] rounded-xl p-5 text-center cursor-pointer transition relative" onclick="document.getElementById('coverInput').click()">
                        <input type="file" name="cover" id="coverInput" class="hidden" accept="image/*" onchange="previewCover(event)">
                        <div id="dropText">
                            <p class="text-xs font-semibold text-orange-500 mb-1">Unggah Sampul Gambar</p>
                            <p class="text-[11px] text-gray-500">Rekomendasi rasio 3:4 (Maks 2MB)</p>
                        </div>
                        <img id="coverPreview" class="hidden h-36 mx-auto rounded object-cover shadow">
                    </div>
                </div>

                <!-- Input Judul -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">JUDUL BUKU</label>
                    <input type="text" name="judul" placeholder="Masukkan judul buku lengkap" required class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-orange-500">
                </div>

                <!-- Input Penulis & Genre -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">PENULIS</label>
                        <input type="text" name="penulis" placeholder="Nama penulis" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">GENRE</label>
                        <select name="genre" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-orange-500">
                            <option value="">Pilih genre</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Adventure">Adventure</option>
                            <option value="History">History</option>
                            <option value="Self-Help">Self-Help</option>
                            <option value="Pemrograman">Pemrograman</option>
                            <option value="Sains">Sains</option>
                        </select>
                    </div>
                </div>

                <!-- Input ISBN & Jumlah Halaman -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">ISBN</label>
                        <input type="text" name="isbn" placeholder="978-..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">JUMLAH HALAMAN</label>
                        <input type="number" name="jumlah_halaman" placeholder="E.g. 350" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                </div>

                <!-- Input Jumlah Stok & Lokasi Rak -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">JUMLAH STOK</label>
                        <input type="number" name="stok" placeholder="Jumlah fisik" value="1" required class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">LOKASI RAK</label>
                        <select name="rack_id" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-orange-500">
                            <option value="">Pilih rak</option>
                            @if(isset($racks))
                                @foreach($racks as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_rak }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Input Deskripsi Tambahan -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">DESKRIPSI BUKU</label>
                    <textarea name="deskripsi" rows="3" placeholder="Sinopsis ringkas buku..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-orange-500"></textarea>
                </div>

                <!-- Tombol Action -->
                <div class="flex gap-3 pt-2">
                    <button type="reset" class="w-1/3 bg-[#1E293B] hover:bg-gray-800 text-gray-300 font-semibold py-2.5 rounded-lg transition text-sm">
                        Batal
                    </button>
                    <button type="submit" class="w-2/3 bg-[#FF5500] hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewCover(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('coverPreview').src = e.target.result;
            document.getElementById('coverPreview').classList.remove('hidden');
            document.getElementById('dropText').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection