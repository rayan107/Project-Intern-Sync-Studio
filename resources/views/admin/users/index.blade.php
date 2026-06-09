<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Users | EventHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: radial-gradient(circle at 20% 30%, #a78bfa15 0%, transparent 35%),
                        radial-gradient(circle at 80% 70%, #818cf815 0%, transparent 40%),
                        linear-gradient(145deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            position: relative;
            overflow-x: hidden;
            color: #ffffff;
        }

        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }
        .blob1 { width: 500px; height: 500px; background: #667eea; top: -100px; right: -100px; animation: float1 20s infinite alternate; }
        .blob2 { width: 400px; height: 400px; background: #a78bfa; bottom: -80px; left: -80px; animation: float2 18s infinite alternate; }
        .blob3 { width: 350px; height: 350px; background: #f472b6; top: 50%; left: 50%; animation: float3 22s infinite alternate; }

        @keyframes float1 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(-60px,50px) scale(1.2); } }
        @keyframes float2 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(60px,-40px) scale(1.3); } }
        @keyframes float3 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(-40px,-60px) scale(1.1); } }

        .sidebar {
            width: var(--sidebar-width);
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(30px);
            border-right: 1px solid var(--border-glass);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.4);
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
        }
        
        @media (min-width: 769px) {
            .sidebar { transform: translateX(0) !important; }
            .sidebar.collapsed { transform: translateX(-100%); width: 0; opacity: 0; pointer-events: none; }
        }

        .logo {
            padding: 30px 24px;
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            align-items: center;
            justify-content: space-between;
            white-space: nowrap;
            min-width: var(--sidebar-width);
        }
        .logo h1 {
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
            font-family: 'Syne', sans-serif;
        }
        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 18px;
            flex-shrink: 0;
        }
        .sidebar-toggle-btn:hover {
            background: rgba(102, 126, 234, 0.4);
            border-color: #667eea;
        }

        .sidebar-open-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 22px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            transition: all 0.2s;
        }
        .sidebar-open-btn:hover { transform: scale(1.05); }
        .sidebar-open-btn.visible { display: flex; }

        .mobile-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(3px);
            z-index: 999;
            display: none;
        }
        .mobile-overlay.active { display: block; }

        .nav-menu { list-style: none; padding: 20px 0; flex: 1; min-width: var(--sidebar-width); }
        .nav-item { margin-bottom: 5px; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 28px;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            border-radius: 0 8px 8px 0;
            margin-right: 12px;
            font-size: 15px;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(102, 126, 234, 0.12);
            color: #fff;
            border-left-color: #667eea;
        }
        .nav-icon { width: 26px; text-align: center; font-size: 20px; }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s;
            position: relative;
            z-index: 1;
            min-width: 0;
        }
        @media (max-width: 768px) { .main-content { margin-left: 0 !important; } }
        .main-content.expanded { margin-left: 0; }

        .topbar {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(25px);
            padding: 18px 36px;
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
            flex-wrap: wrap;
            gap: 10px;
        }
        .topbar-left h2 { color: #fff; font-size: 24px; font-family: 'Syne', sans-serif; margin: 0; }
        .breadcrumb { color: #94a3b8; font-size: 13px; margin-top: 5px; }
        .breadcrumb a { color: #a78bfa; text-decoration: none; }
        .topbar-right { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 16px;
            border-radius: 14px;
            border: 1px solid var(--border-glass);
        }
        .user-avatar {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 15px; flex-shrink: 0;
        }
        .user-name { color: #fff; font-size: 13px; font-weight: 600; }
        .user-role { color: #94a3b8; font-size: 11px; }

        .btn-logout {
            padding: 10px 20px;
            background: rgba(239,68,68,0.2);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 12px;
            color: #fca5a5;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.4); color: #fff; transform: translateY(-2px); }

        .content-area { padding: 36px; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 22px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 28px 26px;
            border: 1px solid var(--border-glass);
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-4px); background: rgba(255,255,255,0.07); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; }
        .stat-card.blue::before { background: linear-gradient(90deg, #667eea, #764ba2); }
        .stat-card.green::before { background: linear-gradient(90deg, #22c55e, #16a34a); }
        .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 18px; }
        .blue .stat-icon { background: rgba(102,126,234,0.2); }
        .green .stat-icon { background: rgba(34,197,94,0.2); }
        .orange .stat-icon { background: rgba(245,158,11,0.2); }
        .purple .stat-icon { background: rgba(139,92,246,0.2); }
        .stat-label { font-size: 12px; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; }
        .stat-value { font-size: 42px; color: #fff; font-weight: 800; font-family: 'Syne', sans-serif; }

        .panel {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            overflow: hidden;
        }
        .panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .panel-header h4 { color: #fff; font-family: 'Syne', sans-serif; margin: 0; }
        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(15,23,42,0.5);
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            padding: 8px 14px;
            max-width: 280px;
            width: 100%;
        }
        .search-box input { background: transparent; border: none; outline: none; color: #fff; width: 100%; font-size: 14px; }
        .search-box input::placeholder { color: #94a3b8; }
        .search-box i { color: #94a3b8; flex-shrink: 0; }

        .table-wrap { overflow-x: auto; }
        .custom-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .custom-table th { text-align: left; padding: 14px 18px; font-size: 11px; color: #94a3b8; text-transform: uppercase; background: rgba(15,23,42,0.3); white-space: nowrap; font-weight: 600; }
        .custom-table td { padding: 16px 18px; color: #e2e8f0; border-bottom: 1px solid rgba(255,255,255,0.05); white-space: nowrap; }
        .custom-table td:nth-child(2) { white-space: normal; max-width: 200px; }
        .custom-table tbody tr:hover td { background: rgba(102,126,234,0.06); }

        .badge { padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
        .badge-success { background: rgba(34,197,94,0.2); color: #4ade80; }
        .badge-info { background: rgba(6,182,212,0.2); color: #22d3ee; }
        .badge-warning { background: rgba(245,158,11,0.2); color: #fbbf24; }

        .btn-sm { padding: 7px 14px; border-radius: 9px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; margin: 2px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
        .btn-view { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
        .btn-view:hover { transform: translateY(-2px); color: #fff; }
        .btn-delete { background: rgba(239,68,68,0.25); color: #fca5a5; }
        .btn-delete:hover { background: #ef4444; color: #fff; transform: translateY(-2px); }
        .btn-delete:disabled { opacity: 0.5; cursor: not-allowed; }

        .empty-state { text-align: center; padding: 60px; color: #94a3b8; }

        .pagination-wrap {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: center;
        }
        .pagination-wrap .pagination { margin: 0; }
        .pagination-wrap .page-link {
            background: rgba(15,23,42,0.5);
            border: 1px solid rgba(255,255,255,0.1);
            color: #e2e8f0;
        }
        .pagination-wrap .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: transparent;
        }

        .custom-toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #22c55e;
            color: white;
            padding: 14px 24px;
            border-radius: 12px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .custom-toast.error { background: #ef4444; }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 992px) { 
            .content-area { padding: 24px; } 
            .topbar { padding: 14px 20px; } 
            .topbar-left h2 { font-size: 20px; } 
        }
        @media (max-width: 768px) {
            .content-area { padding: 16px; } 
            .topbar { padding: 12px 16px; } 
            .topbar-left h2 { font-size: 18px; }
            .topbar-right { gap: 10px; } 
            .user-details { display: none; } 
            .stats-grid { grid-template-columns: 1fr; gap: 14px; }
            .stat-card { padding: 20px 18px; } 
            .stat-value { font-size: 32px; } 
            .stat-icon { width: 40px; height: 40px; font-size: 18px; }
            .panel-header { padding: 14px 16px; } 
            .search-box { max-width: 100%; } 
            .custom-table { min-width: 650px; }
            .custom-table th, .custom-table td { padding: 10px 12px; font-size: 12px; }
            .btn-sm { padding: 5px 10px; font-size: 10px; }
            .btn-logout { padding: 8px 14px; font-size: 12px; }
        }
        @media (max-width: 480px) { 
            .content-area { padding: 12px; } 
            .stat-value { font-size: 28px; }
            .custom-table { min-width: 550px; }
            .custom-table th, .custom-table td { padding: 8px 10px; font-size: 11px; }
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
        <div class="topbar-left">
            <h2>Users</h2>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a> / Users
            </div>
        </div>
        <div class="topbar-right">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}</div>
                <div class="user-details">
                    <div class="user-name">{{ auth('admin')->user()->name }}</div>
                    <div class="user-role">
                        {{ auth('admin')->user()->hasRole('super_admin') ? 'Super Admin' : (auth('admin')->user()->getRoleNames()->first() ?? 'Admin') }}
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7;padding:14px 20px;border-radius:14px;margin-bottom:24px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:14px 20px;border-radius:14px;margin-bottom:24px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($stats['totalUsers'] ?? 0) }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-label">New This Month</div>
                <div class="stat-value">{{ number_format($stats['newThisMonth'] ?? 0) }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-label">Joined Today</div>
                <div class="stat-value">{{ number_format($stats['newToday'] ?? 0) }}</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-label">In Events</div>
                <div class="stat-value">{{ number_format($stats['usersWithEvents'] ?? 0) }}</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h4><i class="fas fa-list"></i> All Registered Users</h4>
                <div class="search-box">
                    <i class="fas fa-search" style="color:#64748b;"></i>
                    <input type="text" id="searchInput" placeholder="Search users..." onkeyup="filterUsers()">
                </div>
            </div>
            <div class="table-wrap">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Events</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @php $currentAdmin = auth('admin')->user(); @endphp
                        @forelse($users ?? [] as $user)
                        <tr class="user-row"
                            data-name="{{ strtolower($user->name) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-user-id="{{ $user->id }}">
                            <td><strong style="color:#a78bfa;">#{{ $user->id }}</strong></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg, #667eea, #764ba2);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:12px;flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td style="color:#94a3b8;">{{ $user->email }}</td>
                            <td>
                                <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                                <small style="color:#64748b;">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <span class="badge {{ ($user->events_count ?? 0) > 0 ? 'badge-success' : 'badge-info' }}">
                                    <i class="fas fa-ticket-alt"></i> {{ $user->events_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                @if($user->created_at->gt(now()->subDays(7)))
                                    <span class="badge badge-success">New</span>
                                @else
                                    <span class="badge badge-warning">Member</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn-sm btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($currentAdmin->hasRole('super_admin') || $currentAdmin->hasPermissionTo('manage_users'))
                                <button class="btn-sm btn-delete delete-user-btn"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-users" style="font-size:40px;"></i>
                                    <p>No users registered yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($users) && $users->hasPages())
            <div class="pagination-wrap">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const openBtn = document.getElementById('sidebarOpenBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');
    const mobileOverlay = document.getElementById('mobileOverlay');

    function isMobile() { return window.innerWidth <= 768; }

    function closeSidebar() {
        if (isMobile()) { 
            sidebar.classList.remove('mobile-open'); 
            mobileOverlay.classList.remove('active'); 
            openBtn.classList.add('visible'); 
        } else { 
            sidebar.classList.add('collapsed'); 
            mainContent.classList.add('expanded'); 
            openBtn.classList.add('visible'); 
        }
    }

    function openSidebar() {
        if (isMobile()) { 
            sidebar.classList.add('mobile-open'); 
            mobileOverlay.classList.add('active'); 
            openBtn.classList.remove('visible'); 
        } else { 
            sidebar.classList.remove('collapsed'); 
            mainContent.classList.remove('expanded'); 
            openBtn.classList.remove('visible'); 
        }
    }

    openBtn.onclick = openSidebar;
    if (closeBtn) closeBtn.onclick = closeSidebar;
    mobileOverlay.onclick = closeSidebar;

    function handleResize() {
        if (isMobile()) {
            sidebar.classList.remove('collapsed'); 
            mainContent.classList.remove('expanded');
            if (!sidebar.classList.contains('mobile-open')) { 
                openBtn.classList.add('visible'); 
            }
        } else {
            sidebar.classList.remove('mobile-open'); 
            mobileOverlay.classList.remove('active');
            openBtn.classList.remove('visible');
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize();

    function filterUsers() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
        });
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    function showToast(message, type = 'success') { 
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) existingToast.remove();
        
        const toastDiv = document.createElement('div'); 
        toastDiv.className = `custom-toast ${type === 'error' ? 'error' : ''}`;
        const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>';
        toastDiv.innerHTML = `${icon} ${message}`;
        document.body.appendChild(toastDiv); 
        setTimeout(() => { 
            toastDiv.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toastDiv.remove(), 300);
        }, 3000); 
    }

    function escapeHtml(str) { 
        if (!str) return ''; 
        return String(str).replace(/[&<>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); 
    }

    async function deleteUser(userId, userName) {
        const result = await Swal.fire({
            title: '<span style="color:#fca5a5">Delete User</span>',
            html: `
                <div style="text-align:center">
                    <i class="fas fa-user-slash" style="font-size: 64px; color: #ef4444; margin: 20px 0;"></i>
                    <p style="font-size: 16px; margin-bottom: 10px;">Are you sure you want to delete</p>
                    <p style="font-size: 20px; font-weight: 700; color: #fca5a5;">"${escapeHtml(userName)}"?</p>
                    <p style="font-size: 13px; color: #94a3b8; margin-top: 15px;">
                        <i class="fas fa-info-circle"></i> This action cannot be undone.
                    </p>
                </div>
            `,
            background: '#1e293b',
            color: '#e2e8f0',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#475569',
            confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Delete User',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
            showCancelButton: true
        });
        
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#1e293b'
            });
            
            try {
                const response = await fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'User has been deleted successfully.',
                        icon: 'success',
                        background: '#1e293b',
                        confirmButtonColor: '#22c55e',
                        timer: 1500
                    }).then(() => { location.reload(); });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to delete user',
                        icon: 'error',
                        background: '#1e293b',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Network error. Please try again.',
                    icon: 'error',
                    background: '#1e293b',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    }

    // Attach delete event listeners
    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            deleteUser(userId, userName);
        });
    });

    window.filterUsers = filterUsers;
    window.deleteUser = deleteUser;
</script>
</body>
</html>