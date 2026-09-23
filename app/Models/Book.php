<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Satu Buku dimiliki oleh Satu Rak
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }
}