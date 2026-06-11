<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <p>Tanggal: {{ date('d/m/Y H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $i => $t)
            <tr>
                <td>{{ $i+1 }}</td>
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
</body>
</html>