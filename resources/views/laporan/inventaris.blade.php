@extends('layouts.app')

@section('title', 'Laporan Inventaris')
@section('page-title', 'Laporan Inventaris Barang')
@section('page-description', 'Data lengkap seluruh barang inventaris')

@section('content')
<div class="inventory-report">

    <!-- Tombol Kembali & Export -->
    <div class="action-bar">
        <a href="{{ route('laporan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div class="export-group">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="{{ route('laporan.export.excel') }}?type=inventaris" class="btn-excel">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('laporan.export.pdf') }}?type=inventaris" class="btn-pdf">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Statistik Cards (grid modern) -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $barangs->count() }}</h3>
                <p>Total Barang</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $barangs->where('stok', '>', 0)->count() }}</h3>
                <p>Stok Tersedia</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $barangs->sum('stok') }}</h3>
                <p>Total Stok</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $barangs->groupBy('kategori_id')->count() }}</h3>
                <p>Kategori Terisi</p>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $index => $barang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="kode">{{ $barang->kode_inventaris }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $barang->lokasi->nama_lokasi ?? '-' }}</td>
                    <td class="text-center">{{ $barang->stok }}</td>
                    <td>
                        @php
                            $status = strtolower($barang->status_barang ?? 'tersedia');
                        @endphp
                        @if($status == 'tersedia')
                            <span class="badge-tersedia"><i class="fas fa-check-circle"></i> Tersedia</span>
                        @elseif($status == 'dipinjam')
                            <span class="badge-dipinjam"><i class="fas fa-hand-holding"></i> Dipinjam</span>
                        @else
                            <span class="badge-other">{{ ucfirst($status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-row">
                        <i class="fas fa-box-open"></i>
                        <p>Belum ada data barang.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer cetak -->
    <div class="print-footer">
        <i class="fas fa-calendar-alt"></i> Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | Sistem InventarisPro
    </div>
</div>

<style>
    .inventory-report {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 24px;
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    }

    /* Action Bar */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 32px;
    }
    .btn-back {
        background: #f1f5f9;
        padding: 8px 20px;
        border-radius: 40px;
        text-decoration: none;
        color: #1e293b;
        font-weight: 500;
        font-size: 0.85rem;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    .export-group {
        display: flex;
        gap: 12px;
    }
    .btn-print, .btn-excel, .btn-pdf {
        padding: 8px 22px;
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-print { background: #334155; color: white; }
    .btn-print:hover { background: #1e293b; transform: translateY(-2px); }
    .btn-excel { background: #2e7d32; color: white; }
    .btn-excel:hover { background: #1b5e20; transform: translateY(-2px); }
    .btn-pdf { background: #d32f2f; color: white; }
    .btn-pdf:hover { background: #b71c1c; transform: translateY(-2px); }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: white;
        border-radius: 28px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #edf2f7;
        transition: 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -12px rgba(0,0,0,0.1);
        border-color: #e2e8f0;
    }
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: white;
    }
    .stat-details h3 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
        color: #0f172a;
    }
    .stat-details p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #5b6e8c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Tabel */
    .table-wrapper {
        background: white;
        border-radius: 24px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        border: 1px solid #edf2f7;
        margin-bottom: 24px;
    }
    .inventory-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .inventory-table th {
        background: #f8fafc;
        padding: 16px 16px;
        text-align: left;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }
    .inventory-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f8;
        color: #334155;
    }
    .inventory-table tr:hover td {
        background-color: #fafcff;
    }
    .kode {
        font-weight: 600;
        font-family: monospace;
        letter-spacing: 0.3px;
    }
    .badge-tersedia, .badge-dipinjam, .badge-other {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-tersedia {
        background: #e0f2e9;
        color: #1e7b48;
    }
    .badge-dipinjam {
        background: #fff4e5;
        color: #c2410c;
    }
    .badge-other {
        background: #eef2ff;
        color: #4338ca;
    }
    .empty-row {
        text-align: center;
        padding: 48px !important;
        color: #94a3b8;
    }
    .empty-row i {
        font-size: 48px;
        margin-bottom: 12px;
        display: block;
    }
    .print-footer {
        text-align: center;
        font-size: 11px;
        color: #8196b1;
        border-top: 1px dashed #dce3ec;
        padding-top: 20px;
        margin-top: 16px;
    }

    /* Print styles */
    @media print {
        .action-bar, .btn-back, .export-group, .btn-print, .btn-excel, .btn-pdf {
            display: none !important;
        }
        .inventory-report {
            padding: 0;
        }
        .stat-card {
            border: 1px solid #ccc;
            break-inside: avoid;
        }
        .inventory-table th {
            background: #e9ecef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-tersedia, .badge-dipinjam, .badge-other {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .print-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: white;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .inventory-report {
            padding: 16px;
        }
        .stats-grid {
            gap: 16px;
        }
        .stat-card {
            padding: 16px;
        }
        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .export-group {
            justify-content: center;
        }
        .inventory-table th, .inventory-table td {
            padding: 10px 12px;
        }
    }
</style>
@endsection