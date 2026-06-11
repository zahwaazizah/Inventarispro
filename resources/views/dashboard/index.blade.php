@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan data inventaris barang')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #4361ee;"><i class="fas fa-boxes"></i></div>
            <div class="stat-info">
                <h3>{{ $totalBarang }}</h3>
                <p>Total Barang</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #10b981;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $barangTersedia }}</h3>
                <p>Barang Tersedia</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #f59e0b;"><i class="fas fa-hand-holding"></i></div>
            <div class="stat-info">
                <h3>{{ $barangDipinjam }}</h3>
                <p>Barang Dipinjam</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-info">
                <h3>{{ $barangTerlambat }}</h3>
                <p>Terlambat</p>
            </div>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #8b5cf6;"><i class="fas fa-tags"></i></div>
            <div class="stat-info">
                <h3>{{ $totalKategori }}</h3>
                <p>Total Kategori</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #ec489a;"><i class="fas fa-map-marker-alt"></i></div>
            <div class="stat-info">
                <h3>{{ $totalLokasi }}</h3>
                <p>Total Lokasi</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #14b8a6;"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>{{ $totalPetugas }}</h3>
                <p>Total Petugas</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #f97316;"><i class="fas fa-qrcode"></i></div>
            <div class="stat-info">
                <h3>{{ $totalQrCode }}</h3>
                <p>QR Code</p>
            </div>
        </div>
    </div>
    
    <!-- Barang Hampir Habis -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-circle"></i> Barang Hampir Habis</h3>
        </div>
        <div class="card-body">
            @if($barangHampirHabis->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Kode</th><th>Nama Barang</th><th>Stok</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($barangHampirHabis as $barang)
                            <tr>
                                <td>{{ $barang->kode_inventaris }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td><span class="stok-rendah">{{ $barang->stok }}</span></td>
                                <td><span class="status-sedikit">Menipis</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-small"><i class="fas fa-check-circle"></i><p>Semua barang memiliki stok yang cukup</p></div>
            @endif
        </div>
    </div>
    
    <!-- Peminjaman Terbaru -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clock"></i> Peminjaman Terbaru</h3>
        </div>
        <div class="card-body">
            @if($peminjamanTerbaru->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Peminjam</th><th>Barang</th><th>Tgl Pinjam</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($peminjamanTerbaru as $transaksi)
                            <tr>
                                <td>{{ $transaksi->peminjam ?? '-' }}</td>
                                <td>{{ $transaksi->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $transaksi->tanggal_pinjam ? date('d/m/Y', strtotime($transaksi->tanggal_pinjam)) : '-' }}</td>
                                <td><span class="status-dipinjam">Dipinjam</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-small"><i class="fas fa-inbox"></i><p>Belum ada peminjaman</p></div>
            @endif
        </div>
    </div>
    
    <!-- Peringatan Terlambat -->
    @if($transaksiTerlambat->count() > 0)
    <div class="card warning-card">
        <div class="card-header" style="background: #fef3c7;"><h3 style="color: #92400e;"><i class="fas fa-bell"></i> Peringatan Pengembalian Terlambat</h3></div>
        <div class="card-body">
            <table class="table">
                <thead><tr><th>Peminjam</th><th>Barang</th><th>Batas Kembali</th><th>Terlambat</th></tr></thead>
                <tbody>
                    @foreach($transaksiTerlambat as $transaksi)
                    <tr>
                        <td>{{ $transaksi->peminjam ?? '-' }}</td>
                        <td>{{ $transaksi->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $transaksi->tanggal_kembali ? date('d/m/Y', strtotime($transaksi->tanggal_kembali)) : '-' }}</td>
                        <td><span class="terlambat-badge">{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->diffInDays(now()) }} hari</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card { background: white; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .stat-icon { width: 55px; height: 55px; border-radius: 14px; display: flex; align-items: center; justify-content: center; }
    .stat-icon i { font-size: 28px; color: white; }
    .stat-info h3 { font-size: 28px; margin: 0; color: #1e293b; font-weight: 700; }
    .stat-info p { margin: 5px 0 0; font-size: 13px; color: #64748b; }
    .stok-rendah { background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px; font-weight: 600; }
    .status-sedikit { background: #fef3c7; color: #d97706; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
    .status-dipinjam { background: #fef3c7; color: #d97706; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
    .terlambat-badge { background: #fee2e2; color: #dc2626; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .empty-small { text-align: center; padding: 30px; color: #94a3b8; }
    .empty-small i { font-size: 40px; margin-bottom: 10px; display: block; }
    .warning-card { border-left: 4px solid #f59e0b; }
    @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } }
</style>
@endsection