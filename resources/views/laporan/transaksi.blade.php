@extends('layouts.app')

@section('title', 'Laporan Transaksi')
@section('page-title', 'Laporan Transaksi Peminjaman')
@section('page-description', 'Riwayat lengkap peminjaman dan pengembalian barang')

@section('content')
<div class="transaction-report">

    <!-- Tombol Kembali & Export -->
    <div class="action-bar">
        <a href="{{ route('laporan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div class="export-group">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="{{ route('laporan.export.excel') }}?type=transaksi" class="btn-excel">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('laporan.export.pdf') }}?type=transaksi" class="btn-pdf">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $transaksis->count() }}</h3>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-hand-holding"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $transaksis->where('status', 'dipinjam')->count() }}</h3>
                <p>Masih Dipinjam</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $transaksis->where('status', 'dikembalikan')->count() }}</h3>
                <p>Sudah Dikembalikan</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $transaksis->groupBy('peminjam')->count() }}</h3>
                <p>Total Peminjam</p>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-wrapper">
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>#</th>
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
                @forelse($transaksis as $index => $trx)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="kode">{{ $trx->kode_transaksi ?? '-' }}</td>
                    <td>{{ $trx->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $trx->peminjam ?? '-' }}</td>
                    <td class="text-center">{{ $trx->jumlah ?? 1 }}</td>
                    <td>{{ $trx->tanggal_pinjam ? date('d/m/Y', strtotime($trx->tanggal_pinjam)) : '-' }}</td>
                    <td>{{ $trx->tanggal_kembali_aktual ? date('d/m/Y', strtotime($trx->tanggal_kembali_aktual)) : ($trx->tanggal_kembali ? date('d/m/Y', strtotime($trx->tanggal_kembali)) : '-') }}</td>
                    <td>
                        @if($trx->status == 'dipinjam')
                            <span class="badge-dipinjam"><i class="fas fa-hand-holding"></i> Dipinjam</span>
                        @else
                            <span class="badge-kembali"><i class="fas fa-check-circle"></i> Dikembalikan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-row">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada data transaksi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="print-footer">
        <i class="fas fa-calendar-alt"></i> Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | Sistem InventarisPro
    </div>
</div>

<style>
    .transaction-report {
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
    .transaction-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .transaction-table th {
        background: #f8fafc;
        padding: 16px 16px;
        text-align: left;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }
    .transaction-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f8;
        color: #334155;
    }
    .transaction-table tr:hover td {
        background-color: #fafcff;
    }
    .kode {
        font-weight: 600;
        font-family: monospace;
        letter-spacing: 0.3px;
    }
    .badge-dipinjam, .badge-kembali {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-dipinjam {
        background: #fff4e5;
        color: #c2410c;
    }
    .badge-kembali {
        background: #e0f2e9;
        color: #1e7b48;
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
        .transaction-report {
            padding: 0;
        }
        .stat-card {
            border: 1px solid #ccc;
            break-inside: avoid;
        }
        .transaction-table th {
            background: #e9ecef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-dipinjam, .badge-kembali {
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
        .transaction-report {
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
        .transaction-table th, .transaction-table td {
            padding: 10px 12px;
        }
    }
</style>
@endsection