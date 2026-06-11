@extends('layouts.app')

@section('title', 'Form Peminjaman')
@section('page-title', 'Form Peminjaman Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-hand-holding"></i> Form Peminjaman Barang</h3>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        <div class="info-barang">
            <h4>Informasi Barang</h4>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Kode Barang:</span>
                    <span class="info-value">{{ $barang->kode_inventaris }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Barang:</span>
                    <span class="info-value">{{ $barang->nama_barang }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Stok Tersedia:</span>
                    <span class="info-value {{ $barang->stok <= 3 ? 'stok-sedikit' : '' }}">
                        {{ $barang->stok }}
                        @if($barang->stok <= 3)
                            <small class="text-warning">(Stok menipis!)</small>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('transaksi.pinjam') }}">
            @csrf
            <input type="hidden" name="barang_id" value="{{ $barang->id }}">
            
            <div class="form-group">
                <label>Nama Peminjam</label>
                <input type="text" name="peminjam" class="form-control" value="{{ old('peminjam') }}" required>
            </div>
            
            <div class="form-group">
                <label>Jumlah <span class="required">*</span></label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" max="{{ $barang->stok }}" required>
                <small class="form-text">Maksimal peminjaman: {{ $barang->stok }} unit</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Kembali (Rencana)</label>
                    <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan jika perlu">{{ old('keterangan') }}</textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Pinjam Barang
                </button>
                <a href="{{ route('qr.scan') }}" class="btn-cancel">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .info-barang {
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .info-barang h4 {
        margin: 0 0 15px;
        font-size: 14px;
        color: #1e293b;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .info-item {
        display: flex;
        gap: 10px;
    }
    
    .info-label {
        font-weight: 600;
        color: #64748b;
        font-size: 13px;
    }
    
    .info-value {
        color: #1e293b;
        font-weight: 500;
    }
    
    .stok-sedikit {
        color: #d97706;
        font-weight: bold;
    }
    
    .text-warning {
        color: #d97706;
        font-size: 11px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #334155;
        font-size: 13px;
    }
    
    .required {
        color: #ef4444;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
    }
    
    .form-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 5px;
        display: block;
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-save {
        background: #10b981;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }
    
    .btn-cancel {
        background: #64748b;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .info-grid, .form-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-save, .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection