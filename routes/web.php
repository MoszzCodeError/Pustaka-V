<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// HALAMAN PUBLIK (BERANDA, KATALOG, TENTANG, KONTAK)
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog Buku
Route::get('/catalog', [BookController::class, 'index'])->name('catalog');
Route::get('/catalog/{id}', [BookController::class, 'show'])->name('books.show');

// Halaman Tentang
Route::get('/about', function () { 
    return view('about'); 
})->name('about');

// Halaman Contact Us
Route::get('/contact', function () { 
    return view('contact'); 
})->name('contact');


// ==========================================
// AUTHENTICATION ROUTES (LOGIN, REGISTER, LOGOUT)
// ==========================================

// Auth Register Routes
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Auth Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Auth Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// ==========================================
// USER / SISWA DASHBOARD & LOANS (PROTECTED)
// ==========================================

Route::middleware(['auth'])->group(function () {

    // Proses Pengajuan Pinjam Buku oleh User
    Route::post('/catalog/{id}/borrow', [LoanController::class, 'borrow'])->name('books.borrow');

    // Route Dashboard Utama User / Admin / Super Admin
    Route::get('/dashboard', function () {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            return redirect()->route('admin.dashboard');
        }
        return app(DashboardController::class)->index();
    })->name('dashboard');

    // Alias 'user.dashboard' agar kompatibilitas kode lama tetap terjaga
    Route::get('/user/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('user.dashboard');

    // Generate Kode Unik Pembayaran QRIS User
    Route::post('/bayar-qris/generate/{loanId}', [LoanController::class, 'generateKodeQris'])->name('user.bayar-qris.generate');

    // Manajemen Pinjaman User
    Route::prefix('user/loans')->name('user.loans.')->group(function () {
        Route::get('/', [LoanController::class, 'userIndex'])->name('index');
        
        // Minta Kode Pengembalian (Mengarah ke DashboardController)
        Route::post('/{id}/return', [DashboardController::class, 'returnRequest'])->name('return');
        
        // Batal Pinjam (Tetap ke LoanController)
        Route::post('/{id}/cancel', [LoanController::class, 'cancelBorrow'])->name('cancel');
    });

});


// ==========================================
// ADMIN & SUPER ADMIN PANEL ROUTES (HARUS LOGIN & ROLE ADMIN/SUPER ADMIN)
// ==========================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Kelola Buku (CRUD Penuh)
    Route::get('/buku', [AdminController::class, 'buku'])->name('buku');
    Route::post('/buku', [AdminController::class, 'storeBuku'])->name('buku.store');
    Route::put('/buku/{id}', [AdminController::class, 'updateBuku'])->name('buku.update');
    Route::delete('/buku/{id}', [AdminController::class, 'destroyBuku'])->name('buku.destroy');

    // Kelola Genre (Tambah & Hapus Genre)
    Route::post('/genre', [AdminController::class, 'storeGenre'])->name('genre.store');
    Route::delete('/genre/{id}', [AdminController::class, 'destroyGenre'])->name('genre.destroy');
    
    // Kelola Pinjaman & Verifikasi Kode
    Route::get('/pinjaman', [LoanController::class, 'adminIndex'])->name('pinjaman');
    Route::post('/pinjaman/verifikasi', [LoanController::class, 'verifyCode'])->name('pinjaman.verifikasi');
    Route::post('/pinjaman/verifikasi-kembali', [LoanController::class, 'verifyCode'])->name('pinjaman.verifikasi-kembali');
    Route::post('/pinjaman/verifikasi-qris', [AdminController::class, 'verifikasiQris'])->name('pinjaman.verifikasi-qris');
    Route::post('/pinjaman/bayar-denda-cash', [LoanController::class, 'confirmCashPayment'])->name('pinjaman.bayar-denda-cash');
    Route::post('/pinjaman/{id}/kembali', [LoanController::class, 'returnBook'])->name('pinjaman.kembali');
    
    // Riwayat Pinjaman, Analytics, Export Excel & Export PDF
    Route::get('/riwayat', [AdminController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/export', [AdminController::class, 'exportRiwayat'])->name('riwayat.export');
    Route::get('/riwayat/export-pdf', [AdminController::class, 'exportRiwayatPdf'])->name('riwayat.export_pdf');
    
    // Kelola Banner & Promosi
    Route::get('/banner', [AdminController::class, 'banner'])->name('banner');
    Route::post('/banner', [AdminController::class, 'storeBanner'])->name('banner.store');
    Route::delete('/banner/{id}', [AdminController::class, 'destroyBanner'])->name('banner.destroy');
    
    // Upload & Hapus QRIS
    Route::post('/qris/upload', [AdminController::class, 'uploadQris'])->name('qris.upload');
    Route::delete('/qris/destroy', [AdminController::class, 'destroyQris'])->name('qris.destroy');

    // Kelola Berita
    Route::post('/news', [AdminController::class, 'storeNews'])->name('news.store');
    Route::put('/news/{id}', [AdminController::class, 'updateNews'])->name('news.update');
    Route::delete('/news/{id}', [AdminController::class, 'destroyNews'])->name('news.destroy');
    
    // Kelola Lokasi & Rak Buku
    Route::get('/lokasi', [AdminController::class, 'lokasi'])->name('lokasi');
    Route::post('/lokasi/update', [AdminController::class, 'updateLokasiBuku'])->name('lokasi.update');
    Route::post('/lokasi/rak/store', [AdminController::class, 'storeRak'])->name('lokasi.rak.store');
    Route::delete('/lokasi/rak/{id}', [AdminController::class, 'destroyRak'])->name('lokasi.rak.destroy');
    
    // ==========================================
    // KHUSUS SUPER ADMIN (KELOLA SELURUH PENGGUNA)
    // ==========================================
    Route::middleware(['can:super-admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

});