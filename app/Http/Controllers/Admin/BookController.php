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

        $books = $query->orderBy('books.created_at', 'desc')->paginate(12);

        return view('catalog', compact('books', 'genres'));
    }

    public function show($id)
    {
        $book = DB::table('books')
            ->leftJoin('racks', 'books.rack_id', '=', 'racks.id')
            ->select('books.*', 'racks.nama_rak')
            ->where('books.id', $id)
            ->first();

        if (!$book) {
            abort(404, 'Buku tidak ditemukan');
        }

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

    public function borrow(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $buku = DB::table('books')->where('id', $id)->first();
        if (!$buku || $buku->stok < 1) {
            return redirect()->back()->with('error', 'Stok buku sedang tidak tersedia.');
        }

        $durasi = (int) $request->input('durasi', 3);
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

        DB::table('books')->where('id', $id)->decrement('stok');

        return redirect()->route('user.dashboard')->with('success', "Permintaan peminjaman berhasil! Kode Unik Anda: {$kodeTukar}");
    }

    public function dashboard()
    {
        $userId = auth()->id();

        $loans = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('loans.*', 'books.judul', 'books.penulis', 'books.cover')
            ->where('loans.user_id', $userId)
            ->orderBy('loans.created_at', 'desc')
            ->get();

        $activeCount = $loans->whereIn('status', ['menunggu_pengambilan', 'aktif'])->count();

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
}