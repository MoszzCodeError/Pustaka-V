<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Dashboard Admin Overview
    public function dashboard()
    {
        $totalBuku = Book::count();
        $bukuDipinjam = 0; // Dinamis nanti
        $bukuTersedia = $totalBuku;
        $siswaAktif = 0;

        return view('admin.dashboard', compact('totalBuku', 'bukuDipinjam', 'bukuTersedia', 'siswaAktif'));
    }

    // Halaman Kelola Buku
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    // Proses Simpan Buku Baru
    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'genre'        => 'required|string',
            'isbn'         => 'nullable|string',
            'stok'         => 'required|integer|min:0',
            'lokasi_rak'   => 'required|string',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi'    => 'nullable|string',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $coverPath = '/storage/' . $coverPath;
        }

        Book::create([
            'judul'       => $request->judul,
            'penulis'     => $request->penulis,
            'genre'       => $request->genre,
            'isbn'        => $request->isbn,
            'stok'        => $request->stok,
            'lokasi_rak'  => $request->lokasi_rak,
            'cover_image' => $coverPath,
            'deskripsi'   => $request->deskripsi,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan!');
    }

    // Hapus Buku
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return back()->with('success', 'Buku berhasil dihapus!');
    }
}