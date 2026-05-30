<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Si Lapor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            color: #3B82F6;
            margin: 0;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead tr {
            background-color: #3B82F6;
            color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 7px 10px;
            text-align: left;
        }
        tbody tr:nth-child(even) {
            background-color: #F3F4F6;
        }
        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .pending  { background: #FEF3C7; color: #D97706; }
        .diproses { background: #DBEAFE; color: #2563EB; }
        .selesai  { background: #D1FAE5; color: #059669; }
        .ditolak  { background: #FEE2E2; color: #DC2626; }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #999;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Sistem Informasi Pengaduan Masyarakat</h1>
    <p>Si Lapor — Laporan Dicetak pada {{ date('d F Y, H:i') }} WIB</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Laporan</th>
            <th>Pelapor</th>
            <th>Kategori</th>
            <th>Judul</th>
            <th>Lokasi</th>
            <th>Petugas</th>
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($laporans as $i => $laporan)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $laporan->nomor_laporan }}</td>
            <td>{{ $laporan->user->name ?? '-' }}</td>
            <td>{{ $laporan->kategori->nama ?? '-' }}</td>
            <td>{{ $laporan->judul }}</td>
            <td>{{ $laporan->lokasi }}</td>
            <td>{{ $laporan->petugas->user->name ?? '-' }}</td>
            <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
            <td>
                <span class="badge {{ $laporan->status }}">
                    {{ ucfirst($laporan->status) }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center">Tidak ada data laporan</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Total: {{ $laporans->count() }} laporan &nbsp;|&nbsp; Dicetak oleh sistem Si Lapor
</div>

</body>
</html>