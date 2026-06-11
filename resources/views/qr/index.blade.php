@extends('layouts.app')

@section('title', 'Kelola QR Code')
@section('page-title', 'Kelola QR Code')
@section('page-description', 'Generate, unduh, dan kelola QR Code barang inventaris')

@section('content')
<div class="qr-container">
    <!-- Statistik Cards -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon bg-primary"><i class="fas fa-qrcode"></i></div>
            <div class="stat-text">
                <span class="stat-number">{{ $barangs->whereNotNull('qr_code_hash')->count() }}</span>
                <span class="stat-label">QR Tergenerate</span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon bg-success"><i class="fas fa-boxes"></i></div>
            <div class="stat-text">
                <span class="stat-number">{{ $barangs->total() }}</span>
                <span class="stat-label">Total Barang</span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon bg-warning"><i class="fas fa-spinner"></i></div>
            <div class="stat-text">
                <span class="stat-number">{{ $barangs->whereNull('qr_code_hash')->count() }}</span>
                <span class="stat-label">Belum Generate</span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon bg-info"><i class="fas fa-download"></i></div>
            <div class="stat-text">
                <span class="stat-number">{{ $barangs->whereNotNull('qr_code_hash')->count() }}</span>
                <span class="stat-label">Siap Download</span>
            </div>
        </div>
    </div>

    <!-- Preview QR (jika ada selected) -->
    @if(isset($selected_barang) && $selected_barang)
    <div class="preview-card">
        <div class="preview-header">
            <h4><i class="fas fa-eye"></i> Preview QR Code</h4>
            <a href="{{ route('qr.index') }}" class="preview-close">&times;</a>
        </div>
        <div class="preview-body">
            <div class="qr-box" id="qrcode"></div>
            <div class="info-box">
                <h5>{{ $selected_barang->nama_barang }}</h5>
                <p><strong>Kode:</strong> {{ $selected_barang->kode_inventaris }}</p>
                <p><strong>Kategori:</strong> {{ $selected_barang->kategori->nama_kategori ?? '-' }}</p>
                <p><strong>Lokasi:</strong> {{ $selected_barang->lokasi->nama_lokasi ?? '-' }}</p>
                <p><strong>Stok:</strong> {{ $selected_barang->stok }}</p>
                <div class="action-group">
                    <button id="btnDownload" class="btn-download">Download QR</button>
                    <a href="{{ route('qr.refresh', $selected_barang->id) }}" class="btn-refresh">Refresh</a>
                    <a href="{{ route('qr.destroy', $selected_barang->id) }}" class="btn-delete">Hapus</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="empty-preview">
        <i class="fas fa-qrcode"></i>
        <p>Pilih barang dari daftar untuk melihat QR Code</p>
    </div>
    @endif

    <!-- Daftar Barang -->
    <div class="data-card">
        <div class="card-toolbar">
            <h3><i class="fas fa-list"></i> Daftar Barang</h3>
            <form method="GET" action="{{ route('qr.index') }}" class="search-form">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Cari kode atau nama..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn-search">Cari</button>
                @if(request('search'))
                    <a href="{{ route('qr.index') }}" class="btn-reset">Reset</a>
                @endif
            </form>
        </div>
        <div class="table-wrapper">
            <table class="qr-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th width="70">Stok</th>
                        <th width="100">QR Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $barang)
                    <tr class="{{ $selected_barang && $selected_barang->id == $barang->id ? 'row-selected' : '' }}">
                        <td class="text-center">{{ $barangs->firstItem() + $index }} </td>
                        <td><strong>{{ $barang->kode_inventaris }}</strong></td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                        <td class="text-center">{{ $barang->stok }}</td>
                        <td class="text-center">
                            @if($barang->qr_code_hash)
                                <span class="badge-ok"><i class="fas fa-check-circle"></i> Ada QR</span>
                            @else
                                <span class="badge-no"><i class="fas fa-times-circle"></i> Belum</span>
                            @endif
                        </td>
                        <td class="action-buttons">
                            @if($barang->qr_code_hash)
                                <a href="{{ route('qr.index', ['id' => $barang->id]) }}" class="icon-btn view" title="Lihat QR"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('qr.download', $barang->id) }}" class="icon-btn download" title="Download QR"><i class="fas fa-download"></i></a>
                                <a href="{{ route('qr.refresh', $barang->id) }}" class="icon-btn refresh" title="Refresh QR" onclick="return confirm('Refresh QR?')"><i class="fas fa-sync-alt"></i></a>
                            @else
                                <a href="{{ route('qr.generate', $barang->id) }}" class="btn-generate">Generate QR</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-row">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada data barang</p>
                            @if(Auth::user()->role == 'admin')
                                <a href="{{ route('inventaris.create') }}" class="btn-add">Tambah Barang</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($barangs, 'links'))
            <div class="pagination-box">
                {{ $barangs->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* Reset container */
    .qr-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 4px;
    }
    /* Stats */
    .stats-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-box {
        flex: 1;
        min-width: 180px;
        background: white;
        border-radius: 20px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: 0.2s;
    }
    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    .bg-primary { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
    .bg-success { background: linear-gradient(135deg, #10b981, #059669); }
    .bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .bg-info { background: linear-gradient(135deg, #0dcaf0, #0bb5d8); }
    .stat-text {
        display: flex;
        flex-direction: column;
    }
    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 13px;
        color: #64748b;
    }
    /* Preview */
    .preview-card {
        background: white;
        border-radius: 24px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #eef2f6;
    }
    .preview-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .preview-close {
        font-size: 28px;
        line-height: 1;
        text-decoration: none;
        color: #94a3b8;
    }
    .preview-close:hover { color: #ef4444; }
    .preview-body {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        padding: 24px;
        align-items: center;
    }
    .qr-box {
        background: #f8fafc;
        padding: 20px;
        border-radius: 20px;
        display: inline-flex;
    }
    #qrcode canvas {
        width: 160px !important;
        height: 160px !important;
    }
    .info-box h5 {
        margin: 0 0 8px;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .info-box p {
        margin: 6px 0;
        font-size: 0.9rem;
    }
    .action-group {
        display: flex;
        gap: 12px;
        margin-top: 16px;
        flex-wrap: wrap;
    }
    .btn-download, .btn-refresh, .btn-delete {
        padding: 6px 16px;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-download { background: #10b981; color: white; }
    .btn-refresh { background: #f59e0b; color: white; }
    .btn-delete { background: #ef4444; color: white; }
    .btn-download:hover, .btn-refresh:hover, .btn-delete:hover {
        filter: brightness(0.95);
        transform: translateY(-1px);
    }
    .empty-preview {
        background: white;
        border-radius: 24px;
        text-align: center;
        padding: 40px;
        margin-bottom: 30px;
        color: #94a3b8;
    }
    .empty-preview i {
        font-size: 48px;
        margin-bottom: 12px;
        display: block;
    }
    /* Data Table */
    .data-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .card-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding: 16px 24px;
        border-bottom: 1px solid #eef2f6;
    }
    .card-toolbar h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .search-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
    }
    .search-wrapper i {
        position: absolute;
        left: 12px;
        color: #94a3b8;
    }
    .search-wrapper input {
        padding: 8px 12px 8px 36px;
        border: 1px solid #e2e8f0;
        border-radius: 30px;
        width: 240px;
        font-size: 0.85rem;
    }
    .btn-search {
        background: #4361ee;
        border: none;
        color: white;
        padding: 8px 18px;
        border-radius: 30px;
        cursor: pointer;
    }
    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        padding: 8px 18px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.85rem;
    }
    .table-wrapper {
        overflow-x: auto;
    }
    .qr-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    .qr-table th, .qr-table td {
        padding: 14px 12px;
        text-align: left;
        border-bottom: 1px solid #eef2f6;
        vertical-align: middle;
    }
    .qr-table th {
        background: #fafcff;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
    }
    .text-center {
        text-align: center;
    }
    .badge-ok, .badge-no {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .badge-ok {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-no {
        background: #fee2e2;
        color: #991b1b;
    }
    .action-buttons {
        white-space: nowrap;
    }
    .icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        margin: 0 3px;
        text-decoration: none;
        transition: 0.2s;
    }
    .icon-btn.view { background: #e0f2fe; color: #0284c7; }
    .icon-btn.download { background: #d1fae5; color: #059669; }
    .icon-btn.refresh { background: #fef3c7; color: #d97706; }
    .icon-btn:hover { filter: brightness(0.92); transform: scale(1.02); }
    .btn-generate {
        background: #4361ee;
        color: white;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
    }
    .btn-generate:hover {
        background: #3a0ca3;
    }
    .row-selected {
        background-color: #eef2ff;
    }
    .empty-row {
        text-align: center;
        padding: 40px !important;
    }
    .empty-row i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }
    .btn-add {
        background: #4361ee;
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
    }
    .pagination-box {
        padding: 16px 24px;
        display: flex;
        justify-content: center;
        border-top: 1px solid #eef2f6;
    }
    @media (max-width: 768px) {
        .stats-row {
            gap: 12px;
        }
        .stat-box {
            min-width: calc(50% - 12px);
            flex: 1 1 calc(50% - 12px);
        }
        .preview-body {
            flex-direction: column;
            text-align: center;
        }
        .card-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        .search-wrapper input {
            width: 100%;
        }
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($selected_barang) && $selected_barang && $selected_barang->qr_code_hash)
        var qrcodeContainer = document.getElementById("qrcode");
        if(qrcodeContainer) {
            qrcodeContainer.innerHTML = "";
            new QRCode(qrcodeContainer, {
                text: "{{ route('public.scan', $selected_barang->qr_code_hash) }}",
                width: 160,
                height: 160,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
        var btnDownload = document.getElementById("btnDownload");
        if(btnDownload) {
            btnDownload.addEventListener("click", function() {
                var canvas = document.querySelector("#qrcode canvas");
                if(canvas) {
                    var link = document.createElement("a");
                    link.download = "QR_{{ $selected_barang->kode_inventaris }}.png";
                    link.href = canvas.toDataURL();
                    link.click();
                } else {
                    alert("QR Code belum siap.");
                }
            });
        }
    @endif
});
</script>
@endpush