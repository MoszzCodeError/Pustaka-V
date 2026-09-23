<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustaka V - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #0B0F19; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0B0F19; }
        ::-webkit-scrollbar-thumb { background: #1F2937; rounded: 10px; }
    </style>
</head>
<body class="text-gray-200 antialiased min-h-screen flex">

    <!-- SIDEBAR LEFT -->
    <aside class="w-64 bg-[#080C14] border-r border-gray-800/60 min-h-screen flex flex-col justify-between p-5 fixed top-0 bottom-0 left-0 z-40">
        <div>
            <!-- LOGO -->
            <div class="flex items-center space-x-3 mb-8 px-2">
                <div class="w-9 h-9 bg-[#FF5500] rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-orange-500/20">P</div>
                <div>
                    <span class="text-lg font-bold tracking-wide text-white block leading-none">Pustaka V</span>
                    <span class="text-[9px] text-[#FF5500] font-bold tracking-widest uppercase">ADMIN PANEL</span>
                </div>
            </div>

            <!-- NAVIGATION MENU -->
            <nav class="space-y-1.5 text-xs font-medium">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF5500]/15 text-[#FF5500] border border-[#FF5500]/30 font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.buku') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.buku') ? 'bg-[#FF5500]/15 text-[#FF5500] border border-[#FF5500]/30 font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Kelola Buku</span>
                </a>

                <a href="{{ route('admin.pinjaman') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.pinjaman') ? 'bg-[#FF5500]/15 text-[#FF5500] border border-[#FF5500]/30 font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Kelola Pinjaman</span>
                </a>

                <a href="{{ route('admin.riwayat') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.riwayat') ? 'bg-[#FF5500]/15 text-[#FF5500] border border-[#FF5500]/30 font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Riwayat Pinjaman</span>
                </a>

                <a href="{{ route('admin.banner') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.banner') ? 'bg-[#FF5500]/15 text-[#FF5500] border border-[#FF5500]/30 font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Kelola Banner</span>
                </a>

                <a href="{{ route('admin.lokasi') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.lokasi') ? 'bg-[#FF5500]/15 text-[#FF5500] border border-[#FF5500]/30 font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Lokasi Buku</span>
                </a>
            </nav>
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-xs text-red-500 hover:bg-red-500/10 rounded-xl transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Logout Admin</span>
            </button>
        </form>
    </aside>

    <!-- CONTENT RIGHT -->
    <main class="flex-1 ml-64 p-8">
        <!-- TOP HEADER USER INFO -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">@yield('title', 'Dashboard Overview')</h1>
                <p class="text-xs text-gray-500">Sistem Informasi Manajemen Perpustakaan SMKN 5 Surakarta</p>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Cari data..." class="bg-[#111827] text-xs text-gray-300 pl-9 pr-4 py-2 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] w-64 transition">
                    <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="w-9 h-9 bg-[#111827] border border-gray-800 rounded-xl flex items-center justify-center text-gray-400 cursor-pointer">🔔</div>
                <div class="flex items-center space-x-3 pl-2">
                    <div class="w-9 h-9 rounded-xl bg-[#FF5500]/20 border border-[#FF5500]/30 flex items-center justify-center font-bold text-[#FF5500] text-xs">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div>
                        <span class="text-xs font-bold text-white block leading-none">{{ Auth::user()->name ?? 'Sri Wahyuni, S.Pd.' }}</span>
                        <span class="text-[10px] text-[#FF5500] font-medium">Kepala Pustaka V</span>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </main>

</body>
</html>