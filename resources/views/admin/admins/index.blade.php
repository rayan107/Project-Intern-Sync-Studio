<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Management - Roles & Permissions | EventHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --sidebar-width: 280px;
            --bg-dark: #0f172a;
            --surface-glass: rgba(255, 255, 255, 0.05);
            --border-glass: rgba(255, 255, 255, 0.1);
            --text-light: #e2e8f0;
            --text-muted: #94a3b8;
            --accent: #667eea;
            --accent2: #764ba2;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: radial-gradient(circle at 20% 30%, #a78bfa15 0%, transparent 35%),
                        radial-gradient(circle at 80% 70%, #818cf815 0%, transparent 40%),
                        linear-gradient(145deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
            min-height: 100vh; display: flex; position: relative; overflow-x: hidden; color: #ffffff;
        }
        .form-control, .form-select, input, textarea, select {
            background: #0f172a !important; border: 1px solid rgba(255,255,255,0.15) !important;
            color: #ffffff !important; border-radius: 10px; padding: 10px 14px; font-size: 14px;
        }
        .form-control::placeholder, input::placeholder, textarea::placeholder { color: #ffffff !important; opacity: 0.6 !important; font-weight: 400; }
        .form-control:focus, .form-select:focus, input:focus {
            border-color: #667eea !important; box-shadow: 0 0 0 2px rgba(102,126,234,0.3) !important;
            color: #ffffff !important; background: #0f172a !important;
        }
        .form-label { color: #e2e8f0 !important; font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block; }
        .text-muted { color: #94a3b8 !important; }
        .form-select option { background-color: #1e293b; color: #ffffff; }
        .bg-blob { position: fixed; border-radius: 50%; filter: blur(100px); opacity: 0.15; z-index: 0; pointer-events: none; }
        .blob1 { width: 500px; height: 500px; background: #667eea; top: -100px; right: -100px; animation: float1 20s infinite alternate; }
        .blob2 { width: 400px; height: 400px; background: #a78bfa; bottom: -80px; left: -80px; animation: float2 18s infinite alternate; }
        .blob3 { width: 350px; height: 350px; background: #f472b6; top: 50%; left: 50%; animation: float3 22s infinite alternate; }
        @keyframes float1 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(-60px,50px) scale(1.2); } }
        @keyframes float2 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(60px,-40px) scale(1.3); } }
        @keyframes float3 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(-40px,-60px) scale(1.1); } }
        .sidebar { width: var(--sidebar-width); background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border-right: 1px solid var(--border-glass); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 1000; transition: transform 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1); box-shadow: 4px 0 30px rgba(0, 0, 0, 0.4); }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.mobile-open { transform: translateX(0); } }
        @media (min-width: 769px) { .sidebar { transform: translateX(0) !important; } .sidebar.collapsed { transform: translateX(-100%); width: 0; opacity: 0; pointer-events: none; } }
        .logo { padding: 30px 24px; border-bottom: 1px solid var(--border-glass); display: flex; align-items: center; justify-content: space-between; white-space: nowrap; min-width: var(--sidebar-width); }
        .logo h1 { color: #fff; font-size: 24px; font-weight: 700; display: flex; align-items: center; gap: 12px; margin: 0; font-family: 'Syne', sans-serif; }
        .logo-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3); }
        .sidebar-toggle-btn { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; flex-shrink: 0; font-size: 18px; }
        .sidebar-toggle-btn:hover { background: rgba(102,126,234,0.4); border-color: #667eea; }
        .sidebar-open-btn { position: fixed; top: 20px; left: 20px; z-index: 1100; background: linear-gradient(135deg, #667eea, #764ba2); border: none; color: #fff; width: 48px; height: 48px; border-radius: 14px; display: none; align-items: center; justify-content: center; cursor: pointer; font-size: 22px; box-shadow: 0 8px 25px rgba(102,126,234,0.5); transition: all 0.2s; }
        .sidebar-open-btn:hover { transform: scale(1.05); }
        .sidebar-open-btn.visible { display: flex; }
        .mobile-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(3px); z-index: 999; display: none; }
        .mobile-overlay.active { display: block; }
        .nav-menu { list-style: none; padding: 20px 0; flex: 1; min-width: var(--sidebar-width); }
        .nav-item { margin-bottom: 5px; }
        .nav-link { display: flex; align-items: center; gap: 14px; padding: 14px 28px; color: #94a3b8; text-decoration: none; transition: all 0.3s; border-left: 3px solid transparent; white-space: nowrap; font-size: 15px; font-weight: 500; border-radius: 0 8px 8px 0; margin-right: 12px; cursor: pointer; }
        .nav-link:hover { background: rgba(102,126,234,0.12); color: #fff; border-left-color: #667eea; transform: translateX(4px); }
        .nav-link.active { background: rgba(102,126,234,0.18); color: #fff; border-left-color: #a78bfa; box-shadow: 0 4px 15px rgba(102,126,234,0.2); }
        .nav-icon { font-size: 20px; width: 26px; text-align: center; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); transition: margin-left 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1); position: relative; z-index: 1; min-width: 0; }
        @media (max-width: 768px) { .main-content { margin-left: 0 !important; } }
        .main-content.expanded { margin-left: 0; }
        .topbar { background: rgba(15,23,42,0.5); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px); padding: 18px 36px; border-bottom: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 99; flex-wrap: wrap; gap: 12px; }
        .topbar-left h2 { color: #fff; font-size: 24px; font-weight: 700; font-family: 'Syne', sans-serif; margin: 0; }
        .breadcrumb { color: #94a3b8; font-size: 13px; margin-top: 5px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .breadcrumb a { color: #667eea; text-decoration: none; }
        .topbar-right { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .user-info { display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.05); padding: 8px 16px; border-radius: 14px; border: 1px solid var(--border-glass); }
        .user-avatar { width: 42px; height: 42px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 15px; flex-shrink: 0; }
        .user-details { display: flex; flex-direction: column; }
        .user-name { color: #e2e8f0; font-weight: 600; font-size: 13px; }
        .user-role { color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-logout { padding: 10px 20px; background: rgba(239,68,68,0.2); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s; display: flex; align-items: center; gap: 6px; white-space: nowrap; }
        .btn-logout:hover { background: rgba(239,68,68,0.4); color: #fff; transform: translateY(-2px); }
        .btn-create { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 28px; border-radius: 50px; font-weight: 700; font-size: 14px; letter-spacing: 0.5px; color: #fff; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); display: inline-flex; align-items: center; gap: 10px; cursor: pointer; white-space: nowrap; }
        .btn-create:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(102,126,234,0.6); }
        .content-area { padding: 36px; flex: 1; max-width: 1400px; margin: 0 auto; }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 22px; margin-bottom: 30px; }
        .stat-card { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); border-radius: 20px; padding: 28px 26px; border: 1px solid var(--border-glass); position: relative; overflow: hidden; transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-4px); background: rgba(255,255,255,0.07); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; }
        .stat-card.blue::before { background: linear-gradient(90deg, #667eea, #764ba2); }
        .stat-card.green::before { background: linear-gradient(90deg, #22c55e, #16a34a); }
        .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
        .stat-card.red::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 18px; }
        .blue .stat-icon { background: rgba(102,126,234,0.2); }
        .green .stat-icon { background: rgba(34,197,94,0.2); }
        .orange .stat-icon { background: rgba(245,158,11,0.2); }
        .purple .stat-icon { background: rgba(139,92,246,0.2); }
        .red .stat-icon { background: rgba(239,68,68,0.2); }
        .stat-value { font-size: 42px; font-weight: 800; font-family: 'Syne', sans-serif; color: #fff; }
        .stat-label { font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px; }
        .panel { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); border-radius: 20px; border: 1px solid var(--border-glass); overflow: hidden; }
        .panel-header { padding: 20px 24px; border-bottom: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .panel-header h4 { font-family: 'Syne', sans-serif; margin: 0; color: #fff; font-size: 18px; }
        .search-box { display: flex; align-items: center; gap: 8px; background: rgba(15,23,42,0.5); border: 1px solid var(--border-glass); border-radius: 10px; padding: 8px 14px; max-width: 280px; width: 100%; }
        .search-box input { background: transparent; border: none; outline: none; color: #fff; width: 100%; font-size: 14px; }
        .search-box input::placeholder { color: #ffffff !important; opacity: 0.6; }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .admin-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .admin-table th { text-align: left; padding: 14px 18px; font-size: 11px; color: #94a3b8; text-transform: uppercase; background: rgba(15,23,42,0.3); font-weight: 600; white-space: nowrap; }
        .admin-table td { padding: 16px 18px; border-bottom: 1px solid rgba(255,255,255,0.05); color: #e2e8f0; vertical-align: middle; }
        .role-badge { display: inline-block; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; }
        .role-super_admin { background: rgba(139,92,246,0.2); color: #a78bfa; }
        .role-events_manager { background: rgba(34,197,94,0.2); color: #4ade80; }
        .role-viewer { background: rgba(100,116,139,0.2); color: #94a3b8; }
        .role-custom { background: rgba(239,68,68,0.2); color: #fca5a5; }
        .permission-tag { display: inline-block; background: rgba(102,126,234,0.15); padding: 4px 10px; border-radius: 6px; font-size: 11px; margin: 2px; white-space: nowrap; color: #a78bfa; }
        .btn-sm { padding: 7px 14px; border-radius: 9px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; margin: 2px; display: inline-flex; align-items: center; gap: 4px; }
        .btn-sm:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-sm:disabled:hover { transform: none; }
        .btn-edit { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .btn-edit:hover { background: #f59e0b; color: #fff; transform: translateY(-2px); }
        .btn-delete { background: rgba(239,68,68,0.25); color: #fca5a5; }
        .btn-delete:hover { background: #ef4444; color: #fff; transform: translateY(-2px); }
        .btn-perms { background: rgba(102,126,234,0.2); color: #a78bfa; }
        .btn-perms:hover { background: #667eea; color: #fff; transform: translateY(-2px); }
        .modal-content { background: #1e293b; border: 1px solid rgba(255,255,255,0.15); border-radius: 20px; color: #ffffff; }
        .modal-header { background: linear-gradient(135deg, #667eea, #764ba2); border-bottom: 1px solid rgba(255,255,255,0.1); border-radius: 20px 20px 0 0; padding: 20px 24px; }
        .modal-header h5 { color: #ffffff; margin: 0; font-family: 'Syne', sans-serif; font-size: 18px; }
        .modal-header .btn-close { filter: brightness(0) invert(1); }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.1); }
        .permission-group { background: rgba(15,23,42,0.5); border-radius: 12px; padding: 12px 15px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.08); }
        .permission-group.allowed { border-color: rgba(34,197,94,0.3); background: rgba(34,197,94,0.08); }
        .permission-group label { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #e2e8f0; font-weight: 500; cursor: pointer; }
        .locked-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #fbbf24; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-right: 8px; }
        .custom-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(102,126,234,0.15); border: 1px solid rgba(102,126,234,0.3); color: #a78bfa; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-right: 8px; }
        .password-strength-meter { height: 6px; border-radius: 3px; margin-top: 8px; background: #334155; overflow: hidden; }
        .password-strength-meter .fill { height: 100%; border-radius: 3px; transition: width 0.3s; width: 0%; }
        .strength-weak .fill { width: 25%; background: #ef4444; }
        .strength-fair .fill { width: 50%; background: #f59e0b; }
        .strength-good .fill { width: 75%; background: #3b82f6; }
        .strength-strong .fill { width: 100%; background: #22c55e; }
        .password-requirements { font-size: 11px; margin-top: 6px; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; border: none !important; padding: 10px 24px; border-radius: 10px; font-weight: 600; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
        .btn-secondary { background: rgba(100,116,139,0.3) !important; border: 1px solid rgba(255,255,255,0.15) !important; color: #e2e8f0 !important; padding: 10px 24px; border-radius: 10px; }
        .admin-avatar-small { width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0; }
        
        /* QR Scanner Modal */
        .qr-scanner-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.95);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .qr-scanner-box {
            background: #1e293b;
            border-radius: 28px;
            width: 90%;
            max-width: 500px;
            padding: 24px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .qr-scanner-box h3 {
            color: #fff;
            margin-bottom: 20px;
        }
        #qr-reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        #qr-result {
            margin-top: 20px;
            padding: 12px;
            border-radius: 12px;
            display: none;
        }
        .qr-success {
            background: rgba(34,216,122,0.2);
            border: 1px solid #22d87a;
            color: #22d87a;
        }
        .qr-error {
            background: rgba(255,77,109,0.2);
            border: 1px solid #ff4d6d;
            color: #ff4d6d;
        }
        .btn-close-scanner {
            margin-top: 20px;
            padding: 10px 25px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
        }
        
        @media (max-width: 1200px) { 
            .stats-row { grid-template-columns: repeat(2, 1fr); } 
        }
        @media (max-width: 992px) { 
            .content-area { padding: 24px; } 
            .topbar { padding: 14px 20px; } 
            .topbar-left h2 { font-size: 20px; } 
            .modal-dialog { margin: 1rem; } 
        }
        @media (max-width: 768px) { 
            .content-area { padding: 16px; } 
            .topbar { padding: 12px 16px; } 
            .topbar-left h2 { font-size: 18px; } 
            .topbar-right { gap: 10px; } 
            .user-details { display: none; } 
            .stats-row { grid-template-columns: 1fr; gap: 14px; } 
            .stat-card { padding: 20px 18px; } 
            .stat-value { font-size: 32px; } 
            .stat-icon { width: 40px; height: 40px; font-size: 18px; } 
            .panel-header { padding: 14px 16px; } 
            .panel-header h4 { font-size: 16px; } 
            .search-box { max-width: 100%; } 
            .admin-table { min-width: 650px; } 
            .admin-table th, .admin-table td { padding: 10px 12px; font-size: 12px; } 
            .btn-sm { padding: 5px 10px; font-size: 10px; } 
            .btn-create { padding: 8px 18px; font-size: 12px; }
            .btn-logout { padding: 8px 14px; font-size: 12px; } 
            .modal-body { padding: 1rem; } 
            .modal-header { padding: 1rem 1.5rem; } 
            .modal-footer { padding: 1rem 1.5rem; } 
            .form-control, .form-select { font-size: 16px; } 
            .user-info { padding: 6px 12px; } 
            .user-avatar { width: 36px; height: 36px; font-size: 13px; } 
        }
        @media (max-width: 576px) { 
            .content-area { padding: 12px; } 
            .stat-value { font-size: 28px; } 
            .topbar-right { width: 100%; justify-content: space-between; } 
            .btn-create { width: 100%; justify-content: center; margin-bottom: 8px; }
        }
        @media (max-width: 480px) { 
            .content-area { padding: 10px; } 
            .stat-card { padding: 16px 14px; } 
            .stat-value { font-size: 24px; } 
            .stat-label { font-size: 10px; } 
            .btn-sm { padding: 4px 8px; font-size: 9px; margin: 1px; } 
            .admin-table { min-width: 550px; } 
            .admin-table th, .admin-table td { padding: 8px 10px; font-size: 11px; } 
        }
    </style>
</head>
<body>
<div class="bg-blob blob1"></div>
<div class="bg-blob blob2"></div>
<div class="bg-blob blob3"></div>
<button class="sidebar-open-btn visible" id="sidebarOpenBtn"><i class="fas fa-bars"></i></button>
<div class="mobile-overlay" id="mobileOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="logo">
        <h1><span class="logo-icon"><i class="fas fa-calendar-check"></i></span><span>EventHub</span></h1>
        <button class="sidebar-toggle-btn" id="sidebarCloseBtn"><i class="fas fa-chevron-left"></i></button>
    </div>
    <ul class="nav-menu">
    @php $currentAdmin = auth('admin')->user(); @endphp
    
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
            <span>Dashboard</span>
        </a>
    </li>
    
    <!-- QR SCANNER BUTTON - Only show if user has scan_qr_codes permission -->
    @if($currentAdmin->can('scan_qr_codes'))
    <li class="nav-item">
        <a href="#" onclick="openQRScanner()" class="nav-link">
            <span class="nav-icon"><i class="fas fa-qrcode"></i></span>
            <span>Scan QR Code</span>
        </a>
    </li>
    @endif
    
    @if($currentAdmin->can('view_events'))
    <li class="nav-item">
        <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
            <span>Events</span>
        </a>
    </li>
    @endif
    
    @if($currentAdmin->hasRole('super_admin'))
    <li class="nav-item">
        <a href="{{ route('admin.admins.index') }}" class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
            <span>Admins</span>
        </a>
    </li>
    @endif
    
    @if($currentAdmin->can('view_users'))
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-users"></i></span>
            <span>Users</span>
        </a>
    </li>
    @endif
    </ul>
</aside>

<main class="main-content" id="mainContent">
    <div class="topbar">
        <div class="topbar-left"><h2>Admin Management</h2><div class="breadcrumb"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a> / Admins / Roles & Permissions</div></div>
        <div class="topbar-right">
            <button class="btn-create" id="openCreateAdminBtn"><i class="bi bi-person-plus-fill"></i> Create Admin</button>
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth('admin')->user()->name ?? 'AD', 0, 2)) }}</div>
                <div class="user-details"><span class="user-name">{{ auth('admin')->user()->name ?? 'Admin' }}</span><span class="user-role">{{ auth('admin')->user()->hasRole('super_admin') ? 'Super Admin' : 'Admin' }}</span></div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">@csrf<button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button></form>
        </div>
    </div>
    <div class="content-area">
        <div class="stats-row" id="statsContainer">
            <div class="stat-card blue"><div class="stat-icon"><i class="bi bi-shield-lock"></i></div><div class="stat-value" id="totalAdmins">0</div><div class="stat-label">Total Admins</div></div>
            <div class="stat-card green"><div class="stat-icon"><i class="bi bi-star"></i></div><div class="stat-value" id="superCount">0</div><div class="stat-label">Super Admins</div></div>
            <div class="stat-card orange"><div class="stat-icon"><i class="bi bi-calendar-event"></i></div><div class="stat-value" id="eventsManagerCount">0</div><div class="stat-label">Events Managers</div></div>
            <div class="stat-card purple"><div class="stat-icon"><i class="bi bi-eye"></i></div><div class="stat-value" id="viewerCount">0</div><div class="stat-label">Viewers</div></div>
            <div class="stat-card red"><div class="stat-icon"><i class="bi bi-person-gear"></i></div><div class="stat-value" id="customCount">0</div><div class="stat-label">Custom Roles</div></div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <h4><i class="bi bi-people-fill me-2"></i> All Administrators</h4>
                <div class="search-box"><i class="bi bi-search"></i><input type="text" id="searchInput" placeholder="Search by name or email..."></div>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Admin</th>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="adminsTableBody">
                        @php
                            use App\Models\Admin;
                            $adminsList = Admin::with('roles')->get();
                            $totalSuper = $adminsList->filter(fn($a) => $a->getRoleNames()->first() === 'super_admin')->count();
                            $currentAdmin = auth('admin')->user();
                            $protectedEmail = 'rayanabifrem6@gmail.com';
                        @endphp
                        @forelse($adminsList as $admin)
                        @php 
                            $role = $admin->getRoleNames()->first() ?? 'viewer'; 
                            $isSuper = ($role === 'super_admin'); 
                            $isProtected = ($admin->email === $protectedEmail);
                            $isSelf = ($admin->id === $currentAdmin->id);
                            $cannotEdit = ($isProtected || $isSelf);
                            $cannotDelete = ($isProtected || $isSelf);
                            $permsList = $admin->getAllPermissions()->pluck('name')->toArray(); 
                        @endphp
                        <tr class="admin-row" data-id="{{ $admin->id }}" data-name="{{ strtolower($admin->name) }}" data-email="{{ $admin->email }}" data-role="{{ $role }}">
                            <td><strong style="color:#a78bfa;">#{{ $admin->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="admin-avatar-small">{{ strtoupper(substr($admin->name,0,2)) }}</div>
                                    <div>
                                        <div class="admin-name-text">{{ $admin->name }}</div>
                                        <div class="admin-email-text">{{ $admin->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge role-{{ in_array($role,['super_admin','events_manager','viewer']) ? $role : 'custom' }}">{{ ucfirst(str_replace('_',' ',$role)) }}</span></td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($permsList as $p)
                                        <span class="permission-tag">{{ str_replace('_',' ',$p) }}</span>
                                    @endforeach
                                    @if(empty($permsList))
                                        <span class="text-muted">No permissions</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ optional($admin->created_at)->format('M d, Y') }}</small>
                                @if($isProtected) <span class="badge bg-danger ms-2">Protected</span>@endif
                            </td>
                            <td>
                                @if(!$cannotEdit)
                                    <button class="btn-sm btn-edit" onclick='editAdminModal({{ $admin->id }}, "{{ addslashes($admin->name) }}", "{{ $admin->email }}", "{{ addslashes($role) }}", {{ json_encode($permsList) }})'>
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                @else
                                    <button class="btn-sm btn-edit" disabled style="opacity:0.5; cursor:not-allowed;">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                @endif
                                
                                <button class="btn-sm btn-perms" onclick='viewPermsOnly({{ $admin->id }}, "{{ addslashes($admin->name) }}", {{ json_encode($permsList) }})'>
                                    <i class="bi bi-lock"></i> Permissions
                                </button>
                                
                                @if(!$cannotDelete)
                                    <button class="btn-sm btn-delete" onclick='confirmDelete({{ $admin->id }}, "{{ addslashes($admin->name) }}")'>
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                @else
                                    <button class="btn-sm btn-delete" disabled style="opacity:0.5; cursor:not-allowed;">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No admins found in database</p>
                                    <button class="btn-create mt-2" onclick="openCreateModal()"><i class="bi bi-person-plus"></i> Create First Admin</button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- CREATE/EDIT MODAL -->
<div class="modal fade" id="adminModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalTitle"><i class="bi bi-person-plus me-2"></i> Create New Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="adminForm">
                    <input type="hidden" id="adminId">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span style="color:#f87171;">*</span></label>
                            <input type="text" id="adminName" class="form-control" placeholder="e.g., John Carter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span style="color:#f87171;">*</span></label>
                            <input type="email" id="adminEmail" class="form-control" placeholder="admin@example.com" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password <span id="passRequired" style="color:#f87171;">*</span></label>
                            <div class="input-group">
                                <input type="password" id="adminPassword" class="form-control" placeholder="At least 8 chars, uppercase, number, special" oninput="checkPwdStrength()">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd()"><i class="bi bi-eye" id="togglePwdIcon"></i></button>
                            </div>
                            <div class="password-strength-meter"><div class="fill" id="strengthFill"></div></div>
                            <div class="password-requirements">
                                <small id="reqLen">🔴 8+ chars</small>
                                <small id="reqUp">🔴 Upper</small>
                                <small id="reqNum">🔴 Number</small>
                                <small id="reqSpec">🔴 Special</small>
                            </div>
                            <small class="text-muted">Leave blank to keep current password (edit)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" id="adminPasswordConfirm" class="form-control" placeholder="Re-enter password">
                            <small id="confirmMsg" class="text-danger" style="display:none;">Passwords do not match</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role Type</label>
                        <select id="adminRoleSelect" class="form-select">
                            <option value="super_admin">Super Admin (Full Access)</option>
                            <option value="events_manager" selected>Events Manager</option>
                            <option value="viewer">Viewer (Read Only)</option>
                            <option value="custom">Custom Role</option>
                        </select>
                    </div>
                    <div class="mb-3" id="customRoleContainer" style="display:none;">
                        <label class="form-label">Custom Role Name <span style="color:#f87171;">*</span></label>
                        <input type="text" id="customRoleName" class="form-control" placeholder="e.g., Supervisor, Content Manager">
                    </div>
                    <input type="hidden" id="adminRole" value="events_manager">
                    <div class="mb-3">
                        <label>
                            <span class="locked-badge" id="lockBadge"><i class="bi bi-lock-fill"></i> Locked by Role</span>
                            <span class="custom-badge" id="customBadge" style="display:none;"><i class="bi bi-pencil-fill"></i> Select Permissions</span>
                        </label>
                        <div id="permContainer" class="row">
                            <div class="col-md-6" id="permCol1"></div>
                            <div class="col-md-6" id="permCol2"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="saveAdminBtn">Save Admin</button>
            </div>
        </div>
    </div>
</div>

<!-- VIEW PERMISSIONS MODAL -->
<div class="modal fade" id="viewPermsModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5><i class="bi bi-lock"></i> Permissions: <span id="viewPermName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="viewPermsList" class="row"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar'), mainC = document.getElementById('mainContent'), openBtn = document.getElementById('sidebarOpenBtn'), closeBtn = document.getElementById('sidebarCloseBtn'), overlay = document.getElementById('mobileOverlay');
    function isMobile(){ return window.innerWidth <= 768; }
    function closeSidebar(){ if(isMobile()){ sidebar.classList.remove('mobile-open'); overlay.classList.remove('active'); openBtn.classList.add('visible'); } else { sidebar.classList.add('collapsed'); mainC.classList.add('expanded'); openBtn.classList.add('visible'); } }
    function openSidebar(){ if(isMobile()){ sidebar.classList.add('mobile-open'); overlay.classList.add('active'); openBtn.classList.remove('visible'); } else { sidebar.classList.remove('collapsed'); mainC.classList.remove('expanded'); openBtn.classList.remove('visible'); } }
    openBtn.onclick = openSidebar; if(closeBtn) closeBtn.onclick = closeSidebar; overlay.onclick = closeSidebar;
    window.addEventListener('resize',()=>{ if(isMobile()){ sidebar.classList.remove('collapsed'); mainC.classList.remove('expanded'); if(!sidebar.classList.contains('mobile-open')) openBtn.classList.add('visible'); } else { sidebar.classList.remove('mobile-open'); overlay.classList.remove('active'); if(!sidebar.classList.contains('collapsed')) openBtn.classList.remove('visible'); } });
    
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    let adminModalObj, viewPermsModalObj;
    let currentCustomPerms = [];
    let html5QrCode = null;

    const roleDefaults = {
        super_admin: ['view_dashboard','view_events','create_events','edit_events','delete_events','view_users','manage_users','view_admins','manage_admins','view_reviews','delete_reviews','view_messages','delete_messages','view_reports','scan_qr_codes'],
        events_manager: ['view_dashboard','view_events','create_events','edit_events','delete_events','view_reviews','delete_reviews','view_messages','delete_messages','view_reports','scan_qr_codes'],
        viewer: ['view_dashboard','view_events','view_users','view_reviews','view_messages','view_reports'],
        custom: []
    };
    const INDIVIDUAL_PERMS = [
        {value:'view_dashboard',label:'View Dashboard',col:1},{value:'view_events',label:'View Events',col:1},{value:'create_events',label:'Create Events',col:1},
        {value:'edit_events',label:'Edit Events',col:1},{value:'delete_events',label:'Delete Events',col:1},{value:'view_users',label:'View Users',col:2},
        {value:'manage_users',label:'Manage Users',col:2},{value:'view_admins',label:'View Admins',col:2},{value:'manage_admins',label:'Manage Admins',col:2},
        {value:'view_reviews',label:'View Reviews',col:1},{value:'delete_reviews',label:'Delete Reviews',col:1},{value:'view_messages',label:'View Messages',col:2},
        {value:'delete_messages',label:'Delete Messages',col:2},{value:'view_reports',label:'View Reports',col:2},
        {value:'scan_qr_codes',label:'Scan QR Codes',col:1}
    ];
    const GROUP_PERMS = [
        {value:'manage_events',label:'View & Manage Events',col:1, perms:['view_events','create_events','edit_events','delete_events']},
        {value:'manage_users',label:'View & Manage Users',col:2, perms:['view_users','manage_users']},
        {value:'manage_admins',label:'View & Manage Admins',col:2, perms:['view_admins','manage_admins']},
        {value:'manage_reviews',label:'View & Delete Reviews',col:1, perms:['view_reviews','delete_reviews']},
        {value:'manage_messages',label:'View & Delete Messages',col:2, perms:['view_messages','delete_messages']},
        {value:'scan_qr_codes',label:'Scan QR Codes',col:1, perms:['scan_qr_codes']}
    ];

    // ============ QR SCANNER FUNCTIONS ============
    function openQRScanner() {
        const modalHtml = `
            <div id="qrScannerModal" class="qr-scanner-modal">
                <div class="qr-scanner-box">
                    <h3><i class="fas fa-qrcode"></i> Scan QR Code</h3>
                    <div id="qr-reader"></div>
                    <div id="qr-result"></div>
                    <button class="btn-close-scanner" onclick="closeQRScanner()">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        if (typeof Html5Qrcode !== 'undefined') {
            html5QrCode = new Html5Qrcode("qr-reader");
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                html5QrCode.stop();
                
                const resultDiv = document.getElementById('qr-result');
                resultDiv.style.display = 'block';
                
                if (decodedText.includes('/checkin/')) {
                    resultDiv.innerHTML = `<div class="qr-success" style="padding: 12px; border-radius: 12px;">✅ QR Code detected! Redirecting...</div>`;
                    window.location.href = decodedText;
                } else {
                    resultDiv.innerHTML = `<div class="qr-error" style="padding: 12px; border-radius: 12px;">⚠️ Invalid check-in QR code. Please scan a valid EventHub QR code.</div>`;
                    setTimeout(() => {
                        html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, qrCodeSuccessCallback);
                        resultDiv.style.display = 'none';
                    }, 3000);
                }
            };
            
            html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, qrCodeSuccessCallback)
                .catch(err => {
                    document.getElementById('qr-result').innerHTML = `<div class="qr-error" style="padding: 12px; border-radius: 12px;">❌ Cannot access camera. Please allow camera permissions.</div>`;
                    document.getElementById('qr-result').style.display = 'block';
                });
        } else {
            document.getElementById('qr-result').innerHTML = `<div class="qr-error" style="padding: 12px; border-radius: 12px;">❌ QR scanner library not loaded. Please refresh the page.</div>`;
            document.getElementById('qr-result').style.display = 'block';
        }
    }
    
    function closeQRScanner() {
        if (html5QrCode) {
            html5QrCode.stop().catch(err => console.log('Stop error:', err));
            html5QrCode = null;
        }
        const modal = document.getElementById('qrScannerModal');
        if (modal) modal.remove();
    }

    function renderPermsByRole(){
        const role = document.getElementById('adminRoleSelect').value;
        const allowed = roleDefaults[role] || [];
        let c1='',c2='';
        INDIVIDUAL_PERMS.forEach(p=>{
            const checked = allowed.includes(p.value);
            if(p.col===1){
                c1+=`<div class="permission-group ${checked?'allowed':''}"><label><input type="checkbox" disabled ${checked?'checked':''}> ${p.label} ${checked?'✅':'🔒'}</label></div>`;
            } else {
                c2+=`<div class="permission-group ${checked?'allowed':''}"><label><input type="checkbox" disabled ${checked?'checked':''}> ${p.label} ${checked?'✅':'🔒'}</label></div>`;
            }
        });
        document.getElementById('permCol1').innerHTML = c1;
        document.getElementById('permCol2').innerHTML = c2;
    }
    
    function renderCustomPerms(){
        let c1='',c2='';
        if(!currentCustomPerms.includes('view_dashboard')) currentCustomPerms.push('view_dashboard');
        c1+=`<div class="permission-group allowed"><label><input type="checkbox" checked disabled> View Dashboard ✅ <small>(required)</small></label></div>`;
        GROUP_PERMS.forEach(g=>{
            const isChecked = g.perms.every(p=>currentCustomPerms.includes(p));
            let disabled = false;
            if((g.value==='manage_reviews'||g.value==='manage_messages') && !currentCustomPerms.includes('view_events')){
                disabled = true;
            }
            if(g.col===1){
                c1+=`<div class="permission-group"><label><input type="checkbox" value="${g.value}" ${isChecked?'checked':''} ${disabled?'disabled':''} onchange="toggleCustomGroup('${g.value}',this.checked)"> ${g.label}</label><small style="margin-left:28px;">Includes: ${g.perms.map(p=>p.replace('_',' ')).join(', ')}</small></div>`;
            } else {
                c2+=`<div class="permission-group"><label><input type="checkbox" value="${g.value}" ${isChecked?'checked':''} ${disabled?'disabled':''} onchange="toggleCustomGroup('${g.value}',this.checked)"> ${g.label}</label><small style="margin-left:28px;">Includes: ${g.perms.map(p=>p.replace('_',' ')).join(', ')}</small></div>`;
            }
        });
        document.getElementById('permCol1').innerHTML = c1;
        document.getElementById('permCol2').innerHTML = c2;
    }
    
    window.toggleCustomGroup = function(groupName,checked){
        const group = GROUP_PERMS.find(g=>g.value===groupName);
        if(!group) return;
        if(group.value === 'manage_events'){
            if(checked) group.perms.forEach(p=>{if(!currentCustomPerms.includes(p)) currentCustomPerms.push(p);});
            else { currentCustomPerms = currentCustomPerms.filter(p=>!group.perms.includes(p)); currentCustomPerms = currentCustomPerms.filter(p=>!['view_reviews','delete_reviews','view_messages','delete_messages'].includes(p));}
        } else {
            if(checked) group.perms.forEach(p=>{if(!currentCustomPerms.includes(p)) currentCustomPerms.push(p);});
            else currentCustomPerms = currentCustomPerms.filter(p=>!group.perms.includes(p));
        }
        if(!currentCustomPerms.includes('view_dashboard')) currentCustomPerms.push('view_dashboard');
        renderCustomPerms();
    };
    
    function updateRoleSelectUI(){
        const role = document.getElementById('adminRoleSelect').value;
        document.getElementById('adminRole').value = role;
        const customDiv = document.getElementById('customRoleContainer');
        const lockBadge = document.getElementById('lockBadge'), customBadge = document.getElementById('customBadge');
        if(role === 'custom'){
            customDiv.style.display='block';
            lockBadge.style.display='none';
            customBadge.style.display='inline-flex';
            renderCustomPerms();
        } else {
            customDiv.style.display='none';
            lockBadge.style.display='inline-flex';
            customBadge.style.display='none';
            renderPermsByRole();
        }
    }
    
    function getSelectedPerms(){
        const role = document.getElementById('adminRoleSelect').value;
        if(role === 'custom') return currentCustomPerms;
        return roleDefaults[role] || [];
    }

    function checkPwdStrength(){
        const pwd = document.getElementById('adminPassword').value;
        document.getElementById('reqLen').innerHTML = pwd.length>=8 ? '✅ 8+ chars' : '🔴 8+ chars';
        document.getElementById('reqUp').innerHTML = /[A-Z]/.test(pwd) ? '✅ Upper' : '🔴 Upper';
        document.getElementById('reqNum').innerHTML = /[0-9]/.test(pwd) ? '✅ Number' : '🔴 Number';
        document.getElementById('reqSpec').innerHTML = /[!@#$%^&*(),.?":{}|<>]/.test(pwd) ? '✅ Special' : '🔴 Special';
        let strength=0; if(pwd.length>=8) strength++; if(/[A-Z]/.test(pwd)) strength++; if(/[0-9]/.test(pwd)) strength++; if(/[!@#$%^&*(),.?":{}|<>]/.test(pwd)) strength++;
        const fill = document.getElementById('strengthFill');
        if(pwd.length===0) fill.style.width='0%';
        else if(strength<=1) fill.style.width='25%'; else if(strength===2) fill.style.width='50%'; else if(strength===3) fill.style.width='75%'; else fill.style.width='100%';
        const confirm = document.getElementById('adminPasswordConfirm').value;
        document.getElementById('confirmMsg').style.display = (confirm && pwd !== confirm) ? 'block' : 'none';
    }
    
    function togglePwd(){ const inp=document.getElementById('adminPassword'); const icon=document.getElementById('togglePwdIcon'); if(inp.type==='password'){ inp.type='text'; icon.className='bi bi-eye-slash';} else {inp.type='password'; icon.className='bi bi-eye';} }
    document.getElementById('adminPasswordConfirm')?.addEventListener('input',()=>checkPwdStrength());
    
    function openCreateModal(){
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-person-plus me-2"></i> Create New Admin';
        document.getElementById('adminForm').reset();
        document.getElementById('adminId').value='';
        document.getElementById('adminRoleSelect').value='events_manager';
        document.getElementById('adminRole').value='events_manager';
        document.getElementById('customRoleName').value='';
        document.getElementById('customRoleContainer').style.display='none';
        document.getElementById('passRequired').style.display='inline';
        currentCustomPerms = [];
        updateRoleSelectUI();
        adminModalObj.show();
    }
    
    window.editAdminModal = function(id,name,email,role,perms){
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i> Edit Admin';
        document.getElementById('adminId').value = id;
        document.getElementById('adminName').value = name;
        document.getElementById('adminEmail').value = email;
        document.getElementById('adminPassword').value = '';
        document.getElementById('adminPasswordConfirm').value = '';
        document.getElementById('passRequired').style.display = 'none';
        const predefined = ['super_admin','events_manager','viewer'];
        if(predefined.includes(role)){
            document.getElementById('adminRoleSelect').value = role;
            document.getElementById('adminRole').value = role;
            currentCustomPerms = [];
            document.getElementById('customRoleName').value = '';
        } else {
            document.getElementById('adminRoleSelect').value = 'custom';
            document.getElementById('adminRole').value = 'custom';
            currentCustomPerms = [...perms];
            if(!currentCustomPerms.includes('view_dashboard')) currentCustomPerms.push('view_dashboard');
            document.getElementById('customRoleName').value = role;
        }
        updateRoleSelectUI();
        adminModalObj.show();
    };
    
    window.viewPermsOnly = function(id,name,perms){
        document.getElementById('viewPermName').innerText = name;
        let c1='',c2='';
        INDIVIDUAL_PERMS.forEach(p=>{
            const ch = perms.includes(p.value);
            const html = `<div class="permission-group ${ch?'allowed':''}"><label><input type="checkbox" disabled ${ch?'checked':''}> ${p.label} ${ch?'✅':'🔒'}</label></div>`;
            if(p.col===1) c1+=html; else c2+=html;
        });
        document.getElementById('viewPermsList').innerHTML = `<div class="col-md-6">${c1}</div><div class="col-md-6">${c2}</div>`;
        viewPermsModalObj.show();
    };
    
    window.confirmDelete = function(id,name){
        const row = document.querySelector(`.admin-row[data-id="${id}"]`);
        const email = row?.getAttribute('data-email') || '';
        if(email === 'rayanabifrem6@gmail.com') {
            Swal.fire({ title: 'Cannot Delete', text: 'This super admin account is protected and cannot be deleted.', icon: 'warning', background: '#1e293b', color: '#e2e8f0', confirmButtonColor: '#667eea' });
            return;
        }
        
        Swal.fire({ title: 'Delete Admin?', text: `Delete "${name}" permanently?`, icon:'warning', background:'#1e293b', color:'#e2e8f0', showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#475569', confirmButtonText:'Yes, delete' }).then(res=>{
            if(res.isConfirmed){
                Swal.fire({ title:'Deleting...', didOpen:()=>Swal.showLoading(), allowOutsideClick:false, background:'#1e293b' });
                fetch(`/admin/admins/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'} })
                .then(r=>r.json()).then(data=>{ if(data.success) Swal.fire('Deleted!','','success').then(()=>location.reload()); else Swal.fire('Error',data.message,'error'); }).catch(()=>Swal.fire('Error','Network error','error'));
            }
        });
    };
    
    document.getElementById('saveAdminBtn').addEventListener('click',function(){
        const id = document.getElementById('adminId').value;
        const name = document.getElementById('adminName').value.trim();
        const email = document.getElementById('adminEmail').value.trim();
        const password = document.getElementById('adminPassword').value;
        const passConfirm = document.getElementById('adminPasswordConfirm').value;
        let role = document.getElementById('adminRole').value.trim();
        const customRoleName = document.getElementById('customRoleName').value.trim();
        const permissions = getSelectedPerms();
        if(!name || !email) return Swal.fire('Error','Name and email required','error');
        if(!id && !password) return Swal.fire('Error','Password required for new admin','error');
        if(password && (password.length<8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*(),.?":{}|<>]/.test(password))) return Swal.fire('Error','Password must be 8+ chars, uppercase, number, special','error');
        if(password && password !== passConfirm) return Swal.fire('Error','Passwords do not match','error');
        if(role === 'custom' && !customRoleName) return Swal.fire('Error','Please enter custom role name','error');
        if(role === 'custom') role = customRoleName.toLowerCase().replace(/\s+/g,'_');
        const payload = { name, email, role, permissions, custom_role_name: customRoleName };
        if(password) payload.password = password;
        const url = id ? `/admin/admins/${id}` : '/admin/admins';
        const method = id ? 'PUT' : 'POST';
        const btn = this; btn.disabled=true; btn.innerHTML='<span class="spinner-border spinner-border-sm"></span> Saving...';
        fetch(url,{ method, headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}, body:JSON.stringify(payload) })
        .then(r=>r.json()).then(data=>{ if(data.success){ adminModalObj.hide(); location.reload(); } else { Swal.fire('Error',data.message||'Error saving','error'); } })
        .catch(err=>Swal.fire('Error','Server error','error')).finally(()=>{ btn.disabled=false; btn.innerHTML='Save Admin'; });
    });
    
    function filterAdmins(){
        const term = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.admin-row').forEach(row=>{
            const name = row.getAttribute('data-name')||'', email = row.getAttribute('data-email')||'';
            row.style.display = (name.includes(term)||email.includes(term))?'':'none';
        });
        updateStats();
    }
    
    function updateStats(){
        let rows = Array.from(document.querySelectorAll('.admin-row')).filter(r=>r.style.display!=='none');
        let total=rows.length, superC=0, eventsC=0, viewerC=0, customC=0;
        rows.forEach(r=>{
            let roleVal = r.getAttribute('data-role');
            if(roleVal==='super_admin') superC++;
            else if(roleVal==='events_manager') eventsC++;
            else if(roleVal==='viewer') viewerC++;
            else if(roleVal && roleVal!=='undefined') customC++;
        });
        document.getElementById('totalAdmins').innerText = total;
        document.getElementById('superCount').innerText = superC;
        document.getElementById('eventsManagerCount').innerText = eventsC;
        document.getElementById('viewerCount').innerText = viewerC;
        document.getElementById('customCount').innerText = customC;
    }
    
    document.getElementById('searchInput').addEventListener('keyup',filterAdmins);
    document.getElementById('adminRoleSelect').addEventListener('change',updateRoleSelectUI);
    document.getElementById('openCreateAdminBtn').addEventListener('click',openCreateModal);
    document.addEventListener('DOMContentLoaded',()=>{
        adminModalObj = new bootstrap.Modal(document.getElementById('adminModal'));
        viewPermsModalObj = new bootstrap.Modal(document.getElementById('viewPermsModal'));
        updateRoleSelectUI();
        updateStats();
        document.getElementById('adminPassword').addEventListener('input',checkPwdStrength);
        checkPwdStrength();
    });
    window.filterAdmins = filterAdmins;
    window.openCreateModal = openCreateModal;
    window.openQRScanner = openQRScanner;
    window.closeQRScanner = closeQRScanner;
</script>
</body>
</html>