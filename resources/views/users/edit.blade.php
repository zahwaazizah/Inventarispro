@extends('layouts.app')

@section('title', 'Edit Petugas')
@section('page-title', 'Edit Data Petugas')
@section('page-description', 'Form edit data petugas')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;"><i class="fas fa-user-edit"></i> Form Edit Petugas</h3>
            <a href="{{ route('users.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                <small class="form-text">Minimal 8 karakter, kosongkan jika tidak ingin mengubah</small>
                @error('password')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> Role <span class="required">*</span></label>
                <select name="role" class="form-control" required>
                    <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>👤 Petugas</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>👑 Administrator</option>
                </select>
                @error('role')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('users.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
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
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
    }
    
    .btn-cancel {
        background: #64748b;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        transition: all 0.2s;
    }
    
    .btn-cancel:hover {
        background: #475569;
    }
    
    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }
        
        .btn-save, .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsectionA