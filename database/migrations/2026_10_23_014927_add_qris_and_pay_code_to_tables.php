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
    // Tabel Settings untuk simpan 1 QRIS Admin saja
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable();
        $table->timestamps();
    });

    // Tambah kode unik bayar & status konfirmasi di tabel loans
    Schema::table('loans', function (Blueprint $table) {
        $table->string('kode_bayar_qris')->nullable()->after('denda_lunas');
        $table->enum('status_pembayaran_qris', ['none', 'pending', 'lunas', 'ditolak'])->default('none')->after('kode_bayar_qris');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
