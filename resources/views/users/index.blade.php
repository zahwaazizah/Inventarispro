@extends('layouts.app')

@section('title', 'Kelola Petugas')
@section('page-title', 'Kelola Petugas')
@section('page-description', 'Manajemen data petugas dan administrator')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <h3 style="margin: 0;"><i class="fas fa-users"></i> Daftar Petugas</h3>
            <a href="{{ route('users.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Petugas
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Filter -->
        <div class="filter-section">
            <form method="GET" action="{{ route('users.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <input type="text" name="search" placeholder="Cari nama atau email..." class="search-input" value="{{ request('search') }}">
                
                <select name="role" class="role-select">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
                
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Cari
                </button>
                
                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="btn-reset">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="120">Role</th>
                        <th width="120">Tanggal Bergabung</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($users) && count($users) > 0)
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $user->name }}
                                @if($user->id == auth()->id())
                                    <span class="badge-me">(Anda)</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge-admin">Administrator</span>
                                @else
                                    <span class="badge-petugas">Petugas</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at ? date('d/m/Y', strtotime($user->created_at)) : '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id != auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus {{ $user->name }}?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <i class="fas fa-users-slash" style="font-size: 40px; color: #ccc; margin-bottom: 10px; display: block;"></i>
                                Belum ada data petugas
                                <br>
                                <a href="{{ route('users.create') }}" style="margin-top: 10px; display: inline-block;" class="btn-add-small">
                                    <i class="fas fa-plus"></i> Tambah Petugas
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if(isset($users) && method_exists($users, 'links'))
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .btn-add {
        background: #4361ee;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-add:hover {
        background: #3a0ca3;
    }
    
    .filter-section {
        background: #f8fafc;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .search-input {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        width: 220px;
    }
    
    .role-select {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        background: white;
    }
    
    .btn-filter {
        background: #4361ee;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
    }
    
    .btn-reset {
        background: #64748b;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
    }
    
    .badge-me {
        background: #e0e7ff;
        color: #3730a3;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        margin-left: 8px;
    }
    
    .badge-admin {
        background: #d1fae5;
        color: #065f46;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        display: inline-block;
    }
    
    .badge-petugas {
        background: #e0e7ff;
        color: #3730a3;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        display: inline-block;
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
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
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit, .btn-delete {
        padding: 5px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-edit {
        background: #fef3c7;
        color: #d97706;
    }
    
    .btn-edit:hover {
        background: #f59e0b;
        color: white;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .btn-delete:hover {
        background: #ef4444;
        color: white;
    }
    
    .btn-add-small {
        background: #4361ee;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th {
        text-align: left;
        padding: 12px;
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        font-size: 13px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 13px;
    }
    
    .table tr:hover {
        background: #f8fafc;
    }
    
    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .search-input, .role-select, .btn-filter, .btn-reset {
            width: 100%;
        }
        
        .table {
            font-size: 12px;
        }
        
        .table th, .table td {
            padding: 8px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
    }
</style>
@endsection