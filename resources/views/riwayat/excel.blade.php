<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body>
    <h2>{{ $title }}</h2>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayats as $index => $trx)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $trx->kode_transaksi ?? '-' }}</td>
                <td>{{ $trx->barang->kode_inventaris ?? '-' }}</td>
                <td>{{ $trx->barang->nama_barang ?? '-' }}</td>
                <td>{{ $trx->peminjam ?? '-' }}</td>
                <td align="center">{{ $trx->jumlah ?? 1 }}</td>
                <td align="center">{{ $trx->tanggal_pinjam ? date('d/m/Y', strtotime($trx->tanggal_pinjam)) : '-' }}</td>
                <td align="center">
                    @if($trx->tanggal_kembali_aktual)
                        {{ date('d/m/Y', strtotime($trx->tanggal_kembali_aktual)) }}
                    @elseif($trx->tanggal_kembali)
                        {{ date('d/m/Y', strtotime($trx->tanggal_kembali)) }}
                    @else
                        -
                    @endif
                </td
                <td align="center">{{ ucfirst($trx->status ?? '-') }}</td>
                <td align="center">{{ ucfirst($trx->kondisi ?? '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>