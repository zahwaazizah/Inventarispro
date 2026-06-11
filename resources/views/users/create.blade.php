@extends('layouts.app')

@section('title', 'Tambah Petugas')
@section('page-title', 'Tambah Petugas Baru')
@section('page-description', 'Form tambah petugas atau administrator')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;"><i class="fas fa-user-plus"></i> Form Tambah Petugas</h3>
            <a href="{{ route('users.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                @error('name')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="contoh@email.com">
                @error('email')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password <span class="required">*</span></label>
                <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                @error('password')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Konfirmasi Password <span class="required">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> Role <span class="required">*</span></label>
                <select name="role" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>👤 Petugas</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Administrator</option>
                </select>
                @error('role')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <button type="reset" class="btn-reset">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .btn-back {
        background: #64748b;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
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
        width: 20px;
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
        transition: all 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .error-text {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }
    
    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }
        
        .btn-save, .btn-reset {
            width: 100%;
        }
    }
</style>
@endsection