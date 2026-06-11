@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang Inventaris')
@section('page-description', 'Informasi lengkap barang inventaris')

@section('content')
<div class="row">
    <!-- Detail Barang Card -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <h3 style="margin: 0; font-size: 1.3rem;"><i class="fas fa-info-circle"></i> Detail Barang</h3>
                <div style="display: flex; gap: 10px;">
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('qr.generate', $barang->id) }}" class="btn-qr">
                            <i class="fas fa-qrcode"></i> Generate QR
                        </a>
                        <a href="{{ route('inventaris.edit', $barang->id) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('inventaris.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <!-- Kolom Kiri -->
                <div class="detail-section">
                    <div class="detail-header">
                        <i class="fas fa-box"></i>
                        <h4>Informasi Barang</h4>
                    </div>
                    <div class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-barcode"></i> Kode Inventaris
                            </div>
                            <div class="detail-value">{{ $barang->kode_inventaris ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-box"></i> Nama Barang
                            </div>
                            <div class="detail-value">{{ $barang->nama_barang ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-tags"></i> Kategori
                            </div>
                            <div class="detail-value">
                                <span class="badge-category">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-map-marker-alt"></i> Lokasi
                            </div>
                            <div class="detail-value">
                                <span class="badge-location">{{ $barang->lokasi->nama_lokasi ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-trademark"></i> Merk
                            </div>
                            <div class="detail-value">{{ $barang->merk ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-hashtag"></i> Serial Number
                            </div>
                            <div class="detail-value">{{ $barang->serial_number ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="detail-section">
                    <div class="detail-header">
                        <i class="fas fa-chart-line"></i>
                        <h4>Status & Stok</h4>
                    </div>
                    <div class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-chart-simple"></i> Status
                            </div>
                            <div class="detail-value">
                                @php
                                    $statusClass = match($barang->status_barang) {
                                        'tersedia' => 'status-tersedia',
                                        'dipinjam' => 'status-dipinjam',
                                        'maintenance' => 'status-maintenance',
                                        'rusak' => 'status-rusak',
                                        'hilang' => 'status-hilang',
                                        default => 'status-default'
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($barang->status_barang ?? 'Tersedia') }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-clipboard-list"></i> Kondisi
                            </div>
                            <div class="detail-value">
                                @php
                                    $kondisiClass = match($barang->kondisi_barang) {
                                        'baik' => 'kondisi-baik',
                                        'rusak ringan' => 'kondisi-ringan',
                                        'rusak berat' => 'kondisi-berat',
                                        default => 'kondisi-default'
                                    };
                                @endphp
                                <span class="kondisi-badge {{ $kondisiClass }}">
                                    {{ ucfirst($barang->kondisi_barang ?? '-') }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-cubes"></i> Stok
                            </div>
                            <div class="detail-value">
                                <span class="stok-number {{ $barang->stok <= 3 ? 'stok-sedikit' : '' }}">
                                    {{ $barang->stok ?? 0 }}
                                </span>
                                <span class="stok-unit">unit</span>
                                @if($barang->stok <= 3 && $barang->stok > 0)
                                    <span class="warning-text">(Stok menipis!)</span>
                                @elseif($barang->stok <= 0)
                                    <span class="warning-text">(Stok habis!)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3 - Info Pembelian -->
                <div class="detail-section">
                    <div class="detail-header">
                        <i class="fas fa-shopping-cart"></i>
                        <h4>Informasi Pembelian</h4>
                    </div>
                    <div class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-calendar-alt"></i> Tahun Pembelian
                            </div>
                            <div class="detail-value">{{ $barang->tahun_pembelian ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-money-bill-wave"></i> Harga Pembelian
                            </div>
                            <div class="detail-value">
                                Rp {{ number_format($barang->harga_pembelian ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-shield-alt"></i> Masa Garansi
                            </div>
                            <div class="detail-value">{{ $barang->masa_garansi ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-landmark"></i> Sumber Dana
                            </div>
                            <div class="detail-value">{{ $barang->sumber_dana ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 4 - Spesifikasi -->
                <div class="detail-section">
                    <div class="detail-header">
                        <i class="fas fa-microchip"></i>
                        <h4>Spesifikasi</h4>
                    </div>
                    <div class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-file-alt"></i> Deskripsi
                            </div>
                            <div class="detail-value">
                                {{ $barang->spesifikasi ?? '-' }}
                            </div>
                        </div>
                        @if($barang->foto && file_exists(public_path('storage/' . $barang->foto)))
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-camera"></i> Foto Barang
                            </div>
                            <div class="detail-value">
                                <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Barang" class="foto-barang">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Card (jika sudah ada) -->
    @if($barang->qr_code_hash)
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 1.3rem;"><i class="fas fa-qrcode"></i> QR Code Barang</h3>
        </div>
        <div class="card-body" style="text-align: center;">
            <div style="display: inline-block; background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div id="qrcode"></div>
            </div>
            <div style="margin-top: 15px;">
                <a href="{{ route('qr.download', $barang->id) }}" class="btn-download">
                    <i class="fas fa-download"></i> Download QR Code
                </a>
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('qr.refresh', $barang->id) }}" class="btn-refresh" onclick="return confirm('Refresh QR akan membuat kode baru. Lanjutkan?')">
                        <i class="fas fa-sync-alt"></i> Refresh QR
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Riwayat Transaksi Card -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 1.3rem;"><i class="fas fa-history"></i> Riwayat Transaksi</h3>
        </div>
        <div class="card-body">
            @if(isset($riwayats) && $riwayats->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Jumlah</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali (Rencana)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayats as $index => $riwayat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $riwayat->peminjam ?? '-' }}</td
                                <td>{{ $riwayat->jumlah ?? 1 }}</td
                                <td>{{ $riwayat->tanggal_pinjam ? date('d/m/Y', strtotime($riwayat->tanggal_pinjam)) : '-' }}</td
                                <td>{{ $riwayat->tanggal_kembali ? date('d/m/Y', strtotime($riwayat->tanggal_kembali)) : '-' }}</td
                                <td>
                                    @php
                                        $status = $riwayat->status ?? 'dipinjam';
                                        $tglKembali = $riwayat->tanggal_kembali ? strtotime($riwayat->tanggal_kembali) : null;
                                        $tglSekarang = strtotime(now());
                                        $isTerlambat = ($status == 'dipinjam' && $tglKembali && $tglSekarang > $tglKembali);
                                    @endphp
                                    
                                    @if($status == 'dikembalikan')
                                        <span class="status-dikembalikan">Dikembalikan</span>
                                    @elseif($isTerlambat)
                                        <span class="status-terlambat">Terlambat</span>
                                    @else
                                        <span class="status-dipinjam">Dipinjam</span>
                                    @endif
                                 </td
                                <td>
                                    @if($status == 'dipinjam')
                                        <a href="{{ route('transaksi.kembali.form', $riwayat->id) }}" class="btn-kembali">
                                            <i class="fas fa-undo-alt"></i> Kembali
                                        </a>
                                    @else
                                        <span class="sudah-kembali">-</span>
                                    @endif
                                 </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(method_exists($riwayats, 'links'))
                    <div class="pagination-wrapper">
                        {{ $riwayats->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>Belum Ada Riwayat Transaksi</h4>
                    <p>Barang ini belum pernah dipinjam</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Card */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
        overflow: hidden;
    }
    
    .card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* Buttons */
    .btn-qr, .btn-edit, .btn-back, .btn-download, .btn-refresh {
        padding: 9px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-qr { background: #10b981; color: white; }
    .btn-edit { background: #f59e0b; color: white; }
    .btn-back { background: #64748b; color: white; }
    .btn-download { background: #6366f1; color: white; }
    .btn-refresh { background: #f59e0b; color: white; }
    
    .btn-qr:hover, .btn-edit:hover, .btn-back:hover, .btn-download:hover, .btn-refresh:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
    
    /* Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    
    .detail-section {
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .detail-header {
        padding: 14px 18px;
        background: white;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .detail-header i {
        font-size: 20px;
        color: #4361ee;
    }
    
    .detail-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
    }
    
    .detail-list {
        padding: 18px;
    }
    
    .detail-item {
        display: flex;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .detail-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .detail-label {
        width: 150px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .detail-label i {
        font-size: 14px;
        width: 18px;
    }
    
    .detail-value {
        flex: 1;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }
    
    /* Badges */
    .badge-category, .badge-location {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .badge-category {
        background: #e0e7ff;
        color: #3730a3;
    }
    
    .badge-location {
        background: #d1fae5;
        color: #065f46;
    }
    
    /* Status Badges */
    .status-badge, .kondisi-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        font-weight: 500;
    }
    
    .status-tersedia { background: #d1fae5; color: #065f46; }
    .status-dipinjam { background: #fef3c7; color: #92400e; }
    .status-maintenance { background: #e0e7ff; color: #3730a3; }
    .status-rusak { background: #fee2e2; color: #991b1b; }
    .status-hilang { background: #f1f5f9; color: #475569; }
    .status-default { background: #f1f5f9; color: #475569; }
    
    .kondisi-baik { background: #d1fae5; color: #065f46; }
    .kondisi-ringan { background: #fef3c7; color: #92400e; }
    .kondisi-berat { background: #fee2e2; color: #991b1b; }
    .kondisi-default { background: #f1f5f9; color: #475569; }
    
    /* Stok */
    .stok-number {
        font-weight: 700;
        font-size: 18px;
    }
    
    .stok-sedikit {
        color: #d97706;
    }
    
    .stok-unit {
        margin-left: 5px;
        font-size: 13px;
        color: #64748b;
    }
    
    .warning-text {
        font-size: 12px;
        color: #ef4444;
        margin-left: 8px;
    }
    
    /* Foto */
    .foto-barang {
        max-width: 150px;
        max-height: 150px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 5px;
    }
    
    /* Table */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    
    .table th {
        padding: 12px 15px;
        text-align: left;
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        font-size: 13px;
    }
    
    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 13px;
    }
    
    .table tr:hover {
        background: #f8fafc;
    }
    
    /* Status di tabel */
    .status-dipinjam {
        background: #fef3c7;
        color: #92400e;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .status-dikembalikan {
        background: #d1fae5;
        color: #065f46;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .status-terlambat {
        background: #fee2e2;
        color: #991b1b;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .btn-kembali {
        background: #f59e0b;
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        display: inline-block;
    }
    
    .sudah-kembali {
        color: #10b981;
        font-size: 13px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px;
    }
    
    .empty-state i {
        font-size: 60px;
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
        font-size: 14px;
        color: #94a3b8;
    }
    
    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }
    
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .detail-item {
            flex-direction: column;
            gap: 8px;
        }
        
        .detail-label {
            width: 100%;
        }
        
        .foto-barang {
            max-width: 100%;
        }
        
        .btn-qr, .btn-edit, .btn-back {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    @if($barang->qr_code_hash)
        var qrcodeContainer = document.getElementById("qrcode");
        if(qrcodeContainer) {
            new QRCode(qrcodeContainer, {
                text: "{{ route('public.scan', $barang->qr_code_hash) }}",
                width: 160,
                height: 160,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    @endif
</script>
@endpush