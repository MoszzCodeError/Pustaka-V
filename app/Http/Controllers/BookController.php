<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BookController extends Controller
{
    // ==========================================
    // 1. KATALOG & DETAIL BUKU (PUBLIK)
    // ==========================================

    // Halaman Catalog (List semua buku + Search & Filter Genre Dinamis + Pagination Query String)
    public function index(Request $request)
    {
        // Murni Ambil Genre dari Tabel `genres` Database
        $genres = DB::table('genres')->get();

        $query = DB::table('books')
            ->leftJoin('racks', 'books.rack_id', '=', 'racks.id')
            ->select('books.*', 'racks.nama_rak');

        // Search berdasarkan judul atau penulis
        if ($request->filled('search') || $request->filled('q')) {
            $search = $request->search ?? $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('books.judul', 'like', '%' . $search . '%')
                  ->orWhere('books.penulis', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan Genre
        if ($request->filled('genre')) {
            $query->where('books.genre', $request->genre);
        }

        // Menggunakan withQueryString() agar parameter search & genre tetap terbawa saat pindah halaman
        $books = $query->orderBy('books.created_at', 'desc')
                       ->paginate(12)
                       ->withQueryString();

        return view('catalog', compact('books', 'genres'));
    }

    // Halaman Detail Buku
    public function show($id)
    {
        // Ambil data detail buku + join ke tabel racks untuk nama_rak
        $book = DB::table('books')
            ->leftJoin('racks', 'books.rack_id', '=', 'racks.id')
            ->select('books.*', 'racks.nama_rak')
            ->where('books.id', $id)
            ->first();

        if (!$book) {
            abort(404, 'Buku tidak ditemukan');
        }

        // Ambil Genre untuk mencari buku terkait
        $genreBook = $book->genre ?? null;

        $relatedBooks = DB::table('books')
            ->where('id', '!=', $id)
            ->when($genreBook, function ($query) use ($genreBook) {
                return $query->where('genre', $genreBook);
            })
            ->take(4)
            ->get();

        if ($relatedBooks->count() < 4) {
            $excludeIds = $relatedBooks->pluck('id')->push($id)->toArray();

            $additional = DB::table('books')
                ->whereNotIn('id', $excludeIds)
                ->take(4 - $relatedBooks->count())
                ->get();

            $relatedBooks = $relatedBooks->merge($additional);
        }

        return view('books.show', compact('book', 'relatedBooks'));
    }

    // ==========================================
    // 2. PEMINJAMAN & USER DASHBOARD
    // ==========================================

    // Proses Pinjam Buku (Strict Role Guard)
    public function borrow(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = strtolower(trim(auth()->user()->role ?? ''));

        // Proteksi ketat: Admin & Super Admin dilarang meminjam buku
        if (in_array($userRole, ['admin', 'super_admin', 'superadmin', 'administrator'])) {
            return redirect()->back()->with('error', '🚫 Akun Administrator / Super Admin tidak diperbolehkan meminjam buku.');
        }

        $buku = DB::table('books')->where('id', $id)->first();
        if (!$buku || $buku->stok < 1) {
            return redirect()->back()->with('error', 'Stok buku sedang tidak tersedia.');
        }

        $durasi = (int) $request->input('durasi', 3);

        // Generasi Kode Unik 5 Karakter Alfanumerik
        $kodeTukar = strtoupper(Str::random(5));

        DB::table('loans')->insert([
            'kode_tukar'      => $kodeTukar,
            'user_id'         => auth()->id(),
            'book_id'         => $id,
            'tanggal_pinjam'  => Carbon::now()->toDateString(),
            'tanggal_kembali' => Carbon::now()->addDays($durasi)->toDateString(),
            'durasi_hari'     => $durasi,
            'status'          => 'menunggu_pengambilan',
            'denda'           => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Kurangi stok buku
        DB::table('books')->where('id', $id)->decrement('stok');

        return redirect()->route('user.dashboard')->with('success', "Permintaan peminjaman berhasil! Kode Unik Anda: {$kodeTukar}");
    }

    // Halaman User Dashboard
    public function dashboard()
    {
        $userId = auth()->id();

        // Mengambil semua data peminjaman milik user bersangkutan + join data buku
        $loans = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('loans.*', 'books.judul', 'books.penulis', 'books.cover')
            ->where('loans.user_id', $userId)
            ->orderBy('loans.created_at', 'desc')
            ->get();

        // Hitung statistik untuk cards dashboard
        $activeCount = $loans->whereIn('status', ['menunggu_pengambilan', 'aktif'])->count();

        // Menghitung pinjaman aktif yang jatuh tempo dalam kurung waktu <= 2 hari
        $dueSoonCount = $loans->where('status', 'aktif')
            ->filter(function ($item) {
                $daysRemaining = Carbon::now()->diffInDays(Carbon::parse($item->tanggal_kembali), false);
                return $daysRemaining >= 0 && $daysRemaining <= 2;
            })->count();

        $totalFinesSum = $loans->sum('denda');
        $booksReadCount = $loans->where('status', 'dikembalikan')->count();

        $stats = [
            'active_loans' => $activeCount,
            'due_soon'     => $dueSoonCount,
            'total_fines'  => 'Rp ' . number_format($totalFinesSum, 0, ',', '.'),
            'books_read'   => $booksReadCount,
        ];

        return view('user.dashboard', compact('loans', 'stats'));
    }

    // ==========================================
    // 3. ADMIN MANAGEMENT BUKU & LOKASI RAK
    // ==========================================

    // List Buku untuk Admin
    public function adminBuku()
    {
        $books = DB::table('books')
            ->leftJoin('racks', 'books.rack_id', '=', 'racks.id')
            ->select('books.*', 'racks.nama_rak')
            ->orderBy('books.id', 'desc')
            ->paginate(10);

        $racks = DB::table('racks')->get();

        return view('admin.buku', compact('books', 'racks'));
    }

    // Tambah Buku Baru Oleh Admin
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
            'deskripsi'      => 'nullable|string',
            'rack_id'        => 'nullable|exists:racks,id',
            'stok'           => 'required|integer|min:0',
            'jumlah_halaman' => 'nullable|integer',
            'penerbit'       => 'nullable|string|max:255',
            'tahun_terbit'   => 'nullable|numeric',
            'isbn'           => 'nullable|string|max:100',
            'genre'          => 'nullable|string|max:100',
            'cover'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        DB::table('books')->insert([
            'judul'          => trim($request->judul),
            'penulis'        => trim($request->penulis),
            'deskripsi'      => trim($request->deskripsi),
            'rack_id'        => $request->rack_id,
            'stok'           => $request->stok,
            'cover'          => $coverPath,
            'jumlah_halaman' => $request->jumlah_halaman ?? null,
            'penerbit'       => trim($request->penerbit) ?? null,
            'tahun_terbit'   => $request->tahun_terbit ?? null,
            'isbn'           => trim($request->isbn) ?? null,
            'genre'          => trim($request->genre) ?? null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    // Update Buku Oleh Admin
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
            'deskripsi'      => 'nullable|string',
            'rack_id'        => 'nullable|exists:racks,id',
            'stok'           => 'required|integer|min:0',
            'jumlah_halaman' => 'nullable|integer',
            'penerbit'       => 'nullable|string|max:255',
            'tahun_terbit'   => 'nullable|numeric',
            'isbn'           => 'nullable|string|max:100',
            'genre'          => 'nullable|string|max:100',
            'cover'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $book = DB::table('books')->where('id', $id)->first();
        if (!$book) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan.');
        }

        $coverPath = $book->cover;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        DB::table('books')->where('id', $id)->update([
            'judul'          => trim($request->judul),
            'penulis'        => trim($request->penulis),
            'deskripsi'      => trim($request->deskripsi),
            'rack_id'        => $request->rack_id,
            'stok'           => $request->stok,
            'cover'          => $coverPath,
            'jumlah_halaman' => $request->jumlah_halaman,
            'penerbit'       => trim($request->penerbit),
            'tahun_terbit'   => $request->tahun_terbit,
            'isbn'           => trim($request->isbn),
            'genre'          => trim($request->genre),
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Buku berhasil diperbarui!');
    }

    // Hapus Buku Oleh Admin
    public function destroyBuku($id)
    {
        $deleted = DB::table('books')->where('id', $id)->delete();

        if ($deleted) {
            return redirect()->back()->with('success', 'Buku berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Buku tidak ditemukan atau gagal dihapus.');
    }

    // Kelola Lokasi Rak Buku
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

    // Update Lokasi Rak Buku
    public function updateLokasiBuku(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rack_id' => 'required|exists:racks,id',
        ]);

        DB::table('books')->where('id', $request->book_id)->update([
            'rack_id'    => $request->rack_id,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Lokasi buku berhasil diperbarui!');
    }
}