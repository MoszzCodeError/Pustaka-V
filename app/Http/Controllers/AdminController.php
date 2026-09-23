<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBuku = DB::table('books')->count();
        $bukuDipinjam = DB::table('loans')->where('status', 'dipinjam')->count();
        $bukuTersedia = max(0, $totalBuku - $bukuDipinjam);
        $siswaAktif = User::where('role', 'user')->count();

        return view('admin.dashboard', compact('totalBuku', 'bukuDipinjam', 'bukuTersedia', 'siswaAktif'));
    }

    public function buku()
    {
        $books = DB::table('books')
            ->leftJoin('racks', 'books.rack_id', '=', 'racks.id')
            ->select('books.*', 'racks.nama_rak')
            ->paginate(10);

        $racks = DB::table('racks')->get();

        return view('admin.buku', compact('books', 'racks'));
    }

    // FUNGSI SIMPAN BUKU BARU (DENGAN COVER, DESKRIPSI, DAN RAK)
    public function storeBuku(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'penulis'   => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'rack_id'   => 'nullable|exists:racks,id',
            'stok'      => 'required|integer|min:1',
            'cover'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        DB::table('books')->insert([
            'judul'      => $request->judul,
            'penulis'    => $request->penulis,
            'deskripsi'  => $request->deskripsi,
            'rack_id'    => $request->rack_id,
            'stok'       => $request->stok,
            'cover'      => $coverPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    // FIX ERROR KELOLA PINJAMAN (books.judul)
    public function pinjaman()
    {
        $pinjaman = DB::table('loans')
            ->join('users', 'loans.user_id', '=', 'users.id')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('loans.*', 'users.name as nama_siswa', 'books.judul as judul')
            ->get();

        return view('admin.pinjaman', compact('pinjaman'));
    }

    public function riwayat()
    {
        $riwayat = DB::table('loans')->get();
        $totalPinjaman = $riwayat->count();
        $totalDenda = DB::table('loans')->sum('denda');

        return view('admin.riwayat', compact('riwayat', 'totalPinjaman', 'totalDenda'));
    }

    public function banner()
    {
        $banners = DB::table('banners')->get();
        return view('admin.banner', compact('banners'));
    }

    // FUNGSI UPLOAD BANNER (Aman dari bentrok nama kolom image / image_path)
    public function storeBanner(Request $request)
    {
        $request->validate([
            'image'     => 'required|image|mimes:jpeg,png,jpg|max:3072',
            'judul'     => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');

            // Deteksi otomatis kolom di tabel banners
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

            DB::table('banners')->insert($data);
        }

        return redirect()->back()->with('success', 'Banner berhasil diunggah!');
    }

    // Buka halaman Lokasi Buku & Rak
    public function lokasi(Request $request)
    {
        // 1. Ambil data unik dari tabel racks
        $racks = DB::table('racks')
            ->select('racks.*')
            ->selectSub(function ($query) {
                $query->from('books')
                    ->whereColumn('books.rack_id', 'racks.id')
                    ->selectRaw('COALESCE(SUM(stok), 0)');
            }, 'total_buku')
            ->get();

        // 2. Ambil semua buku untuk dropdown
        $allBooks = DB::table('books')->select('id', 'judul', 'rack_id')->get();

        // 3. Rak aktif yang dipilih
        $firstRack = $racks->first();
        $selectedRackId = $request->query('rack_id', $firstRack ? $firstRack->id : null);

        // 4. Buku dalam rak terpilih
        $booksInRack = [];
        if ($selectedRackId) {
            $booksInRack = DB::table('books')
                ->where('rack_id', $selectedRackId)
                ->get();
        }

        return view('admin.lokasi', compact('racks', 'allBooks', 'booksInRack', 'selectedRackId'));
    }
    // FUNGSI UPDATE LOKASI BUKU
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
}