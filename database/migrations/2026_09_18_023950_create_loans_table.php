<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_tukar', 5)->unique(); // Kode unik misal: A7K2X
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali');
        $table->integer('durasi_hari');
        $table->enum('status', ['menunggu_pengambilan', 'aktif', 'dikembalikan', 'terlambat', 'ditolak'])->default('menunggu_pengambilan');
        $table->decimal('denda', 10, 2)->default(0);
        $table->boolean('denda_lunas')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
