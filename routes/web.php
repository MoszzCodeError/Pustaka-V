<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==========================================
// AUTHENTICATION ROUTES (LOGIN & REGISTER)
// ==========================================

// Auth Register Routes
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Auth Login Routes
// Jika pakai Controller (rekomendasi agar fungsi login berjalan):
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Catatan: Jika kamu BELUM punya method showLoginForm di LoginController, 
// gunakan route closure ini sementara waktu:
// Route::get('/login', function () { return view('auth.login'); })->name('login');


// Auth Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==========================================
// PROTECTED ROUTES (HARUS LOGIN)
// ==========================================

// User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});

// ==========================================
// HALAMAN TAMBAHAN / DUMMY
// ==========================================

Route::get('/catalog', function () { return "Halaman Catalog"; })->name('catalog');
Route::get('/contact', function () { return "Halaman Contact Us"; })->name('contact');

// Catalog & Detail Buku
Route::get('/catalog', [BookController::class, 'index'])->name('catalog');
Route::get('/catalog/{id}', [BookController::class, 'show'])->name('books.show');

// Action Meminjam Buku
Route::post('/catalog/{id}/borrow', [BookController::class, 'borrow'])->name('books.borrow');

// Route khusus siswa
Route::get('/dashboard', function () {
    // Jika admin malah buka /dashboard, redirect otomatis ke /admin/dashboard
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard'); // view siswa
})->middleware(['auth'])->name('dashboard');

// Route khusus ADMIN (Diproteksi middleware admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Kelola Buku
    Route::get('/buku', [AdminController::class, 'buku'])->name('admin.buku');
    Route::post('/buku', [AdminController::class, 'storeBuku'])->name('admin.buku.store');
    
    // Kelola Pinjaman & Riwayat
    Route::get('/pinjaman', [AdminController::class, 'pinjaman'])->name('admin.pinjaman');
    Route::get('/riwayat', [AdminController::class, 'riwayat'])->name('admin.riwayat');
    
    // Kelola Banner
    Route::get('/banner', [AdminController::class, 'banner'])->name('admin.banner');
    Route::post('/banner', [AdminController::class, 'storeBanner'])->name('admin.banner.store');
    
    Route::get('/lokasi', [AdminController::class, 'lokasi'])->name('admin.lokasi');
    Route::post('/admin/lokasi/update', [AdminController::class, 'updateLokasiBuku'])->name('admin.lokasi.update');

    Route::get('/lokasi', [AdminController::class, 'lokasi'])->name('admin.lokasi');
    Route::post('/lokasi/update', [AdminController::class, 'updateLokasiBuku'])->name('admin.lokasi.update');
});