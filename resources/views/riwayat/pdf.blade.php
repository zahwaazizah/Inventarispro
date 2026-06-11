<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
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
                <td class="text-center">{{ $trx->jumlah ?? 1 }}</td>
                <td class="text-center">{{ $trx->tanggal_pinjam ? date('d/m/Y', strtotime($trx->tanggal_pinjam)) : '-' }}</td>
                <td class="text-center">
                    @if($trx->tanggal_kembali_aktual)
                        {{ date('d/m/Y', strtotime($trx->tanggal_kembali_aktual)) }}
                    @elseif($trx->tanggal_kembali)
                        {{ date('d/m/Y', strtotime($trx->tanggal_kembali)) }}
                    @else
                        -
                    @endif
                </td
                <td class="text-center">{{ ucfirst($trx->status ?? '-') }}</td>
                <td class="text-center">{{ ucfirst($trx->kondisi ?? '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>