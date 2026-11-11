<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoanController extends Controller
{
    // ==========================================
    // 1. FITUR USER (Peminjam Buku)
    // ==========================================

    /**
     * Menampilkan Dashboard User (Daftar Pinjaman & Statistik)
     */
    public function userDashboard()
    {
        $userId = auth()->id();

        // Ambil riwayat peminjaman user beserta data buku
        $loans = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('loans.*', 'books.judul', 'books.penulis', 'books.cover', 'books.cover_image')
            ->where('loans.user_id', $userId)
            ->orderBy('loans.created_at', 'desc')
            ->get();

        $today = Carbon::now()->startOfDay();
        $tarifDendaPerHari = 1000;
        $totalDenda = 0;

        foreach ($loans as $loan) {
            if ($loan->status === 'aktif' && $loan->tanggal_kembali) {
                $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();
                if ($today->gt($dueDate)) {
                    $hariTerlambat = (int) $dueDate->diffInDays($today);
                    $loan->denda_berjalan = $hariTerlambat * $tarifDendaPerHari;
                    $totalDenda += $loan->denda_berjalan;
                } else {
                    $loan->denda_berjalan = 0;
                }
            } else {
                if (isset($loan->denda_lunas) && $loan->denda_lunas == 0) {
                    $totalDenda += max(0, (int) abs($loan->denda ?? 0));
                }
                $loan->denda_berjalan = max(0, (int) abs($loan->denda ?? 0));
            }
        }

        // Hitung statistik user
        $stats = [
            'active_loans' => $loans->whereIn('status', ['menunggu_pengambilan', 'aktif', 'menunggu_pengembalian'])->count(),
            'due_soon'     => $loans->where('status', 'aktif')->filter(function ($item) use ($today) {
                return Carbon::parse($item->tanggal_kembali)->startOfDay()->isPast();
            })->count(),
            'total_fines'  => 'Rp ' . number_format($totalDenda, 0, ',', '.'),
            'books_read'   => $loans->where('status', 'dikembalikan')->count(),
        ];

        return view('user.dashboard', compact('loans', 'stats'));
    }

    /**
     * Menampilkan Halaman Riwayat & Daftar Pinjaman User (/user/loans)
     */
    public function userIndex()
    {
        $userId = auth()->id();

        $loans = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('loans.*', 'books.judul', 'books.penulis', 'books.cover', 'books.cover_image')
            ->where('loans.user_id', $userId)
            ->orderBy('loans.created_at', 'desc')
            ->get();

        $today = Carbon::now()->startOfDay();
        $tarifDendaPerHari = 1000;

        foreach ($loans as $loan) {
            if ($loan->status === 'aktif' && $loan->tanggal_kembali) {
                $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();
                if ($today->gt($dueDate)) {
                    $hariTerlambat = (int) $dueDate->diffInDays($today);
                    $loan->denda_berjalan = $hariTerlambat * $tarifDendaPerHari;
                } else {
                    $loan->denda_berjalan = 0;
                }
            } else {
                $loan->denda_berjalan = max(0, (int) abs($loan->denda ?? 0));
            }
        }

        return view('user.loans', compact('loans'));
    }

    /**
     * Proses Pengajuan Pinjam Buku oleh User
     */
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
        $kodeTukar = strtoupper(Str::random(5)); // Kode tukar saat ambil buku

        // Simpan data transaksi ke tabel loans
        DB::table('loans')->insert([
            'kode_tukar'      => $kodeTukar,
            'kode_kembali'    => null,
            'user_id'         => auth()->id(),
            'book_id'         => $id,
            'tanggal_pinjam'  => Carbon::now()->toDateString(),
            'tanggal_kembali' => Carbon::now()->addDays($durasi)->toDateString(),
            'durasi_hari'     => $durasi,
            'status'          => 'menunggu_pengambilan',
            'denda'           => 0,
            'denda_lunas'     => 1, // Default dianggap lunas jika tidak ada denda
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Kurangi stok buku
        DB::table('books')->where('id', $id)->decrement('stok');

        return redirect()->route('dashboard')->with('success', "Permintaan peminjaman berhasil! Kode Penyerahan Anda: {$kodeTukar}");
    }

    /**
     * Generate Kode Unik Pembayaran QRIS oleh User
     */
    public function generateKodeQris($loanId)
    {
        $loan = Loan::where('user_id', auth()->id())->findOrFail($loanId);
        
        $loan->update([
            'kode_bayar_qris' => 'Q-' . strtoupper(Str::random(4)),
            'status_pembayaran_qris' => 'pending'
        ]);

        return back()->with('success', 'Kode Unik Pembayaran berhasil dibuat! Tunjukkan kode tersebut ke admin.');
    }

    /**
     * Proses Minta Kode Pengembalian oleh User (Ditolak jika belum bayar denda)
     */
    public function requestReturn(Request $request, $id)
    {
        $loan = DB::table('loans')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$loan) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if ($loan->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        // --- PROTEKSI DENDA ---
        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();

        if ($today->gt($dueDate) && isset($loan->denda_lunas) && $loan->denda_lunas == 0) {
            $hariTerlambat = (int) $dueDate->diffInDays($today);
            $tarifDenda = 1000; // Rp 1.000 / hari
            $totalDenda = $hariTerlambat * $tarifDenda;

            // Kunci total denda ke database
            DB::table('loans')->where('id', $id)->update([
                'denda'       => $totalDenda,
                'denda_lunas' => 0,
                'updated_at'  => now()
            ]);

            return redirect()->back()->with('error', 'Buku ini mengalami keterlambatan! Kamu harus melunasi denda sebesar Rp ' . number_format($totalDenda, 0, ',', '.') . ' terlebih dahulu sebelum meminta kode pengembalian.');
        }

        // Cek jika sudah pernah di-lock denda sebelumnya dan belum lunas
        if (isset($loan->denda_lunas) && $loan->denda_lunas == 0 && $loan->denda > 0) {
            return redirect()->back()->with('error', 'Harap lunasi denda sebesar Rp ' . number_format($loan->denda, 0, ',', '.') . ' terlebih dahulu.');
        }

        // Generate Kode Pengembalian jika belum ada
        $kodeKembali = $loan->kode_kembali ?? 'RET-' . strtoupper(Str::random(5));

        DB::table('loans')->where('id', $id)->update([
            'kode_kembali' => $kodeKembali,
            'status'       => 'menunggu_pengembalian',
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', "Kode Pengembalian berhasil dibuat: {$kodeKembali}. Tunjukkan kode ini ke petugas perpustakaan.");
    }

    /**
     * Membatalkan Peminjaman (Hanya jika masih berstatus 'menunggu_pengambilan')
     */
    public function cancelBorrow($id)
    {
        $loan = DB::table('loans')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'menunggu_pengambilan')
            ->first();

        if (!$loan) {
            return redirect()->back()->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        // Hapus record transaksi peminjaman
        DB::table('loans')->where('id', $id)->delete();

        // Kembalikan stok buku (+1)
        DB::table('books')->where('id', $loan->book_id)->increment('stok');

        return redirect()->back()->with('success', 'Peminjaman berhasil dibatalkan dan stok buku telah dikembalikan.');
    }

    // ==========================================
    // 2. FITUR ADMIN (Pengelola Pinjaman)
    // ==========================================

    /**
     * Menampilkan Halaman Kelola Pinjaman untuk Admin
     */
    public function adminIndex()
    {
        // Ambil semua transaksi peminjaman untuk ditampilkan di tabel admin
        $loans = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->join('users', 'loans.user_id', '=', 'users.id')
            ->select('loans.*', 'books.judul', 'users.name as nama_peminjam', 'users.email')
            ->orderBy('loans.created_at', 'desc')
            ->get();

        return view('admin.pinjaman', compact('loans'));
    }

    /**
     * Memproses Verifikasi Kode (Bisa berupa Kode Tukar atau Kode Pengembalian)
     */
    public function verifyCode(Request $request)
    {
        $kodeInput = $request->input('kode_tukar') ?? $request->input('kode_kembali');

        if (!$kodeInput) {
            return redirect()->back()->with('error', 'Silakan masukkan kode terlebih dahulu.');
        }

        $kode = strtoupper(trim($kodeInput));

        $loan = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->join('users', 'loans.user_id', '=', 'users.id')
            ->select('loans.*', 'books.judul', 'users.name as nama_peminjam')
            ->where('loans.kode_tukar', $kode)
            ->orWhere('loans.kode_kembali', $kode)
            ->first();

        if (!$loan) {
            return redirect()->back()->with('error', "Kode '{$kode}' tidak ditemukan!");
        }

        // 1. Jika kode yang diinput cocok dengan KODE TUKAR (Penyerahan buku)
        if ($loan->kode_tukar === $kode) {
            if ($loan->status === 'menunggu_pengambilan') {
                DB::table('loans')
                    ->where('id', $loan->id)
                    ->update([
                        'status'     => 'aktif',
                        'updated_at' => now(),
                    ]);

                return redirect()->back()->with('success', "Berhasil! Buku '{$loan->judul}' telah diserahkan kepada {$loan->nama_peminjam}. Status transaksi saat ini: AKTIF.");
            }

            return redirect()->back()->with('error', "Kode penyerahan '{$kode}' sudah pernah diproses.");
        }

        // 2. Jika kode yang diinput cocok dengan KODE KEMBALI (Pengembalian buku)
        if ($loan->kode_kembali === $kode) {
            if ($loan->status === 'dikembalikan') {
                return redirect()->back()->with('error', "Buku ini sudah berstatus dikembalikan sebelumnya.");
            }

            // Hitung denda akhir saat dikembalikan jika belum bayar
            $today = Carbon::now()->startOfDay();
            $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();
            $denda = max(0, (int) abs($loan->denda ?? 0));

            if ($today->gt($dueDate) && isset($loan->denda_lunas) && $loan->denda_lunas == 0) {
                $hariTerlambat = (int) $dueDate->diffInDays($today);
                $denda = $hariTerlambat * 1000;
            }

            $updateData = [
                'status'      => 'dikembalikan',
                'denda'       => $denda,
                'denda_lunas' => 1, // Otomatis lunas saat dikembalikan admin
                'updated_at'  => now(),
            ];

            // Cek jika kolom tanggal_dikembalikan ada di DB
            if (Schema::hasColumn('loans', 'tanggal_dikembalikan')) {
                $updateData['tanggal_dikembalikan'] = now()->toDateString();
            }

            DB::table('loans')
                ->where('id', $loan->id)
                ->update($updateData);

            DB::table('books')->where('id', $loan->book_id)->increment('stok');

            return redirect()->back()->with('success', "Berhasil! Buku '{$loan->judul}' dari {$loan->nama_peminjam} telah diterima kembali. Stok buku otomatis bertambah (+1).");
        }

        return redirect()->back()->with('error', "Kode '{$kode}' tidak valid.");
    }

    /**
     * Konfirmasi Pembayaran Denda Secara Cash/Tunai oleh Admin
     */
    public function confirmCashPayment(Request $request)
    {
        $inputKey = trim($request->input('loan_id_or_code'));

        if (!$inputKey) {
            return redirect()->back()->with('error', 'Masukkan ID Pinjaman, Kode Tukar, atau Kode Pengembalian!');
        }

        // Cari data transaksi
        $loan = DB::table('loans')
            ->where('id', $inputKey)
            ->orWhere('kode_tukar', strtoupper($inputKey))
            ->orWhere('kode_kembali', strtoupper($inputKey))
            ->first();

        if (!$loan) {
            return redirect()->back()->with('error', "Peminjaman dengan ID / Kode '{$inputKey}' tidak ditemukan!");
        }

        // Hitung denda keterlambatan saat ini
        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();
        $totalDenda = max(0, (int) abs($loan->denda ?? 0));

        if ($today->gt($dueDate)) {
            $hariTerlambat = (int) $dueDate->diffInDays($today);
            $totalDenda = $hariTerlambat * 1000;
        }

        // Update status denda menjadi LUNAS (denda_lunas = 1)
        DB::table('loans')
            ->where('id', $loan->id)
            ->update([
                'denda'       => $totalDenda,
                'denda_lunas' => 1,
                'updated_at'  => now(),
            ]);

        return redirect()->back()->with('success', "Pelunasan Denda CASH sebesar Rp " . number_format($totalDenda, 0, ',', '.') . " berhasil dikonfirmasi! User sekarang bisa meminta Kode Pengembalian.");
    }

    /**
     * Memproses Pengembalian Buku secara Manual oleh Admin (Fallback / Tombol di Tabel)
     */
    public function returnBook($id)
    {
        $loan = DB::table('loans')->where('id', $id)->first();

        if (!$loan) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if ($loan->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();
        $denda = max(0, (int) abs($loan->denda ?? 0));

        if ($today->gt($dueDate) && isset($loan->denda_lunas) && $loan->denda_lunas == 0) {
            $hariTerlambat = (int) $dueDate->diffInDays($today);
            $denda = $hariTerlambat * 1000;
        }

        $updateData = [
            'status'      => 'dikembalikan',
            'denda'       => $denda,
            'denda_lunas' => 1,
            'updated_at'  => now(),
        ];

        if (Schema::hasColumn('loans', 'tanggal_dikembalikan')) {
            $updateData['tanggal_dikembalikan'] = now()->toDateString();
        }

        DB::table('loans')
            ->where('id', $id)
            ->update($updateData);

        DB::table('books')->where('id', $loan->book_id)->increment('stok');

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan dan stok telah bertambah.');
    }
}