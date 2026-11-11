@extends('layouts.admin')
@section('title', 'Kelola Pinjaman')

@section('content')
<div class="p-4 sm:p-6 bg-[#0B0F17] min-h-screen text-white font-sans" x-data="{ showCashModal: false }">

    <!-- Header Alert Notification -->
    @if(session('success'))
        <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs sm:text-sm flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs sm:text-sm flex items-center gap-2">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-2xl font-bold">Kelola Transaksi & Verifikasi</h1>
        <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Proses pengambilan buku, pengembalian, dan konfirmasi pembayaran denda siswa</p>
    </div>

    <!-- GRID FORM VERIFIKASI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        
        <!-- 1. FORM VERIFIKASI KODE TUKAR -->
        <div class="bg-[#111827] p-4 sm:p-6 rounded-2xl border border-gray-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Verifikasi Kode Tukar</h3>
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                </div>
                <p class="text-[11px] text-gray-400 mb-3 sm:mb-4">Input kode untuk memverifikasi pengambilan buku oleh siswa.</p>
                
                <form action="{{ route('admin.pinjaman.verifikasi') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" 
                               name="kode_tukar" 
                               placeholder="KODE (MISAL: CRQCI)" 
                               value="{{ old('kode_tukar') }}"
                               class="w-full bg-[#0B0F19] border border-gray-700 focus:border-orange-500 text-white px-3.5 py-2.5 sm:py-3 rounded-xl uppercase font-mono tracking-widest text-xs outline-none transition"
                               required>
                    </div>
                    <button type="submit" 
                            class="w-full bg-[#FF5500] hover:bg-orange-600 text-white font-semibold py-2.5 sm:py-3 rounded-xl text-xs transition cursor-pointer shadow-lg shadow-orange-500/20">
                        Verifikasi Pengambilan
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. FORM VERIFIKASI KODE PENGEMBALIAN -->
        <div class="bg-[#111827] p-4 sm:p-6 rounded-2xl border border-gray-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Verifikasi Pengembalian</h3>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <p class="text-[11px] text-gray-400 mb-3 sm:mb-4">Input kode saat siswa mengembalikan buku perpustakaan.</p>

                <form action="{{ route('admin.pinjaman.verifikasi-kembali') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" 
                               name="kode_kembali" 
                               placeholder="KODE (MISAL: RET-F060D)" 
                               value="{{ old('kode_kembali') }}"
                               class="w-full bg-[#0B0F19] border border-gray-700 focus:border-emerald-500 text-emerald-400 px-3.5 py-2.5 sm:py-3 rounded-xl uppercase font-mono tracking-widest text-xs outline-none transition"
                               required>
                    </div>
                    <button type="submit" 
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 sm:py-3 rounded-xl text-xs transition cursor-pointer">
                        Proses Pengembalian
                    </button>
                </form>
            </div>
        </div>

        <!-- 3. FORM VERIFIKASI KODE UNIK QRIS -->
        <div class="bg-[#111827] p-4 sm:p-6 rounded-2xl border border-gray-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Konfirmasi QRIS</h3>
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                </div>
                <p class="text-[11px] text-gray-400 mb-3 sm:mb-4">Masukkan kode unik QRIS dari aplikasi user.</p>

                <form action="{{ route('admin.pinjaman.verifikasi-qris') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" 
                               name="kode_bayar_qris" 
                               placeholder="KODE UNIK (MISAL: Q-8F2A)" 
                               value="{{ old('kode_bayar_qris') }}"
                               class="w-full bg-[#0B0F19] border border-gray-700 focus:border-cyan-500 text-cyan-400 px-3.5 py-2.5 sm:py-3 rounded-xl uppercase font-mono tracking-widest text-xs outline-none transition"
                               required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <button type="submit" name="aksi" value="terima" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 sm:py-2.5 rounded-xl text-xs transition cursor-pointer">
                            ✓ Terima
                        </button>
                        <button type="submit" name="aksi" value="tolak" class="bg-rose-600/80 hover:bg-rose-600 text-white font-semibold py-2 sm:py-2.5 rounded-xl text-xs transition cursor-pointer">
                            ✕ Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 4. FORM PELUNASAN DENDA CASH -->
        <div class="bg-[#111827] p-4 sm:p-6 rounded-2xl border border-gray-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Pelunasan Denda (Cash)</h3>
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                </div>
                <p class="text-[11px] text-gray-400 mb-3 sm:mb-4">Konfirmasi pelunasan denda tunai di tempat.</p>

                <form id="formCash" action="{{ route('admin.pinjaman.bayar-denda-cash') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" 
                               name="loan_id_or_code" 
                               placeholder="ID PINJAMAN / KODE" 
                               value="{{ old('loan_id_or_code') }}"
                               class="w-full bg-[#0B0F19] border border-gray-700 focus:border-yellow-500 text-yellow-400 px-3.5 py-2.5 sm:py-3 rounded-xl uppercase font-mono tracking-widest text-xs outline-none transition"
                               required>
                    </div>
                    <button type="button" 
                            @click="showCashModal = true"
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2.5 sm:py-3 rounded-xl text-xs transition cursor-pointer">
                        Konfirmasi Lunas Cash
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- CUSTOM MODAL UI CONFIRMATION DENDA CASH -->
    <div x-show="showCashModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-[#151C28] border border-gray-800 rounded-2xl p-5 sm:p-6 max-w-sm w-full text-center shadow-2xl relative">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 text-xl sm:text-2xl">
                💵
            </div>
            
            <h3 class="text-sm sm:text-base font-bold text-white mb-1">Konfirmasi Pembayaran Cash</h3>
            <p class="text-xs text-gray-400 mb-5 sm:mb-6 leading-relaxed">
                Apakah Anda yakin siswa ini sudah melunasi tagihan denda keterlambatan secara tunai/cash?
            </p>

            <div class="flex gap-2.5">
                <button type="button" 
                        @click="showCashModal = false" 
                        class="w-1/2 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs rounded-xl font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="button" 
                        @click="document.getElementById('formCash').submit()" 
                        class="w-1/2 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white text-xs rounded-xl font-semibold transition cursor-pointer shadow-lg shadow-yellow-600/30">
                    Ya, Lunas Cash
                </button>
            </div>
        </div>
    </div>

</div>
@endsection