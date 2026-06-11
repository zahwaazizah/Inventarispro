@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas')
@section('page-description', 'Ringkasan aktivitas peminjaman barang')

@section('content')
<div class="dashboard-wrapper">
    
    <!-- Statistik Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary"><i class="fas fa-boxes"></i></div>
            <div class="stat-info">
                <h3>{{ $totalBarang }}</h3>
                <p>Total Barang</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $barangTersedia }}</h3>
                <p>Tersedia</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning"><i class="fas fa-hand-holding"></i></div>
            <div class="stat-info">
                <h3>{{ $barangDipinjam }}</h3>
                <p>Sedang Dipinjam</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-danger"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h3>{{ $barangTerlambat }}</h3>
                <p>Terlambat</p>
            </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="quick-actions">
        <a href="{{ route('qr.scan') }}" class="action-btn primary">
            <i class="fas fa-qrcode"></i> Scan QR
        </a>
        <a href="{{ route('transaksi.index') }}" class="action-btn success">
            <i class="fas fa-undo-alt"></i> Pengembalian
        </a>
        <a href="{{ route('inventaris.index') }}" class="action-btn info">
            <i class="fas fa-boxes"></i> Inventaris
        </a>
    </div>

    <!-- Two Columns -->
    <div class="two-columns">
        
        <!-- Peminjaman Aktif -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-hand-holding me-2"></i> Peminjaman Aktif</h3>
                <a href="{{ route('transaksi.index') }}" class="link-more">Lihat Semua →</a>
            </div>
            <div class="card-body p-0">
                @if($peminjamanAktif->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Barang</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamanAktif as $pinjam)
                                <tr class="{{ $pinjam->isTerlambat() ? 'table-danger' : '' }}">
                                    <td><strong>{{ $pinjam->peminjam ?? '-' }}</strong></td>
                                    <td>{{ $pinjam->barang->nama_barang ?? '-' }}</td>
                                    <td>{{ $pinjam->tanggal_pinjam ? date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) : '-' }}</td>
                                    <td>{{ $pinjam->tanggal_kembali ? date('d/m/Y', strtotime($pinjam->tanggal_kembali)) : '-' }}</td>
                                    <td>
                                        @if($pinjam->isTerlambat())
                                            <span class="badge bg-danger">Terlambat</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transaksi.kembali.form', $pinjam->id) }}" class="btn btn-sm btn-success rounded-pill">
                                            <i class="fas fa-undo-alt"></i> Kembali
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">Tidak ada peminjaman aktif saat ini.</div>
                @endif
            </div>
        </div>

        <!-- Barang Hampir Habis -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-exclamation-circle me-2"></i> Barang Hampir Habis</h3>
                <a href="{{ route('inventaris.index') }}" class="link-more">Lihat Semua →</a>
            </div>
            <div class="card-body p-0">
                @if($barangHampirHabis->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangHampirHabis as $brg)
                                <tr>
                                    <td><code>{{ $brg->kode_inventaris }}</code></td>
                                    <td>{{ $brg->nama_barang }}</td>
                                    <td><span class="badge bg-orange">{{ $brg->stok }} Unit</span></td>
                                    <td>{{ $brg->lokasi->nama_lokasi ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">Stok semua barang dalam kondisi aman.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Peminjaman Terbaru -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history me-2"></i> Riwayat Peminjaman Terbaru</h3>
            <a href="{{ route('riwayat.index') }}" class="link-more">Lihat Semua →</a>
        </div>
        <div class="card-body p-0">
            @if($riwayatTerbaru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Peminjam</th>
                                <th>Barang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatTerbaru as $riwayat)
                            <tr>
                                <td>{{ $riwayat->created_at ? date('d/m/Y H:i', strtotime($riwayat->created_at)) : '-' }}</td>
                                <td>{{ $riwayat->peminjam ?? '-' }}</td>
                                <td>{{ $riwayat->barang->nama_barang ?? '-' }}</td>
                                <td>
                                    @if($riwayat->status == 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">Belum ada riwayat peminjaman.</div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .dashboard-wrapper {
        max-width: 1450px;
        margin: 0 auto;
        padding: 20px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 62px;
        height: 62px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: white;
        flex-shrink: 0;
    }
    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #1e2937;
    }
    .stat-info p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 0.95rem;
    }

    .quick-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .action-btn {
        flex: 1;
        min-width: 180px;
        padding: 14px 20px;
        border-radius: 50px;
        color: white;
        text-align: center;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .primary { background: linear-gradient(135deg, #4361ee, #3b4ec2); }
    .success { background: linear-gradient(135deg, #10b981, #059669); }
    .info    { background: linear-gradient(135deg, #0ea5e9, #0284c8); }

    .two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .card-header {
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e2937;
    }
    .link-more {
        color: #4361ee;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .table th {
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        padding: 14px 16px;
    }
    .table td {
        padding: 14px 16px;
        vertical-align: middle;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
        font-style: italic;
    }

    @media (max-width: 1024px) {
        .two-columns { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .quick-actions { flex-direction: column; }
    }
</style>
@endpush