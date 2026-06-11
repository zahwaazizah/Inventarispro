@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Peminjaman Barang')
@section('page-description', 'History peminjaman dan pengembalian barang')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <h3 style="margin: 0;"><i class="fas fa-history"></i> Riwayat Transaksi</h3>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('riwayat.export.excel') }}" class="btn-excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('riwayat.export.pdf') }}" class="btn-pdf">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('riwayat.filter') }}" class="filter-form">
                <div class="filter-group">
                    <label><i class="fas fa-calendar"></i> Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="filter-input" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar"></i> Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="filter-input" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-search"></i> Cari Barang</label>
                    <input type="text" name="search" placeholder="Kode/Nama Barang..." class="filter-input" value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-filter"></i> Status</label>
                    <select name="status" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    @if(request('dari_tanggal') || request('sampai_tanggal') || request('search') || request('status'))
                        <a href="{{ route('riwayat.index') }}" class="btn-reset">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Riwayat -->
        <div class="table-responsive">
            <table class="table riwayat-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode Transaksi</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Peminjam</th>
                        <th width="70">Jumlah</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th width="100">Status</th>
                        <th width="100">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $index => $transaksi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="kode-transaksi">{{ $transaksi->kode_transaksi ?? '-' }}</span></td>
                        <td>{{ $transaksi->barang->kode_inventaris ?? '-' }}</td>
                        <td><strong>{{ $transaksi->barang->nama_barang ?? '-' }}</strong></td>
                        <td>{{ $transaksi->peminjam ?? '-' }}</td>
                        <td class="text-center">{{ $transaksi->jumlah ?? 1 }}</td>
                        <td>{{ $transaksi->tanggal_pinjam ? date('d/m/Y', strtotime($transaksi->tanggal_pinjam)) : '-' }}</td>
                        <td>
                            @if($transaksi->tanggal_kembali_aktual)
                                {{ date('d/m/Y', strtotime($transaksi->tanggal_kembali_aktual)) }}
                            @elseif($transaksi->tanggal_kembali)
                                {{ date('d/m/Y', strtotime($transaksi->tanggal_kembali)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @php
                                $status = $transaksi->status ?? 'dipinjam';
                                $isTerlambat = ($status == 'dipinjam' && $transaksi->tanggal_kembali && now()->gt($transaksi->tanggal_kembali));
                            @endphp
                            @if($status == 'dikembalikan')
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Dikembalikan</span>
                            @elseif($isTerlambat)
                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                            @else
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $kondisi = $transaksi->kondisi ?? 'baik';
                                $kondisiClass = match($kondisi) {
                                    'baik' => 'badge-info',
                                    'rusak ringan' => 'badge-warning',
                                    'rusak berat' => 'badge-danger',
                                    default => 'badge-secondary'
                                };
                                $kondisiText = match($kondisi) {
                                    'baik' => 'Baik',
                                    'rusak ringan' => 'Rusak Ringan',
                                    'rusak berat' => 'Rusak Berat',
                                    default => ucfirst($kondisi)
                                };
                            @endphp
                            <span class="badge {{ $kondisiClass }}">{{ $kondisiText }}</span>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h4>Belum ada data riwayat transaksi</h4>
                                <p>Belum ada peminjaman atau pengembalian yang tercatat</p>
                             </td
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($riwayats, 'links'))
            <div class="pagination-wrapper">
                {{ $riwayats->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* Button Export */
    .btn-excel, .btn-pdf {
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-excel { background: #10b981; color: white; border: none; }
    .btn-pdf { background: #ef4444; color: white; border: none; }
    .btn-excel:hover, .btn-pdf:hover { transform: translateY(-1px); opacity: 0.9; }

    /* Filter Section */
    .filter-section {
        background: #f8fafc;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 24px;
    }
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }
    .filter-group {
        flex: 1;
        min-width: 140px;
    }
    .filter-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 5px;
    }
    .filter-group label i {
        margin-right: 4px;
        color: #4361ee;
    }
    .filter-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        transition: all 0.2s;
        background: white;
    }
    .filter-input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
    }
    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .btn-filter {
        background: #4361ee;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-filter:hover {
        background: #3a0ca3;
    }
    .btn-reset {
        background: #64748b;
        color: white;
        padding: 8px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-reset:hover {
        background: #475569;
    }

    /* Alert */
    .alert {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
    .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }

    /* Table */
    .riwayat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .riwayat-table th {
        padding: 12px 12px;
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .riwayat-table td {
        padding: 12px 12px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    .riwayat-table tbody tr:hover {
        background: #f8fafc;
    }
    .kode-transaksi {
        font-family: monospace;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
    .text-center {
        text-align: center;
    }

    /* Badge */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-info { background: #e0e7ff; color: #3730a3; }
    .badge-secondary { background: #f1f5f9; color: #475569; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    .empty-state i {
        font-size: 50px;
        color: #cbd5e1;
        margin-bottom: 15px;
        display: block;
    }
    .empty-state h4 {
        font-size: 16px;
        color: #475569;
        margin-bottom: 8px;
    }
    .empty-state p {
        font-size: 13px;
        color: #94a3b8;
        margin: 0;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group {
            width: 100%;
        }
        .filter-actions {
            flex-direction: column;
        }
        .btn-filter, .btn-reset {
            width: 100%;
            justify-content: center;
        }
        .riwayat-table th, .riwayat-table td {
            padding: 8px 6px;
            font-size: 11px;
        }
        .badge {
            padding: 2px 8px;
            font-size: 10px;
        }
        .kode-transaksi {
            font-size: 9px;
            padding: 2px 4px;
        }
    }
</style>
@endsection