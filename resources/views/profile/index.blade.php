@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-description', 'Kelola informasi akun Anda')

@section('content')
<div class="profile-container">
    <!-- Header Profil -->
    <div class="profile-header">
        <div class="profile-cover"></div>
        <div class="profile-avatar-wrapper">
            <div class="profile-avatar">
                <div class="avatar-large">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="avatar-badge">
                    @if(Auth::user()->role == 'admin')
                        <i class="fas fa-crown"></i>
                    @else
                        <i class="fas fa-user-check"></i>
                    @endif
                </div>
            </div>
            <h2 class="profile-name">{{ Auth::user()->name }}</h2>
            <p class="profile-role">
                @if(Auth::user()->role == 'admin')
                    <span class="role-badge-admin">Administrator</span>
                @else
                    <span class="role-badge-petugas">Petugas</span>
                @endif
            </p>
        </div>
    </div>
    
    <div class="profile-content">
        <!-- Info Card -->
        <div class="info-card">
            <div class="info-header">
                <i class="fas fa-id-card"></i>
                <h3>Informasi Akun</h3>
            </div>
            <div class="info-body">
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </div>
                    <div class="info-value">{{ Auth::user()->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Bergabung Sejak</span>
                    </div>
                    <div class="info-value">{{ Auth::user()->created_at ? date('d F Y', strtotime(Auth::user()->created_at)) : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-clock"></i>
                        <span>Terakhir Login</span>
                    </div>
                    <div class="info-value">{{ now()->format('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Edit Profil Card -->
        <div class="form-card">
            <div class="form-header">
                <i class="fas fa-user-edit"></i>
                <h3>Edit Profil</h3>
                <p>Perbarui informasi akun Anda</p>
            </div>
            <div class="form-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="input-group">
                        <label>
                            <i class="fas fa-user"></i>
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="input-group">
                        <label>
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn-update">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Ganti Password Card -->
        <div class="form-card">
            <div class="form-header">
                <i class="fas fa-lock"></i>
                <h3>Ganti Password</h3>
                <p>Perbarui password akun Anda</p>
            </div>
            <div class="form-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="input-group">
                        <label>
                            <i class="fas fa-key"></i>
                            Password Baru
                        </label>
                        <input type="password" name="password" required>
                        @error('password')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="input-group">
                        <label>
                            <i class="fas fa-check-circle"></i>
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" required>
                    </div>
                    
                    <button type="submit" class="btn-password">
                        <i class="fas fa-sync-alt"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Statistik Card -->
        <div class="stats-card">
            <div class="stats-header">
                <i class="fas fa-chart-line"></i>
                <h3>Aktivitas Saya</h3>
            </div>
            <div class="stats-body">
                <div class="stat-item">
                    <div class="stat-number">{{ $totalTransaksi ?? 0 }}</div>
                    <div class="stat-label">Total Transaksi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalPeminjaman ?? 0 }}</div>
                    <div class="stat-label">Peminjaman</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalPengembalian ?? 0 }}</div>
                    <div class="stat-label">Pengembalian</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Profile Header */
    .profile-header {
        position: relative;
        margin-bottom: 80px;
    }
    
    .profile-cover {
        height: 150px;
        background: linear-gradient(135deg, #4361ee, #3a0ca3, #4cc9f0);
        border-radius: 20px;
    }
    
    .profile-avatar-wrapper {
        text-align: center;
        position: relative;
        margin-top: -50px;
    }
    
    .profile-avatar {
        display: inline-block;
        position: relative;
    }
    
    .avatar-large {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        color: #4361ee;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .avatar-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #f59e0b;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid white;
    }
    
    .profile-name {
        margin: 15px 0 5px;
        font-size: 24px;
        color: #1e293b;
    }
    
    .role-badge-admin, .role-badge-petugas {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .role-badge-admin {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }
    
    .role-badge-petugas {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #3730a3;
    }
    
    /* Profile Content */
    .profile-content {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    
    /* Cards */
    .info-card, .form-card, .stats-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .info-card:hover, .form-card:hover, .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .info-header, .form-header, .stats-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-header i, .form-header i, .stats-header i {
        font-size: 24px;
        color: #4361ee;
        margin-bottom: 10px;
        display: inline-block;
    }
    
    .info-header h3, .form-header h3, .stats-header h3 {
        margin: 0;
        font-size: 18px;
        color: #1e293b;
    }
    
    .form-header p {
        margin: 5px 0 0;
        font-size: 12px;
        color: #64748b;
    }
    
    .info-body, .form-body, .stats-body {
        padding: 24px;
    }
    
    /* Info Row */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 13px;
    }
    
    .info-label i {
        width: 20px;
        color: #4361ee;
    }
    
    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
    }
    
    /* Input Group */
    .input-group {
        margin-bottom: 20px;
    }
    
    .input-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-weight: 600;
        color: #334155;
        font-size: 13px;
    }
    
    .input-group label i {
        color: #4361ee;
    }
    
    .input-group input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .input-group input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
    
    /* Buttons */
    .btn-update, .btn-password {
        width: 100%;
        padding: 12px 20px;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-update {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
    }
    
    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }
    
    .btn-password {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .btn-password:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
    }
    
    /* Stats Card */
    .stats-body {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: bold;
        color: #4361ee;
    }
    
    .stat-label {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .profile-cover {
            height: 100px;
        }
        
        .avatar-large {
            width: 80px;
            height: 80px;
            font-size: 36px;
        }
        
        .profile-name {
            font-size: 20px;
        }
        
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
</style>
@endsection