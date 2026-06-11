@extends('layouts.app')

@section('title', 'Kelola QR Code')
@section('page-title', 'Kelola QR Code')
@section('page-description', 'Generate, unduh, dan kelola QR Code barang inventaris')

@section('content')
<div class="qr-container">
    <!-- Statistik Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $barangs->whereNotNull('qr_code_hash')->count() }}</h3>
                    <p>QR Tergenerate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $barangs->total() }}</h3>
                    <p>Total Barang</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $barangs->whereNull('qr_code_hash')->count() }}</h3>
                    <p>Belum Generate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $barangs->whereNotNull('qr_code_hash')->count() }}</h3>
                    <p>Siap Download</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview QR Code Card -->
    @if(isset($selected_barang) && $selected_barang)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-eye me-2 text-primary"></i> Preview QR Code
            </h5>
            <a href="{{ route('qr.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="fas fa-times"></i> Tutup
            </a>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <div class="qr-preview-box p-3 bg-light rounded-4 d-inline-block">
                        <div id="qrcode"></div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="qr-details">
                        <h4 class="mb-2">{{ $selected_barang->nama_barang }}</h4>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <small class="text-muted">Kode Inventaris</small>
                                <p class="fw-semibold mb-1">{{ $selected_barang->kode_inventaris }}</p>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Kategori</small>
                                <p class="fw-semibold mb-1">{{ $selected_barang->kategori->nama_kategori ?? '-' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Lokasi</small>
                                <p class="fw-semibold mb-1">{{ $selected_barang->lokasi->nama_lokasi ?? '-' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Stok</small>
                                <p class="fw-semibold mb-1">{{ $selected_barang->stok }} unit</p>
                            </div>
                        </div>
                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button id="btnDownload" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-download me-1"></i> Download QR
                            </button>
                            <a href="{{ route('qr.refresh', $selected_barang->id) }}" class="btn btn-warning rounded-pill px-4" onclick="return confirm('Refresh QR akan membuat kode baru. Lanjutkan?')">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </a>
                            <a href="{{ route('qr.destroy', $selected_barang->id) }}" class="btn btn-danger rounded-pill px-4" onclick="return confirm('Hapus QR Code untuk barang {{ $selected_barang->nama_barang }}?')">
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-qrcode fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada QR Code dipilih</h5>
            <p class="text-muted">Pilih barang dari daftar untuk melihat preview QR Code</p>
        </div>
    </div>
    @endif

    <!-- Daftar Barang -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-list me-2 text-primary"></i> Daftar Barang
            </h5>
            <form method="GET" action="{{ route('qr.index') }}" class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 280px;">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama barang..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
                @if(request('search'))
                    <a href="{{ route('qr.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" width="50">No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>QR Status</th>
                            <th class="text-center" width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $index => $barang)
                        <tr class="{{ $selected_barang && $selected_barang->id == $barang->id ? 'table-active' : '' }}">
                            <td class="ps-3">{{ $barangs->firstItem() + $index }} </td
                            <td><span class="fw-semibold">{{ $barang->kode_inventaris }}</span></td
                            <td>{{ $barang->nama_barang }}</td
                            <td><span class="badge bg-light text-dark border">{{ $barang->kategori->nama_kategori ?? '-' }}</span></td
                            <td>{{ $barang->stok }}</td
                            <td>
                                @if($barang->qr_code_hash)
                                    <span class="badge bg-success rounded-pill"><i class="fas fa-check-circle me-1"></i> Ada QR</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill"><i class="fas fa-times-circle me-1"></i> Belum</span>
                                @endif
                            </td
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    @if($barang->qr_code_hash)
                                        <a href="{{ route('qr.index', ['id' => $barang->id]) }}" class="btn btn-sm btn-outline-info rounded-circle" style="width: 32px; height: 32px;" title="Lihat QR">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('qr.download', $barang->id) }}" class="btn btn-sm btn-outline-success rounded-circle" style="width: 32px; height: 32px;" title="Download QR">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('qr.refresh', $barang->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" style="width: 32px; height: 32px;" title="Refresh QR" onclick="return confirm('Refresh QR? Kode akan berubah.')">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('qr.generate', $barang->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="fas fa-qrcode me-1"></i> Generate
                                        </a>
                                    @endif
                                </div>
                            </td
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                <p>Belum ada data barang.</p>
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('inventaris.create') }}" class="btn btn-primary rounded-pill">Tambah Barang</a>
                                @endif
                            </td
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($barangs->hasPages())
            <div class="card-footer bg-white border-0 d-flex justify-content-center py-3">
                {{ $barangs->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* Stats Card */
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .stats-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: white;
    }
    .stats-icon.bg-primary { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
    .stats-icon.bg-success { background: linear-gradient(135deg, #10b981, #059669); }
    .stats-icon.bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stats-icon.bg-info { background: linear-gradient(135deg, #0dcaf0, #0bb5d8); }
    .stats-info h3 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
    }
    .stats-info p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }

    /* QR Preview */
    .qr-preview-box {
        background: white;
        border-radius: 24px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    #qrcode canvas {
        width: 160px !important;
        height: 160px !important;
    }

    /* Table */
    .table th, .table td {
        padding: 14px 12px;
        vertical-align: middle;
    }
    .table-active {
        background-color: #eef2ff !important;
    }

    /* Buttons */
    .btn-outline-info, .btn-outline-success, .btn-outline-warning {
        transition: all 0.2s;
    }
    .btn-outline-info:hover {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: white;
    }
    .btn-outline-success:hover {
        background-color: #10b981;
        border-color: #10b981;
        color: white;
    }
    .btn-outline-warning:hover {
        background-color: #f59e0b;
        border-color: #f59e0b;
        color: white;
    }

    @media (max-width: 768px) {
        .stats-card {
            padding: 12px;
        }
        .stats-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }
        .stats-info h3 {
            font-size: 22px;
        }
        .table th, .table td {
            white-space: nowrap;
        }
        .input-group {
            width: 100% !important;
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