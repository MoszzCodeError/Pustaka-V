<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil daftar pinjaman milik user
        $loans = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select(
                'loans.*', 
                'books.judul', 
                'books.cover', 
                'books.penulis'
            )
            ->where('loans.user_id', $userId)
            ->orderBy('loans.id', 'desc')
            ->get();

        // 2. Hitung Denda Berjalan & Keterlambatan Real-Time
        $today = Carbon::now()->startOfDay();
        $tarifDendaPerHari = 1000;

        $calculatedTotalFines = 0;
        $dueSoonCount = 0;

        foreach ($loans as $loan) {
            $isLunas = isset($loan->denda_lunas) && (int)$loan->denda_lunas === 1;

            // JIKA DENDA SUDAH DILUNASI OLEH ADMIN/CASH -> RESET DENDA JADI 0!
            if ($isLunas) {
                $loan->denda_berjalan = 0;
                $loan->hari_terlambat = 0;
                continue; // Lanjut ke transaksi berikutnya, denda tidak ditambahkan ke total!
            }

            // Hitung denda HANYA jika status masih 'aktif' DAN BELUM LUNAS
            if ($loan->status === 'aktif' && $loan->tanggal_kembali) {
                $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();

                if ($today->gt($dueDate)) {
                    $hariTerlambat = (int) $dueDate->diffInDays($today);
                    $dendaBerjalan = $hariTerlambat * $tarifDendaPerHari;

                    $loan->denda_berjalan = $dendaBerjalan;
                    $loan->hari_terlambat = $hariTerlambat;
                    $calculatedTotalFines += $dendaBerjalan;
                } else {
                    $loan->denda_berjalan = 0;
                    $loan->hari_terlambat = 0;

                    $selisihHari = $today->diffInDays($dueDate, false);
                    if ($selisihHari >= 0 && $selisihHari <= 1) {
                        $dueSoonCount++;
                    }
                }
            } else {
                // Untuk status selain aktif (misal dikembalikan), cek denda db
                $dendaDb = max(0, (int) abs($loan->denda ?? 0));
                $loan->denda_berjalan = $dendaDb;
                $loan->hari_terlambat = 0;
            }
        }

        // 3. Hitung Statistik Riil
        $activeLoansCount = $loans->whereIn('status', ['menunggu_pengambilan', 'aktif', 'menunggu_pengembalian'])->count();
        $historyTotal     = $loans->count();
        $historyOntime    = $loans->where('status', 'dikembalikan')->count();
        $historyLate      = $loans->where('status', 'terlambat')->count();

        $stats = [
            'active_loans'   => $activeLoansCount,
            'due_soon'       => $dueSoonCount,
            'total_fines'    => 'Rp ' . number_format($calculatedTotalFines, 0, ',', '.'),
            'raw_fines'      => $calculatedTotalFines,
            'books_read'     => $historyOntime,
            'history_total'  => $historyTotal,
            'history_ontime' => $historyOntime,
            'history_late'   => $historyLate,
        ];

        $qrisImage = asset('storage/qris/qris-perpustakaan.png');

        return view('user.dashboard', compact('stats', 'loans', 'qrisImage'));
    }

    /**
     * Minta Kode Pengembalian Buku oleh User
     */
    public function returnRequest($id)
    {
        $userId = Auth::id();

        $loan = DB::table('loans')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->where('status', 'aktif')
            ->first();

        if (!$loan) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan atau tidak dalam status aktif.');
        }

        $today = Carbon::now()->startOfDay();
        $isLunas = isset($loan->denda_lunas) && (int)$loan->denda_lunas === 1;

        // JIKA KETERLAMBATAN TERJADI DAN BELUM LUNAS -> BLOKIR!
        if ($loan->tanggal_kembali && !$isLunas) {
            $dueDate = Carbon::parse($loan->tanggal_kembali)->startOfDay();

            if ($today->gt($dueDate)) {
                $hariTerlambat = (int) $dueDate->diffInDays($today);
                $denda = $hariTerlambat * 1000;

                return redirect()->back()->with('error', 'Buku ini mengalami keterlambatan ' . $hariTerlambat . ' hari (Denda: Rp ' . number_format($denda, 0, ',', '.') . '). Silakan pelunasan denda terlebih dahulu!');
            }
        }

        // Kalau denda sudah lunas ATAU tidak ada denda -> Buat kode pengembalian
        $kodeKembali = 'RET-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));

        DB::table('loans')
            ->where('id', $id)
            ->update([
                'status'       => 'menunggu_pengembalian',
                'kode_kembali' => $kodeKembali,
                'updated_at'   => Carbon::now(),
            ]);

        return redirect()->back()->with('success', 'Kode pengembalian berhasil dibuat: ' . $kodeKembali . '. Tunjukkan kode ini kepada pustakawan!');
    }
}