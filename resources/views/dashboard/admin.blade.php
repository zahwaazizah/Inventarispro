@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-description', 'Selamat datang, ' . Auth::user()->name . '!')

@section('content')
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <i class="fas fa-boxes" style="font-size: 40px; color: var(--primary);"></i>
            <h3 style="font-size: 28px; margin: 10px 0;">0</h3>
            <p>Total Barang</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <i class="fas fa-check-circle" style="font-size: 40px; color: var(--success);"></i>
            <h3 style="font-size: 28px; margin: 10px 0;">0</h3>
            <p>Tersedia</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <i class="fas fa-hand-holding" style="font-size: 40px; color: var(--warning);"></i>
            <h3 style="font-size: 28px; margin: 10px 0;">0</h3>
            <p>Dipinjam</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Menu Cepat</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('inventaris.index') }}" class="btn-primary">Data Barang</a>
            <a href="{{ route('kategori.index') }}" class="btn-primary">Kategori</a>
            <a href="{{ route('lokasi.index') }}" class="btn-primary">Lokasi</a>
            <a href="{{ route('qr.scan') }}" class="btn-primary">Scan QR</a>
        </div>
    </div>
</div>
@endsection