@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang Inventaris')
@section('page-description', 'Form edit data barang inventaris')

@section('content')
<div class="row">
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <h3 style="margin: 0; font-size: 1.3rem;"><i class="fas fa-edit"></i> Form Edit Barang</h3>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('inventaris.show', $barang->id) }}" class="btn-view">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('inventaris.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('inventaris.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <!-- Kolom Kiri -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-box"></i>
                            <h4>Informasi Barang</h4>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-barcode"></i> Kode Inventaris <span class="required">*</span></label>
                            <input type="text" name="kode_inventaris" class="form-control" value="{{ old('kode_inventaris', $barang->kode_inventaris) }}" required>
                            @error('kode_inventaris')
                                <small class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-box"></i> Nama Barang <span class="required">*</span></label>
                            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                            @error('nama_barang')
                                <small class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-tags"></i> Kategori <span class="required">*</span></label>
                                <select name="kategori_id" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-map-marker-alt"></i> Lokasi <span class="required">*</span></label>
                                <select name="lokasi_id" class="form-control" required>
                                    <option value="">-- Pilih Lokasi --</option>
                                    @foreach($lokasis as $lokasi)
                                        <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $barang->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                            {{ $lokasi->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lokasi_id')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-trademark"></i> Merk</label>
                                <input type="text" name="merk" class="form-control" value="{{ old('merk', $barang->merk) }}" placeholder="Contoh: Apple, Samsung, Epson">
                                @error('merk')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-hashtag"></i> Serial Number</label>
                                <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $barang->serial_number) }}" placeholder="Serial number barang">
                                @error('serial_number')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-chart-line"></i>
                            <h4>Status & Stok</h4>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-chart-simple"></i> Status Barang</label>
                                <select name="status_barang" class="form-control">
                                    <option value="tersedia" {{ old('status_barang', $barang->status_barang) == 'tersedia' ? 'selected' : '' }}>✅ Tersedia</option>
                                    <option value="maintenance" {{ old('status_barang', $barang->status_barang) == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                                    <option value="rusak" {{ old('status_barang', $barang->status_barang) == 'rusak' ? 'selected' : '' }}>❌ Rusak</option>
                                    <option value="hilang" {{ old('status_barang', $barang->status_barang) == 'hilang' ? 'selected' : '' }}>🔍 Hilang</option>
                                </select>
                                @error('status_barang')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-clipboard-list"></i> Kondisi Barang</label>
                                <select name="kondisi_barang" class="form-control">
                                    <option value="baik" {{ old('kondisi_barang', $barang->kondisi_barang) == 'baik' ? 'selected' : '' }}>👍 Baik</option>
                                    <option value="rusak ringan" {{ old('kondisi_barang', $barang->kondisi_barang) == 'rusak ringan' ? 'selected' : '' }}>⚠️ Rusak Ringan</option>
                                    <option value="rusak berat" {{ old('kondisi_barang', $barang->kondisi_barang) == 'rusak berat' ? 'selected' : '' }}>💔 Rusak Berat</option>
                                </select>
                                @error('kondisi_barang')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-cubes"></i> Stok</label>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok', $barang->stok) }}" min="0">
                            @error('stok')
                                <small class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom 3 - Informasi Pembelian -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-shopping-cart"></i>
                            <h4>Informasi Pembelian</h4>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-alt"></i> Tahun Pembelian</label>
                                <input type="number" name="tahun_pembelian" class="form-control" value="{{ old('tahun_pembelian', $barang->tahun_pembelian) }}" placeholder="Contoh: 2024" min="2000" max="{{ date('Y') }}">
                                @error('tahun_pembelian')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-money-bill-wave"></i> Harga Pembelian</label>
                                <input type="number" name="harga_pembelian" class="form-control" value="{{ old('harga_pembelian', $barang->harga_pembelian) }}" placeholder="0" step="1000">
                                @error('harga_pembelian')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-shield-alt"></i> Masa Garansi</label>
                                <input type="text" name="masa_garansi" class="form-control" value="{{ old('masa_garansi', $barang->masa_garansi) }}" placeholder="Contoh: 1 Tahun">
                                @error('masa_garansi')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-landmark"></i> Sumber Dana</label>
                                <input type="text" name="sumber_dana" class="form-control" value="{{ old('sumber_dana', $barang->sumber_dana) }}" placeholder="Contoh: APBN, APBD, Hibah">
                                @error('sumber_dana')
                                    <small class="error-text">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 4 - Spesifikasi & Foto -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-microchip"></i>
                            <h4>Spesifikasi & Dokumentasi</h4>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-file-alt"></i> Spesifikasi</label>
                            <textarea name="spesifikasi" class="form-control" rows="4" placeholder="Detail spesifikasi barang (RAM, Processor, dll)">{{ old('spesifikasi', $barang->spesifikasi) }}</textarea>
                            @error('spesifikasi')
                                <small class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-camera"></i> Foto Barang</label>
                            <div class="file-upload">
                                <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput">
                                @if($barang->foto && file_exists(public_path('storage/' . $barang->foto)))
                                    <div class="current-photo">
                                        <label>Foto Saat Ini:</label>
                                        <div class="photo-preview">
                                            <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Barang">
                                            <div class="photo-info">
                                                <p>Foto saat ini akan diganti jika Anda upload file baru</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="preview-image" id="previewImage" style="display: none;">
                                    <label>Preview Foto Baru:</label>
                                    <div class="preview-container">
                                        <img id="preview" src="#" alt="Preview">
                                        <button type="button" class="remove-image" onclick="removeImage()">×</button>
                                    </div>
                                </div>
                            </div>
                            @error('foto')
                                <small class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-update">
                        <i class="fas fa-save"></i> Update Barang
                    </button>
                    <button type="reset" class="btn-reset" onclick="resetForm()">
                        <i class="fas fa-undo-alt"></i> Reset
                    </button>
                    <a href="{{ route('inventaris.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    
    .form-section {
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .section-header {
        padding: 14px 18px;
        background: white;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-header i {
        font-size: 20px;
        color: #4361ee;
    }
    
    .section-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
    }
    
    .form-group {
        padding: 0 18px;
        margin-bottom: 18px;
    }
    
    .form-group:first-of-type {
        margin-top: 18px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 0;
    }
    
    .form-row .form-group {
        padding: 0;
        margin-bottom: 18px;
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
        margin-right: 6px;
    }
    
    .required {
        color: #ef4444;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }
    
    .error-text {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
    
    /* Buttons */
    .btn-view, .btn-back {
        padding: 9px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-view {
        background: #10b981;
        color: white;
    }
    
    .btn-back {
        background: #64748b;
        color: white;
    }
    
    .btn-view:hover, .btn-back:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-update {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 12px 28px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
    }
    
    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        padding: 12px 28px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-reset:hover {
        background: #cbd5e1;
    }
    
    .btn-cancel {
        background: #ef4444;
        color: white;
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-cancel:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }
    
    /* File Upload */
    .file-upload {
        position: relative;
    }
    
    .current-photo {
        margin-top: 15px;
        padding: 12px;
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .current-photo label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
        display: block;
    }
    
    .photo-preview {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .photo-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    
    .photo-info p {
        margin: 0;
        font-size: 11px;
        color: #94a3b8;
    }
    
    .preview-image {
        margin-top: 15px;
    }
    
    .preview-image label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
        display: block;
    }
    
    .preview-container {
        position: relative;
        display: inline-block;
    }
    
    .preview-container img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 5px;
    }
    
    .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
    
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-view, .btn-back {
            width: 100%;
            justify-content: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-update, .btn-reset, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
        
        .photo-preview {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
    // Preview image before upload
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('previewImage');
        const preview = document.getElementById('preview');
        
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    
    function removeImage() {
        document.getElementById('fotoInput').value = '';
        document.getElementById('previewImage').style.display = 'none';
        document.getElementById('preview').src = '#';
    }
    
    function resetForm() {
        if (confirm('Reset semua perubahan yang belum disimpan?')) {
            document.querySelector('form').reset();
            removeImage();
        }
    }
</script>
@endsection