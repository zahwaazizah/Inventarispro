<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-qrcode"></i></div>
            <div class="logo-text">
                <h2>InventarisPro</h2>
                <p>Petugas</p>
            </div>
        </div>
    </div>
    <div class="sidebar-nav">
        <div class="nav-section-title">UTAMA</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('inventaris.index') }}" class="nav-item {{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Data Barang
        </a>

        <div class="nav-section-title">QR CODE</div>
        <!-- Menu KELOLA QR CODE TIDAK ADA UNTUK PETUGAS -->
        <a href="{{ route('qr.scan') }}" class="nav-item {{ request()->routeIs('qr.scan') ? 'active' : '' }}">
            <i class="fas fa-camera"></i> Scan QR Code
        </a>

        <div class="nav-section-title">TRANSAKSI</div>
        <a href="{{ route('transaksi.peminjaman.form') }}" class="nav-item {{ request()->routeIs('transaksi.peminjaman.form') ? 'active' : '' }}">
            <i class="fas fa-hand-holding"></i> Form Peminjaman
        </a>
        <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
            <i class="fas fa-list"></i> Daftar Peminjaman
        </a>

        <div class="nav-section-title">RIWAYAT</div>
        <a href="{{ route('riwayat.index') }}" class="nav-item {{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Riwayat Transaksi
        </a>

        <div class="nav-section-title">AKUN</div>
        <a href="{{ route('profile.index') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil Saya
        </a>
    </div>
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>