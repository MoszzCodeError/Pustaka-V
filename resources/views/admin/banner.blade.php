@extends('layouts.admin') {{-- sesuaikan nama layout admin lu --}}

@section('content')
<div class="p-6 text-white bg-[#0B0F17] min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold">Kelola Banner & Konten</h1>
            <p class="text-sm text-gray-400">Atur tampilan slider promosi, berita terbaru, dan pengumuman untuk siswa</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="Cari data..." class="bg-[#151C28] text-sm px-4 py-2 pl-9 rounded-lg border border-gray-800 focus:outline-none w-64">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Urutan Banner Slider Berjalan -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Urutan Banner Slider Berjalan (Aktif)</h2>
            <span class="text-xs text-gray-400">Gunakan drag-handle untuk mengatur prioritas urutan tampil</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($banners as $index => $b)
            <div class="bg-[#151C28] border border-gray-800 rounded-xl p-3 relative group">
                <div class="h-36 rounded-lg overflow-hidden mb-3 bg-gray-900">
                    <img src="{{ asset('storage/' . $b->image_path) }}" class="w-full h-full object-cover">
                </div>
                <h3 class="font-semibold text-sm truncate">{{ $b->judul ?? 'Banner ' . ($index+1) }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">AKTIF</span>
                    <span class="text-xs text-gray-400">• Urutan {{ $b->display_order ?? ($index+1) }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-3 bg-[#151C28] border border-gray-800 rounded-xl p-6 text-center text-gray-400 text-sm">
                Belum ada banner aktif.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Grid Upload & News -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Upload Banner Slider Baru -->
        <div class="lg:col-span-6 bg-[#151C28] border border-gray-800 rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">Upload Banner Slider Baru</h2>
            
            <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <!-- Upload Box -->
                <div class="border-2 border-dashed border-red-500/40 hover:border-orange-500 transition rounded-xl p-6 text-center relative cursor-pointer bg-[#0D121D]" onclick="document.getElementById('bannerInput').click()">
                    <input type="file" name="image" id="bannerInput" class="hidden" accept="image/*" required onchange="previewFile(event)">
                    <div id="dropZoneContent">
                        <svg class="w-8 h-8 text-orange-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-sm font-semibold text-gray-200">Tarik gambar di sini atau klik untuk browse</p>
                        <p class="text-xs text-gray-500 mt-1">Rekomendasi ukuran: 1200 x 480 piksel (Maks 5MB)</p>
                    </div>
                    <img id="imgPreview" class="hidden h-32 mx-auto rounded-lg object-cover">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Judul Banner</label>
                    <input type="text" name="judul" placeholder="Masukkan judul promosi..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Deskripsi singkat</label>
                    <input type="text" name="deskripsi" placeholder="Tulis penjelasan promo secara ringkas di sini..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Display Order</label>
                        <input type="number" name="display_order" value="1" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Status Tampil</label>
                        <div class="flex items-center pt-2">
                            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 accent-orange-500 cursor-pointer">
                            <span class="text-xs ml-2 text-gray-300">Aktifkan Sekarang</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#FF5500] hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Simpan & Terbitkan Banner
                </button>
            </form>
        </div>

        <!-- Kelola Berita Terbaru -->
        <div class="lg:col-span-6 bg-[#151C28] border border-gray-800 rounded-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Kelola Berita Terbaru</h2>
                <button class="bg-[#1E293B] text-xs font-semibold px-3 py-1.5 rounded-lg text-orange-400 border border-orange-500/20">+ Tulis Berita</button>
            </div>

            <div class="space-y-3">
                <div class="bg-[#0D121D] border border-gray-800 p-4 rounded-xl flex justify-between items-start">
                    <div>
                        <div class="text-xs text-gray-500 mb-1">14 Nov 2024 • Oleh Admin</div>
                        <h4 class="text-sm font-semibold mb-2">Pengumuman Libur Semester Ganjil & Batas Pengembalian Buku</h4>
                        <div class="flex gap-3 text-xs text-orange-500">
                            <a href="#" class="hover:underline">Edit</a>
                            <a href="#" class="hover:underline text-red-500">Hapus</a>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">PUBLISHED</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewFile(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('imgPreview').classList.remove('hidden');
            document.getElementById('dropZoneContent').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection