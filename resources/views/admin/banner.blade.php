@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 text-white bg-[#0B0F17] min-h-screen font-sans" x-data="{ showModal: false, editMode: false, newsData: {} }">
    <!-- Header Alert Notification -->
    @if(session('success'))
        <div class="mb-4 p-3.5 sm:p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs sm:text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3.5 sm:p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs sm:text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">Kelola Banner & Konten</h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Atur tampilan slider promosi, berita terbaru, dan QRIS pembayaran denda</p>
        </div>
    </div>

    <!-- Urutan Banner Slider Berjalan (Aktif - Mentok 3) -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
            <h2 class="text-base sm:text-lg font-semibold">Banner Slider Berjalan (Maksimal 3)</h2>
            <span class="text-[11px] sm:text-xs text-orange-400 bg-orange-500/10 px-3 py-1 rounded-full border border-orange-500/20 font-medium">
                Terpakai: {{ count($banners) }} / 3
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse($banners as $index => $b)
            <div class="bg-[#151C28] border border-gray-800 rounded-xl p-3 relative group">
                <div class="h-36 sm:h-40 rounded-lg overflow-hidden mb-3 bg-gray-900 relative">
                    <img src="{{ asset('storage/' . $b->image_path) }}" class="w-full h-full object-cover">
                    <!-- Tombol Hapus Banner -->
                    <form action="{{ route('admin.banner.destroy', $b->id) }}" method="POST" class="absolute top-2 right-2 sm:opacity-0 group-hover:opacity-100 transition" onsubmit="return confirm('Yakin ingin menghapus banner ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600/90 hover:bg-red-600 text-white text-[11px] sm:text-xs px-2.5 py-1 rounded-lg backdrop-blur-md cursor-pointer font-medium shadow">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
                <h3 class="font-semibold text-xs sm:text-sm truncate">{{ $b->judul ?? 'Banner ' . ($index+1) }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    @if($b->is_active)
                        <span class="text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">AKTIF</span>
                    @else
                        <span class="text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded bg-gray-500/10 text-gray-400 border border-gray-500/20">NONAKTIF</span>
                    @endif
                    <span class="text-[11px] sm:text-xs text-gray-400">• Urutan {{ $b->display_order ?? ($index+1) }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-[#151C28] border border-gray-800 rounded-xl p-6 text-center text-gray-400 text-xs sm:text-sm">
                Belum ada banner aktif.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Grid Upload Banner & List Berita -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6 sm:mb-8">
        <!-- Form Upload Banner Baru -->
        <div class="lg:col-span-6 bg-[#151C28] border border-gray-800 rounded-xl p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold mb-4">Upload Banner Slider Baru</h2>
            
            @if(count($banners) >= 3)
                <div class="p-3.5 bg-orange-500/10 border border-orange-500/30 text-orange-400 rounded-xl text-xs mb-4">
                    ⚠️ Slot Banner sudah penuh (3/3). Hapus salah satu banner di atas untuk mengunggah yang baru.
                </div>
            @endif

            <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="border-2 border-dashed border-orange-500/40 hover:border-orange-500 transition rounded-xl p-4 sm:p-6 text-center relative cursor-pointer bg-[#0D121D]" onclick="document.getElementById('bannerInput').click()">
                    <input type="file" name="image" id="bannerInput" class="hidden" accept="image/*" {{ count($banners) >= 3 ? 'disabled' : 'required' }} onchange="previewFile(event)">
                    <div id="dropZoneContent">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-orange-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-xs sm:text-sm font-semibold text-gray-200">Klik / Tarik gambar di sini</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-1">Ukuran ideal: 1200 x 480 px (Max 5MB)</p>
                    </div>
                    <img id="imgPreview" class="hidden h-28 sm:h-32 mx-auto rounded-lg object-cover">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Judul Banner</label>
                    <input type="text" name="judul" placeholder="Masukkan judul promosi..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:border-orange-500 text-white" {{ count($banners) >= 3 ? 'disabled' : '' }}>
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Deskripsi Singkat</label>
                    <input type="text" name="deskripsi" placeholder="Tulis penjelasan promo secara ringkas..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:border-orange-500 text-white" {{ count($banners) >= 3 ? 'disabled' : '' }}>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Display Order</label>
                        <input type="number" name="display_order" value="{{ count($banners) + 1 }}" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Status Tampil</label>
                        <div class="flex items-center pt-2">
                            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 accent-orange-500 cursor-pointer">
                            <span class="text-xs ml-2 text-gray-300">Aktifkan Sekarang</span>
                        </div>
                    </div>
                </div>

                <button type="submit" {{ count($banners) >= 3 ? 'disabled' : '' }} class="w-full bg-[#FF5500] hover:bg-orange-600 disabled:bg-gray-700 text-white font-semibold py-2.5 rounded-lg transition text-xs sm:text-sm cursor-pointer shadow-lg shadow-orange-500/20">
                    Simpan & Terbitkan Banner
                </button>
            </form>
        </div>

        <!-- Kelola Berita Terbaru -->
        <div class="lg:col-span-6 bg-[#151C28] border border-gray-800 rounded-xl p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                <h2 class="text-base sm:text-lg font-semibold">Kelola Berita Terbaru</h2>
                <button @click="showModal = true; editMode = false; newsData = { tag_header: 'NEWS & EVENTS', status: 'published' }" class="bg-[#1E293B] text-xs font-semibold px-3 py-1.5 rounded-lg text-orange-400 border border-orange-500/20 hover:bg-orange-500 hover:text-white transition cursor-pointer w-full sm:w-auto text-center">
                    + Tulis Berita
                </button>
            </div>

            <div class="space-y-3">
                @forelse($news as $n)
                <div class="bg-[#0D121D] border border-gray-800 p-3.5 sm:p-4 rounded-xl flex flex-col sm:flex-row justify-between items-start gap-2">
                    <div class="flex-1 w-full">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                            <div class="text-[10px] sm:text-[11px] text-gray-500">
                                {{ date('d M Y', strtotime($n->created_at)) }} • Tag: <span class="text-orange-400 font-semibold">{{ $n->tag_header }}</span>
                            </div>
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded {{ $n->status == 'published' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-gray-500/10 text-gray-400 border border-gray-500/20' }}">
                                {{ strtoupper($n->status) }}
                            </span>
                        </div>
                        <h4 class="text-xs sm:text-sm font-semibold mb-1 text-white leading-snug">{{ $n->judul }}</h4>
                        <p class="text-[11px] sm:text-xs text-gray-400 line-clamp-2 mb-2">{{ $n->isi }}</p>
                        <div class="flex gap-4 text-xs pt-1 border-t border-gray-800/60">
                            <button @click="showModal = true; editMode = true; newsData = {{ json_encode($n) }}" class="text-orange-500 hover:underline cursor-pointer font-medium">Edit</button>
                            <form action="{{ route('admin.news.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus berita ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:underline cursor-pointer font-medium">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-[#0D121D] border border-gray-800 p-6 rounded-xl text-center text-gray-500 text-xs">
                    Belum ada berita yang ditulis.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- KELOLA QRIS PEMBAYARAN DENDA (MAKSIMAL 1 GAMBAR) -->
    <div class="bg-[#151C28] border border-gray-800 rounded-xl p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-white">QRIS Pembayaran Denda Utama</h2>
                <p class="text-xs text-gray-400">QRIS ini akan tampil secara otomatis pada modal pelunasan denda di aplikasi siswa.</p>
            </div>
            <span class="text-[10px] sm:text-xs font-bold text-orange-400 bg-orange-500/10 border border-orange-500/20 px-3 py-1 rounded-full whitespace-nowrap">
                Maksimal 1 QRIS
            </span>
        </div>

        @php
            $qrisImage = DB::table('settings')->where('key', 'qris_image')->value('value');
        @endphp

        @if($qrisImage)
            <div class="bg-[#0D121D] border border-gray-800 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row items-center justify-between gap-5 sm:gap-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 w-full">
                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-xl bg-white p-2 border border-gray-700 flex items-center justify-center shadow-lg flex-shrink-0">
                        <img src="{{ asset('storage/' . $qrisImage) }}" class="max-w-full max-h-full object-contain">
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> QRIS AKTIF
                        </div>
                        <h4 class="text-xs sm:text-sm font-bold text-white mb-1">Siap Digunakan Transaksi</h4>
                        <p class="text-[11px] sm:text-xs text-gray-400 max-w-md leading-relaxed">
                            QRIS di atas saat ini aktif. Jika ingin mengganti dengan gambar QRIS lain, Anda harus menghapus QRIS ini terlebih dahulu.
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.qris.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar QRIS ini? Siswa tidak akan bisa scan QRIS sebelum Anda mengunggah QRIS baru.');" class="w-full md:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full md:w-auto bg-rose-600/20 hover:bg-rose-600 border border-rose-500/30 text-rose-400 hover:text-white text-xs font-semibold px-4 py-2.5 sm:py-3 rounded-xl transition cursor-pointer flex items-center justify-center gap-2">
                        🗑️ Hapus QRIS Ini
                    </button>
                </form>
            </div>
        @else
            <form action="{{ route('admin.qris.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="border-2 border-dashed border-emerald-500/40 hover:border-emerald-500 transition rounded-2xl p-6 sm:p-8 text-center cursor-pointer bg-[#0D121D]" onclick="document.getElementById('qrisInput').click()">
                    <input type="file" name="qris_image" id="qrisInput" class="hidden" accept="image/*" required onchange="this.form.submit()">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-2 sm:mb-3 text-lg sm:text-xl">
                        📲
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-white mb-1">Klik untuk Upload Gambar QRIS</p>
                    <p class="text-[10px] sm:text-xs text-gray-400">Format yang didukung: PNG, JPG, WEBP (Maksimal 2MB)</p>
                </div>
            </form>
        @endif
    </div>

    <!-- MODAL TULIS / EDIT BERITA -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-[#151C28] border border-gray-800 rounded-2xl w-full max-w-lg p-4 sm:p-6 text-white relative max-h-[90vh] overflow-y-auto">
            <h3 class="text-base sm:text-lg font-bold mb-4 text-orange-500" x-text="editMode ? 'Edit Berita' : 'Tulis Berita Baru'"></h3>

            <form :action="editMode ? '{{ url('admin/news') }}/' + newsData.id : '{{ route('admin.news.store') }}'" method="POST" class="space-y-3.5">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Pasang di Banner Slider (Opsional)</label>
                    <select name="banner_id" x-model="newsData.banner_id" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs focus:outline-none text-white">
                        <option value="">-- Pilih Banner --</option>
                        @foreach($banners as $b)
                            <option value="{{ $b->id }}">{{ $b->judul ?? 'Banner #' . $b->id }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Tag / Header Banner (Misal: NEWS & EVENTS)</label>
                    <input type="text" name="tag_header" x-model="newsData.tag_header" required placeholder="Contoh: NEW ARRIVALS, ANNOUNCEMENT" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none text-white">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Judul Berita / Banner Header</label>
                    <input type="text" name="judul" x-model="newsData.judul" required placeholder="Judul berita..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none text-white">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Isi Berita / Deskripsi</label>
                    <textarea name="isi" x-model="newsData.isi" rows="4" required placeholder="Tuliskan detail berita..." class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none text-white"></textarea>
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Status</label>
                    <select name="status" x-model="newsData.status" class="w-full bg-[#0D121D] border border-gray-800 rounded-lg px-3 py-2 text-xs focus:outline-none text-white">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-800 text-xs rounded-lg hover:bg-gray-700 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#FF5500] text-xs font-semibold rounded-lg hover:bg-orange-600 cursor-pointer shadow-lg shadow-orange-500/20">Simpan Berita</button>
                </div>
            </form>
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