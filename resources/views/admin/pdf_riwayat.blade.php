<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Peminjaman Buku — Pustaka V</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #FF5500;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #0b0f17;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #6b7280;
        }
        .meta-info {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-box td {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            text-align: center;
        }
        .summary-box td .val {
            font-size: 14px;
            font-weight: bold;
            color: #FF5500;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th {
            background-color: #0b0f17;
            color: #ffffff;
            padding: 8px 10px;
            font-size: 10px;
            text-transform: uppercase;
            text-align: left;
        }
        table.data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            text-transform: uppercase;
        }
        .badge-dikembalikan { background-color: #d1fae5; color: #065f46; }
        .badge-aktif { background-color: #fef3c7; color: #92400e; }
        .badge-terlambat { background-color: #fee2e2; color: #991b1b; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="background-color: #FF5500; color: white; border: none; padding: 8px 15px; font-weight: bold; border-radius: 6px; cursor: pointer;">🖨️ Cetak / Save PDF</button>
    </div>

    <!-- KOP HEADER LAPORAN -->
    <div class="header">
        <h1>LAPORAN SIRKULASI PEMINJAMAN BUKU</h1>
        <p>SMKN 5 SURAKARTA — SISTEM INFORMASI PERPUSTAKAAN PUSTAKA V</p>
        <p style="margin-top: 4px; font-weight: bold; color: #374151;">Dicetak Pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <!-- RINGKASAN PEMBUKUAN STATS -->
    <table class="summary-box">
        <tr>
            <td>
                <div style="font-size: 9px; color: #6b7280; font-weight: bold;">TOTAL TRANSAKSI</div>
                <div class="val" style="color: #111827;">{{ number_format($totalTransaksi) }}</div>
            </td>
            <td>
                <div style="font-size: 9px; color: #6b7280; font-weight: bold;">DIKEMBALIKAN</div>
                <div class="val" style="color: #059669;">{{ number_format($totalSelesai) }}</div>
            </td>
            <td>
                <div style="font-size: 9px; color: #6b7280; font-weight: bold;">SEDANG DIPINJAM</div>
                <div class="val" style="color: #d97706;">{{ number_format($totalAktif) }}</div>
            </td>
            <td>
                <div style="font-size: 9px; color: #6b7280; font-weight: bold;">TOTAL DENDA REKAP</div>
                <div class="val">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <!-- TABEL UTAMA REKAP SIRKULASI -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Siswa</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 25%;">Buku Dipinjam</th>
                <th style="width: 15%;">Denda</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $r)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ isset($r->created_at) ? date('d/m/Y H:i', strtotime($r->created_at)) : '-' }}</td>
                <td><strong>{{ $r->nama_siswa ?? 'User ID: ' . $r->user_id }}</strong></td>
                <td>{{ $r->kelas ?? '-' }}</td>
                <td>{{ $r->judul_buku ?? 'Buku ID: ' . $r->book_id }}</td>
                <td style="font-weight: bold; color: {{ ($r->denda ?? 0) > 0 ? '#dc2626' : '#6b7280' }};">
                    Rp {{ number_format($r->denda ?? 0, 0, ',', '.') }}
                </td>
                <td>
                    @php
                        $st = strtolower($r->status ?? '');
                        $badgeClass = 'badge-aktif';
                        if ($st === 'dikembalikan') $badgeClass = 'badge-dikembalikan';
                        elseif ($st === 'terlambat') $badgeClass = 'badge-terlambat';
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ str_replace('_', ' ', $r->status ?? 'aktif') }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 15px; color: #9ca3af;">Tidak ada data riwayat transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- SIGNATURE FOOTER -->
    <div style="margin-top: 40px; float: right; text-align: center; width: 200px;">
        <p style="margin: 0;">Surakarta, {{ date('d F Y') }}</p>
        <p style="margin: 0; font-weight: bold;">Petugas Perpustakaan</p>
        <div style="height: 60px;"></div>
        <p style="margin: 0; font-weight: bold; text-decoration: underline;">( Administrator )</p>
    </div>

</body>
</html>