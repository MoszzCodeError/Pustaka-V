<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data buku untuk halaman home
        $trendingBooks = Book::latest()->take(5)->get();

        return view('home', compact('trendingBooks'));
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