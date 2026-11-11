<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Pustaka V</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0B0F19; }
        ::-webkit-scrollbar-thumb { background: #1F2937; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #374151; }
    </style>
</head>
<body class="bg-[#0B0F19] text-gray-200 min-h-screen flex flex-col md:flex-row font-sans antialiased selection:bg-[#FF5500] selection:text-white" 
      x-data="{ 
          activeTab: 'dashboard', 
          sidebarOpen: false,
          searchQuery: '',
          copiedCode: null,
          payModalOpen: false,
          payMethod: 'qris',
          selectedLoan: null,
          openPaymentModal(loan) {
              this.selectedLoan = loan;
              this.payMethod = 'qris';
              this.payModalOpen = true;
          },
          copyToClipboard(text) {
              if(!text) return;
              navigator.clipboard.writeText(text);
              this.copiedCode = text;
              setTimeout(() => this.copiedCode = null, 2000);
          }
      }">

    @php
        // Ambil gambar QRIS aktif yang diunggah Admin dari tabel settings
        $qrisImage = DB::table('settings')->where('key', 'qris_image')->value('value');
    @endphp

    <!-- OVERLAY MOBILE SIDEBAR -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 md:hidden" 
         x-cloak></div>

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
           class="fixed md:static inset-y-0 left-0 z-50 w-72 bg-[#0B0F19] border-r border-gray-800/80 min-h-screen flex flex-col justify-between p-6 shrink-0 transition-transform duration-300 ease-in-out">
        <div>
            <!-- Logo Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-tr from-[#FF5500] to-orange-400 rounded-xl flex items-center justify-center font-black text-white text-xl shadow-lg shadow-orange-500/20">P</div>
                    <div>
                        <span class="text-xl font-black text-white tracking-wide block leading-none">Pustaka V</span>
                        <span class="text-[10px] text-gray-500 font-bold tracking-wider uppercase mt-1 block">Perpustakaan Digital</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- DETAIL AKUN SISWA (TAMPIL DI MOBILE SAAT SIDEBAR DIBUKA) -->
            <div class="mb-6 p-4 bg-[#111827] rounded-2xl border border-gray-800/80">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 bg-gradient-to-tr from-orange-600 to-amber-500 rounded-xl flex items-center justify-center text-white text-sm font-black shadow-md shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Siswa' }}</p>
                        <p class="text-xs text-gray-400 truncate">NIS: <strong class="text-gray-200">{{ auth()->user()->nis_nip ?? '-' }}</strong></p>
                        <p class="text-xs text-gray-400 truncate">Kelas: <strong class="text-gray-200">{{ auth()->user()->kelas ?? '-' }}</strong></p>
                    </div>
                </div>
                
                <a href="{{ route('home') }}" class="mt-3.5 w-full flex items-center justify-center gap-2 text-xs font-bold text-gray-300 hover:text-white bg-[#0B0F19] hover:bg-gray-800 py-2.5 rounded-xl border border-gray-800 transition">
                    <svg class="w-4 h-4 text-[#FF5500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Halaman Utama</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-2">
                <button @click="activeTab = 'dashboard'; sidebarOpen = false" 
                        :class="activeTab === 'dashboard' ? 'bg-[#FF5500]/10 text-[#FF5500] font-bold border-r-4 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition duration-150 text-left cursor-pointer font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </button>

                <a href="{{ route('catalog') }}" 
                   class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm text-gray-400 hover:bg-gray-800/40 hover:text-white transition duration-150 text-left font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Katalog Buku
                </a>

                <button @click="activeTab = 'my_loans'; sidebarOpen = false" 
                        :class="activeTab === 'my_loans' ? 'bg-[#FF5500]/10 text-[#FF5500] font-bold border-r-4 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition duration-150 text-left cursor-pointer font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Pinjaman Saya
                </button>

                <button @click="activeTab = 'history'; sidebarOpen = false" 
                        :class="activeTab === 'history' ? 'bg-[#FF5500]/10 text-[#FF5500] font-bold border-r-4 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition duration-150 text-left cursor-pointer font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Riwayat Transaksi
                </button>

                <button @click="activeTab = 'fines'; sidebarOpen = false" 
                        :class="activeTab === 'fines' ? 'bg-[#FF5500]/10 text-[#FF5500] font-bold border-r-4 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition duration-150 text-left cursor-pointer font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Denda
                </button>

                <button @click="activeTab = 'profile'; sidebarOpen = false" 
                        :class="activeTab === 'profile' ? 'bg-[#FF5500]/10 text-[#FF5500] font-bold border-r-4 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition duration-150 text-left cursor-pointer font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil Saya
                </button>
            </nav>
        </div>

        <!-- Logout Button -->
        <div class="pt-6 border-t border-gray-800/60">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3.5 text-red-400 hover:bg-red-500/10 rounded-2xl text-sm font-bold transition group">
                    <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 p-6 sm:p-8 md:p-10 overflow-y-auto max-w-7xl mx-auto w-full">
        
        <!-- HEADER UTAMA (RINGKAS & RAPI: "Hi, Nama" & AVATAR) -->
        <div class="flex items-center justify-between mb-8 pb-5 border-b border-gray-800/60">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="md:hidden p-2.5 text-gray-300 hover:text-white bg-[#111827] rounded-xl border border-gray-800/80 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-xl md:text-3xl font-black text-white tracking-tight">Hi, {{ auth()->user()->name ?? 'Siswa' }} 👋</h1>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1">Selamat datang kembali di panel anggota Pustaka V</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-300 hover:text-white bg-[#111827] hover:bg-gray-800 px-4 py-2.5 rounded-xl border border-gray-800 transition">
                    <svg class="w-4 h-4 text-[#FF5500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Halaman Utama
                </a>
                <div class="w-11 h-11 bg-gradient-to-tr from-orange-600 to-amber-500 rounded-2xl flex items-center justify-center text-white text-sm font-black shadow-md shadow-orange-500/10 border border-orange-400/20">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-5 py-4 rounded-2xl text-sm font-semibold flex items-center justify-between shadow-lg shadow-emerald-500/5">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <span class="font-bold cursor-pointer text-base" @click="$el.parentElement.remove()">✕</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-5 py-4 rounded-2xl text-sm font-semibold flex items-center justify-between shadow-lg shadow-red-500/5">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <span class="font-bold cursor-pointer text-base" @click="$el.parentElement.remove()">✕</span>
            </div>
        @endif

        <!-- 1. TAB DASHBOARD -->
        <div x-show="activeTab === 'dashboard'" class="space-y-8">
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-gray-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-xs uppercase font-bold text-gray-400 tracking-wider">Pinjaman Aktif</p>
                        <div class="p-2.5 bg-blue-500/10 text-blue-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-white mt-3">{{ $stats['active_loans'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1.5 font-medium">Buku dipinjam / menunggu</p>
                </div>

                <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-gray-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-xs uppercase font-bold text-gray-400 tracking-wider">Segera Jatuh Tempo</p>
                        <div class="p-2.5 bg-amber-500/10 text-amber-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-amber-500 mt-3">{{ $stats['due_soon'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1.5 font-medium">Harus segera dikembalikan</p>
                </div>

                <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-gray-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-xs uppercase font-bold text-gray-400 tracking-wider">Total Denda</p>
                        <div class="p-2.5 bg-red-500/10 text-red-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-red-400 mt-3">{{ $stats['total_fines'] ?? 'Rp 0' }}</p>
                    <p class="text-xs text-gray-400 mt-1.5 font-medium">Tagihan keterlambatan</p>
                </div>

                <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-gray-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-xs uppercase font-bold text-gray-400 tracking-wider">Selesai Dibaca</p>
                        <div class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-emerald-400 mt-3">{{ $stats['books_read'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1.5 font-medium">Sudah dikembalikan</p>
                </div>
            </div>

            <!-- Active Loans Card List -->
            <div class="bg-[#111827] rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-white">Buku Yang Sedang Dipinjam</h2>
                        <p class="text-xs sm:text-sm text-gray-400 mt-1">Tunjukkan kode tukar atau bayar denda jika terlambat</p>
                    </div>
                    <button @click="activeTab = 'my_loans'" class="text-xs sm:text-sm text-[#FF5500] hover:underline font-bold">Lihat Semua →</button>
                </div>
                
                <div class="space-y-4">
                    @forelse($loans->whereIn('status', ['menunggu_pengambilan', 'aktif', 'menunggu_pengembalian', 'terlambat']) as $loan)
                        @php
                            $coverImg = $loan->cover ?? $loan->cover_image ?? null;
                            $dendaAmount = $loan->denda_berjalan ?? $loan->denda ?? 0;
                        @endphp
                        <div class="bg-[#0B0F19] p-5 rounded-2xl border border-gray-800/80 hover:border-gray-700 transition flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-24 bg-gray-800 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border border-gray-700 shadow-md">
                                    @if(!empty($coverImg))
                                        <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $loan->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-white font-extrabold text-base md:text-lg">{{ $loan->judul }}</h4>
                                    <p class="text-xs sm:text-sm text-gray-400 mt-1">Penulis: <span class="text-gray-200 font-semibold">{{ $loan->penulis ?? '-' }}</span></p>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-xs text-gray-400">
                                        <span>Pinjam: <strong class="text-gray-200">{{ $loan->tanggal_pinjam }}</strong></span>
                                        <span>•</span>
                                        <span>Batas Kembali: <strong class="text-amber-400">{{ $loan->tanggal_kembali }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3.5 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 border-gray-800/80 pt-4 md:pt-0">
                                <div class="flex items-center gap-2">
                                    @if($loan->status == 'menunggu_pengambilan')
                                        <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-3.5 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                            Menunggu Ambil
                                        </span>
                                    @elseif($loan->status == 'aktif')
                                        @if($dendaAmount > 0)
                                            <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-3.5 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-red-400 animate-bounce"></span>
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3.5 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                                Aktif Dipinjam
                                            </span>
                                        @endif
                                    @elseif($loan->status == 'menunggu_pengembalian')
                                        <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3.5 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                                            Menunggu Pengembalian
                                        </span>
                                    @endif
                                </div>

                                @if($loan->status == 'menunggu_pengambilan')
                                    <button @click="copyToClipboard('{{ $loan->kode_tukar }}')" 
                                            class="bg-[#111827] hover:bg-gray-800/80 px-4 py-2.5 rounded-xl border border-orange-500/30 text-center min-w-[130px] transition cursor-pointer relative group">
                                        <span class="block text-[10px] text-gray-400 uppercase tracking-widest font-bold">Kode Tukar</span>
                                        <span class="text-base font-mono font-black text-[#FF5500] tracking-wider flex items-center justify-center gap-1.5">
                                            {{ $loan->kode_tukar }}
                                            <svg class="w-4 h-4 text-gray-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </span>
                                    </button>

                                @elseif($loan->status == 'aktif')
                                    @if($dendaAmount > 0)
                                        <button @click="openPaymentModal({{ json_encode($loan) }})" 
                                                class="px-5 py-3 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-lg shadow-red-500/20 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Bayar Denda (Rp {{ number_format($dendaAmount, 0, ',', '.') }})
                                        </button>
                                    @else
                                        <form action="{{ route('user.loans.return', $loan->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-5 py-3 bg-[#FF5500] hover:bg-orange-600 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-lg shadow-orange-500/20 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H4m0 0l4-4m-4 4l4 4"/></svg>
                                                Minta Kode Kembali
                                            </button>
                                        </form>
                                    @endif

                                @elseif($loan->status == 'menunggu_pengembalian')
                                    <button @click="copyToClipboard('{{ $loan->kode_kembali }}')" 
                                            class="bg-[#111827] hover:bg-gray-800/80 px-4 py-2.5 rounded-xl border border-blue-500/30 text-center min-w-[150px] transition cursor-pointer relative group">
                                        <span class="block text-[10px] text-gray-400 uppercase tracking-widest font-bold">Kode Pengembalian</span>
                                        <span class="text-base font-mono font-black text-blue-400 tracking-wider flex items-center justify-center gap-1.5">
                                            {{ $loan->kode_kembali }}
                                            <svg class="w-4 h-4 text-gray-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400 bg-[#0B0F19] rounded-2xl border border-dashed border-gray-800">
                            <div class="w-12 h-12 bg-gray-800 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <p class="font-bold text-gray-300 text-base">Tidak ada pinjaman aktif saat ini.</p>
                            <p class="text-xs text-gray-500 mt-1">Yuk cari buku menarik di katalog perpustakaan!</p>
                            <a href="{{ route('catalog') }}" class="inline-block mt-4 px-5 py-2.5 bg-[#FF5500] text-white rounded-xl font-bold text-xs sm:text-sm hover:bg-orange-600 transition">Buka Katalog</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Aksi Cepat Perpustakaan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <a href="{{ route('catalog') }}" class="p-5 bg-[#111827] hover:bg-gray-800/80 rounded-2xl border border-gray-800 text-xs font-bold text-white flex items-center gap-4 transition group">
                        <span class="p-3 bg-orange-500/10 text-[#FF5500] rounded-2xl group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <div>
                            <p class="font-black text-white text-sm sm:text-base">Jelajahi Katalog</p>
                            <p class="text-xs text-gray-400 font-medium">Cari & pinjam buku baru</p>
                        </div>
                    </a>
                    
                    <button @click="activeTab = 'fines'" class="p-5 bg-[#111827] hover:bg-gray-800/80 rounded-2xl border border-gray-800 text-xs font-bold text-white flex items-center gap-4 transition text-left group cursor-pointer">
                        <span class="p-3 bg-orange-500/10 text-[#FF5500] rounded-2xl group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <p class="font-black text-white text-sm sm:text-base">Cek Tagihan Denda</p>
                            <p class="text-xs text-gray-400 font-medium">Lihat rincian keterlambatan</p>
                        </div>
                    </button>
                    
                    <button @click="activeTab = 'my_loans'" class="p-5 bg-[#111827] hover:bg-gray-800/80 rounded-2xl border border-gray-800 text-xs font-bold text-white flex items-center gap-4 transition text-left group cursor-pointer">
                        <span class="p-3 bg-orange-500/10 text-[#FF5500] rounded-2xl group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </span>
                        <div>
                            <p class="font-black text-white text-sm sm:text-base">Tunjukkan Kode Tukar</p>
                            <p class="text-xs text-gray-400 font-medium">Untuk verifikasi pustakawan</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. TAB MY LOANS -->
        <div x-show="activeTab === 'my_loans'" x-cloak class="space-y-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-white">Pinjaman Saya</h2>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Gunakan kode di bawah untuk serah terima buku di perpustakaan</p>
            </div>

            <div class="space-y-5">
                @forelse($loans->whereIn('status', ['menunggu_pengambilan', 'aktif', 'menunggu_pengembalian', 'terlambat']) as $loan)
                    @php
                        $coverImg = $loan->cover ?? $loan->cover_image ?? null;
                        $dendaAmount = $loan->denda_berjalan ?? $loan->denda ?? 0;
                    @endphp
                    <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-xl">
                        <div class="flex items-start gap-4">
                            <div class="w-20 h-28 bg-gray-800 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border border-gray-700 shadow">
                                @if(!empty($coverImg))
                                    <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $loan->judul }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-9 h-9 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @endif
                            </div>
                            <div class="space-y-1.5">
                                <h3 class="text-white font-extrabold text-lg md:text-xl">{{ $loan->judul }}</h3>
                                <p class="text-xs sm:text-sm text-gray-400">Penulis: <span class="text-gray-200 font-semibold">{{ $loan->penulis ?? '-' }}</span></p>
                                <p class="text-xs sm:text-sm text-gray-400">Durasi Pinjam: <span class="text-gray-200 font-semibold">{{ $loan->durasi_hari ?? 3 }} Hari</span></p>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 pt-2 text-xs sm:text-sm text-gray-300">
                                    <span>Tgl Pinjam: <strong>{{ $loan->tanggal_pinjam }}</strong></span>
                                    <span class="text-gray-600">•</span>
                                    <span>Tgl Kembali: <strong class="text-orange-400">{{ $loan->tanggal_kembali }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:items-end gap-3 w-full md:w-auto border-t md:border-t-0 border-gray-800 pt-4 md:pt-0">
                            @if($loan->status === 'menunggu_pengambilan')
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-bold">Kode Tukar Pengambilan</span>
                                <div class="bg-[#0B0F19] px-6 py-3.5 rounded-2xl border-2 border-[#FF5500] text-center w-full md:w-auto shadow-lg shadow-orange-500/10 relative">
                                    <span class="text-3xl font-mono font-black text-[#FF5500] tracking-widest block">{{ $loan->kode_tukar }}</span>
                                    <button @click="copyToClipboard('{{ $loan->kode_tukar }}')" class="text-xs text-gray-400 hover:text-white mt-1.5 underline font-bold">
                                        Salin Kode
                                    </button>
                                </div>

                            @elseif($loan->status === 'aktif')
                                @if($dendaAmount > 0)
                                    <span class="text-xs text-red-400 uppercase tracking-widest font-bold">Buku Terlambat (Ada Denda)</span>
                                    <button @click="openPaymentModal({{ json_encode($loan) }})" 
                                            class="w-full md:w-auto px-6 py-3.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-2xl transition shadow-lg shadow-red-500/20 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Bayar Denda (Rp {{ number_format($dendaAmount, 0, ',', '.') }})
                                    </button>
                                @else
                                    <span class="text-xs text-emerald-400 uppercase tracking-widest font-bold">Buku Sedang Dipinjam</span>
                                    <form action="{{ route('user.loans.return', $loan->id) }}" method="POST" class="w-full md:w-auto">
                                        @csrf
                                        <button type="submit" class="w-full md:w-auto px-6 py-3.5 bg-[#FF5500] hover:bg-orange-600 text-white text-sm font-bold rounded-2xl transition shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H4m0 0l4-4m-4 4l4 4"/></svg>
                                            Minta Kode Pengembalian
                                        </button>
                                    </form>
                                @endif

                            @elseif($loan->status === 'menunggu_pengembalian')
                                <span class="text-xs text-blue-400 uppercase tracking-widest font-bold">Kode Pengembalian Buku</span>
                                <div class="bg-[#0B0F19] px-6 py-3.5 rounded-2xl border-2 border-blue-500 text-center w-full md:w-auto shadow-lg shadow-blue-500/10 relative">
                                    <span class="text-3xl font-mono font-black text-blue-400 tracking-widest block">{{ $loan->kode_kembali }}</span>
                                    <button @click="copyToClipboard('{{ $loan->kode_kembali }}')" class="text-xs text-gray-400 hover:text-white mt-1.5 underline font-bold">
                                        Salin Kode
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400 bg-[#111827] rounded-2xl border border-gray-800 text-sm">
                        <div class="w-12 h-12 bg-gray-800 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <p class="font-bold text-gray-200 text-base">Anda sedang tidak meminjam buku apapun.</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Pilih buku pilihanmu di katalog dan ajukan peminjaman sekarang.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 3. TAB BORROWING HISTORY -->
        <div x-show="activeTab === 'history'" class="space-y-6" x-cloak>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-white">Riwayat Peminjaman</h2>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1">Daftar lengkap seluruh transaksi peminjaman buku Anda</p>
                </div>

                <div class="relative w-full sm:w-72">
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Cari judul atau kode tukar..." 
                           class="w-full bg-[#111827] border border-gray-800 px-4 py-3 pl-10 rounded-xl text-xs sm:text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[#FF5500]">
                    <svg class="w-5 h-5 text-gray-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="bg-[#111827] rounded-2xl border border-gray-800 overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-gray-900/60 text-gray-400 border-b border-gray-800 font-bold uppercase text-xs tracking-wider">
                            <tr>
                                <th class="p-4 sm:p-5">Kode Tukar</th>
                                <th class="p-4 sm:p-5">Kode Kembali</th>
                                <th class="p-4 sm:p-5">Judul Buku</th>
                                <th class="p-4 sm:p-5">Tgl Pinjam</th>
                                <th class="p-4 sm:p-5">Tgl Kembali</th>
                                <th class="p-4 sm:p-5">Status</th>
                                <th class="p-4 sm:p-5">Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/60 text-gray-300 font-medium">
                            @forelse($loans as $loan)
                                <tr x-show="!searchQuery || '{{ strtolower($loan->judul) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($loan->kode_tukar) }}'.includes(searchQuery.toLowerCase())"
                                    class="hover:bg-gray-800/30 transition">
                                    <td class="p-4 sm:p-5 font-mono font-bold text-orange-400 text-sm">{{ $loan->kode_tukar }}</td>
                                    <td class="p-4 sm:p-5 font-mono font-bold text-blue-400 text-sm">{{ $loan->kode_kembali ?? '-' }}</td>
                                    <td class="p-4 sm:p-5 font-bold text-white max-w-xs truncate">{{ $loan->judul }}</td>
                                    <td class="p-4 sm:p-5 text-gray-400 whitespace-nowrap">{{ $loan->tanggal_pinjam }}</td>
                                    <td class="p-4 sm:p-5 text-gray-400 whitespace-nowrap">{{ $loan->tanggal_kembali }}</td>
                                    <td class="p-4 sm:p-5 whitespace-nowrap">
                                        @if($loan->status == 'dikembalikan')
                                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-full text-xs font-bold">Dikembalikan</span>
                                        @elseif($loan->status == 'aktif')
                                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-full text-xs font-bold">Aktif</span>
                                        @elseif($loan->status == 'menunggu_pengambilan')
                                            <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-3 py-1 rounded-full text-xs font-bold">Menunggu Ambil</span>
                                        @elseif($loan->status == 'menunggu_pengembalian')
                                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded-full text-xs font-bold">Menunggu Kembali</span>
                                        @elseif($loan->status == 'terlambat')
                                            <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-3 py-1 rounded-full text-xs font-bold">Terlambat</span>
                                        @else
                                            <span class="bg-gray-800 text-gray-400 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($loan->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 sm:p-5 font-bold whitespace-nowrap {{ ($loan->denda ?? 0) > 0 ? 'text-red-400' : 'text-gray-500' }}">
                                        {{ ($loan->denda ?? 0) > 0 ? 'Rp ' . number_format($loan->denda, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-500 text-sm">Belum ada riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 4. TAB INFORMASI DENDA -->
        <div x-show="activeTab === 'fines'" x-cloak class="space-y-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-white">Informasi Denda & Pembayaran</h2>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Rincian tagihan keterlambatan pengembalian buku dan opsi pelunasan</p>
            </div>

            <div class="bg-[#111827] rounded-2xl border border-gray-800 p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-b border-gray-800 pb-6">
                    <div>
                        <span class="text-xs sm:text-sm font-bold text-gray-400 block uppercase tracking-wider">Total Denda Belum Dibayar</span>
                        <span class="text-4xl font-black text-red-400 mt-2 block">{{ $stats['total_fines'] ?? 'Rp 0' }}</span>
                    </div>
                    
                    @php
                        $lateLoan = $loans->first(function($item) {
                            return ($item->denda_berjalan ?? 0) > 0 || (($item->denda ?? 0) > 0 && ($item->denda_lunas ?? 0) == 0);
                        });
                    @endphp

                    @if($lateLoan)
                        <button @click="openPaymentModal({{ json_encode($lateLoan) }})" 
                                class="px-6 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs sm:text-sm font-bold transition shadow-lg shadow-red-500/20 flex items-center justify-center gap-2.5 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Bayar Denda Sekarang
                        </button>
                    @else
                        <div class="p-4 bg-emerald-500/10 text-emerald-400 rounded-2xl border border-emerald-500/20 flex items-center gap-2.5 text-xs sm:text-sm font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Bebas Denda
                        </div>
                    @endif
                </div>

                <!-- Daftar Buku Yang Memiliki Denda -->
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-white uppercase tracking-wider mb-4">Rincian Buku Terlambat:</h3>
                    <div class="space-y-3.5">
                        @forelse($loans->filter(fn($l) => ($l->denda_berjalan ?? 0) > 0 || (($l->denda ?? 0) > 0 && ($l->denda_lunas ?? 0) == 0)) as $finedLoan)
                            <div class="p-5 bg-[#0B0F19] rounded-2xl border border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-base font-extrabold text-white">{{ $finedLoan->judul }}</h4>
                                    <p class="text-xs sm:text-sm text-gray-400 mt-1">Batas Kembali: <span class="text-red-400 font-bold">{{ $finedLoan->tanggal_kembali }}</span></p>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-5">
                                    <span class="text-base sm:text-lg font-black text-red-400">Rp {{ number_format($finedLoan->denda_berjalan ?? $finedLoan->denda, 0, ',', '.') }}</span>
                                    <button @click="openPaymentModal({{ json_encode($finedLoan) }})" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-xl text-xs font-bold transition">
                                        Bayar
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 bg-[#0B0F19] rounded-2xl border border-gray-800/60 text-xs sm:text-sm text-gray-400 text-center font-medium">
                                Tidak ada penunggakan denda aktif. Semua pinjaman tepat waktu!
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-3 pt-6 border-t border-gray-800">
                    <h3 class="text-xs sm:text-sm font-bold text-white uppercase tracking-wider">Aturan & Ketentuan Pembayaran:</h3>
                    <ul class="text-xs sm:text-sm text-gray-300 space-y-2 list-disc pl-5">
                        <li><strong>QRIS:</strong> Scan QRIS yang diunggah Admin. Setelah klik Selesai, berikan Kode Unik ke Admin.</li>
                        <li><strong>CASH / TUNAI:</strong> Tunjukkan Kode Pembayaran kepada petugas admin di perpustakaan.</li>
                        <li>Setelah denda dikonfirmasi lunas oleh admin, buku dapat dikembalikan secara normal.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 5. TAB PROFILE -->
        <div x-show="activeTab === 'profile'" x-cloak class="space-y-6 max-w-2xl">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-white">Profil Saya</h2>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Informasi akun pengguna terdaftar Pustaka V</p>
            </div>

            <div class="bg-[#111827] rounded-2xl border border-gray-800 p-6 sm:p-8 space-y-5 shadow-xl">
                <div class="space-y-5 text-xs sm:text-sm">
                    <div>
                        <label class="text-gray-300 block mb-2 font-bold">Nama Lengkap</label>
                        <input type="text" readonly value="{{ auth()->user()->name ?? '' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-4 rounded-xl text-white font-bold focus:outline-none">
                    </div>
                    <div>
                        <label class="text-gray-300 block mb-2 font-bold">Alamat Email</label>
                        <input type="text" readonly value="{{ auth()->user()->email ?? '' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-4 rounded-xl text-white font-bold focus:outline-none">
                    </div>
                    <div>
                        <label class="text-gray-300 block mb-2 font-bold">NIS / NIP</label>
                        <input type="text" readonly value="{{ auth()->user()->nis_nip ?? '-' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-4 rounded-xl text-white font-bold focus:outline-none">
                    </div>
                    <div>
                        <label class="text-gray-300 block mb-2 font-bold">Kelas / Unit Work</label>
                        <input type="text" readonly value="{{ auth()->user()->kelas ?? '-' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-4 rounded-xl text-white font-bold focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- POP-UP MODAL PEMBAYARAN DENDA (QRIS / CASH) -->
    <div x-show="payModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        
        <div @click.away="payModalOpen = false" 
             class="bg-[#111827] border border-gray-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative space-y-6 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-800 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-red-500/10 text-red-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-white">Pembayaran Denda</h3>
                        <p class="text-xs text-gray-400">Pilih metode pelunasan denda perpustakaan</p>
                    </div>
                </div>
                <button @click="payModalOpen = false" class="text-gray-400 hover:text-white text-xl font-bold">✕</button>
            </div>

            <!-- Detail Nominal & Judul Buku -->
            <div class="bg-[#0B0F19] p-5 rounded-2xl border border-gray-800 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Buku Terlambat</p>
                <p class="text-base font-extrabold text-white mt-1 truncate" x-text="selectedLoan ? selectedLoan.judul : '-'"></p>
                <div class="mt-3 pt-3 border-t border-gray-800 flex justify-between items-center text-sm">
                    <span class="text-gray-400 font-bold">Total Nominal Denda:</span>
                    <span class="text-xl font-black text-red-400" x-text="'Rp ' + Number(selectedLoan ? (selectedLoan.denda_berjalan || selectedLoan.denda || 0) : 0).toLocaleString('id-ID')"></span>
                </div>
            </div>

            <!-- Tab Switcher Metode Pembayaran -->
            <div class="grid grid-cols-2 gap-2 p-1.5 bg-[#0B0F19] rounded-2xl border border-gray-800 text-xs sm:text-sm font-bold">
                <button @click="payMethod = 'qris'" 
                        :class="payMethod === 'qris' ? 'bg-[#FF5500] text-white shadow-md' : 'text-gray-400 hover:text-white'"
                        class="py-3 rounded-xl transition cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    QRIS (Transfer)
                </button>
                <button @click="payMethod = 'cash'" 
                        :class="payMethod === 'cash' ? 'bg-[#FF5500] text-white shadow-md' : 'text-gray-400 hover:text-white'"
                        class="py-3 rounded-xl transition cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Cash (Tunai)
                </button>
            </div>

            <!-- CONTENT 1: PEMBAYARAN QRIS -->
            <div x-show="payMethod === 'qris'" class="space-y-4 text-center">
                <template x-if="selectedLoan && selectedLoan.kode_bayar_qris">
                    <div class="bg-[#0B0F19] p-5 rounded-2xl border border-cyan-500/40 text-center">
                        <span class="text-xs text-cyan-400 font-bold bg-cyan-500/10 px-3.5 py-1.5 rounded-full border border-cyan-500/20">MENUNGGU VERIFIKASI ADMIN</span>
                        <p class="text-xs sm:text-sm text-gray-400 mt-3 mb-1">Tunjukkan Kode Unik Pembayaran ini ke Petugas Admin:</p>
                        <div class="text-3xl font-mono font-black text-cyan-400 tracking-widest my-3 bg-black/50 py-3 rounded-xl border border-gray-800 shadow-inner flex items-center justify-center gap-2">
                            <span x-text="selectedLoan.kode_bayar_qris"></span>
                            <button @click="copyToClipboard(selectedLoan.kode_bayar_qris)" class="text-xs text-gray-500 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">Jika admin memverifikasi/menolak, status akan terupdate secara otomatis.</p>
                    </div>
                </template>

                <template x-if="!selectedLoan || !selectedLoan.kode_bayar_qris">
                    <div class="space-y-4">
                        <div class="p-3 bg-white rounded-2xl inline-block shadow-lg mx-auto border-4 border-gray-800 max-w-[240px]">
                            @if($qrisImage)
                                <img src="{{ asset('storage/' . $qrisImage) }}" alt="QRIS Pembayaran Denda Admin" class="w-full h-auto rounded-lg object-contain">
                            @else
                                <div class="p-4 text-center">
                                    <p class="text-xs text-rose-500 font-bold">⚠️ Admin belum mengunggah gambar QRIS.</p>
                                    <p class="text-xs text-gray-500 mt-1">Silakan gunakan pembayaran Tunai (Cash).</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed font-medium">
                            Scan QRIS di atas menggunakan e-Wallet atau M-Banking. Jika sudah transfer, klik tombol di bawah untuk membuat Kode Unik Pembayaran.
                        </p>
                        
                        <form :action="'{{ url('/bayar-qris/generate') }}/' + (selectedLoan ? selectedLoan.id : '')" method="POST">
                            @csrf
                            <button type="submit" :disabled="!{{ $qrisImage ? 'true' : 'false' }}" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-800 text-white rounded-xl text-xs sm:text-sm font-bold transition shadow-lg shadow-emerald-500/20 cursor-pointer">
                                Selesai / Sudah Transfer
                            </button>
                        </form>
                    </div>
                </template>
            </div>

            <!-- CONTENT 2: PEMBAYARAN CASH -->
            <div x-show="payMethod === 'cash'" class="space-y-4 text-center" x-cloak>
                <div class="bg-[#0B0F19] p-5 rounded-2xl border-2 border-dashed border-amber-500/40 relative">
                    <span class="text-xs text-gray-400 uppercase tracking-widest font-bold block mb-1">Kode Verifikasi Pembayaran Cash</span>
                    <span class="text-3xl font-mono font-black text-amber-400 tracking-wider block" x-text="selectedLoan ? (selectedLoan.kode_tukar || selectedLoan.kode_kembali || 'PAY-CASH') : 'PAY-CASH'"></span>
                    <button @click="copyToClipboard(selectedLoan ? (selectedLoan.kode_tukar || selectedLoan.kode_kembali) : '')" class="text-xs text-gray-400 hover:text-white mt-2 underline font-bold">
                        Salin Kode
                    </button>
                </div>

                <div class="text-left bg-amber-500/10 border border-amber-500/20 p-4 rounded-xl text-xs sm:text-sm text-amber-300 space-y-1.5">
                    <p class="font-bold">Langkah Pembayaran Tunai:</p>
                    <ol class="list-decimal pl-5 space-y-1 text-amber-200/90 font-medium">
                        <li>Tunjukkan <strong>Kode Pembayaran</strong> di atas kepada Admin / Pustakawan.</li>
                        <li>Petugas akan memasukkan kode ini di sistem untuk mengecek nominal denda.</li>
                        <li>Serahkan uang tunai sesuai nominal denda.</li>
                        <li>Admin akan mengonfirmasi pembayaran dan denda di akun Anda otomatis terhapus!</li>
                    </ol>
                </div>

                <button @click="payModalOpen = false" class="w-full py-3.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl text-xs sm:text-sm font-bold transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</body>
</html>