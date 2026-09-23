<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Pustaka V</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#0B0F19] text-gray-200 min-h-screen flex" x-data="{ activeTab: 'dashboard' }">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#0B0F19] border-r border-gray-800/80 min-h-screen flex flex-col justify-between p-6 shrink-0">
        <div>
            <!-- Logo Header -->
            <div class="flex items-center space-x-3 mb-10">
                <div class="w-9 h-9 bg-[#FF5500] rounded-xl flex items-center justify-center font-bold text-white text-lg shadow-lg shadow-orange-500/20">P</div>
                <span class="text-lg font-bold text-white tracking-wide">Pustaka V</span>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5">
                <button @click="activeTab = 'dashboard'" 
                        :class="activeTab === 'dashboard' ? 'bg-[#1E1715] text-[#FF5500] font-semibold border-r-2 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs transition duration-150 text-left cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </button>

                <button @click="activeTab = 'catalog'" 
                        :class="activeTab === 'catalog' ? 'bg-[#1E1715] text-[#FF5500] font-semibold border-r-2 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs transition duration-150 text-left cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Catalog
                </button>

                <button @click="activeTab = 'my_loans'" 
                        :class="activeTab === 'my_loans' ? 'bg-[#1E1715] text-[#FF5500] font-semibold border-r-2 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs transition duration-150 text-left cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    My Loans
                </button>

                <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'bg-[#1E1715] text-[#FF5500] font-semibold border-r-2 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs transition duration-150 text-left cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Borrowing History
                </button>

                <button @click="activeTab = 'fines'" 
                        :class="activeTab === 'fines' ? 'bg-[#1E1715] text-[#FF5500] font-semibold border-r-2 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs transition duration-150 text-left cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Fines
                </button>

                <button @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'bg-[#1E1715] text-[#FF5500] font-semibold border-r-2 border-[#FF5500]' : 'text-gray-400 hover:bg-gray-800/40 hover:text-white'"
                        class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs transition duration-150 text-left cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile
                </button>
            </nav>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-500/10 rounded-xl text-xs font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 p-8 overflow-y-auto">
        
        <!-- HEADER PROFILE UMUM -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Selamat Datang, {{ $user->name ?? 'Bagus Saputra' }}</h1>
                <p class="text-xs text-gray-500 mt-1">NIS {{ $user->nis_nip ?? '20248903' }} | {{ $user->kelas ?? 'XII RPL 1' }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-white transition">Halaman Utama</a>
                <div class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center border border-gray-700">
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
                </div>
                <div class="w-9 h-9 bg-orange-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($user->name ?? 'B', 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- 1. TAB DASHBOARD -->
        <div x-show="activeTab === 'dashboard'" class="space-y-6">
            <!-- Warning Banner -->
            <div class="bg-[#1A0F0F] border border-red-900/50 rounded-2xl p-4 flex items-center justify-between text-xs text-red-300">
                <div class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full bg-red-900/50 flex items-center justify-center text-red-400 text-xs font-bold">!</span>
                    <span><strong class="text-white">Attention:</strong> "Bumi Manusia" is due in 2 days. Return on time to avoid fines.</span>
                </div>
                <span class="bg-red-900/30 text-red-400 px-3 py-1 rounded-lg font-medium border border-red-800/40 text-[10px]">Due Soon</span>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Active Loans</p>
                    <p class="text-2xl font-extrabold text-white mt-2">{{ $stats['active_loans'] }}</p>
                    <p class="text-[11px] text-gray-500 mt-1">Books borrowed</p>
                </div>
                <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Books Due Soon</p>
                    <p class="text-2xl font-extrabold text-white mt-2">{{ $stats['due_soon'] }}</p>
                    <p class="text-[11px] text-gray-500 mt-1">Within 48 hours</p>
                </div>
                <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Total Fines</p>
                    <p class="text-2xl font-extrabold text-white mt-2">{{ $stats['total_fines'] }}</p>
                    <p class="text-[11px] text-gray-500 mt-1">SMKN 5 Standard</p>
                </div>
                <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Books Read</p>
                    <p class="text-2xl font-extrabold text-white mt-2">{{ $stats['books_read'] }}</p>
                    <p class="text-[11px] text-gray-500 mt-1">This semester</p>
                </div>
            </div>

            <!-- Active Loans Table -->
            <div class="bg-[#111827] rounded-2xl border border-gray-800 p-6">
                <h2 class="text-sm font-bold text-white mb-4">Your Active Loans</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-gray-500 border-b border-gray-800/80 pb-3">
                                <th class="pb-3 font-semibold">Book Title</th>
                                <th class="pb-3 font-semibold">Borrow Date</th>
                                <th class="pb-3 font-semibold">Due Date</th>
                                <th class="pb-3 font-semibold">Countdown</th>
                                <th class="pb-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50 text-gray-300">
                            <tr>
                                <td class="py-4 font-semibold text-white">Bumi Manusia</td>
                                <td class="py-4 text-gray-400">12 Nov 2024</td>
                                <td class="py-4 text-gray-400">15 Nov 2024</td>
                                <td class="py-4 text-amber-500 font-semibold">2 days left</td>
                                <td class="py-4"><span class="bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded-full text-[10px] font-medium border border-emerald-500/20">Active</span></td>
                            </tr>
                            <tr>
                                <td class="py-4 font-semibold text-white">Filosofi Teras</td>
                                <td class="py-4 text-gray-400">10 Nov 2024</td>
                                <td class="py-4 text-gray-400">13 Nov 2024</td>
                                <td class="py-4 text-gray-500">Returned</td>
                                <td class="py-4"><span class="bg-gray-800 text-gray-400 px-2.5 py-1 rounded-full text-[10px] font-medium">Returned</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div>
                <h3 class="text-xs font-bold text-white mb-3">Quick Library Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button @click="activeTab = 'catalog'" class="p-4 bg-[#111827] hover:bg-gray-800/80 rounded-xl border border-gray-800 text-xs font-semibold text-white flex items-center gap-3 transition">
                        <span class="p-2 bg-orange-500/10 text-[#FF5500] rounded-lg">📚</span> Browse Catalog
                    </button>
                    <button @click="activeTab = 'fines'" class="p-4 bg-[#111827] hover:bg-gray-800/80 rounded-xl border border-gray-800 text-xs font-semibold text-white flex items-center gap-3 transition">
                        <span class="p-2 bg-orange-500/10 text-[#FF5500] rounded-lg">💳</span> Pay Fines Online
                    </button>
                    <button class="p-4 bg-[#111827] hover:bg-gray-800/80 rounded-xl border border-gray-800 text-xs font-semibold text-white flex items-center gap-3 transition">
                        <span class="p-2 bg-orange-500/10 text-[#FF5500] rounded-lg">⏳</span> Request Extension
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. TAB BORROWING HISTORY -->
        <div x-show="activeTab === 'history'" class="space-y-6" x-cloak>
            <div>
                <h2 class="text-xl font-bold text-white">Riwayat Peminjaman</h2>
                <p class="text-xs text-gray-400 mt-0.5">Daftar lengkap seluruh buku yang pernah Anda pinjam di Pustaka V</p>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500">TOTAL PINJAMAN</p>
                    <p class="text-xl font-extrabold text-white mt-1">{{ $stats['history_total'] }} Buku</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Sepanjang semester</p>
                </div>
                <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500">TEPAT WAKTU</p>
                    <p class="text-xl font-extrabold text-emerald-400 mt-1">{{ $stats['history_ontime'] }} Buku</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Disiplin mengembalikan</p>
                </div>
                <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500">TERLAMBAT</p>
                    <p class="text-xl font-extrabold text-red-500 mt-1">{{ $stats['history_late'] }} Kali</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Butuh peningkatan</p>
                </div>
                <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800">
                    <p class="text-[10px] uppercase font-bold text-gray-500">TOTAL DENDA</p>
                    <p class="text-xl font-extrabold text-amber-500 mt-1">{{ $stats['total_fines'] }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Telah dilunasi</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-3">
                    <select class="bg-[#111827] border border-gray-800 rounded-xl px-3 py-2 text-gray-300 focus:outline-none">
                        <option>Pilih Tanggal: Semua Waktu</option>
                    </select>
                    <select class="bg-[#111827] border border-gray-800 rounded-xl px-3 py-2 text-gray-300 focus:outline-none">
                        <option>Status: Semua</option>
                    </select>
                </div>
                <button class="text-gray-400 hover:text-white transition">✓ Reset Filter</button>
            </div>

            <!-- History Table -->
            <div class="bg-[#111827] rounded-2xl border border-gray-800 overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-900/40 text-gray-500 border-b border-gray-800">
                        <tr>
                            <th class="p-4">No</th>
                            <th class="p-4">Judul Buku</th>
                            <th class="p-4">Tanggal Pinjam</th>
                            <th class="p-4">Tanggal Kembali</th>
                            <th class="p-4">Durasi</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60 text-gray-300">
                        <tr>
                            <td class="p-4 text-gray-500">1</td>
                            <td class="p-4 font-semibold text-white">Bumi Manusia</td>
                            <td class="p-4 text-gray-400">12 Nov 2024</td>
                            <td class="p-4 text-gray-400">15 Nov 2024</td>
                            <td class="p-4 text-gray-400">3 Hari</td>
                            <td class="p-4"><span class="bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded-full text-[10px]">Aktif</span></td>
                            <td class="p-4 text-gray-500">-</td>
                        </tr>
                        <tr>
                            <td class="p-4 text-gray-500">2</td>
                            <td class="p-4 font-semibold text-white">Negeri 5 Menara</td>
                            <td class="p-4 text-gray-400">01 Nov 2024</td>
                            <td class="p-4 text-gray-400">04 Nov 2024</td>
                            <td class="p-4 text-gray-400">3 Hari</td>
                            <td class="p-4"><span class="bg-gray-800 text-gray-400 px-2.5 py-1 rounded-full text-[10px]">Kembali</span></td>
                            <td class="p-4 text-gray-500">-</td>
                        </tr>
                        <tr>
                            <td class="p-4 text-gray-500">3</td>
                            <td class="p-4 font-semibold text-white">Filosofi Teras</td>
                            <td class="p-4 text-gray-400">15 Okt 2024</td>
                            <td class="p-4 text-gray-400">22 Okt 2024</td>
                            <td class="p-4 text-gray-400">7 Hari</td>
                            <td class="p-4"><span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-full text-[10px]">Terlambat</span></td>
                            <td class="p-4 text-red-400 font-semibold">Rp 4.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. TAB CATALOG (Placeholder) -->
        <div x-show="activeTab === 'catalog'" x-cloak class="p-8 text-center text-gray-400 bg-[#111827] rounded-2xl border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-2">Katalog Buku Perpustakaan</h2>
            <p class="text-xs">Daftar buku yang tersedia dapat dicari di sini.</p>
        </div>

        <!-- 4. TAB MY LOANS (Placeholder) -->
        <div x-show="activeTab === 'my_loans'" x-cloak class="p-8 text-center text-gray-400 bg-[#111827] rounded-2xl border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-2">Pinjaman Saya</h2>
            <p class="text-xs">Daftar buku yang sedang dalam status Anda pinjam.</p>
        </div>

        <!-- 5. TAB FINES (Placeholder) -->
        <div x-show="activeTab === 'fines'" x-cloak class="p-8 text-center text-gray-400 bg-[#111827] rounded-2xl border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-2">Informasi Denda</h2>
            <p class="text-xs">Rincian tagihan keterlambatan pengembalian buku.</p>
        </div>

        <!-- 6. TAB PROFILE -->
        <div x-show="activeTab === 'profile'" x-cloak class="bg-[#111827] rounded-2xl border border-gray-800 p-6 space-y-4 max-w-xl">
            <h2 class="text-xl font-bold text-white mb-4">Profil Akun</h2>
            <div class="space-y-3 text-xs">
                <div>
                    <label class="text-gray-500 block mb-1">Nama Lengkap</label>
                    <input type="text" readonly value="{{ $user->name ?? '' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="text-gray-500 block mb-1">Email</label>
                    <input type="text" readonly value="{{ $user->email ?? '' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="text-gray-500 block mb-1">NIS / NIP</label>
                    <input type="text" readonly value="{{ $user->nis_nip ?? '' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="text-gray-500 block mb-1">Kelas</label>
                    <input type="text" readonly value="{{ $user->kelas ?? '' }}" class="w-full bg-[#0B0F19] border border-gray-800 p-3 rounded-xl text-white">
                </div>
            </div>
        </div>

    </main>

</body>
</html>