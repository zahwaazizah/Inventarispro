<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-qrcode"></i></div>
            <div class="logo-text">
                <h2>InventarisPro</h2>
                <p>Administrator</p>
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

        <div class="nav-section-title">MASTER DATA</div>
        <a href="{{ route('kategori.index') }}" class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Kategori
        </a>
        <a href="{{ route('lokasi.index') }}" class="nav-item {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
            <i class="fas fa-map-marker-alt"></i> Lokasi
        </a>

        <div class="nav-section-title">QR CODE</div>
        <a href="{{ route('qr.index') }}" class="nav-item {{ request()->routeIs('qr.index') ? 'active' : '' }}">
            <i class="fas fa-qrcode"></i> Kelola QR Code
        </a>
        <!-- Scan QR Code tidak ditampilkan untuk admin -->

        <div class="nav-section-title">RIWAYAT & LAPORAN</div>
        <a href="{{ route('riwayat.index') }}" class="nav-item {{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Riwayat Transaksi
        </a>
        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Laporan
        </a>

        <div class="nav-section-title">PENGATURAN</div>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Kelola Petugas
        </a>

        <div class="nav-section-title">AKUN</div>
        <a href="{{ route('profile.index') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil Saya
        </a>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>