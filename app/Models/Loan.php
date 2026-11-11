<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk pengisian masal (mass assignment).
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'denda',
        'denda_lunas',
        'kode_bayar_qris',
        'status_pembayaran_qris',
    ];

    /**
     * Relasi ke model User (Siswa/Peminjam).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Book (Buku yang dipinjam).
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}