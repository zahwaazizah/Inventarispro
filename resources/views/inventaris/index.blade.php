@extends('layouts.app')

@section('title', 'Data Inventaris')
@section('page-title', 'Data Inventaris')
@section('page-description', 'Kelola data barang inventaris perusahaan')

@section('content')

<div class="inventory-wrapper">

    {{-- Header --}}
    <div class="inventory-header">
        <div>
            <h4 class="inventory-title">Data Inventaris Barang</h4>
            <p class="inventory-subtitle">Daftar seluruh barang inventaris yang terdaftar dalam sistem</p>
        </div>

        @if(Auth::user()->role == 'admin')
        <div class="inventory-actions">
            <a href="{{ route('inventaris.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Barang
            </a>
            <a href="{{ route('qr.index') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-qrcode me-1"></i> Kelola QR
            </a>
        </div>
        @endif
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('inventaris.index') }}">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control"
                            placeholder="Cari kode barang atau nama barang..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-dark btn-sm px-3">
                        Cari
                    </button>
                </div>

                @if(request('search'))
                <div class="col-md-auto">
                    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                        Reset
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table inventory-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">QR</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($barangs as $index => $barang)
                    <tr>
                        <td class="text-center text-muted">
                            {{ $barangs->firstItem() + $index }}
                        </td>

                        <td>
                            <span class="code-badge">
                                {{ $barang->kode_inventaris }}
                            </span>
                        </td>

                        <td>
                            <div class="item-name">
                                {{ $barang->nama_barang }}
                            </div>
                        </td>

                        <td>
                            {{ $barang->kategori->nama_kategori ?? '-' }}
                        </td>

                        <td>
                            {{ $barang->lokasi->nama_lokasi ?? '-' }}
                        </td>

                        <td class="text-center">
                            <span class="stock-badge">
                                {{ $barang->stok }}
                            </span>
                        </td>

                        <td class="text-center">
                            @if($barang->status_barang == 'tersedia')
                                <span class="status-badge status-available">Tersedia</span>
                            @elseif($barang->status_barang == 'maintenance')
                                <span class="status-badge status-maintenance">Maintenance</span>
                            @elseif($barang->status_barang == 'rusak')
                                <span class="status-badge status-broken">Rusak</span>
                            @elseif($barang->status_barang == 'hilang')
                                <span class="status-badge status-lost">Hilang</span>
                            @else
                                <span class="status-badge status-lost">
                                    {{ ucfirst($barang->status_barang) }}
                                </span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($barang->qr_code_hash)
                                <i class="fas fa-check-circle text-success" title="QR tersedia"></i>
                            @else
                                <i class="fas fa-times-circle text-danger" title="Belum ada QR"></i>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('inventaris.show', $barang->id) }}" 
                                   class="btn-action btn-view" 
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->role == 'admin')
                                <a href="{{ route('inventaris.edit', $barang->id) }}" 
                                   class="btn-action btn-edit" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('inventaris.destroy', $barang->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" 
                                            class="btn-action btn-delete" 
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada data barang inventaris.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($barangs, 'links'))
        <div class="pagination-wrapper">
            {{ $barangs->links() }}
        </div>
        @endif
    </div>

</div>

@endsection

@push('styles')
<style>
    .inventory-wrapper {
        background: #f8fafc;
        padding: 22px;
        border-radius: 14px;
    }

    .inventory-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .inventory-title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
    }

    .inventory-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .inventory-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-card {
        background: #ffffff;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-bottom: 16px;
    }

    .table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .inventory-table {
        min-width: 1000px;
        font-size: 14px;
    }

    .inventory-table thead th {
        background: #f3f4f6;
        color: #374151;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .inventory-table tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #374151;
        vertical-align: middle;
    }

    .inventory-table tbody tr:hover {
        background: #f9fafb;
    }

    .item-name {
        font-weight: 600;
        color: #111827;
        max-width: 260px;
        white-space: normal;
        word-break: break-word;
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

    .stock-badge {
        background: #f3f4f6;
        color: #111827;
        padding: 5px 10px;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
        min-width: 38px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-available {
        background: #dcfce7;
        color: #166534;
    }

    .status-maintenance {
        background: #fef3c7;
        color: #92400e;
    }

    .status-broken {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-lost {
        background: #e5e7eb;
        color: #374151;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        font-size: 13px;
        cursor: pointer;
    }

    .btn-view {
        background: #e0f2fe;
        color: #0369a1;
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-action:hover {
        opacity: 0.85;
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

    .pagination-wrapper {
        padding: 16px;
        display: flex;
        justify-content: center;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 768px) {
        .inventory-wrapper {
            padding: 14px;
        }

        .inventory-title {
            font-size: 19px;
        }

        .inventory-actions {
            width: 100%;
        }

        .inventory-actions .btn {
            flex: 1;
        }
    }
</style>
@endpush