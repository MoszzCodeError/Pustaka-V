<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rack;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Dummy
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@pustakav.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Rak Dummy
        $rak1 = Rack::create(['nama_rak' => 'RAK A1', 'kategori' => 'Fiksi & Sastra']);

        // 3. Data Buku Sesuai Design UI/UX
        $books = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'genre' => 'Fiction',
                'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&q=80',
                'isbn' => '978-979-3062-79-1',
                'stok_total' => 10,
                'stok_tersedia' => 5,
                'rack_id' => $rak1->id
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'A. Fuadi',
                'genre' => 'Adventure',
                'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=500&q=80',
                'isbn' => '978-979-22-4861-6',
                'stok_total' => 8,
                'stok_tersedia' => 4,
                'rack_id' => $rak1->id
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'genre' => 'History',
                'cover_image' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=500&q=80',
                'isbn' => '978-979-97312-3-4',
                'stok_total' => 12,
                'stok_tersedia' => 7,
                'rack_id' => $rak1->id
            ],
            [
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'genre' => 'Self-Help',
                'cover_image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=500&q=80',
                'isbn' => '978-602-424-694-5',
                'stok_total' => 15,
                'stok_tersedia' => 10,
                'rack_id' => $rak1->id
            ],
            [
                'judul' => 'Pulang - Pergi',
                'penulis' => 'Tere Liye',
                'genre' => 'Mystery',
                'cover_image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=500&q=80',
                'isbn' => '978-623-96074-0-0',
                'stok_total' => 6,
                'stok_tersedia' => 3,
                'rack_id' => $rak1->id
            ],
        ];

        foreach ($books as $b) {
            Book::create($b);
        }
    }
}