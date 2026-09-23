<?php

namespace App\Http\Controllers;

use App\Models\Book; // Sesuaikan dengan nama model buku kamu (misal: Book)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    // Halaman Catalog (List semua buku)
    public function index(Request $request)
    {
        $query = Book::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        // Filter Genre
        if ($request->has('genre') && $request->genre != '') {
            $query->where('genre', $request->genre);
        }

        $books = $query->latest()->paginate(12);

        return view('catalog', compact('books'));
    }

    // Halaman Detail Buku
    public function show($id)
    {
        $book = Book::findOrFail($id);

        // Ambil 4 buku lain sebagai rekomendasi (Related Books)
        $relatedBooks = Book::where('id', '!=', $id)
                            ->where('genre', $book->genre)
                            ->take(4)
                            ->get();

        // Jika buku bergenre sama kurang dari 4, ambil acak sisanya
        if ($relatedBooks->count() < 4) {
            $additional = Book::where('id', '!=', $id)
                              ->whereNotIn('id', $relatedBooks->pluck('id'))
                              ->take(4 - $relatedBooks->count())
                              ->get();
            $relatedBooks = $relatedBooks->merge($additional);
        }

        return view('books.show', compact('book', 'relatedBooks'));
    }

    // Proses Pinjam Buku
    public function borrow(Request $request, $id)
    {
        // Cek apakah user sudah login, jika belum arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk meminjam buku.');
        }

        // Logika simpan transaksi peminjaman kamu di sini
        // Contoh sederhana:
        // BorrowLog::create([...]);

        return back()->with('success', 'Permintaan peminjaman buku berhasil diajukan!');
    }
}