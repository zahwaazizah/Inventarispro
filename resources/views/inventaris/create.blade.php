@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')
@section('page-description', 'Manajemen stok dan informasi barang')

@section('content')
<div class="container-fluid px-0">
    <!-- Header dengan Tombol -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1 fw-semibold">📦 Daftar Barang</h4>
            <p class="text-muted small mb-0">Kelola semua data inventaris dalam satu tempat</p>
        </div>
        @if(Auth::user()->role == 'admin')
            <a href="{{ url('/tambah-barang-aman.php') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>Tambah Barang
            </a>
        @endif
    </div>

    <!-- Statistik Ringkas -->
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="bg-white rounded-4 p-3 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-primary">
                <div>
                    <span class="text-muted small">Total Barang</span>
                    <h2 class="mb-0 fw-bold">{{ $totalBarang ?? $barangs->total() }}</h2>
                </div>
                <i class="fas fa-boxes fa-2x text-primary opacity-50"></i>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="bg-white rounded-4 p-3 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-success">
                <div>
                    <span class="text-muted small">Tersedia</span>
                    <h2 class="mb-0 fw-bold">{{ $totalTersedia ?? $barangs->where('stok','>',0)->count() }}</h2>
                </div>
                <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="bg-white rounded-4 p-3 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-warning">
                <div>
                    <span class="text-muted small">Stok Menipis</span>
                    <h2 class="mb-0 fw-bold">{{ $totalMenipis ?? $barangs->where('stok','<=',3)->where('stok','>',0)->count() }}</h2>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x text-warning opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Filter Pencarian -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-6 col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-primary"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-pill" placeholder="Cari kode atau nama barang..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
                @if(request('search'))
                <div class="col-md-2 col-lg-1">
                    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Grid Barang (Card) -->
    <div class="row g-4">
        @forelse($barangs as $barang)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-lift">
                <!-- Badge stok di pojok -->
                <div class="position-absolute top-0 end-0 mt-3 me-3 z-1">
                    @if($barang->stok <= 0)
                        <span class="badge bg-danger rounded-pill px-3 py-2">Stok Habis</span>
                    @elseif($barang->stok <= 3)
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Sisa {{ $barang->stok }}</span>
                    @else
                        <span class="badge bg-success rounded-pill px-3 py-2">Stok {{ $barang->stok }}</span>
                    @endif
                </div>

                <!-- Body Card -->
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-uppercase small text-muted">Kode</span>
                            <h5 class="mb-0 fw-monospace">{{ $barang->kode_inventaris }}</h5>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item" href="{{ route('inventaris.show', $barang->id) }}"><i class="fas fa-eye me-2"></i>Detail</a></li>
                                @if(Auth::user()->role == 'admin')
                                    <li><a class="dropdown-item" href="{{ route('inventaris.edit', $barang->id) }}"><i class="fas fa-pen me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('inventaris.destroy', $barang->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash me-2"></i>Hapus</button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <h4 class="fw-semibold mb-3">{{ $barang->nama_barang }}</h4>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-2 text-center">
                                <i class="fas fa-tag text-secondary me-1"></i>
                                <small class="text-muted d-block">Kategori</small>
                                <span class="fw-medium">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-2 text-center">
                                <i class="fas fa-map-marker-alt text-secondary me-1"></i>
                                <small class="text-muted d-block">Lokasi</small>
                                <span class="fw-medium">{{ $barang->lokasi->nama_lokasi ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge rounded-pill {{ $barang->status_barang == 'tersedia' ? 'bg-success' : ($barang->status_barang == 'dipinjam' ? 'bg-warning' : 'bg-secondary') }}">
                                {{ ucfirst($barang->status_barang ?? 'Tersedia') }}
                            </span>
                        </div>
                        <a href="{{ route('inventaris.show', $barang->id) }}" class="text-primary text-decoration-none small fw-semibold">
                            Lihat detail <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="bg-white rounded-4 p-5 shadow-sm">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h5>Belum ada barang</h5>
                <p class="text-muted">Silakan tambahkan barang inventaris terlebih dahulu</p>
                @if(Auth::user()->role == 'admin')
                    <a href="{{ url('/tambah-barang-aman.php') }}" class="btn btn-primary rounded-pill px-4">Tambah Barang</a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $barangs->links() }}
    </div>
</div>

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .card-body {
            padding: 1rem !important;
        }
    }
</style>
@endsection