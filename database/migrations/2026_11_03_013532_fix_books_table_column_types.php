<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->integer('tahun_terbit')->nullable()->change();
            $table->integer('jumlah_halaman')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->smallInteger('tahun_terbit')->nullable()->change();
            $table->smallInteger('jumlah_halaman')->nullable()->change();
        });
    }
};