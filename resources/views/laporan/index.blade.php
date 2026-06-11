@extends('layouts.app')
@section('title', 'Laporan Inventaris')
@section('page-title', 'Laporan Inventaris Barang')
@section('page-description', 'Generate laporan inventaris dan transaksi')

@section('content')
<div class="report-dashboard">

    <!-- Statistik Cards - New Design -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <div class="stat-details">
                <span class="stat-label">Total Barang</span>
                <h2 class="stat-value">{{ $totalBarang ?? 0 }}</h2>
                <span class="stat-unit">Unit</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-details">
                <span class="stat-label">Total Transaksi</span>
                <h2 class="stat-value">{{ $totalTransaksi ?? 0 }}</h2>
                <span class="stat-unit">Kali</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="stat-details">
                <span class="stat-label">Sedang Dipinjam</span>
                <h2 class="stat-value">{{ $sedangDipinjam ?? 0 }}</h2>
                <span class="stat-unit">Barang aktif</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="stat-details">
                <span class="stat-label">Barang Terpakai</span>
                <h2 class="stat-value">{{ $persentaseTerpakai ?? 0 }}%</h2>
                <div class="progress-bar-custom">
                    <div class="progress-fill" style="width: {{ $persentaseTerpakai ?? 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Laporan Section - Modern Cards -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <h3>Generate Laporan</h3>
            <p>Pilih jenis laporan yang ingin Anda lihat atau unduh</p>
        </div>

        <div class="report-grid">
            <!-- Laporan Inventaris -->
            <div class="report-card">
                <div class="report-card-header bg-primary-light">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="report-card-body">
                    <h4>Inventaris Barang</h4>
                    <p>Laporan lengkap semua data barang, kategori, lokasi, dan status.</p>
                    <div class="report-actions">
                        <a href="{{ route('laporan.inventaris') }}" class="btn btn-sm btn-outline">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="{{ route('laporan.export.excel') }}?type=inventaris" class="btn btn-sm btn-excel">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                        <a href="{{ route('laporan.export.pdf') }}?type=inventaris" class="btn btn-sm btn-pdf">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan Transaksi -->
            <div class="report-card">
                <div class="report-card-header bg-success-light">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="report-card-body">
                    <h4>Transaksi</h4>
                    <p>Riwayat peminjaman dan pengembalian barang secara lengkap.</p>
                    <div class="report-actions">
                        <a href="{{ route('laporan.transaksi') }}" class="btn btn-sm btn-outline">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="{{ route('laporan.export.excel') }}?type=transaksi" class="btn btn-sm btn-excel">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                        <a href="{{ route('laporan.export.pdf') }}?type=transaksi" class="btn btn-sm btn-pdf">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan per Kategori -->
            <div class="report-card">
                <div class="report-card-header bg-warning-light">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="report-card-body">
                    <h4>Per Kategori</h4>
                    <p>Filter barang berdasarkan kategori tertentu.</p>
                    <div class="filter-group">
                        <select id="kategoriSelect" class="form-select-sm">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <button onclick="exportByKategori()" class="btn-filter">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Laporan per Lokasi -->
            <div class="report-card">
                <div class="report-card-header bg-info-light">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="report-card-body">
                    <h4>Per Lokasi</h4>
                    <p>Filter barang berdasarkan lokasi penyimpanan.</p>
                    <div class="filter-group">
                        <select id="lokasiSelect" class="form-select-sm">
                            <option value="">Pilih Lokasi</option>
                            @foreach($lokasis ?? [] as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                        <button onclick="exportByLokasi()" class="btn-filter">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Export All - Stylish Call to Action -->
    <div class="export-all-card">
        <div class="export-all-content">
            <div class="export-all-icon">
                <i class="fas fa-download"></i>
            </div>
            <div class="export-all-text">
                <h4>Export Semua Data</h4>
                <p>Unduh laporan inventaris dan transaksi dalam satu file</p>
            </div>
            <div class="export-all-buttons">
                <a href="{{ route('laporan.export.excel') }}?type=all" class="btn-excel-lg">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('laporan.export.pdf') }}?type=all" class="btn-pdf-lg">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    /* Modern Reset & Variables */
    .report-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 24px;
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 48px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 28px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.03), 0 2px 6px rgba(0,0,0,0.05);
        transition: all 0.25s ease;
        border: 1px solid #f0f2f5;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -12px rgba(0,0,0,0.1);
        border-color: #e2e8f0;
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }

    .stat-details {
        flex: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        color: #5b6e8c;
        display: block;
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 2.4rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.1;
        margin: 0 0 4px 0;
    }

    .stat-unit {
        font-size: 0.75rem;
        color: #8196b1;
        font-weight: 500;
    }

    .progress-bar-custom {
        background: #e2e8f0;
        border-radius: 40px;
        height: 6px;
        width: 100%;
        margin-top: 12px;
        overflow: hidden;
    }

    .progress-fill {
        background: linear-gradient(90deg, #0dcaf0, #0b9bc2);
        height: 100%;
        border-radius: 40px;
        width: 0%;
    }

    /* Section Header */
    .section-header {
        margin-bottom: 28px;
        text-align: left;
    }

    .section-header i {
        font-size: 1.8rem;
        color: #4361ee;
        background: #eef2ff;
        padding: 12px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 12px;
    }

    .section-header h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px 0;
    }

    .section-header p {
        color: #5b6e8c;
        margin: 0;
    }

    /* Report Grid */
    .report-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 28px;
        margin-bottom: 48px;
    }

    .report-card {
        background: #ffffff;
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #edf2f7;
    }

    .report-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 36px -12px rgba(0,0,0,0.12);
        border-color: #e0e7ef;
    }

    .report-card-header {
        padding: 24px 24px 16px 24px;
        text-align: center;
        font-size: 2.2rem;
        color: white;
    }

    .bg-primary-light { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
    .bg-success-light { background: linear-gradient(135deg, #10b981, #059669); }
    .bg-warning-light { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .bg-info-light    { background: linear-gradient(135deg, #0dcaf0, #0b9bc2); }

    .report-card-body {
        padding: 20px 24px 28px;
    }

    .report-card-body h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .report-card-body p {
        font-size: 0.85rem;
        color: #5b6e8c;
        line-height: 1.45;
        margin-bottom: 24px;
    }

    .report-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #cbd5e1;
        color: #334155;
        border-radius: 40px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .btn-excel, .btn-pdf {
        border-radius: 40px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-excel {
        background: #eef2ff;
        color: #2c5e2e;
        border: 1px solid #c6e0c6;
    }
    .btn-excel:hover {
        background: #e2f0e2;
        transform: scale(1.02);
    }
    .btn-pdf {
        background: #fff0f0;
        color: #b91c1c;
        border: 1px solid #f1c0c0;
    }
    .btn-pdf:hover {
        background: #ffe0e0;
    }

    .filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .form-select-sm {
        padding: 6px 24px 6px 12px;
        font-size: 0.8rem;
        border-radius: 40px;
        border: 1px solid #cbd5e1;
        background: white;
        color: #1e293b;
        cursor: pointer;
        flex: 1;
    }

    .btn-filter {
        background: #f1f5f9;
        border: none;
        border-radius: 40px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-filter:hover {
        background: #e2e8f0;
    }

    /* Export All Card */
    .export-all-card {
        background: linear-gradient(105deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 32px;
        border: 1px solid #eef2ff;
        padding: 24px 32px;
        margin-top: 24px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.02);
    }

    .export-all-content {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .export-all-icon {
        background: #eef2ff;
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 24px;
        font-size: 28px;
        color: #4361ee;
    }

    .export-all-text h4 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 6px 0;
        color: #0f172a;
    }

    .export-all-text p {
        margin: 0;
        color: #5b6e8c;
    }

    .export-all-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .btn-excel-lg, .btn-pdf-lg {
        padding: 10px 28px;
        border-radius: 48px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
    }

    .btn-excel-lg {
        background: #2e7d32;
        color: white;
        box-shadow: 0 4px 8px rgba(46,125,50,0.2);
    }
    .btn-excel-lg:hover {
        background: #1b5e20;
        transform: translateY(-2px);
    }
    .btn-pdf-lg {
        background: #d32f2f;
        color: white;
        box-shadow: 0 4px 8px rgba(211,47,47,0.2);
    }
    .btn-pdf-lg:hover {
        background: #b71c1c;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .report-dashboard {
            padding: 16px;
        }
        .stats-grid {
            gap: 16px;
        }
        .stat-card {
            padding: 16px;
        }
        .stat-value {
            font-size: 1.8rem;
        }
        .export-all-content {
            flex-direction: column;
            text-align: center;
        }
        .report-actions {
            justify-content: center;
        }
        .report-card-body {
            text-align: center;
        }
        .filter-group {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .report-actions a, .btn-excel, .btn-pdf {
            font-size: 0.7rem;
            padding: 5px 12px;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 22px;
        }
    }
</style>

<script>
    function exportByKategori() {
        let kategoriId = document.getElementById('kategoriSelect').value;
        if (kategoriId) {
            window.location.href = "{{ route('laporan.export.excel') }}?type=kategori&id=" + kategoriId;
        } else {
            alert('Pilih kategori terlebih dahulu');
        }
    }

    function exportByLokasi() {
        let lokasiId = document.getElementById('lokasiSelect').value;
        if (lokasiId) {
            window.location.href = "{{ route('laporan.export.excel') }}?type=lokasi&id=" + lokasiId;
        } else {
            alert('Pilih lokasi terlebih dahulu');
        }
    }
</script>
@endsection