<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Dashboard - Statistics | EventHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        /* Same CSS as before - keeping it consistent */
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
            transition: transform 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.4);
        }

        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.mobile-open { transform: translateX(0); } }
        @media (min-width: 769px) { .sidebar { transform: translateX(0) !important; } .sidebar.collapsed { transform: translateX(-100%); width: 0; opacity: 0; pointer-events: none; } }

        .logo { padding: 30px 24px; border-bottom: 1px solid var(--border-glass); display: flex; align-items: center; justify-content: space-between; white-space: nowrap; min-width: var(--sidebar-width); }
        .logo h1 { color: #fff; font-size: 24px; font-weight: 700; display: flex; align-items: center; gap: 12px; margin: 0; font-family: 'Syne', sans-serif; }
        .logo-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3); }

        .sidebar-toggle-btn { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; flex-shrink: 0; font-size: 18px; }
        .sidebar-toggle-btn:hover { background: rgba(102,126,234,0.4); border-color: #667eea; }

        .sidebar-open-btn { position: fixed; top: 20px; left: 20px; z-index: 1100; background: linear-gradient(135deg, #667eea, #764ba2); border: 1px solid rgba(255,255,255,0.2); color: #fff; width: 48px; height: 48px; border-radius: 14px; display: none; align-items: center; justify-content: center; cursor: pointer; font-size: 22px; box-shadow: 0 8px 25px rgba(102,126,234,0.5); transition: all 0.2s; backdrop-filter: blur(10px); }
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

        .main-content { flex: 1; margin-left: var(--sidebar-width); display: flex; flex-direction: column; transition: margin-left 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1); position: relative; z-index: 1; }
        @media (max-width: 768px) { .main-content { margin-left: 0 !important; } }
        .main-content.expanded { margin-left: 0; }

        .topbar { background: rgba(15,23,42,0.5); backdrop-filter: blur(25px); padding: 18px 36px; border-bottom: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 99; flex-wrap: wrap; gap: 12px; }
        .topbar-left h2 { color: #fff; font-size: 24px; font-weight: 700; font-family: 'Syne', sans-serif; }
        .breadcrumb { color: #94a3b8; font-size: 13px; margin-top: 5px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .breadcrumb i { font-size: 12px; color: #667eea; }
        .topbar-right { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .user-info { display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.05); padding: 8px 16px; border-radius: 14px; border: 1px solid var(--border-glass); }
        .user-avatar { width: 42px; height: 42px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 15px; }
        .user-details { display: flex; flex-direction: column; }
        .user-name { color: #e2e8f0; font-weight: 600; font-size: 13px; }
        .user-role { color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-logout { padding: 10px 20px; background: rgba(239,68,68,0.2); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s; display: flex; align-items: center; gap: 6px; }
        .btn-logout:hover { background: rgba(239,68,68,0.4); color: #fff; transform: translateY(-2px); }

        .content-area { padding: 36px; flex: 1; }
        .hero-banner { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); border-radius: 24px; padding: 32px 36px; margin-bottom: 36px; display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--border-glass); flex-wrap: wrap; gap: 20px; }
        .hero-text h2 { color: #fff; font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; }
        .hero-text p { color: #94a3b8; font-size: 15px; }
        .hero-date { background: rgba(255,255,255,0.06); border: 1px solid var(--border-glass); border-radius: 18px; padding: 18px 28px; text-align: center; }
        .date-day { color: #fff; font-size: 38px; font-weight: 700; font-family: 'Syne', sans-serif; line-height: 1; }
        .date-month { color: #94a3b8; font-size: 12px; margin-top: 6px; text-transform: uppercase; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px; }
        .stat-card { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); border-radius: 24px; padding: 26px 24px; border: 1px solid var(--border-glass); transition: all 0.3s ease; cursor: default; position: relative; }
        .stat-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.2); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 24px 24px 0 0; }
        .stat-card.blue::before { background: linear-gradient(90deg, #667eea, #764ba2); }
        .stat-card.green::before { background: linear-gradient(90deg, #22c55e, #16a34a); }
        .stat-card.teal::before { background: linear-gradient(90deg, #06b6d4, #0891b2); }
        .stat-card.pink::before { background: linear-gradient(90deg, #ec4899, #db2777); }
        .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .stat-card.red::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
        .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }

        .stat-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 18px; }
        .blue .stat-icon { background: rgba(102,126,234,0.2); }
        .green .stat-icon { background: rgba(34,197,94,0.2); }
        .teal .stat-icon { background: rgba(6,182,212,0.2); }
        .pink .stat-icon { background: rgba(236,72,153,0.2); }
        .orange .stat-icon { background: rgba(245,158,11,0.2); }
        .red .stat-icon { background: rgba(239,68,68,0.2); }
        .purple .stat-icon { background: rgba(139,92,246,0.2); }

        .stat-label { font-size: 11px; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 8px; }
        .stat-value { font-size: 42px; font-weight: 800; color: #e2e8f0; font-family: 'Syne', sans-serif; line-height: 1.1; }

        .info-toast {
            position: fixed; bottom: 24px; right: 24px;
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 12px 20px;
            font-weight: 600; z-index: 999;
            transition: all 0.4s;
            cursor: pointer;
        }
        .info-toast.info { background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.4); color: #93c5fd; }
        .info-toast.success { background: rgba(34,197,94,0.2); border: 1px solid rgba(34,197,94,0.4); color: #86efac; }
        .info-toast.error { background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); color: #fca5a5; }

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

        @media (max-width: 992px) { .content-area { padding: 24px; } .topbar { padding: 15px 24px; } }
        @media (max-width: 768px) { .content-area { padding: 16px; } .topbar { padding: 12px 16px; } .topbar-left h2 { font-size: 18px; } .user-details { display: none; } .stats-grid { grid-template-columns: 1fr; gap: 16px; } .hero-date { display: none; } .hero-banner { padding: 20px; } .stat-value { font-size: 32px; } }
        @media (max-width: 480px) { .content-area { padding: 12px; } .stat-value { font-size: 28px; } .stat-card { padding: 18px 16px; } }
    </style>
</head>
<body>

    <div class="bg-blob blob1"></div>
    <div class="bg-blob blob2"></div>
    <div class="bg-blob blob3"></div>

    <button class="sidebar-open-btn" id="sidebarOpenBtn"><i class="fas fa-bars"></i></button>
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <h1><span class="logo-icon"><i class="fas fa-calendar-check"></i></span><span>EventHub</span></h1>
            <button class="sidebar-toggle-btn" id="sidebarCloseBtn"><i class="fas fa-chevron-left"></i></button>
        </div>
        <ul class="nav-menu">
            <?php $currentAdmin = auth('admin')->user(); ?>
            
            
            <li class="nav-item">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>
            
            
            <li class="nav-item">
                <a href="#" onclick="openQRScanner()" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-qrcode"></i></span>
                    <span>Scan QR Code</span>
                </a>
            </li>
            
            
            <?php if($currentAdmin->can('view_events')): ?>
            <li class="nav-item">
                <a href="<?php echo e(route('admin.events.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.events.*') ? 'active' : ''); ?>">
                    <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                    <span>Events</span>
                </a>
            </li>
            <?php endif; ?>
            
            
            <?php if($currentAdmin->hasRole('super_admin')): ?>
            <li class="nav-item">
                <a href="<?php echo e(route('admin.admins.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.admins.*') ? 'active' : ''); ?>">
                    <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
                    <span>Admins</span>
                </a>
            </li>
            <?php endif; ?>
            
            
            <?php if($currentAdmin->can('view_users')): ?>
            <li class="nav-item">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span>Users</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </aside>

    <main class="main-content" id="mainContent">
        <div class="topbar">
            <div class="topbar-left">
                <h2>Dashboard</h2>
                <div class="breadcrumb"><i class="fas fa-home"></i> Home <i class="fas fa-chevron-right"></i> Dashboard <i class="fas fa-chevron-right"></i> Statistics</div>
            </div>
            <div class="topbar-right">
                <div class="user-info">
                    <div class="user-avatar"><?php echo e(strtoupper(substr(auth('admin')->user()->name, 0, 2))); ?></div>
                    <div class="user-details">
                        <span class="user-name">Welcome, <?php echo e(auth('admin')->user()->name); ?></span>
                        <span class="user-role"><?php echo e(auth('admin')->user()->hasRole('super_admin') ? 'Super Administrator' : ucfirst(str_replace('_', ' ', auth('admin')->user()->roles->first()->name ?? 'Administrator'))); ?></span>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('admin.logout')); ?>" style="margin:0;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        
        <?php if(session('success')): ?>
        <div class="info-toast success" id="infoToast">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
        <div class="info-toast info" id="infoToast">
            <i class="fas fa-info-circle"></i> <?php echo e(session('info')); ?>

        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="info-toast error" id="infoToast">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?>

        <div class="content-area">
            <div class="hero-banner">
                <div class="hero-text">
                    <h2><i class="fas fa-chart-line"></i> System Overview</h2>
                    <p>Live statistics across all users, events, and administrators.</p>
                </div>
                <div class="hero-date">
                    <div class="date-day" id="liveDay">--</div>
                    <div class="date-month" id="liveDate">Loading…</div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card blue"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-label">Total Users</div><div class="stat-value" data-target="<?php echo e($totalUsers); ?>">0</div></div>
                <div class="stat-card green"><div class="stat-icon"><i class="fas fa-calendar-check"></i></div><div class="stat-label">Total Events</div><div class="stat-value" data-target="<?php echo e($totalEvents); ?>">0</div></div>
                <div class="stat-card teal"><div class="stat-icon"><i class="fas fa-clock"></i></div><div class="stat-label">Upcoming Events</div><div class="stat-value" data-target="<?php echo e($upcomingEvents); ?>">0</div></div>
                <div class="stat-card pink"><div class="stat-icon"><i class="fas fa-ticket-alt"></i></div><div class="stat-label">Registrations</div><div class="stat-value" data-target="<?php echo e($registrations); ?>">0</div></div>

                <?php if(auth('admin')->user()->hasRole('super_admin')): ?>
                <div class="stat-card orange"><div class="stat-icon"><i class="fas fa-crown"></i></div><div class="stat-label">Super Admins</div><div class="stat-value" data-target="<?php echo e($superAdmins ?? 0); ?>">0</div></div>
                <div class="stat-card purple"><div class="stat-icon"><i class="fas fa-dollar-sign"></i></div><div class="stat-label">Total Revenue</div><div class="stat-value" data-target="<?php echo e($revenue ?? 0); ?>">0</div></div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const openBtn = document.getElementById('sidebarOpenBtn');
        const closeBtn = document.getElementById('sidebarCloseBtn');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function isMobile() { return window.innerWidth <= 768; }

        function closeSidebar() {
            if (isMobile()) { sidebar.classList.remove('mobile-open'); mobileOverlay.classList.remove('active'); openBtn.classList.add('visible'); }
            else { sidebar.classList.add('collapsed'); mainContent.classList.add('expanded'); openBtn.classList.add('visible'); }
        }

        function openSidebar() {
            if (isMobile()) { sidebar.classList.add('mobile-open'); mobileOverlay.classList.add('active'); openBtn.classList.remove('visible'); }
            else { sidebar.classList.remove('collapsed'); mainContent.classList.remove('expanded'); openBtn.classList.remove('visible'); }
        }

        if (openBtn) openBtn.onclick = openSidebar;
        if (closeBtn) closeBtn.onclick = closeSidebar;
        if (mobileOverlay) mobileOverlay.onclick = closeSidebar;

        function handleResize() {
            if (isMobile()) {
                if (!sidebar.classList.contains('mobile-open')) { sidebar.classList.remove('mobile-open'); mobileOverlay.classList.remove('active'); openBtn.classList.add('visible'); }
                sidebar.classList.remove('collapsed'); mainContent.classList.remove('expanded');
            } else {
                sidebar.classList.remove('mobile-open'); mobileOverlay.classList.remove('active');
                openBtn.classList.remove('visible');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();

        const now = new Date();
        const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        const days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        const liveDay = document.getElementById('liveDay');
        const liveDate = document.getElementById('liveDate');
        if (liveDay) liveDay.textContent = now.getDate();
        if (liveDate) liveDate.textContent = `${months[now.getMonth()]} ${now.getFullYear()} · ${days[now.getDay()]}`;

        document.querySelectorAll('.stat-value[data-target]').forEach(el => {
            const target = parseFloat(el.dataset.target);
            const duration = 1000;
            const steps = 50;
            let current = 0;
            const increment = target / steps;
            const isCurrency = el.closest('.stat-card.purple') !== null;
            const timer = setInterval(() => {
                current = Math.min(current + increment, target);
                el.textContent = isCurrency ? '$' + current.toFixed(2) : Math.round(current).toLocaleString();
                if (current >= target) clearInterval(timer);
            }, duration / steps);
        });

        setTimeout(() => {
            const toast = document.getElementById('infoToast');
            if (toast) { toast.style.opacity = "0"; toast.style.transform = "translateX(50px)"; setTimeout(() => toast.remove(), 500); }
        }, 5000);

        // ============ QR SCANNER FUNCTIONS ============
        let html5QrCode = null;

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
        
        window.openQRScanner = openQRScanner;
        window.closeQRScanner = closeQRScanner;
    </script>
</body>
</html><?php /**PATH C:\Users\User\backend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>