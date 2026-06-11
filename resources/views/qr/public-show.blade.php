<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - InventarisPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .icon {
            font-size: 60px;
            color: #4361ee;
            margin-bottom: 20px;
        }
        h2 { margin-bottom: 20px; color: #1e293b; }
        .info { text-align: left; margin: 20px 0; }
        .info-item {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-label {
            width: 120px;
            font-weight: 600;
            color: #64748b;
        }
        .info-value {
            flex: 1;
            color: #1e293b;
            font-weight: 500;
        }
        .btn {
            background: #4361ee;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <i class="fas fa-qrcode"></i>
        </div>
        <h2>Detail Barang</h2>
        
        <div class="info">
            <div class="info-item">
                <div class="info-label">Kode Barang</div>
                <div class="info-value">{{ $barang->kode_inventaris }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nama Barang</div>
                <div class="info-value">{{ $barang->nama_barang }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Kategori</div>
                <div class="info-value">{{ $barang->kategori->nama_kategori ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Lokasi</div>
                <div class="info-value">{{ $barang->lokasi->nama_lokasi ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">{{ ucfirst($barang->status_barang ?? 'Tersedia') }}</div>
            </div>
        </div>
        
        <div class="footer">
            <p>Sistem InventarisPro - Scan QR untuk informasi barang</p>
        </div>
    </div>
</body>
</html>