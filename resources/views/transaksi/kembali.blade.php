@extends('layouts.app')

@section('title', 'Form Pengembalian')
@section('page-title', 'Form Pengembalian Barang')
@section('page-description', 'Proses pengembalian barang yang dipinjam')

@section('content')
<div class="row">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h3 class="mb-0 fw-semibold">
                <i class="fas fa-undo-alt me-2 text-primary"></i> Form Pengembalian Barang
            </h3>
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Informasi Peminjaman -->
            <div class="info-card mb-4">
                <div class="info-card-header">
                    <i class="fas fa-receipt"></i>
                    <h5 class="mb-0">Detail Peminjaman</h5>
                </div>
                <div class="info-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Kode Transaksi</span>
                                <span class="info-value">{{ $transaksi->kode_transaksi ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Peminjam</span>
                                <span class="info-value">{{ $transaksi->peminjam ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Barang</span>
                                <span class="info-value">{{ $transaksi->barang->nama_barang ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Kode Barang</span>
                                <span class="info-value">{{ $transaksi->barang->kode_inventaris ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Jumlah</span>
                                <span class="info-value">{{ $transaksi->jumlah ?? 1 }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tanggal Pinjam</span>
                                <span class="info-value">{{ $transaksi->tanggal_pinjam ? date('d/m/Y', strtotime($transaksi->tanggal_pinjam)) : '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Batas Kembali</span>
                                <span class="info-value {{ $transaksi->tanggal_kembali && now()->gt($transaksi->tanggal_kembali) ? 'text-danger fw-bold' : '' }}">
                                    {{ $transaksi->tanggal_kembali ? date('d/m/Y', strtotime($transaksi->tanggal_kembali)) : '-' }}
                                    @if($transaksi->tanggal_kembali && now()->gt($transaksi->tanggal_kembali))
                                        <span class="badge-terlambat ms-2">Terlambat</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pengembalian -->
            <form method="POST" action="{{ route('transaksi.kembali', $transaksi->id) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar-day me-1 text-primary"></i> Tanggal Kembali Aktual <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_aktual" class="form-control" value="{{ date('Y-m-d') }}" required>
                            @error('tanggal_kembali_aktual')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clipboard-list me-1 text-primary"></i> Kondisi Barang</label>
                            <select name="kondisi" class="form-select">
                                <option value="baik">👍 Baik</option>
                                <option value="rusak ringan">⚠️ Rusak Ringan</option>
                                <option value="rusak berat">💔 Rusak Berat</option>
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="form-label"><i class="fas fa-file-alt me-1 text-primary"></i> Keterangan Pengembalian</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan jika ada kerusakan atau hal penting lainnya..."></textarea>
                    @error('keterangan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                        <i class="fas fa-check-circle me-1"></i> Konfirmasi Pengembalian
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-danger px-4 py-2 rounded-pill">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .info-card {
        background: #f8fafc;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #eef2f6;
    }
    .info-card-header {
        background: white;
        padding: 16px 20px;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-card-header i {
        font-size: 1.2rem;
        color: #4361ee;
    }
    .info-card-header h5 {
        font-weight: 600;
    }
    .info-card-body {
        padding: 20px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 8px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
    }
    .info-value {
        font-weight: 500;
        color: #1e293b;
        font-size: 0.9rem;
    }
    .badge-terlambat {
        background: #ef4444;
        color: white;
        padding: 2px 10px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        transition: 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
        outline: none;
    }
    .btn {
        font-weight: 500;
    }
    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        .d-flex {
            flex-direction: column;
        }
        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection