@extends('layouts.app')
@section('title', 'Laporan Inventaris')
@section('page-title', 'Laporan Inventaris Barang')
@section('page-description', 'Data inventaris lengkap siap cetak & ekspor')

@section('content')
<div class="inventory-report">

    {{-- Ringkasan Statistik (modern & ringkas) --}}
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <span class="stat-label">Total Barang</span>
                <strong class="stat-number">{{ $totalBarang ?? $barangs->count() }}</strong>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">🔄</div>
            <div class="stat-info">
                <span class="stat-label">Total Transaksi</span>
                <strong class="stat-number">{{ $totalTransaksi ?? 0 }}</strong>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">📌</div>
            <div class="stat-info">
                <span class="stat-label">Sedang Dipinjam</span>
                <strong class="stat-number">{{ $sedangDipinjam ?? 0 }}</strong>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
                <span class="stat-label">Terpakai</span>
                <strong class="stat-number">{{ $persentaseTerpakai ?? 0 }}%</strong>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi Cetak / Ekspor --}}
    <div class="action-bar no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
        <a href="{{ route('laporan.export.excel') }}?type=inventaris" class="btn-excel">
            <i class="fas fa-file-excel"></i> Ekspor Excel
        </a>
    </div>

    {{-- Tabel Data Inventaris (dipercantik) --}}
    <div class="table-wrapper">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $i => $b)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $b->kode_inventaris }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $b->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>{{ $b->stok }}</td>
                    <td>
                        <span class="status-badge {{ $b->status_barang == 'Dipinjam' ? 'status-dipinjam' : 'status-tersedia' }}">
                            {{ $b->status_barang ?? 'Tersedia' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Tidak ada data barang</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer-print">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }} | Sistem Inventaris</p>
    </div>
</div>

<style>
    /* Gaya utama – modern, bersih, responsif */
    .inventory-report {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 24px;
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        background: #f8fafc;
    }

    /* Statistik ringkas */
    .stats-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-box {
        flex: 1;
        min-width: 180px;
        background: white;
        border-radius: 24px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #e9edf2;
        transition: 0.2s;
    }
    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.1);
    }
    .stat-icon {
        font-size: 2.2rem;
        background: #eef2ff;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
    }
    .stat-info {
        display: flex;
        flex-direction: column;
    }
    .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #5b6e8c;
    }
    .stat-number {
        font-size: 1.9rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Action bar */
    .action-bar {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .btn-print, .btn-excel {
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-print {
        background: #1e293b;
        color: white;
    }
    .btn-print:hover {
        background: #0f172a;
        transform: translateY(-2px);
    }
    .btn-excel {
        background: #2e7d32;
        color: white;
    }
    .btn-excel:hover {
        background: #1b5e20;
        transform: translateY(-2px);
    }

    /* Tabel modern */
    .table-wrapper {
        background: white;
        border-radius: 24px;
        overflow-x: auto;
        box-shadow: 0 4px 14px rgba(0,0,0,0.02);
        border: 1px solid #edf2f7;
    }
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    .modern-table th {
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        padding: 16px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .modern-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f5;
        color: #334155;
    }
    .modern-table tr:hover td {
        background-color: #fafcff;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-tersedia {
        background: #e0f2e9;
        color: #1e7b48;
    }
    .status-dipinjam {
        background: #ffedea;
        color: #c2410c;
    }
    .footer-print {
        margin-top: 24px;
        text-align: center;
        font-size: 0.75rem;
        color: #8196b1;
        border-top: 1px dashed #cbd5e1;
        padding-top: 20px;
    }

    /* Styling cetak */
    @media print {
        body {
            background: white;
            margin: 0;
            padding: 0.5cm;
        }
        .inventory-report {
            padding: 0;
            background: white;
        }
        .no-print {
            display: none !important;
        }
        .stat-box {
            box-shadow: none;
            border: 1px solid #ccc;
            break-inside: avoid;
        }
        .modern-table th {
            background: #e9ecef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .status-badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .footer-print {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    }

    /* responsif mobile */
    @media (max-width: 720px) {
        .inventory-report {
            padding: 12px;
        }
        .stat-box {
            min-width: 100%;
        }
        .modern-table th, .modern-table td {
            padding: 10px 8px;
            font-size: 0.8rem;
        }
    }
</style>

{{-- Optional: Fontawesome untuk ikon (jika sudah ada di layout) --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@endsection