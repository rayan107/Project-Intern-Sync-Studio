<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>User Details – EventHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            min-height: 100vh;
            display: flex;
            position: relative;
            color: var(--text-light);
        }

        /* Background blobs */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }
        @media (max-width: 768px) {
            .bg-blob { display: none; }
        }
        .blob1 { width: 500px; height: 500px; background: #667eea; top: -100px; right: -100px; animation: float1 20s infinite alternate; }
        .blob2 { width: 400px; height: 400px; background: #a78bfa; bottom: -80px; left: -80px; animation: float2 18s infinite alternate; }
        .blob3 { width: 350px; height: 350px; background: #f472b6; top: 50%; left: 50%; animation: float3 22s infinite alternate; }

        @keyframes float1 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(-60px,50px) scale(1.2); } }
        @keyframes float2 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(60px,-40px) scale(1.3); } }
        @keyframes float3 { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(-40px,-60px) scale(1.1); } }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-right: 1px solid var(--border-glass);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1);
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
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            flex-shrink: 0;
            font-size: 18px;
        }
        .sidebar-toggle-btn:hover { background: rgba(102,126,234,0.4); border-color: #667eea; }

        .sidebar-open-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 22px;
            box-shadow: 0 8px 25px rgba(102,126,234,0.5);
            transition: all 0.2s;
            backdrop-filter: blur(10px);
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

        .nav-menu {
            list-style: none;
            padding: 20px 0;
            flex: 1;
            min-width: var(--sidebar-width);
        }
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
            white-space: nowrap;
            font-size: 15px;
            font-weight: 500;
            border-radius: 0 8px 8px 0;
            margin-right: 12px;
        }
        .nav-link:hover { background: rgba(102,126,234,0.12); color: #fff; border-left-color: #667eea; transform: translateX(4px); }
        .nav-link.active { background: rgba(102,126,234,0.18); color: #fff; border-left-color: #a78bfa; box-shadow: 0 4px 15px rgba(102,126,234,0.2); }
        .nav-icon { font-size: 20px; width: 26px; text-align: center; }

        /* Main content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            position: relative;
            z-index: 1;
            min-width: 0;
        }
        @media (max-width: 768px) { .main-content { margin-left: 0 !important; } }
        .main-content.expanded { margin-left: 0; }

        /* Topbar */
        .topbar {
            background: rgba(15,23,42,0.5);
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
            gap: 12px;
        }
        .topbar-left h2 { color: #fff; font-size: 24px; font-weight: 700; font-family: 'Syne', sans-serif; margin: 0; }
        .breadcrumb { color: #94a3b8; font-size: 13px; margin-top: 5px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .breadcrumb a { color: #667eea; text-decoration: none; }
        .topbar-right { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .user-info {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.05);
            padding: 8px 16px; border-radius: 14px;
            border: 1px solid var(--border-glass);
        }
        .user-avatar {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 15px;
        }
        .user-details { display: flex; flex-direction: column; }
        .user-name { color: #e2e8f0; font-weight: 600; font-size: 13px; }
        .user-role { color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-logout {
            padding: 10px 20px;
            background: rgba(239,68,68,0.2);
            color: #fca5a5;
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 12px; cursor: pointer;
            font-size: 13px; font-weight: 600;
            transition: all 0.3s; display: flex; align-items: center; gap: 6px;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.4); color: #fff; transform: translateY(-2px); }

        .content-area { padding: 36px; flex: 1; max-width: 1400px; margin: 0 auto; width: 100%; }

        /* Back button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            margin-bottom: 24px;
        }
        .btn-back:hover {
            background: rgba(102, 126, 234, 0.15);
            color: #fff;
            border-color: #667eea;
            transform: translateY(-2px);
        }

        /* Cards */
        .custom-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .card-header-gradient {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
            padding: 20px 28px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .card-header-gradient h3 {
            margin: 0;
            font-weight: 700;
            font-size: 18px;
            color: #fff;
            font-family: 'Syne', sans-serif;
        }
        .card-body { padding: 28px; }

        /* User profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 0;
            flex-wrap: wrap;
        }
        .user-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 28px;
            font-family: 'Syne', sans-serif;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
        }
        .user-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            flex: 1;
        }
        .detail-item {
            background: rgba(15, 23, 42, 0.3);
            border-radius: 14px;
            padding: 16px 20px;
            border: 1px solid var(--border-glass);
        }
        .detail-label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #e2e8f0;
            word-break: break-word;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-active {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        /* Table */
        .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        .modern-table th {
            text-align: left;
            padding: 14px 20px;
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
            background: rgba(15, 23, 42, 0.3);
            border-bottom: 1px solid var(--border-glass);
            white-space: nowrap;
        }
        .modern-table td {
            padding: 14px 20px;
            font-size: 13px;
            color: #e2e8f0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }
        .modern-table tbody tr:hover td { background: rgba(102, 126, 234, 0.06); }
        .modern-table tbody tr:last-child td { border-bottom: none; }

        .event-link {
            color: #a78bfa;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .event-link:hover { color: #c4b5fd; text-decoration: underline; }
        .event-date-cell { color: #94a3b8; font-size: 12px; }
        .event-location-cell { color: #94a3b8; font-size: 13px; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #94a3b8;
        }
        .empty-state i { font-size: 50px; display: block; margin-bottom: 16px; opacity: 0.3; }
        .empty-state p { font-size: 15px; margin: 0; }

        /* ============================================ */
        /* RESPONSIVE - EXACTLY LIKE USERS PAGE */
        /* ============================================ */
        
        @media (max-width: 1200px) {
            .content-area { padding: 28px; }
        }
        
        @media (max-width: 992px) {
            .content-area { padding: 24px; }
            .topbar { padding: 14px 20px; }
            .topbar-left h2 { font-size: 20px; }
            .user-detail-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 768px) {
            .content-area { padding: 16px; }
            .topbar { padding: 12px 16px; }
            .topbar-left h2 { font-size: 18px; }
            .topbar-right { gap: 10px; }
            .user-details { display: none; }
            
            .card-header-gradient { padding: 14px 18px; }
            .card-header-gradient h3 { font-size: 16px; }
            .card-body { padding: 18px; }
            
            .user-profile { flex-direction: column; text-align: center; }
            .user-avatar-large { width: 70px; height: 70px; font-size: 24px; }
            .user-detail-grid { grid-template-columns: 1fr; gap: 12px; width: 100%; }
            .detail-item { padding: 12px 16px; }
            .detail-value { font-size: 14px; }
            
            .modern-table { min-width: 550px; }
            .modern-table th, .modern-table td { padding: 10px 12px; font-size: 11px; }
            
            .btn-back { padding: 8px 16px; font-size: 12px; margin-bottom: 16px; }
            
            .user-info { padding: 6px 12px; }
            .user-avatar { width: 36px; height: 36px; font-size: 13px; }
            .btn-logout { padding: 8px 14px; font-size: 12px; }
        }
        
        @media (max-width: 576px) {
            .content-area { padding: 12px; }
            .topbar-right { width: 100%; justify-content: space-between; }
            .card-body { padding: 12px; }
            .detail-item { padding: 10px 12px; }
            .btn-logout span { display: none; }
            .btn-logout i { margin: 0; }
            .btn-logout { padding: 8px 12px; }
        }
        
        @media (max-width: 480px) {
            .content-area { padding: 10px; }
            .topbar-left h2 { font-size: 16px; }
            .breadcrumb { font-size: 10px; }
            .card-header-gradient { padding: 10px 14px; }
            .card-header-gradient h3 { font-size: 14px; }
            .detail-value { font-size: 13px; }
            .detail-label { font-size: 9px; }
            .status-badge { padding: 3px 8px; font-size: 10px; }
            .empty-state { padding: 30px 15px; }
            .empty-state i { font-size: 40px; }
            .empty-state p { font-size: 13px; }
            .btn-back { padding: 6px 12px; font-size: 11px; }
            .user-avatar-large { width: 60px; height: 60px; font-size: 20px; }
            .modern-table { min-width: 480px; }
            .modern-table th, .modern-table td { padding: 8px 10px; font-size: 10px; }
            .event-link { font-size: 11px; }
            .event-location-cell { font-size: 11px; }
        }
        
        @media (max-width: 375px) {
            .modern-table { min-width: 420px; }
            .event-link i { display: none; }
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
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span><span>Dashboard</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.events.index') }}" class="nav-link"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span><span>Events</span></a></li>
            @if(auth('admin')->check() && auth('admin')->user()->hasRole('super_admin'))
            <li class="nav-item"><a href="{{ route('admin.admins.index') }}" class="nav-link"><span class="nav-icon"><i class="fas fa-user-shield"></i></span><span>Admins</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link active"><span class="nav-icon"><i class="fas fa-users"></i></span><span>Users</span></a></li>
            @endif
        </ul>
    </aside>

    <main class="main-content" id="mainContent">
        <div class="topbar">
            <div class="topbar-left">
                <h2>User Details</h2>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="{{ route('admin.users.index') }}">Users</a>
                    <i class="fas fa-chevron-right"></i> {{ Str::limit($user->name, 25) }}
                </div>
            </div>
            <div class="topbar-right">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth('admin')->user()->name ?? 'AD', 0, 2)) }}</div>
                    <div class="user-details">
                        <span class="user-name">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                        <span class="user-role">{{ auth('admin')->user()->hasRole('super_admin') ? 'Super Admin' : (auth('admin')->user()->getRoleNames()->first() ?? 'Admin') }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="content-area">
            <a href="{{ route('admin.users.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>

            <!-- User Information Card -->
            <div class="custom-card">
                <div class="card-header-gradient">
                    <i class="fas fa-user-circle" style="font-size:22px;"></i>
                    <h3>User Information</h3>
                </div>
                <div class="card-body">
                    <div class="user-profile">
                        <div class="user-avatar-large">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="user-detail-grid">
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-user"></i> Full Name</div>
                                <div class="detail-value">{{ $user->name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-envelope"></i> Email Address</div>
                                <div class="detail-value">{{ $user->email }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-calendar-alt"></i> Joined Date</div>
                                <div class="detail-value">{{ $user->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-clock"></i> Member Since</div>
                                <div class="detail-value">{{ $user->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-ticket-alt"></i> Events Registered</div>
                                <div class="detail-value">{{ $user->events->count() }} {{ Str::plural('event', $user->events->count()) }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-circle"></i> Status</div>
                                <div class="detail-value">
                                    <span class="status-badge status-active">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registered Events Card -->
            <div class="custom-card">
                <div class="card-header-gradient">
                    <i class="fas fa-calendar-check" style="font-size:22px;"></i>
                    <h3>Registered Events</h3>
                    <span style="margin-left:auto;background:rgba(255,255,255,0.15);padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;">
                        {{ $user->events->count() }} {{ Str::plural('event', $user->events->count()) }}
                    </span>
                </div>
                <div class="card-body" style="padding:0;">
                    @if($user->events->count())
                        <div class="table-wrapper">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Event Title</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->events as $event)
                                        <tr>
                                            <td><strong style="color:#a78bfa;">#{{ $event->id }}</strong></td>
                                            <td>
                                                <a href="{{ route('admin.events.show', $event->id) }}" class="event-link">
                                                    <i class="fas fa-calendar-alt"></i> {{ Str::limit($event->title, 35) }}
                                                </a>
                                            </td>
                                            <td class="event-date-cell">
                                                <i class="far fa-calendar"></i>
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                                <br>
                                                <small style="color:#64748b;">
                                                    <i class="far fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                                </small>
                                            </td>
                                            <td class="event-location-cell">
                                                <i class="fas fa-map-marker-alt" style="color:#f87171;"></i>
                                                {{ Str::limit($event->location, 30) }}
                                            </td>
                                            <td>
                                                @if($event->price > 0)
                                                    <span style="background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                                                        ${{ number_format($event->price, 2) }}
                                                    </span>
                                                @else
                                                    <span style="background:rgba(6,182,212,0.15);color:#22d3ee;border:1px solid rgba(6,182,212,0.3);padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                                                        Free
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                                                    <span style="background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                                                        <i class="fas fa-check-circle"></i> Upcoming
                                                    </span>
                                                @else
                                                    <span style="background:rgba(100,116,139,0.15);color:#94a3b8;border:1px solid rgba(100,116,139,0.3);padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                                                        <i class="fas fa-check"></i> Completed
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-xmark"></i>
                            <p>This user has not registered for any events yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        // Sidebar functionality - same as users page
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

        if (openBtn) openBtn.onclick = openSidebar;
        if (closeBtn) closeBtn.onclick = closeSidebar;
        if (mobileOverlay) mobileOverlay.onclick = closeSidebar;

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
                if (!sidebar.classList.contains('collapsed')) {
                    openBtn.classList.remove('visible');
                }
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();
    </script>
</body>
</html>