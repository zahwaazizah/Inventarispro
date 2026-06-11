@extends('layouts.app')

@section('title', 'Form Peminjaman')
@section('page-title', 'Form Peminjaman Barang')
@section('page-description', 'Isi form untuk meminjam barang')

@section('content')
<div class="row">
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <h3><i class="fas fa-hand-holding"></i> Form Peminjaman Barang</h3>
                <a href="{{ route('qr.scan') }}" class="btn-scan">
                    <i class="fas fa-camera"></i> Scan QR Code
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('transaksi.pinjam') }}" id="formPeminjaman">
                @csrf
                
                @if(isset($barangFromQR) && $barangFromQR)
                    <input type="hidden" name="barang_id" value="{{ $barangFromQR->id }}">
                    <div class="qr-info">
                        <div class="qr-info-header">
                            <i class="fas fa-qrcode"></i>
                            <h4>✅ Barang dari Scan QR</h4>
                        </div>
                        <div class="qr-info-content">
                            <div class="qr-info-item">
                                <span class="label">Kode Barang:</span>
                                <span class="value">{{ $barangFromQR->kode_inventaris }}</span>
                            </div>
                            <div class="qr-info-item">
                                <span class="label">Nama Barang:</span>
                                <span class="value">{{ $barangFromQR->nama_barang }}</span>
                            </div>
                            <div class="qr-info-item">
                                <span class="label">Stok Tersedia:</span>
                                <span class="value {{ $barangFromQR->stok <= 3 ? 'stok-sedikit' : '' }}">
                                    {{ $barangFromQR->stok }} unit
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label><i class="fas fa-box"></i> Pilih Barang <span class="required">*</span></label>
                        <select name="barang_id" id="barang_id" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @if(isset($barangs) && count($barangs) > 0)
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" data-kode="{{ $barang->kode_inventaris }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->kode_inventaris }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>Belum ada barang tersedia</option>
                            @endif
                        </select>
                        @error('barang_id')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="info-barang" id="infoBarang" style="display: none;">
                        <div class="info-header">
                            <i class="fas fa-info-circle"></i>
                            <h4>Informasi Barang</h4>
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Kode Barang:</span>
                                <span class="info-value" id="infoKode">-</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Stok Tersedia:</span>
                                <span class="info-value" id="infoStok">-</span>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nama Peminjam <span class="required">*</span></label>
                    <input type="text" name="peminjam" class="form-control" value="{{ old('peminjam') }}" required placeholder="Masukkan nama peminjam">
                    @error('peminjam')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-cubes"></i> Jumlah <span class="required">*</span></label>
                        @if(isset($barangFromQR) && $barangFromQR)
                            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" max="{{ $barangFromQR->stok }}" required>
                            <small class="form-text">Maksimal peminjaman: {{ $barangFromQR->stok }} unit</small>
                        @else
                            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
                            <small class="form-text" id="maxStokText">Maksimal peminjaman: - unit</small>
                        @endif
                        @error('jumlah')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Tanggal Pinjam <span class="required">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        @error('tanggal_pinjam')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-check"></i> Tanggal Kembali (Rencana) <span class="required">*</span></label>
                    <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}" required>
                    @error('tanggal_kembali')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-file-alt"></i> Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan jika perlu">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-pinjam">
                        <i class="fas fa-save"></i> Pinjam Barang
                    </button>
                    <button type="reset" class="btn-reset" onclick="resetForm()">
                        <i class="fas fa-undo-alt"></i> Reset
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Lihat Peminjaman
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-scan {
        background: #4361ee;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .qr-info {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
    }
    
    .qr-info-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #a7f3d0;
    }
    
    .qr-info-header i {
        font-size: 20px;
        color: #065f46;
    }
    
    .qr-info-header h4 {
        margin: 0;
        font-size: 14px;
        color: #065f46;
    }
    
    .qr-info-content {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .qr-info-item {
        flex: 1;
        min-width: 150px;
    }
    
    .qr-info-item .label {
        font-size: 11px;
        color: #065f46;
        display: block;
        margin-bottom: 3px;
    }
    
    .qr-info-item .value {
        font-size: 14px;
        font-weight: 600;
        color: #064e3b;
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #334155;
        font-size: 13px;
    }
    
    .form-group label i {
        width: 18px;
        color: #4361ee;
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
        transition: all 0.2s;
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
    
    .error-text {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
    
    .info-barang {
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .info-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-header i {
        font-size: 18px;
        color: #4361ee;
    }
    
    .info-header h4 {
        margin: 0;
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
        width: 100px;
    }
    
    .info-value {
        color: #1e293b;
        font-weight: 500;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-pinjam {
        background: #10b981;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-pinjam:hover {
        background: #059669;
        transform: translateY(-2px);
    }
    
    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }
    
    .btn-reset:hover {
        background: #cbd5e1;
    }
    
    .btn-cancel {
        background: #64748b;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
    }
    
    .btn-cancel:hover {
        background: #475569;
    }
    
    .stok-sedikit {
        color: #d97706;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-pinjam, .btn-reset, .btn-cancel {
            width: 100%;
            text-align: center;
        }
        
        .qr-info-content {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

@push('scripts')
<script>
    @if(!isset($barangFromQR) || !$barangFromQR)
    const barangSelect = document.getElementById('barang_id');
    const jumlahInput = document.getElementById('jumlah');
    const infoBarang = document.getElementById('infoBarang');
    const infoKode = document.getElementById('infoKode');
    const infoStok = document.getElementById('infoStok');
    const maxStokText = document.getElementById('maxStokText');
    
    if (barangSelect) {
        barangSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stok = selectedOption.dataset.stok;
            const kode = selectedOption.dataset.kode;
            
            if (this.value) {
                infoBarang.style.display = 'block';
                infoKode.textContent = kode || '-';
                infoStok.textContent = stok + ' unit';
                maxStokText.textContent = 'Maksimal peminjaman: ' + stok + ' unit';
                jumlahInput.max = stok;
                
                if (parseInt(jumlahInput.value) > parseInt(stok)) {
                    jumlahInput.value = stok;
                }
            } else {
                infoBarang.style.display = 'none';
                maxStokText.textContent = 'Maksimal peminjaman: - unit';
            }
        });
        
        jumlahInput.addEventListener('change', function() {
            const selectedOption = barangSelect.options[barangSelect.selectedIndex];
            const maxStok = selectedOption.dataset.stok;
            
            if (parseInt(this.value) > parseInt(maxStok)) {
                alert('Jumlah melebihi stok yang tersedia! Maksimal ' + maxStok + ' unit.');
                this.value = maxStok;
            }
        });
        
        if (barangSelect.value) {
            barangSelect.dispatchEvent(new Event('change'));
        }
    }
    @endif
    
    function resetForm() {
        if (confirm('Reset semua inputan?')) {
            document.getElementById('formPeminjaman').reset();
            @if(!isset($barangFromQR) || !$barangFromQR)
            if (infoBarang) infoBarang.style.display = 'none';
            if (maxStokText) maxStokText.textContent = 'Maksimal peminjaman: - unit';
            @endif
        }
    }
</script>
@endpush
@endsection