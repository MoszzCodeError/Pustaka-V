<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $banners = [];

        // Logika Banner & News
        if (Schema::hasTable('banners')) {
            $query = DB::table('banners');
            if (Schema::hasColumn('banners', 'is_active')) {
                $query->where('is_active', 1);
            }
            if (Schema::hasColumn('banners', 'display_order')) {
                $query->orderBy('display_order', 'asc');
            }

            $bannersData = $query->get();

            foreach ($bannersData as $b) {
                $news = null;
                if (Schema::hasTable('news')) {
                    $news = DB::table('news')->where('banner_id', $b->id)->latest()->first();
                }

                $imageCol = Schema::hasColumn('banners', 'image_path') ? 'image_path' : 'image';
                $imgPath = $b->$imageCol ?? '';

                $banners[] = [
                    'tag'   => $news ? $news->tag_header : 'ANNOUNCEMENT',
                    'title' => $news ? $news->judul : ($b->judul ?? 'Pustaka V Digital'),
                    'desc'  => $news ? $news->isi : ($b->deskripsi ?? 'Perpustakaan digital resmi SMKN 5 Surakarta'),
                    'image' => asset('storage/' . $imgPath),
                ];
            }
        }

        // Ambil Max 14 Buku (7 Atas & 7 Bawah Pas) Secara Acak dari Database
        $books = DB::table('books')
            ->leftJoin('racks', 'books.rack_id', '=', 'racks.id')
            ->select('books.*', 'racks.nama_rak')
            ->inRandomOrder()
            ->limit(14)
            ->get();

        // Murni Ambil Data Genre dari Database Tabel `genres`
        $categories = DB::table('genres')->get();

        return view('home', compact('banners', 'books', 'categories'));
    }

    public function catalog()
    {
        $books = Book::latest()->paginate(10);

        return view('catalog', compact('books'));
    }

    public function contact()
    {
        return view('contact');
    }
}