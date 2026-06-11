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
            <tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Stok</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($barangs as $i => $b)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $b->kode_inventaris }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $b->lokasi->nama_lokasi ?? '-' }}</td>
                <td>{{ $b->stok }}</td>
                <td>{{ $b->status_barang ?? 'Tersedia' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>