<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 5px; }
        .date { text-align: center; font-size: 10px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #aaa; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #888; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <div class="date">Dicetak: {{ date('d/m/Y H:i:s') }}</div>
    <table>
        <thead>
            <tr><th>No</th><th>Kode Transaksi</th><th>Barang</th><th>Peminjam</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($transaksis as $i => $t)
            <tr>
                <td style="text-align:center">{{ $i+1 }}</td>
                <td>{{ $t->kode_transaksi ?? '-' }}</td>
                <td>{{ $t->barang->nama_barang ?? '-' }}</td>
                <td>{{ $t->peminjam ?? '-' }}</td>
                <td style="text-align:center">{{ $t->jumlah ?? 1 }}</td>
                <td>{{ $t->tanggal_pinjam ? date('d/m/Y', strtotime($t->tanggal_pinjam)) : '-' }}</td>
                <td>{{ $t->tanggal_kembali_aktual ? date('d/m/Y', strtotime($t->tanggal_kembali_aktual)) : ($t->tanggal_kembali ? date('d/m/Y', strtotime($t->tanggal_kembali)) : '-') }}</td>
                <td>{{ ucfirst($t->status ?? '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Sistem InventarisPro – Laporan transaksi</div>
</body>
</html>