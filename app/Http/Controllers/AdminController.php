<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBuku = DB::table('books')->count();
        $bukuDipinjam = DB::table('loans')->where('status', 'dipinjam')->orWhere('status', 'aktif')->count();
        $bukuTersedia = max(0, $totalBuku - $bukuDipinjam);
        $siswaAktif = User::where('role', 'user')->orWhere('role', 'siswa')->count();

        return view('admin.dashboard', compact('totalBuku', 'bukuDipinjam', 'bukuTersedia', 'siswaAktif'));
    }

    // ==========================================
    // KELOLA BUKU & GENRE
    // ==========================================

    public function buku(Request $request)
    {
        $query = DB::table('books')
            ->leftJoin('racks', 'books.rack_id', '=', 'racks.id')
            ->select('books.*', 'racks.nama_rak')
            ->orderBy('books.id', 'desc');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('books.judul', 'like', "%{$q}%")
                    ->orWhere('books.penulis', 'like', "%{$q}%")
                    ->orWhere('books.isbn', 'like', "%{$q}%");
            });
        }

        if ($request->filled('genre')) {
            $query->where('books.genre', $request->genre);
        }

        $books = $query->paginate(5)->withQueryString();
        $racks = DB::table('racks')->get();
        
        if (!Schema::hasTable('genres')) {
            Schema::create('genres', function ($table) {
                $table->id();
                $table->string('nama_genre')->unique();
                $table->timestamps();
            });
        }

        $genres = DB::table('genres')->get();

        return view('admin.buku', compact('books', 'racks', 'genres'));
    }

    public function storeBuku(Request $request)
    {
        $jumlahHalamanClean = $request->jumlah_halaman ? (int) preg_replace('/[^0-9]/', '', $request->jumlah_halaman) : null;
        $tahunTerbitClean   = $request->tahun_terbit   ? (int) preg_replace('/[^0-9]/', '', $request->tahun_terbit)   : null;

        $request->merge([
            'jumlah_halaman' => $jumlahHalamanClean,
            'tahun_terbit'   => $tahunTerbitClean,
        ]);

        $request->validate([
            'judul'          => 'required|string|max:255',
            'penulis'        => 'nullable|string|max:255',
            'penerbit'       => 'nullable|string|max:255',
            'tahun_terbit'   => 'nullable|numeric',
            'genre'          => 'nullable|string|max:100',
            'isbn'           => 'nullable|string|max:100',
            'jumlah_halaman' => 'nullable|integer',
            'deskripsi'      => 'nullable|string',
            'rack_id'        => 'nullable|exists:racks,id',
            'stok'           => 'required|integer|min:0',
            'cover'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        DB::table('books')->insert([
            'judul'          => trim($request->judul),
            'penulis'        => trim($request->penulis),
            'penerbit'       => trim($request->penerbit),
            'tahun_terbit'   => $request->tahun_terbit,
            'genre'          => trim($request->genre),
            'isbn'           => trim($request->isbn),
            'jumlah_halaman' => $request->jumlah_halaman,
            'deskripsi'      => trim($request->deskripsi),
            'rack_id'        => $request->rack_id,
            'stok'           => $request->stok,
            'cover'          => $coverPath,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', '✨ Buku berhasil ditambahkan!');
    }

    public function updateBuku(Request $request, $id)
    {
        $jumlahHalamanClean = $request->jumlah_halaman ? (int) preg_replace('/[^0-9]/', '', $request->jumlah_halaman) : null;
        $tahunTerbitClean   = $request->tahun_terbit   ? (int) preg_replace('/[^0-9]/', '', $request->tahun_terbit)   : null;

        $request->merge([
            'jumlah_halaman' => $jumlahHalamanClean,
            'tahun_terbit'   => $tahunTerbitClean,
        ]);

        $request->validate([
            'judul'          => 'required|string|max:255',
            'penulis'        => 'nullable|string|max:255',
            'penerbit'       => 'nullable|string|max:255',
            'tahun_terbit'   => 'nullable|numeric',
            'genre'          => 'nullable|string|max:100',
            'isbn'           => 'nullable|string|max:100',
            'jumlah_halaman' => 'nullable|integer',
            'deskripsi'      => 'nullable|string',
            'rack_id'        => 'nullable|exists:racks,id',
            'stok'           => 'required|integer|min:0',
            'cover'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = [
            'judul'          => trim($request->judul),
            'penulis'        => trim($request->penulis),
            'penerbit'       => trim($request->penerbit),
            'tahun_terbit'   => $request->tahun_terbit,
            'genre'          => trim($request->genre),
            'isbn'           => trim($request->isbn),
            'jumlah_halaman' => $request->jumlah_halaman,
            'deskripsi'      => trim($request->deskripsi),
            'rack_id'        => $request->rack_id,
            'stok'           => $request->stok,
            'updated_at'     => now(),
        ];

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        DB::table('books')->where('id', $id)->update($data);

        return redirect()->back()->with('success', '✏️ Data buku berhasil diperbarui!');
    }

    public function destroyBuku($id)
    {
        DB::table('books')->where('id', $id)->delete();
        return redirect()->back()->with('success', '🗑️ Buku berhasil dihapus!');
    }

    public function storeGenre(Request $request)
    {
        $request->validate([
            'nama_genre' => 'required|string|max:100|unique:genres,nama_genre'
        ]);

        DB::table('genres')->insert([
            'nama_genre' => trim($request->nama_genre),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', '✨ Genre baru berhasil ditambahkan!');
    }

    public function destroyGenre($id)
    {
        DB::table('genres')->where('id', $id)->delete();
        return redirect()->back()->with('success', '🗑️ Genre berhasil dihapus!');
    }

    // ==========================================
    // PINJAMAN & RIWAYAT
    // ==========================================

    public function pinjaman()
    {
        $pinjaman = DB::table('loans')
            ->join('users', 'loans.user_id', '=', 'users.id')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('loans.*', 'users.name as nama_siswa', 'books.judul as judul')
            ->get();

        return view('admin.pinjaman', compact('pinjaman'));
    }

    /**
     * Halaman Riwayat & Pembukuan Admin (Analytics, Filter, & Sorting)
     */
    public function riwayat(Request $request)
    {
        $query = DB::table('loans')
            ->leftJoin('users', 'loans.user_id', '=', 'users.id')
            ->leftJoin('books', 'loans.book_id', '=', 'books.id')
            ->select(
                'loans.*',
                'users.name as nama_siswa',
                'users.kelas',
                'books.judul as judul_buku'
            );

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('users.name', 'like', "%{$q}%")
                    ->orWhere('books.judul', 'like', "%{$q}%");
            });
        }

        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('loans.created_at', 'asc');
                break;
            case 'denda_desc':
                $query->orderBy('loans.denda', 'desc');
                break;
            case 'denda_asc':
                $query->orderBy('loans.denda', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('users.name', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('users.name', 'desc');
                break;
            default:
                $query->orderBy('loans.created_at', 'desc');
                break;
        }

        $riwayat = $query->paginate(15)->withQueryString();

        $totalTransaksi = DB::table('loans')->count();
        $totalSelesai   = DB::table('loans')->where('status', 'dikembalikan')->count();
        $totalAktif     = DB::table('loans')->whereIn('status', ['aktif', 'menunggu_pengambilan', 'menunggu_pengembalian'])->count();
        $totalTerlambat = DB::table('loans')->where('status', 'terlambat')->count();
        $totalDenda     = DB::table('loans')->sum('denda');

        // FITUR DATA GRAFIK DENGAN KUSTOM FILTER RANGE WAKTU
        $chartRange = $request->get('chart_range', 'year');
        $chartMonthlyLabels = [];
        $chartMonthlyData   = [];

        if ($chartRange === '7days') {
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $chartMonthlyLabels[] = date('d M', strtotime($date));
                $chartMonthlyData[]   = DB::table('loans')
                    ->whereDate('created_at', $date)
                    ->count();
            }
        } elseif ($chartRange === '30days') {
            for ($i = 29; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $chartMonthlyLabels[] = date('d/m', strtotime($date));
                $chartMonthlyData[]   = DB::table('loans')
                    ->whereDate('created_at', $date)
                    ->count();
            }
        } elseif ($chartRange === 'all') {
            $years = DB::table('loans')
                ->selectRaw('YEAR(created_at) as year')
                ->whereNotNull('created_at')
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->pluck('year')
                ->toArray();

            if (empty($years)) {
                $years = [date('Y')];
            }

            foreach ($years as $y) {
                $chartMonthlyLabels[] = (string) $y;
                $chartMonthlyData[]   = DB::table('loans')
                    ->whereYear('created_at', $y)
                    ->count();
            }
        } else {
            // Default 1 Tahun ini (12 Bulan)
            $chartMonthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($m = 1; $m <= 12; $m++) {
                $chartMonthlyData[] = DB::table('loans')
                    ->whereMonth('created_at', $m)
                    ->whereYear('created_at', date('Y'))
                    ->count();
            }
        }

        return view('admin.riwayat', compact(
            'riwayat',
            'totalTransaksi',
            'totalSelesai',
            'totalAktif',
            'totalTerlambat',
            'totalDenda',
            'chartMonthlyLabels',
            'chartMonthlyData',
            'chartRange'
        ));
    }

    /**
     * Ekspor Data Riwayat Peminjaman ke Excel (.xlsx / .csv)
     */
    public function exportRiwayat(Request $request)
    {
        $fileName = 'riwayat_peminjaman_' . date('Y_m_d_H_i_s') . '.csv';

        $query = DB::table('loans')
            ->leftJoin('users', 'loans.user_id', '=', 'users.id')
            ->leftJoin('books', 'loans.book_id', '=', 'books.id')
            ->select(
                'loans.created_at',
                'users.name as nama_siswa',
                'users.kelas',
                'books.judul as judul_buku',
                'loans.denda',
                'loans.status'
            );

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('users.name', 'like', "%{$q}%")
                    ->orWhere('books.judul', 'like', "%{$q}%");
            });
        }

        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('loans.created_at', 'asc');
                break;
            case 'denda_desc':
                $query->orderBy('loans.denda', 'desc');
                break;
            case 'denda_asc':
                $query->orderBy('loans.denda', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('users.name', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('users.name', 'desc');
                break;
            default:
                $query->orderBy('loans.created_at', 'desc');
                break;
        }

        $data = $query->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Tanggal Peminjaman', 'Nama Siswa', 'Kelas', 'Judul Buku', 'Denda (Rp)', 'Status']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->created_at ? date('Y-m-d H:i', strtotime($row->created_at)) : '-',
                    $row->nama_siswa ?? 'User ID ' . $row->user_id,
                    $row->kelas ? trim($row->kelas) : '-',
                    $row->judul_buku ?? 'Buku ID ' . $row->book_id,
                    $row->denda ?? 0,
                    str_replace('_', ' ', $row->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor Cetak PDF Laporan Riwayat Transaksi & Pembukuan
     */
    public function exportRiwayatPdf(Request $request)
    {
        $query = DB::table('loans')
            ->leftJoin('users', 'loans.user_id', '=', 'users.id')
            ->leftJoin('books', 'loans.book_id', '=', 'books.id')
            ->select(
                'loans.*',
                'users.name as nama_siswa',
                'users.kelas',
                'books.judul as judul_buku'
            );

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('users.name', 'like', "%{$q}%")
                    ->orWhere('books.judul', 'like', "%{$q}%");
            });
        }

        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('loans.created_at', 'asc');
                break;
            case 'denda_desc':
                $query->orderBy('loans.denda', 'desc');
                break;
            case 'denda_asc':
                $query->orderBy('loans.denda', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('users.name', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('users.name', 'desc');
                break;
            default:
                $query->orderBy('loans.created_at', 'desc');
                break;
        }

        $riwayat = $query->get();
        $totalTransaksi = $riwayat->count();
        $totalSelesai   = $riwayat->where('status', 'dikembalikan')->count();
        $totalAktif     = $riwayat->whereIn('status', ['aktif', 'dipinjam', 'menunggu_pengambilan', 'menunggu_pengembalian'])->count();
        $totalTerlambat = $riwayat->where('status', 'terlambat')->count();
        $totalDenda     = $riwayat->sum('denda');

        return view('admin.pdf_riwayat', compact(
            'riwayat',
            'totalTransaksi',
            'totalSelesai',
            'totalAktif',
            'totalTerlambat',
            'totalDenda'
        ));
    }

    // ==========================================
    // FITUR KELOLA BANNER, BERITA & QRIS
    // ==========================================

    public function banner()
    {
        $orderColumn = Schema::hasColumn('banners', 'display_order') ? 'display_order' : 'id';
        $banners = DB::table('banners')->orderBy($orderColumn, 'asc')->get();
        
        $news = [];
        if (Schema::hasTable('news')) {
            $news = DB::table('news')
                ->leftJoin('banners', 'news.banner_id', '=', 'banners.id')
                ->select('news.*', 'banners.judul as banner_judul')
                ->orderBy('news.created_at', 'desc')
                ->get();
        }

        return view('admin.banner', compact('banners', 'news'));
    }

    public function storeBanner(Request $request)
    {
        $totalBanners = DB::table('banners')->count();
        if ($totalBanners >= 3) {
            return redirect()->back()->with('error', 'Gagal! Banner sudah maksimal 3. Hapus salah satu banner terlebih dahulu.');
        }

        $request->validate([
            'image'     => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'judul'     => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');

            $imageColumn = Schema::hasColumn('banners', 'image_path') ? 'image_path' : 'image';

            $data = [
                $imageColumn => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('banners', 'judul')) {
                $data['judul'] = $request->judul;
            }
            if (Schema::hasColumn('banners', 'deskripsi')) {
                $data['deskripsi'] = $request->deskripsi;
            }
            if (Schema::hasColumn('banners', 'display_order')) {
                $data['display_order'] = $request->display_order ?? ($totalBanners + 1);
            }
            if (Schema::hasColumn('banners', 'is_active')) {
                $data['is_active'] = $request->has('is_active') ? 1 : 0;
            }

            DB::table('banners')->insert($data);
        }

        return redirect()->back()->with('success', 'Banner berhasil ditambahkan!');
    }

    public function destroyBanner($id)
    {
        $banner = DB::table('banners')->where('id', $id)->first();
        if ($banner) {
            $imageColumn = Schema::hasColumn('banners', 'image_path') ? 'image_path' : 'image';
            $path = $banner->$imageColumn ?? null;

            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            DB::table('banners')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Banner berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Banner tidak ditemukan.');
    }

    public function uploadQris(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $existing = DB::table('settings')->where('key', 'qris_image')->first();

        if ($existing && !empty($existing->value)) {
            return redirect()->back()->with('error', '⚠️ QRIS sudah ada! Hapus QRIS lama terlebih dahulu jika ingin mengganti.');
        }

        $path = $request->file('qris_image')->store('qris', 'public');

        DB::table('settings')->updateOrInsert(
            ['key' => 'qris_image'],
            ['value' => $path, 'created_at' => now(), 'updated_at' => now()]
        );

        return redirect()->back()->with('success', '✅ Gambar QRIS berhasil diunggah!');
    }

    public function destroyQris()
    {
        $setting = DB::table('settings')->where('key', 'qris_image')->first();
        if ($setting && $setting->value) {
            if (Storage::disk('public')->exists($setting->value)) {
                Storage::disk('public')->delete($setting->value);
            }
            DB::table('settings')->where('key', 'qris_image')->delete();
        }
        return redirect()->back()->with('success', '🗑️ Gambar QRIS berhasil dihapus.');
    }

    public function verifikasiQris(Request $request)
    {
        $request->validate([
            'kode_bayar_qris' => 'required|string',
            'aksi'            => 'required|in:terima,tolak'
        ]);

        $kodeClean = strtoupper(trim($request->kode_bayar_qris));
        $loan = DB::table('loans')->where('kode_bayar_qris', $kodeClean)->first();

        if (!$loan) {
            return redirect()->back()->with('error', '❌ Kode unik pembayaran tidak ditemukan!');
        }

        if ($request->aksi === 'terima') {
            DB::table('loans')->where('id', $loan->id)->update([
                'denda_lunas'             => 1,
                'status_pembayaran_qris' => 'lunas',
                'updated_at'             => now()
            ]);
            return redirect()->back()->with('success', '✅ Pembayaran QRIS berhasil dikonfirmasi LUNAS!');
        } else {
            DB::table('loans')->where('id', $loan->id)->update([
                'kode_bayar_qris'        => null,
                'status_pembayaran_qris' => 'ditolak',
                'updated_at'             => now()
            ]);
            return redirect()->back()->with('error', '⚠️ Pembayaran DITOLAK. Kode unik di-reset dan tampilan QRIS aktif kembali di user.');
        }
    }

    public function storeNews(Request $request)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'isi'        => 'required|string',
            'tag_header' => 'required|string|max:100',
        ]);

        DB::table('news')->insert([
            'banner_id'  => $request->banner_id ?: null,
            'tag_header' => $request->tag_header,
            'judul'      => $request->judul,
            'isi'        => $request->isi,
            'status'     => $request->status ?? 'published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Berita baru berhasil dibuat!');
    }

    public function updateNews(Request $request, $id)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'isi'        => 'required|string',
            'tag_header' => 'required|string|max:100',
        ]);

        DB::table('news')->where('id', $id)->update([
            'banner_id'  => $request->banner_id ?: null,
            'tag_header' => $request->tag_header,
            'judul'      => $request->judul,
            'isi'        => $request->isi,
            'status'     => $request->status ?? 'published',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroyNews($id)
    {
        DB::table('news')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Berita berhasil dihapus!');
    }

    // ==========================================
    // LOKASI BUKU & RAK
    // ==========================================

    public function lokasi(Request $request)
    {
        $racks = DB::table('racks')
            ->select('racks.*')
            ->selectSub(function ($query) {
                $query->from('books')
                    ->whereColumn('books.rack_id', 'racks.id')
                    ->selectRaw('COALESCE(SUM(stok), 0)');
            }, 'total_buku')
            ->get();

        $allBooks = DB::table('books')->select('id', 'judul', 'rack_id')->get();

        $firstRack = $racks->first();
        $selectedRackId = $request->query('rack_id', $firstRack ? $firstRack->id : null);

        $booksInRack = [];
        if ($selectedRackId) {
            $booksInRack = DB::table('books')
                ->where('rack_id', $selectedRackId)
                ->get();
        }

        return view('admin.lokasi', compact('racks', 'allBooks', 'booksInRack', 'selectedRackId'));
    }

    public function updateLokasiBuku(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rack_id' => 'required|exists:racks,id',
        ]);

        DB::table('books')->where('id', $request->book_id)->update([
            'rack_id' => $request->rack_id,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Lokasi buku berhasil diperbarui!');
    }

    public function storeRak(Request $request)
    {
        $request->validate([
            'nama_rak' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
        ]);

        DB::table('racks')->insert([
            'nama_rak'   => $request->nama_rak,
            'kategori'   => $request->kategori,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', '✨ Rak baru berhasil ditambahkan!');
    }

    public function destroyRak($id)
    {
        $count = DB::table('books')->where('rack_id', $id)->count();
        if ($count > 0) {
            return redirect()->back()->with('error', '⚠️ Rak tidak dapat dihapus karena masih menampung buku!');
        }

        DB::table('racks')->where('id', $id)->delete();

        return redirect()->back()->with('success', '🗑️ Rak berhasil dihapus!');
    }
}