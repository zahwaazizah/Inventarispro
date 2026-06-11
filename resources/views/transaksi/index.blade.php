@extends('layouts.app')

@section('title', 'Peminjaman Aktif')
@section('page-title', 'Data Peminjaman Barang')
@section('page-description', 'Kelola peminjaman dan pengembalian barang')

@section('content')

<div class="loan-list-wrapper">

    <div class="loan-list-header">
        <div>
            <h4 class="loan-list-title">Data Peminjaman Barang</h4>
            <p class="loan-list-subtitle">Daftar barang yang sedang dipinjam dan belum dikembalikan.</p>
        </div>

        <a href="{{ route('qr.scan') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-qrcode me-1"></i> Scan QR untuk Pinjam
        </a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table loan-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Transaksi</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Peminjam</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Tgl Pinjam</th>
                        <th class="text-center">Batas Kembali</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transaksis as $index => $transaksi)
                    @php
                        $isTerlambat = $transaksi->tanggal_kembali && now()->gt($transaksi->tanggal_kembali);
                    @endphp

                    <tr>
                        <td class="text-center text-muted">{{ $index + 1 }}</td>

                        <td>
                            <span class="code-badge">
                                {{ $transaksi->kode_transaksi ?? '-' }}
                            </span>
                        </td>

                        <td>
                            {{ $transaksi->barang->kode_inventaris ?? '-' }}
                        </td>

                        <td>
                            <div class="item-name">
                                {{ $transaksi->barang->nama_barang ?? '-' }}
                            </div>
                        </td>

                        <td>
                            {{ $transaksi->peminjam ?? '-' }}
                        </td>

                        <td class="text-center">
                            <span class="qty-badge">
                                {{ $transaksi->jumlah ?? 1 }}
                            </span>
                        </td>

                        <td class="text-center">
                            {{ $transaksi->tanggal_pinjam ? date('d/m/Y', strtotime($transaksi->tanggal_pinjam)) : '-' }}
                        </td>

                        <td class="text-center">
                            <div class="{{ $isTerlambat ? 'text-danger fw-bold' : '' }}">
                                {{ $transaksi->tanggal_kembali ? date('d/m/Y', strtotime($transaksi->tanggal_kembali)) : '-' }}
                            </div>
                        </td>

                        <td class="text-center">
                            @if($isTerlambat)
                                <span class="status-badge status-late">Terlambat</span>
                            @else
                                <span class="status-badge status-borrowed">Dipinjam</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('transaksi.kembali.form', $transaksi->id) }}" 
                               class="btn-return">
                                <i class="fas fa-undo-alt me-1"></i> Kembali
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada peminjaman aktif.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if(!$transaksis->isEmpty())
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end">Total Barang Dipinjam</td>
                        <td class="text-center">
                            <span class="total-badge">
                                {{ $transaksis->sum('jumlah') }}
                            </span>
                        </td>
                        <td colspan="4">Unit</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .loan-list-wrapper {
        background: #f8fafc;
        padding: 22px;
        border-radius: 14px;
    }

    .loan-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .loan-list-title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
    }

    .loan-list-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .loan-table {
        min-width: 1050px;
        font-size: 14px;
    }

    .loan-table thead th {
        background: #f3f4f6;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .loan-table tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #374151;
        vertical-align: middle;
    }

    .loan-table tbody tr:hover {
        background: #f9fafb;
    }

    .code-badge {
        background: #eef2ff;
        color: #3730a3;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .item-name {
        font-weight: 600;
        color: #111827;
        max-width: 260px;
        word-break: break-word;
    }

    .qty-badge {
        background: #f3f4f6;
        color: #111827;
        padding: 5px 10px;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
        min-width: 36px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-borrowed {
        background: #fef3c7;
        color: #92400e;
    }

    .status-late {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-return {
        background: #dcfce7;
        color: #166534;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .btn-return:hover {
        background: #bbf7d0;
        color: #14532d;
    }

    .total-badge {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 800;
    }

    .loan-table tfoot td {
        background: #f9fafb;
        padding: 14px 12px;
        font-weight: 700;
        color: #374151;
        border-top: 1px solid #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px !important;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 42px;
        margin-bottom: 10px;
        display: block;
        color: #9ca3af;
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .loan-list-wrapper {
            padding: 14px;
        }

        .loan-list-title {
            font-size: 19px;
        }

        .loan-list-header .btn {
            width: 100%;
        }

        .loan-table {
            font-size: 13px;
        }
    }
</style>
@endpush