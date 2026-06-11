<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-qrcode"></i>
            </div>
            <div class="logo-text">
                <h2>InventarisPro</h2>
                <p>{{ Auth::user()->role == 'admin' ? 'Administrator' : 'Petugas' }}</p>
            </div>
        </div>
    </div>
    
    <div class="sidebar-nav">
        <div class="nav-section-title">MAIN MENU</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('inventaris.index') }}" class="nav-item {{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Data Barang
        </a>
        
        @if(Auth::user()->role == 'admin')
        <div class="nav-section-title">MASTER DATA</div>
        <a href="{{ route('kategori.index') }}" class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Kategori
        </a>
        <a href="{{ route('lokasi.index') }}" class="nav-item {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
            <i class="fas fa-map-marker-alt"></i> Lokasi
        </a>
        @endif
        
        <!-- ==================== QR CODE SECTION ==================== -->
        <div class="nav-section-title">QR CODE</div>
        
        <!-- KELOLA QR CODE - Untuk semua role -->
        <a href="{{ url('/qr') }}" class="nav-item {{ request()->is('qr') || request()->routeIs('qr.index') ? 'active' : '' }}">
            <i class="fas fa-qrcode"></i> Kelola QR Code
        </a>
        
        <!-- SCAN QR CODE - Untuk semua role -->
        <a href="{{ url('/qr/scan') }}" class="nav-item {{ request()->is('qr/scan') || request()->routeIs('qr.scan') ? 'active' : '' }}">
            <i class="fas fa-camera"></i> Scan QR Code
        </a>
        <!-- ======================================================== -->
        
        <div class="nav-section-title">TRANSAKSI & LAPORAN</div>
        <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i> Peminjaman
        </a>
        <a href="{{ route('riwayat.index') }}" class="nav-item {{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Riwayat
        </a>
        
        @if(Auth::user()->role == 'admin')
        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Laporan
        </a>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Kelola Petugas
        </a>
        @endif
        
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
        <div class="version-info">
            <small>Versi 1.0.0</small>
        </div>
    </div>
</div>

<!-- Sidebar Toggle Button -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 280px;
        height: 100%;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        color: #e2e8f0;
        z-index: 1000;
        transition: all 0.3s ease;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
    }
    
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: #4cc9f0;
        border-radius: 4px;
    }
    
    .sidebar.closed {
        left: -280px;
    }
    
    .sidebar-header {
        padding: 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 16px;
    }
    
    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .logo-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #4361ee, #4cc9f0);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .logo-icon i {
        font-size: 22px;
        color: white;
    }
    
    .logo-text h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        margin: 0;
    }
    
    .logo-text p {
        font-size: 10px;
        opacity: 0.6;
        margin: 2px 0 0 0;
    }
    
    .sidebar-nav {
        padding: 0 16px;
    }
    
    .nav-section-title {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.4);
        margin: 16px 0 8px 12px;
    }
    
    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        margin: 4px 0;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .nav-item:hover {
        background: rgba(76, 201, 240, 0.15);
        color: white;
    }
    
    .nav-item.active {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }
    
    .nav-item i {
        width: 22px;
        font-size: 16px;
        text-align: center;
    }
    
    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 20px;
    }
    
    .logout-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    .version-info {
        text-align: center;
        margin-top: 12px;
        font-size: 10px;
        color: rgba(255, 255, 255, 0.3);
    }
    
    /* Sidebar Toggle Button */
    .sidebar-toggle {
        position: fixed;
        left: 290px;
        top: 20px;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1001;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        color: #4361ee;
        border: none;
        transition: all 0.3s ease;
    }
    
    .sidebar-toggle:hover {
        background: #4361ee;
        color: white;
    }
    
    .sidebar.closed + .sidebar-toggle {
        left: 20px;
    }
    
    /* Main Content */
    .main-content {
        margin-left: 280px;
        padding: 24px;
        min-height: 100vh;
        transition: all 0.3s ease;
    }
    
    .sidebar.closed ~ .main-content {
        margin-left: 0;
    }
    
    @media (max-width: 768px) {
        .sidebar-toggle {
            left: auto;
            right: 20px;
            top: 20px;
        }
        .main-content {
            margin-left: 0;
        }
    }
</style>

<script>
    // Sidebar toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        if (sidebar && toggleBtn) {
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'closed' && window.innerWidth > 768) {
                sidebar.classList.add('closed');
                toggleBtn.innerHTML = '<i class="fas fa-arrow-right"></i>';
            }
            
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('closed');
                if (sidebar.classList.contains('closed')) {
                    this.innerHTML = '<i class="fas fa-arrow-right"></i>';
                    if (window.innerWidth > 768) localStorage.setItem('sidebarState', 'closed');
                } else {
                    this.innerHTML = '<i class="fas fa-bars"></i>';
                    if (window.innerWidth > 768) localStorage.setItem('sidebarState', 'open');
                }
            });
            
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    const savedState = localStorage.getItem('sidebarState');
                    if (savedState === 'closed') {
                        sidebar.classList.add('closed');
                        toggleBtn.innerHTML = '<i class="fas fa-arrow-right"></i>';
                    } else {
                        sidebar.classList.remove('closed');
                        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                } else {
                    sidebar.classList.add('closed');
                    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        }
    });
</script>