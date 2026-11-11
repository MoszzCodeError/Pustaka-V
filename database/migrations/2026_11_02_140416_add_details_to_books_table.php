<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'genre')) {
                $table->string('genre')->nullable()->after('judul');
            }
            if (!Schema::hasColumn('books', 'penerbit')) {
                $table->string('penerbit')->nullable()->after('penulis');
            }
            if (!Schema::hasColumn('books', 'tahun_terbit')) {
                $table->integer('tahun_terbit')->nullable()->after('penerbit');
            }
            if (!Schema::hasColumn('books', 'isbn')) {
                $table->string('isbn')->nullable()->after('judul');
            }
            if (!Schema::hasColumn('books', 'jumlah_halaman')) {
                $table->integer('jumlah_halaman')->nullable()->after('isbn');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['genre', 'penerbit', 'tahun_terbit', 'isbn', 'jumlah_halaman']);
        });
    }
};