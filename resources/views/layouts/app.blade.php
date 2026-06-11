<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InventarisPro')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #4cc9f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --gray: #6b7280;
            --light: #f9fafb;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            overflow-x: hidden;
        }
        
        /* ==================== SIDEBAR ==================== */
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
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); }
        .sidebar::-webkit-scrollbar-thumb { background: #4cc9f0; border-radius: 4px; }
        .sidebar.closed { left: -280px; }
        
        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
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
        
        .logo-icon i { font-size: 22px; color: white; }
        
        .logo-text h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }
        
        .logo-text p {
            font-size: 10px;
            opacity: 0.6;
            margin: 2px 0 0;
        }
        
        .sidebar-nav { padding: 0 16px; }
        
        .nav-section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            margin: 16px 0 8px 12px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            margin: 4px 0;
            border-radius: 12px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .nav-item:hover { background: rgba(76,201,240,0.15); color: white; }
        .nav-item.active { background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white; box-shadow: 0 4px 10px rgba(67,97,238,0.3); }
        .nav-item i { width: 22px; font-size: 16px; text-align: center; }
        
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 20px;
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            color: rgba(255,255,255,0.7);
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover { background: rgba(239,68,68,0.2); color: #ef4444; }
        
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
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            color: #4361ee;
            border: none;
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover { background: #4361ee; color: white; }
        .sidebar.closed + .sidebar-toggle { left: 20px; }
        
        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: 280px;
            padding: 24px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .sidebar.closed ~ .main-content { margin-left: 0; }
        
        /* ==================== TOP BAR ==================== */
        .top-bar {
            background: white;
            padding: 16px 24px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .page-title h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .page-title p {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #4361ee, #4cc9f0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 18px;
        }
        
        /* ==================== CARD STYLES ==================== */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .card-header h3 {
            margin: 0;
            font-size: 1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-body { padding: 24px; }
        
        /* ==================== TABLE STYLES ==================== */
        .table-responsive { overflow-x: auto; }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .table th {
            padding: 12px;
            text-align: left;
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table tr:hover { background: #f8fafc; }
        
        /* ==================== BUTTON STYLES ==================== */
        .btn-primary {
            background: #4361ee;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-primary:hover { background: #3a0ca3; transform: translateY(-1px); }
        
        /* ==================== ALERT STYLES ==================== */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .alert-info { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        
        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            .sidebar-toggle { left: auto; right: 20px; top: 20px; }
            .main-content { margin-left: 0; }
            .card-header { flex-direction: column; align-items: flex-start; }
            .table { font-size: 11px; }
            .table th, .table td { padding: 8px; }
        }
    </style>
    
    @stack('styles')
</head>
<body>

<!-- SIDEBAR (Dipanggil berdasarkan role) -->
@if(Auth::user()->role == 'admin')
    @include('layouts.partials.sidebar-admin')
@else
    @include('layouts.partials.sidebar-petugas')
@endif

<!-- SIDEBAR TOGGLE BUTTON -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="page-title">
            <h2>@yield('page-title', 'Dashboard')</h2>
            <p>@yield('page-description', 'Selamat datang di sistem inventaris')</p>
        </div>
        <div class="user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
    </div>
    
    <!-- CONTENT -->
    @yield('content')
</div>

<!-- SIDEBAR TOGGLE SCRIPT -->
<script>
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

@stack('scripts')
</body>
</html>