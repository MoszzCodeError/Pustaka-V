<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — Pustaka V</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        /* Custom scrollbar untuk sidebar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0B0F17; }
        ::-webkit-scrollbar-thumb { background: #1F2937; border-radius: 9999px; }
    </style>
</head>
<body class="bg-[#0B0F17] text-gray-200 font-sans antialiased selection:bg-[#FF5500] selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- BACKDROP MOBILE SIDEBAR -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/80 z-40 lg:hidden backdrop-blur-sm">
        </div>

        <!-- SIDEBAR (FIXED POSITION & STICKY PROFILE AT BOTTOM) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 left-0 z-50 w-72 bg-[#0B0F17] border-r border-gray-800/80 flex flex-col justify-between transform transition-transform duration-300 ease-in-out flex-shrink-0 h-screen overflow-hidden">
            
            <!-- NAV HEADER & LINKS (SCROLLABLE AREA) -->
            <div class="flex-1 flex flex-col min-h-0 overflow-y-auto">
                <!-- SIDEBAR HEADER -->
                <div class="flex items-center justify-between p-5 border-b border-gray-800/60 shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-tr from-[#FF5500] to-orange-400 rounded-xl flex items-center justify-center font-black text-white text-lg shadow-lg shadow-orange-500/20">P</div>
                        <div>
                            <span class="text-lg font-black tracking-wide text-white block leading-none">Pustaka V</span>
                            <span class="text-[10px] text-gray-500 font-bold tracking-widest uppercase mt-1 block">ADMIN PANEL</span>
                        </div>
                    </a>
                    <!-- CLOSE BUTTON MOBILE -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white p-1 text-lg">
                        ✕
                    </button>
                </div>

                <!-- NAVIGATION LINKS (IKON SVG OUTLINE MINIMALIS) -->
                <nav class="p-4 space-y-2 text-sm font-bold flex-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">
                        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Kelola Buku -->
                    <a href="{{ route('admin.buku') }}" 
                       class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition {{ request()->routeIs('admin.buku*') ? 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">
                        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Kelola Buku</span>
                    </a>

                    <!-- Kelola Pinjaman -->
                    <a href="{{ route('admin.pinjaman') }}" 
                       class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition {{ request()->routeIs('admin.pinjaman*') ? 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">
                        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Kelola Pinjaman</span>
                    </a>

                    <!-- Riwayat Pinjaman -->
                    <a href="{{ route('admin.riwayat') }}" 
                       class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition {{ request()->routeIs('admin.riwayat*') ? 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">
                        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Riwayat Pinjaman</span>
                    </a>

                    <!-- Kelola Banner -->
                    <a href="{{ route('admin.banner') }}" 
                       class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition {{ request()->routeIs('admin.banner*') ? 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">
                        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Kelola Banner</span>
                    </a>

                    <!-- Lokasi Buku -->
                    <a href="{{ route('admin.lokasi') }}" 
                       class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition {{ request()->routeIs('admin.lokasi*') ? 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">
                        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Lokasi Buku</span>
                    </a>

                    {{-- CEK JIKA USER ADALAH SUPER ADMIN / ADMIN TERDAFTAR --}}
                    @if(in_array(auth()->user()->role ?? '', ['super_admin', 'admin']))
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition {{ request()->routeIs('admin.users*') ? 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">
                        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Kelola Pengguna</span>
                    </a>
                    @endif
                </nav>
            </div>

            <!-- SIDEBAR FOOTER / PROFILE FIXED DI PALING BAWAH -->
            <div class="p-4 border-t border-gray-800/60 bg-[#0B0F17] shrink-0">
                <div class="bg-[#111827] p-3.5 rounded-2xl border border-gray-800 flex items-center justify-between">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-[#FF5500]/20 text-[#FF5500] border border-[#FF5500]/30 flex items-center justify-center font-black text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold truncate">{{ str_replace('_', ' ', Auth::user()->role ?? 'ADMIN') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
            
            <!-- TOPNAV BAR (TAMPIL DI HP & DESKTOP) -->
            <header class="bg-[#0B0F17]/90 backdrop-blur-md border-b border-gray-800/60 sticky top-0 z-30 px-4 sm:px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- HAMBURGER BUTTON MOBILE -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl bg-[#111827] border border-gray-800 text-gray-300 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <span class="text-sm font-bold text-gray-400 hidden sm:inline-block">Panel Administrator</span>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="px-4 py-2.5 text-xs sm:text-sm bg-[#111827] hover:bg-gray-800 text-gray-300 hover:text-white font-bold rounded-xl border border-gray-800 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Beranda</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 text-xs sm:text-sm bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white font-bold rounded-xl border border-rose-500/20 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>