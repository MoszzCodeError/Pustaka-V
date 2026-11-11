@extends('layouts.admin')
@section('title', 'Riwayat Pinjaman Lengkap & Analytics')

@section('content')
<div class="p-4 sm:p-6 font-sans space-y-6">

    <!-- HEADER PAGE & METRICS STATS -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Pembukuan & Analytics Riwayat</h1>
            <p class="text-xs text-gray-400 mt-0.5">Laporan statistik sirkulasi peminjaman, performa, dan rekap denda siswa</p>
        </div>
        <!-- TOMBOL EXPORT EXCEL & PDF -->
        <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto">
            <a href="{{ route('admin.riwayat.export', request()->query()) }}" class="w-full sm:w-auto bg-[#FF5500] hover:bg-orange-600 text-xs font-bold text-white px-4 py-2.5 rounded-xl transition shadow-lg shadow-orange-500/20 active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                <span>📊</span>
                <span>Export Excel (.xlsx)</span>
            </a>
            <a href="{{ route('admin.riwayat.export_pdf', request()->query()) }}" target="_blank" class="w-full sm:w-auto bg-rose-600 hover:bg-rose-700 text-xs font-bold text-white px-4 py-2.5 rounded-xl transition shadow-lg shadow-rose-600/20 active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                <span>📄</span>
                <span>Export PDF (.pdf)</span>
            </a>
        </div>
    </div>

    <!-- STATS CARDS BRIEF -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-4">
        <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800/80 shadow-md">
            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block">TOTAL TRANSAKSI</span>
            <div class="text-lg sm:text-2xl font-black text-white mt-1">{{ number_format($totalTransaksi ?? 0) }}</div>
            <span class="text-[10px] text-gray-500 mt-0.5 block">Sirkulasi terdata</span>
        </div>
        <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800/80 shadow-md">
            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block">DIKEMBALIKAN</span>
            <div class="text-lg sm:text-2xl font-black text-emerald-400 mt-1">{{ number_format($totalSelesai ?? 0) }}</div>
            <span class="text-[10px] text-gray-500 mt-0.5 block">Selesai tepat waktu</span>
        </div>
        <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800/80 shadow-md">
            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block">SEDANG DIPINJAM</span>
            <div class="text-lg sm:text-2xl font-black text-amber-400 mt-1">{{ number_format($totalAktif ?? 0) }}</div>
            <span class="text-[10px] text-gray-500 mt-0.5 block">Masih di tangan siswa</span>
        </div>
        <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800/80 shadow-md">
            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block">TOTAL DENDA REKAP</span>
            <div class="text-lg sm:text-2xl font-black text-[#FF5500] mt-1">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</div>
            <span class="text-[10px] text-gray-500 mt-0.5 block">Akumulasi denda</span>
        </div>
    </div>

    <!-- SECTION DIAGRAM STATISTIK PEMBUKUAN (CHART.JS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Chart 1: Tren Peminjaman per Bulan / Range -->
        <div class="lg:col-span-8 bg-[#111827] p-4 sm:p-5 rounded-2xl border border-gray-800/80 shadow-xl">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-white mb-0.5">📈 Tren Keramaian Peminjaman Buku</h3>
                    <p class="text-[11px] text-gray-400">Grafik sirkulasi peminjaman buku berdasarkan filter rentang waktu</p>
                </div>

                <!-- FILTER DROPDOWN GRAFIK -->
                <form action="{{ route('admin.riwayat') }}" method="GET" id="formChartRange">
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                    <select name="chart_range" onchange="document.getElementById('formChartRange').submit()" class="bg-[#0B0F19] border border-gray-800 text-xs text-[#FF5500] font-bold px-3 py-1.5 rounded-xl focus:outline-none focus:border-[#FF5500] cursor-pointer">
                        <option value="7days" {{ ($chartRange ?? 'year') == '7days' ? 'selected' : '' }}>📅 7 Hari Terakhir</option>
                        <option value="30days" {{ ($chartRange ?? 'year') == '30days' ? 'selected' : '' }}>📅 30 Hari Terakhir (1 Bulan)</option>
                        <option value="year" {{ ($chartRange ?? 'year') == 'year' ? 'selected' : '' }}>📅 1 Tahun Ini (12 Bulan)</option>
                        <option value="all" {{ ($chartRange ?? 'year') == 'all' ? 'selected' : '' }}>📅 Semua Waktu (Per Tahun)</option>
                    </select>
                </form>
            </div>

            <div class="h-60 sm:h-64 relative">
                <canvas id="chartMonthlyTrend"></canvas>
            </div>
        </div>

        <!-- Chart 2: Distribusi Status Transaksi -->
        <div class="lg:col-span-4 bg-[#111827] p-4 sm:p-5 rounded-2xl border border-gray-800/80 shadow-xl flex flex-col justify-between">
            <div>
                <h3 class="text-xs sm:text-sm font-bold text-white mb-1">🥧 Distribusi Status Transaksi</h3>
                <p class="text-[11px] text-gray-400 mb-4">Persentase status peminjaman siswa</p>
                <div class="h-48 sm:h-52 relative flex items-center justify-center">
                    <canvas id="chartStatusDistribution"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- CONSOLE FILTER & LIST TABEL RIWAYAT -->
    <div class="bg-[#111827] p-4 sm:p-6 rounded-2xl border border-gray-800/80 shadow-xl">
        <div class="mb-5">
            <h3 class="text-sm sm:text-base font-bold text-white">Console Filter & Pencarian Riwayat</h3>
            <p class="text-xs text-gray-400 mt-0.5">Cari berdasarkan nama siswa atau judul buku, serta atur urutan data</p>
        </div>

        <!-- FORM FILTER & SORT -->
        <form action="{{ route('admin.riwayat') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 mb-6">
            @if(request('chart_range')) <input type="hidden" name="chart_range" value="{{ request('chart_range') }}"> @endif
            <!-- Search Text -->
            <div class="sm:col-span-7 relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama siswa, NIS, atau judul buku..." class="w-full bg-[#0B0F19] border border-gray-800 text-xs text-white px-3.5 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
            </div>

            <!-- Sort By Option -->
            <div class="sm:col-span-5">
                <select name="sort" onchange="this.form.submit()" class="w-full bg-[#0B0F19] border border-gray-800 text-xs text-gray-300 px-3.5 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500] font-medium">
                    <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Sortir: Tanggal Terbaru</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Sortir: Tanggal Terlama</option>
                    <option value="denda_desc" {{ request('sort') == 'denda_desc' ? 'selected' : '' }}>Sortir: Denda (Tertinggi → Terendah)</option>
                    <option value="denda_asc" {{ request('sort') == 'denda_asc' ? 'selected' : '' }}>Sortir: Denda (Terendah → Tertinggi)</option>
                    <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Sortir: Nama Siswa (A - Z)</option>
                    <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Sortir: Nama Siswa (Z - A)</option>
                </select>
            </div>
        </form>

        <!-- TABEL DATA RIWAYAT -->
        <div class="overflow-x-auto rounded-xl border border-gray-800/60">
            <table class="w-full text-left text-xs text-gray-400 min-w-[640px]">
                <thead class="bg-[#0B0F19] text-gray-300 uppercase text-[10px] tracking-wider border-b border-gray-800/80">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Buku Dipinjam</th>
                        <th class="py-3 px-4">Denda</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @forelse($riwayat as $r)
                    <tr class="hover:bg-[#1E293B]/40 transition">
                        <td class="py-3.5 px-4 text-gray-400 whitespace-nowrap">
                            {{ isset($r->created_at) ? date('d M Y', strtotime($r->created_at)) : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-white font-semibold whitespace-nowrap">
                            {{ $r->nama_siswa ?? $r->nama_user ?? ('User ID: ' . $r->user_id) }}
                        </td>
                        <td class="py-3.5 px-4 text-gray-300 whitespace-nowrap">
                            <span class="bg-gray-800 px-2.5 py-1 rounded-md text-[10px] border border-gray-700 font-mono">
                                {{ $r->kelas ?? $r->tingkat ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-gray-300 max-w-[180px] sm:max-w-xs truncate" title="{{ $r->judul_buku ?? ('Buku ID: ' . $r->book_id) }}">
                            {{ $r->judul_buku ?? ($r->judul ?? ('Buku ID: ' . $r->book_id)) }}
                        </td>
                        <td class="py-3.5 px-4 font-semibold whitespace-nowrap {{ ($r->denda ?? 0) > 0 ? 'text-[#FF5500]' : 'text-gray-500' }}">
                            Rp {{ number_format($r->denda ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @php
                                $st = strtolower($r->status ?? '');
                                $badgeClass = 'bg-gray-800 text-gray-300 border-gray-700';
                                if ($st === 'dikembalikan') {
                                    $badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                                } elseif ($st === 'aktif' || $st === 'dipinjam') {
                                    $badgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
                                } elseif ($st === 'terlambat') {
                                    $badgeClass = 'bg-rose-500/10 text-rose-400 border-rose-500/20';
                                }
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-[9px] sm:text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                                {{ str_replace('_', ' ', $r->status ?? 'Aktif') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500 text-xs">
                            Belum ada riwayat transaksi yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($riwayat, 'links'))
            <div class="mt-4">
                {{ $riwayat->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

<!-- SCRIPT CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    @php
        $labelsArr = $chartMonthlyLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataArr   = $chartMonthlyData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $stLabels  = ['Dikembalikan', 'Aktif/Pinjam', 'Terlambat'];
        $stData    = [$totalSelesai ?? 0, $totalAktif ?? 0, $totalTerlambat ?? 0];
    @endphp

    // 1. Line Chart Bulanan / Range Dynamic
    const ctxTrend = document.getElementById('chartMonthlyTrend').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: @json($labelsArr),
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: @json($dataArr),
                borderColor: '#FF5500',
                backgroundColor: 'rgba(255, 85, 0, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#FF5500',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#9CA3AF', font: { size: 10 } }
                },
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#9CA3AF', font: { size: 10 } }
                }
            }
        }
    });

    // 2. Doughnut Chart Status
    const ctxStatus = document.getElementById('chartStatusDistribution').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: @json($stLabels),
            datasets: [{
                data: @json($stData),
                backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#D1D5DB', font: { size: 10 }, padding: 12 }
                }
            },
            cutout: '70%'
        }
    });
});
</script>
@endsection