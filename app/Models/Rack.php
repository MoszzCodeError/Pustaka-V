<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Satu Rak memiliki banyak Buku
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}