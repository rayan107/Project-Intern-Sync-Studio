<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>EventHub | Admin Dashboard</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .btn-create { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 28px; border-radius: 50px; font-weight: 700; font-size: 14px; letter-spacing: 0.5px; color: #fff; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); position: relative; overflow: hidden; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; white-space: nowrap; }
        .btn-create::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent); transition: left 0.5s ease; }
        .btn-create:hover::before { left: 100%; }
        .btn-create:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(102,126,234,0.6); }
        .btn-create:active { transform: translateY(0); }
        .btn-create:disabled { background: #4a5568 !important; opacity: 0.5; cursor: not-allowed; box-shadow: none; transform: none; }
        .btn-messages-top { background: linear-gradient(135deg, #a78bfa 0%, #667eea 100%); border: none; padding: 12px 28px; border-radius: 50px; font-weight: 700; font-size: 14px; letter-spacing: 0.5px; color: #fff; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(167, 139, 250, 0.4); position: relative; overflow: hidden; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; white-space: nowrap; }
        .btn-messages-top::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent); transition: left 0.5s ease; }
        .btn-messages-top:hover::before { left: 100%; }
        .btn-messages-top:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(167, 139, 250, 0.6); }
        .btn-messages-top:disabled { background: #4a5568 !important; opacity: 0.5; cursor: not-allowed; box-shadow: none; transform: none; }
        .content-area { padding: 36px; flex: 1; max-width: 1400px; margin: 0 auto; }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 22px; margin-bottom: 30px; }
        .stat-card { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); border-radius: 20px; padding: 28px 26px; border: 1px solid var(--border-glass); position: relative; overflow: hidden; transition: all 0.3s; }
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
        .stat-value { font-size: 42px; font-weight: 800; font-family: 'Syne', sans-serif; color: #fff; }
        .stat-label { font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px; }
        .panel { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); border-radius: 20px; border: 1px solid var(--border-glass); overflow: hidden; }
        .panel-header { padding: 20px 24px; border-bottom: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .panel-header h4 { font-family: 'Syne', sans-serif; margin: 0; color: #fff; font-size: 18px; }
        .search-box { display: flex; align-items: center; gap: 8px; background: rgba(15,23,42,0.5); border: 1px solid var(--border-glass); border-radius: 10px; padding: 8px 14px; max-width: 280px; width: 100%; }
        .search-box input { background: transparent; border: none; outline: none; color: #fff; width: 100%; font-size: 14px; }
        .search-box input::placeholder { color: #94a3b8; }
        .search-box i { color: #94a3b8; flex-shrink: 0; }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .custom-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .custom-table th { text-align: left; padding: 14px 18px; font-size: 11px; color: #94a3b8; text-transform: uppercase; background: rgba(15,23,42,0.3); font-weight: 600; white-space: nowrap; }
        .custom-table td { padding: 16px 18px; border-bottom: 1px solid rgba(255,255,255,0.05); color: #e2e8f0; }
        .custom-table tbody tr:hover td { background: rgba(102,126,234,0.06); }
        .badge { display: inline-block; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .badge.paid { background: rgba(34,197,94,0.2); color: #4ade80; }
        .badge.free { background: rgba(6,182,212,0.2); color: #22d3ee; }
        .badge-reg { background: rgba(102,126,234,0.2); color: #a78bfa; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
        .btn-sm { padding: 7px 14px; border-radius: 9px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; margin: 2px; white-space: nowrap; }
        .btn-sm:disabled { opacity: 0.4; cursor: not-allowed; transform: none !important; }
        .btn-view { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
        .btn-view:hover { transform: translateY(-2px); }
        .btn-edit { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .btn-edit:hover { background: #f59e0b; color: #fff; }
        .btn-delete { background: rgba(239,68,68,0.25); color: #fca5a5; }
        .btn-delete:hover { background: #ef4444; color: #fff; }
        .btn-review { background: rgba(34,197,94,0.2); color: #4ade80; }
        .btn-review:hover { background: #22c55e; color: #fff; transform: translateY(-2px); }
        .btn-reply { background: rgba(34,197,94,0.2); color: #4ade80; padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-right: 8px; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-reply:hover { background: #22c55e; color: #fff; transform: translateY(-2px); }
        .empty-state { text-align: center; padding: 60px; color: #94a3b8; }
        .modal-content { background: #1e293b; border: 1px solid rgba(255,255,255,0.15); border-radius: 20px; color: #ffffff; }
        .modal-header { background: linear-gradient(135deg, #667eea, #764ba2); border-bottom: 1px solid rgba(255,255,255,0.1); border-radius: 20px 20px 0 0; padding: 20px 24px; }
        .modal-header h5 { color: #ffffff; margin: 0; font-family: 'Syne', sans-serif; font-size: 18px; }
        .modal-header .btn-close { filter: brightness(0) invert(1); }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.1); }
        .form-control, .form-select { background: #0f172a !important; border: 1px solid rgba(255,255,255,0.15) !important; color: #ffffff !important; border-radius: 10px; padding: 10px 14px; font-size: 14px; }
        .form-control:focus, .form-select:focus { border-color: #667eea !important; box-shadow: 0 0 0 2px rgba(102,126,234,0.3) !important; color: #ffffff !important; }
        .form-control::placeholder { color: #94a3b8; }
        .form-label { color: #e2e8f0 !important; font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block; }
        .form-select option { background-color: #1e293b; color: #ffffff; }
        .text-muted { color: #94a3b8 !important; }
        textarea.form-control { resize: vertical; }
        .text-danger { color: #f87171 !important; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; border: none !important; padding: 10px 24px; border-radius: 10px; font-weight: 600; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
        .btn-secondary { background: rgba(100,116,139,0.3) !important; border: 1px solid rgba(255,255,255,0.15) !important; color: #e2e8f0 !important; padding: 10px 24px; border-radius: 10px; font-weight: 600; transition: all 0.3s; }
        .btn-secondary:hover { background: rgba(100,116,139,0.5) !important; color: #fff !important; }
        .btn-danger { background: #ef4444; border: none; color: white; font-weight: 600; padding: 10px 24px; border-radius: 10px; }
        .btn-danger:hover { background: #dc2626; }
        .star-rating { color: #fbbf24; font-size: 14px; }
        .star-rating .far { color: #94a3b8; }
        .review-item { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 16px 0; position: relative; }
        .review-item:last-child { border-bottom: none; }
        .review-delete-btn { position: absolute; top: 8px; right: 8px; background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 12px; padding: 4px 8px; border-radius: 6px; transition: all 0.2s; }
        .review-delete-btn:hover { color: #f87171; background: rgba(239,68,68,0.15); }
        .review-delete-btn:disabled { opacity: 0.3; cursor: not-allowed; color: #64748b; }
        .review-delete-btn:disabled:hover { color: #64748b; background: none; }
        .messages-container { max-height: 500px; overflow-y: auto; }
        .message-card { background: rgba(255,255,255,0.03); border: 1px solid var(--border-glass); border-radius: 16px; padding: 18px; margin-bottom: 15px; transition: all 0.2s; }
        .message-card:hover { background: rgba(102,126,234,0.08); border-color: #667eea; }
        .message-card.user-message-replied { border-left: 3px solid #22c55e; background: rgba(34,197,94,0.05); }
        .message-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px; }
        .message-sender { display: flex; align-items: center; gap: 10px; }
        .message-sender-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; }
        .message-sender-info { display: flex; flex-direction: column; }
        .message-sender-name { font-weight: 700; color: #fff; font-size: 14px; }
        .message-sender-email { font-size: 11px; color: #94a3b8; }
        .message-date { font-size: 11px; color: #94a3b8; }
        .message-subject { font-weight: 600; color: #a78bfa; font-size: 13px; margin-bottom: 8px; padding-left: 46px; }
        .message-content { font-size: 13px; color: #cbd5e1; line-height: 1.5; padding-left: 46px; margin-bottom: 12px; }
        .message-actions { padding-left: 46px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-delete-message { background: rgba(239,68,68,0.2); border: none; color: #fca5a5; padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-delete-message:hover { background: #ef4444; color: #fff; }
        .btn-delete-message:disabled { opacity: 0.3; cursor: not-allowed; background: rgba(100,116,139,0.2); color: #64748b; }
        .empty-messages { text-align: center; padding: 60px; color: #94a3b8; }
        .empty-messages i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
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
        .replied-badge { background: rgba(34,197,94,0.2); color: #4ade80; padding: 3px 10px; border-radius: 20px; font-size: 11px; margin-left: 10px; display: inline-flex; align-items: center; gap: 5px; }
        
        /* QR Scanner Modal - Enhanced */
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
            padding: 20px;
        }
        .qr-scanner-box {
            background: #1e293b;
            border-radius: 28px;
            width: 100%;
            max-width: 550px;
            padding: 24px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.15);
            max-height: 95vh;
            overflow-y: auto;
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
            margin-top: 12px;
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
        .qr-info-box {
            display: none;
            margin-top: 20px;
            padding: 18px;
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            text-align: left;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .qr-info-box .event-title {
            color: #a78bfa;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            font-family: 'Syne', sans-serif;
        }
        .qr-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 13px;
            color: #cbd5e1;
        }
        .qr-info-grid .info-item {
            padding: 8px 12px;
            background: rgba(255,255,255,0.03);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qr-info-grid .info-item i {
            width: 20px;
            color: #667eea;
        }
        .qr-info-grid .info-item .label {
            color: #94a3b8;
            font-size: 11px;
            margin-right: 4px;
        }
        .qr-event-status {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
        }
        .qr-event-status.upcoming { background: rgba(6,182,212,0.2); color: #22d3ee; }
        .qr-event-status.today { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .qr-event-status.past { background: rgba(239,68,68,0.2); color: #f87171; }
        .qr-checkin-btn {
            margin-top: 15px;
            padding: 12px 35px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            justify-content: center;
        }
        .qr-checkin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }
        .btn-close-scanner {
            margin-top: 20px;
            padding: 10px 25px;
            background: rgba(239,68,68,0.2);
            color: #fca5a5;
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }
        .btn-close-scanner:hover {
            background: rgba(239,68,68,0.4);
            color: #fff;
        }

        /* View Event Modal - Check-in Result Style */
        #viewEventModal .status-badge {
            display: inline-block;
            padding: 6px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 14px;
        }
        #viewEventModal .status-badge.upcoming {
            background: rgba(6,182,212,0.2);
            color: #22d3ee;
        }
        #viewEventModal .status-badge.today {
            background: rgba(245,158,11,0.2);
            color: #fbbf24;
        }
        #viewEventModal .status-badge.past {
            background: rgba(239,68,68,0.2);
            color: #f87171;
        }
        #viewEventModal .status-badge.active {
            background: rgba(34,197,94,0.2);
            color: #4ade80;
        }

        #viewEventModal .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        #viewEventModal .info-item {
            background: rgba(255,255,255,0.05);
            padding: 14px 18px;
            border-radius: 12px;
        }
        #viewEventModal .info-item .label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        #viewEventModal .info-item .value {
            color: #e2e8f0;
            font-weight: 600;
            margin-top: 4px;
            font-size: 15px;
        }
        #viewEventModal .info-item .value i {
            margin-right: 6px;
        }

        /* Attendee items with status */
        #viewAttendeesList .attendee-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: background 0.2s;
        }
        #viewAttendeesList .attendee-item:hover {
            background: rgba(255,255,255,0.03);
        }
        #viewAttendeesList .attendee-item:last-child {
            border-bottom: none;
        }
        #viewAttendeesList .attendee-item .name {
            color: #e2e8f0;
            font-weight: 500;
        }
        #viewAttendeesList .attendee-item .email {
            color: #94a3b8;
            font-size: 12px;
        }
        #viewAttendeesList .attendee-item .status-badge-sm {
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            white-space: nowrap;
        }
        #viewAttendeesList .attendee-item .status-badge-sm.present {
            background: rgba(34,197,94,0.2);
            color: #4ade80;
        }
        #viewAttendeesList .attendee-item .status-badge-sm.registered {
            background: rgba(245,158,11,0.2);
            color: #fbbf24;
        }
        #viewAttendeesList .attendee-item .status-badge-sm.cancelled {
            background: rgba(239,68,68,0.2);
            color: #f87171;
        }
        #viewAttendeesList .attendee-item .checkin-time {
            font-size: 10px;
            color: #94a3b8;
            margin-left: 8px;
        }

        #viewAttendeesList::-webkit-scrollbar {
            width: 4px;
        }
        #viewAttendeesList::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }
        #viewAttendeesList::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        @media (max-width: 576px) {
            #viewEventModal .info-grid {
                grid-template-columns: 1fr;
            }
            #viewAttendeesList .attendee-item {
                flex-wrap: wrap;
                gap: 5px;
            }
        }
        
        @media (max-width: 1200px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 992px) { .content-area { padding: 24px; } .topbar { padding: 14px 20px; } .topbar-left h2 { font-size: 20px; } .modal-dialog { margin: 1rem; } }
        @media (max-width: 768px) { .content-area { padding: 16px; } .topbar { padding: 12px 16px; } .topbar-left h2 { font-size: 18px; } .topbar-right { gap: 10px; } .user-details { display: none; } .stats-row { grid-template-columns: 1fr; gap: 14px; } .stat-card { padding: 20px 18px; } .stat-value { font-size: 32px; } .stat-icon { width: 40px; height: 40px; font-size: 18px; } .panel-header { padding: 14px 16px; } .panel-header h4 { font-size: 16px; } .search-box { max-width: 100%; } .custom-table { min-width: 650px; } .custom-table th, .custom-table td { padding: 10px 12px; font-size: 12px; } .btn-sm { padding: 5px 10px; font-size: 10px; } .btn-create, .btn-messages-top { padding: 8px 18px; font-size: 12px; } .btn-logout { padding: 8px 14px; font-size: 12px; } .modal-body { padding: 1rem; } .modal-header { padding: 1rem 1.5rem; } .modal-footer { padding: 1rem 1.5rem; } .form-control, .form-select { font-size: 16px; } .user-info { padding: 6px 12px; } .user-avatar { width: 36px; height: 36px; font-size: 13px; } .message-subject, .message-content, .message-actions { padding-left: 0; } .message-sender { margin-bottom: 8px; } .message-header { flex-direction: column; align-items: flex-start; } .qr-info-grid { grid-template-columns: 1fr; } }
        @media (max-width: 576px) { .content-area { padding: 12px; } .stat-value { font-size: 28px; } .topbar-right { width: 100%; justify-content: space-between; } .btn-create, .btn-messages-top { width: 100%; justify-content: center; margin-bottom: 8px; } .qr-scanner-box { padding: 16px; } }
        @media (max-width: 480px) { .content-area { padding: 10px; } .stat-card { padding: 16px 14px; } .stat-value { font-size: 24px; } .stat-label { font-size: 10px; } .btn-sm { padding: 4px 8px; font-size: 9px; margin: 1px; } .custom-table { min-width: 550px; } .custom-table th, .custom-table td { padding: 8px 10px; font-size: 11px; } }
    </style>
</head>
<body>

<div class="bg-blob blob1"></div><div class="bg-blob blob2"></div><div class="bg-blob blob3"></div>

<button class="sidebar-open-btn visible" id="sidebarOpenBtn"><i class="fas fa-bars"></i></button>
<div class="mobile-overlay" id="mobileOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="logo"><h1><span class="logo-icon"><i class="fas fa-calendar-check"></i></span><span>EventHub</span></h1><button class="sidebar-toggle-btn" id="sidebarCloseBtn"><i class="fas fa-chevron-left"></i></button></div>
    <ul class="nav-menu">
        <li class="nav-item"><a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span><span>Dashboard</span></a></li>
        <li class="nav-item">
            <a href="#" onclick="openQRScanner()" class="nav-link">
                <span class="nav-icon"><i class="fas fa-qrcode"></i></span>
                <span>Scan QR Code</span>
            </a>
        </li>
        <li class="nav-item"><a href="<?php echo e(route('admin.events.index')); ?>" class="nav-link active"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span><span>Events</span></a></li>
        <?php if(auth('admin')->check() && auth('admin')->user()->hasRole('super_admin')): ?>
        <li class="nav-item"><a href="<?php echo e(route('admin.admins.index')); ?>" class="nav-link"><span class="nav-icon"><i class="fas fa-user-shield"></i></span><span>Admins</span></a></li>
        <li class="nav-item"><a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link"><span class="nav-icon"><i class="fas fa-users"></i></span><span>Users</span></a></li>
        <?php endif; ?>
    </ul>
</aside>

<main class="main-content" id="mainContent">
    <?php
        $adminUser = auth('admin')->user();
        $isSuper = $adminUser->hasRole('super_admin');
        $canViewMessages = $isSuper || $adminUser->hasPermissionTo('view_messages');
        $canViewReviews = $isSuper || $adminUser->hasPermissionTo('view_reviews');
        $canDeleteReviews = $isSuper || $adminUser->hasPermissionTo('delete_reviews');
        $canDeleteMessages = $isSuper || $adminUser->hasPermissionTo('delete_messages');
    ?>
    <div class="topbar">
        <div class="topbar-left"><h2>Events</h2><div class="breadcrumb"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fas fa-home"></i> Home</a> / Events</div></div>
        <div class="topbar-right">
            <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createEventModal"><i class="fas fa-plus-circle"></i>Create Event</button>
            <button class="btn-messages-top" onclick="if(!this.disabled){loadAllMessages(); messagesModal.show();}" <?php echo e($canViewMessages ? '' : 'disabled'); ?> title="<?php echo e($canViewMessages ? 'View all messages' : 'You don\'t have permission to view messages'); ?>"><i class="fas fa-envelope"></i> All Messages</button>
            <div class="user-info">
                <div class="user-avatar"><?php echo e(strtoupper(substr($adminUser->name ?? 'AD', 0, 2))); ?></div>
                <div class="user-details"><span class="user-name"><?php echo e($adminUser->name ?? 'Admin'); ?></span><span class="user-role"><?php echo e($isSuper ? 'Super Admin' : ($adminUser->getRoleNames()->first() ?? 'Admin')); ?></span></div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.logout')); ?>" style="margin:0;"><?php echo csrf_field(); ?><button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button></form>
        </div>
    </div>

    <div class="content-area">
        <?php if(session('success')): ?><div style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7;padding:14px 20px;border-radius:14px;margin-bottom:24px;"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('error')): ?><div style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:14px 20px;border-radius:14px;margin-bottom:24px;"><i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?></div><?php endif; ?>

        <div class="stats-row">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-value"><?php echo e($stats['totalEvents'] ?? $events->total()); ?></div>
                <div class="stat-label">Total Events</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-value"><?php echo e($stats['totalRegistrations'] ?? 0); ?></div>
                <div class="stat-label">Total Registrations</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-value"><?php echo e($stats['upcomingEvents'] ?? 0); ?></div>
                <div class="stat-label">Upcoming Events</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-value">$<?php echo e(number_format($stats['totalRevenue'] ?? 0, 2)); ?></div>
                <div class="stat-label">Revenue</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header"><h4><i class="fas fa-list me-2"></i> All Events</h4><div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Search events..." onkeyup="filterEvents()"></div></div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead><tr><th>ID</th><th>Event</th><th>Date & Time</th><th>Location</th><th>Price</th><th>Registrations</th><th>Actions</th></tr></thead>
                    <tbody id="eventsTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="event-row" data-name="<?php echo e(strtolower($event->title)); ?>" data-location="<?php echo e(strtolower($event->location)); ?>">
                            <td><strong style="color:#a78bfa;">#<?php echo e($event->id); ?></strong></td>
                            <td><strong><?php echo e($event->title); ?></strong><br><small class="text-muted"><?php echo e(Str::limit($event->description, 40)); ?></small></td>
                            <td><?php echo e(\Carbon\Carbon::parse($event->event_date)->format('M d, Y')); ?><br><small><?php echo e(\Carbon\Carbon::parse($event->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($event->end_time)->format('g:i A')); ?></small></td>
                            <td><i class="fas fa-map-marker-alt" style="color:#f87171;"></i> <?php echo e($event->location); ?></td>
                            <td><?php if($event->price > 0): ?><span class="badge paid">$<?php echo e(number_format($event->price, 2)); ?></span><?php else: ?><span class="badge free">Free</span><?php endif; ?></td>
                            <td><span class="badge-reg"><i class="fas fa-users"></i> <?php echo e($event->users_count ?? 0); ?></span></td>
                            <td>
                                <button class="btn-sm btn-view view-btn" data-event-id="<?php echo e($event->id); ?>" data-event-title="<?php echo e($event->title); ?>"><i class="fas fa-eye"></i> View</button>
                                <button class="btn-sm btn-edit edit-btn" data-event-id="<?php echo e($event->id); ?>" data-title="<?php echo e($event->title); ?>" data-description="<?php echo e($event->description); ?>" data-date="<?php echo e($event->event_date); ?>" data-start-time="<?php echo e($event->start_time); ?>" data-end-time="<?php echo e($event->end_time); ?>" data-price="<?php echo e($event->price); ?>" data-location="<?php echo e($event->location); ?>"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn-sm btn-review review-btn" data-event-id="<?php echo e($event->id); ?>" data-event-title="<?php echo e($event->title); ?>" <?php echo e($canViewReviews ? '' : 'disabled'); ?> title="<?php echo e($canViewReviews ? 'View reviews' : 'You don\'t have permission to view reviews'); ?>"><i class="fas fa-star"></i> Reviews</button>
                                <button class="btn-sm btn-delete delete-btn" data-event-id="<?php echo e($event->id); ?>" data-event-title="<?php echo e($event->title); ?>"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7"><div class="empty-state"><i class="fas fa-calendar-times" style="font-size:40px;"></i><p>No events found.</p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($events->hasPages()): ?><div style="padding:16px 24px;border-top:1px solid rgba(255,255,255,0.05);display:flex;justify-content:center;"><?php echo e($events->links()); ?></div><?php endif; ?>
        </div>
    </div>
</main>

<!-- CREATE EVENT MODAL -->
<div class="modal fade" id="createEventModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5><i class="fas fa-plus-circle me-2"></i>Create New Event</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form action="<?php echo e(route('admin.events.store')); ?>" method="POST" enctype="multipart/form-data"><?php echo csrf_field(); ?><div class="modal-body"><div class="mb-3"><label class="form-label">Event Title <span class="text-danger">*</span></label><input type="text" name="title" class="form-control" required></div><div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"></textarea></div><div class="row mb-3"><div class="col-md-6"><label class="form-label">Event Date <span class="text-danger">*</span></label><input type="date" name="event_date" class="form-control" required></div><div class="col-md-6"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div></div><div class="row mb-3"><div class="col-md-6"><label class="form-label">Start Time</label><input type="time" name="start_time" class="form-control"></div><div class="col-md-6"><label class="form-label">End Time</label><input type="time" name="end_time" class="form-control"></div></div><div class="row mb-3"><div class="col-md-6"><label class="form-label">Price ($)</label><input type="number" step="0.01" name="price" class="form-control" value="0"></div><div class="col-md-6"><label class="form-label">Event Images</label><input type="file" name="images[]" class="form-control" multiple></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Create Event</button></div></form></div></div></div>

<!-- EDIT EVENT MODAL -->
<div class="modal fade" id="editEventModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5><i class="fas fa-edit me-2"></i>Edit Event</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="editEventForm"><input type="hidden" id="editId"><div class="mb-3"><label class="form-label">Title</label><input type="text" id="editTitle" class="form-control"></div><div class="mb-3"><label class="form-label">Description</label><textarea id="editDescription" class="form-control" rows="3"></textarea></div><div class="row mb-3"><div class="col-md-6"><label class="form-label">Date</label><input type="date" id="editDate" class="form-control"></div><div class="col-md-6"><label class="form-label">Location</label><input type="text" id="editLocation" class="form-control"></div></div><div class="row mb-3"><div class="col-md-6"><label class="form-label">Start Time</label><input type="time" id="editStartTime" class="form-control"></div><div class="col-md-6"><label class="form-label">End Time</label><input type="time" id="editEndTime" class="form-control"></div></div><div class="mb-3"><label class="form-label">Price ($)</label><input type="number" step="0.01" id="editPrice" class="form-control"></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-primary" id="saveEditBtn">Save Changes</button></div></div></div></div>

<!-- VIEW EVENT DETAILS MODAL -->
<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5><i class="fas fa-calendar-alt me-2"></i>Event Details & Attendees</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="viewEventContainer">
                    <!-- Loading State -->
                    <div id="viewLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3 text-muted">Loading event details...</p>
                    </div>

                    <!-- Event Info -->
                    <div id="viewEventContent" style="display: none;">
                        <!-- Header with Icon -->
                        <div class="text-center mb-4">
                            <div style="font-size: 64px; color: #667eea; margin-bottom: 10px;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h2 id="viewEventTitle" style="color: #fff; font-weight: 700; font-family: 'Syne', sans-serif;">Event Title</h2>
                            <div class="subtitle" style="color: #94a3b8; font-size: 14px;" id="viewEventDescription">Description</div>
                        </div>

                        <!-- Event Status Badge -->
                        <div class="text-center mb-4">
                            <span id="viewEventStatus" class="status-badge">Status</span>
                        </div>

                        <!-- Info Grid -->
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="label"><i class="fas fa-calendar-day"></i> Date</div>
                                <div class="value" id="viewEventDate">-</div>
                            </div>
                            <div class="info-item">
                                <div class="label"><i class="fas fa-clock"></i> Time</div>
                                <div class="value" id="viewEventTime">-</div>
                            </div>
                            <div class="info-item">
                                <div class="label"><i class="fas fa-map-marker-alt"></i> Location</div>
                                <div class="value" id="viewEventLocation">-</div>
                            </div>
                            <div class="info-item">
                                <div class="label"><i class="fas fa-tag"></i> Price</div>
                                <div class="value" id="viewEventPrice">-</div>
                            </div>
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <div class="label"><i class="fas fa-users"></i> Registered Attendees</div>
                                <div class="value" id="viewEventRegistrations">0</div>
                            </div>
                        </div>

                        <!-- Attendees List with Status -->
                        <div style="background: rgba(34,197,94,0.06); padding: 20px; border-radius: 16px; border: 1px solid rgba(34,197,94,0.15); margin-top: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                                <h6 style="color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                                    <i class="fas fa-user-check"></i> Attendees List
                                </h6>
                                <div style="display: flex; gap: 10px; font-size: 11px;">
                                    <span style="color: #4ade80;"><i class="fas fa-circle"></i> Checked In</span>
                                    <span style="color: #fbbf24;"><i class="fas fa-circle"></i> Registered</span>
                                    <span style="color: #f87171;"><i class="fas fa-circle"></i> Cancelled</span>
                                </div>
                            </div>
                            <div id="viewAttendeesList">
                                <div class="text-center py-3 text-muted">Loading attendees...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ATTENDEES MODAL -->
<div class="modal fade" id="attendeesModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5><i class="fas fa-users me-2"></i>Attendees: <span id="attendeesEventName"></span></h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body"><div id="attendeesList" class="text-center py-3">Loading...</div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div></div></div></div>

<!-- REVIEWS MODAL -->
<div class="modal fade" id="reviewsModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5><i class="fas fa-star me-2"></i>Reviews: <span id="reviewsEventName"></span></h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="text-center mb-4"><div class="stat-value" id="avgRating" style="font-size:36px;">0.0</div><div class="star-rating mb-2" id="avgStars"></div><small class="text-muted" id="totalReviews">0 reviews</small></div><div id="reviewsList" class="text-center py-3">Loading...</div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div></div></div></div>

<!-- MESSAGES MODAL -->
<div class="modal fade" id="messagesModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5><i class="fas fa-envelope me-2"></i>All Messages</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="messages-container" id="messagesContainer"><div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading messages...</p></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div></div></div></div>

<!-- REPLY MESSAGE MODAL -->
<div class="modal fade" id="replyMessageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5><i class="fas fa-reply me-2"></i>Reply to Message</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="replyMessageForm">
                <div class="modal-body">
                    <input type="hidden" id="replyToEmail" name="to_email">
                    <input type="hidden" id="replyToName" name="to_name">
                    <input type="hidden" id="replyOriginalMessageId" name="original_message_id">
                    <input type="hidden" id="replyEventId" name="event_id">
                    <div class="mb-3">
                        <label class="form-label">To</label>
                        <input type="text" id="replyEmailDisplay" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" id="replySubject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea id="replyMessage" class="form-control" rows="5" required placeholder="Write your reply here..."></textarea>
                    </div>
                    <div class="alert alert-info" style="background:rgba(102,126,234,0.1);border:1px solid #667eea;border-radius:10px;font-size:12px;">
                        <i class="fas fa-info-circle"></i> The user will receive a notification inside the website.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sendReplyBtn">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar'), mainContent = document.getElementById('mainContent'), openBtn = document.getElementById('sidebarOpenBtn'), closeBtn = document.getElementById('sidebarCloseBtn'), mobileOverlay = document.getElementById('mobileOverlay');
    function isMobile() { return window.innerWidth <= 768; }
    function closeSidebar() { if (isMobile()) { sidebar.classList.remove('mobile-open'); mobileOverlay.classList.remove('active'); openBtn.classList.add('visible'); } else { sidebar.classList.add('collapsed'); mainContent.classList.add('expanded'); openBtn.classList.add('visible'); } }
    function openSidebar() { if (isMobile()) { sidebar.classList.add('mobile-open'); mobileOverlay.classList.add('active'); openBtn.classList.remove('visible'); } else { sidebar.classList.remove('collapsed'); mainContent.classList.remove('expanded'); openBtn.classList.remove('visible'); } }
    openBtn.onclick = openSidebar; if (closeBtn) closeBtn.onclick = closeSidebar; mobileOverlay.onclick = closeSidebar;
    function handleResize() { if (isMobile()) { sidebar.classList.remove('collapsed'); mainContent.classList.remove('expanded'); if (!sidebar.classList.contains('mobile-open')) openBtn.classList.add('visible'); } else { sidebar.classList.remove('mobile-open'); mobileOverlay.classList.remove('active'); if (!sidebar.classList.contains('collapsed')) openBtn.classList.remove('visible'); } }
    window.addEventListener('resize', handleResize); handleResize();
    function filterEvents() { const q = document.getElementById('searchInput').value.toLowerCase(); document.querySelectorAll('.event-row').forEach(row => { const name = row.dataset.name || '', loc = row.dataset.location || ''; row.style.display = (name.includes(q) || loc.includes(q)) ? '' : 'none'; }); }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';
    const attModal = new bootstrap.Modal(document.getElementById('attendeesModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editEventModal'));
    const reviewsModal = new bootstrap.Modal(document.getElementById('reviewsModal'));
    const messagesModal = new bootstrap.Modal(document.getElementById('messagesModal'));
    const replyModal = new bootstrap.Modal(document.getElementById('replyMessageModal'));
    const viewModal = new bootstrap.Modal(document.getElementById('viewEventModal'));

    const canDeleteReview = <?php echo e($canDeleteReviews ? 'true' : 'false'); ?>;
    const canDeleteMessage = <?php echo e($canDeleteMessages ? 'true' : 'false'); ?>;
    let currentReplyData = null;

    function getStars(rating) { let html = ''; for (let i = 1; i <= 5; i++) { if (i <= Math.floor(rating)) html += '<i class="fas fa-star"></i>'; else if (i - 0.5 <= rating) html += '<i class="fas fa-star-half-alt"></i>'; else html += '<i class="far fa-star"></i>'; } return html; }
    
    function showToast(message, type = 'success') { 
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) existingToast.remove();
        const toastDiv = document.createElement('div'); 
        toastDiv.className = `custom-toast ${type === 'error' ? 'error' : ''}`;
        const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>';
        toastDiv.innerHTML = `${icon} ${message}`;
        document.body.appendChild(toastDiv); 
        setTimeout(() => { toastDiv.style.animation = 'slideOutRight 0.3s ease'; setTimeout(() => toastDiv.remove(), 300); }, 3000); 
    }

    function escapeHtml(str) { if (!str) return ''; return String(str).replace(/[&<>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); }

    function formatTime(timeStr) {
        if (!timeStr) return 'N/A';
        try {
            const [hours, minutes] = timeStr.split(':');
            const h = parseInt(hours);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const h12 = h % 12 || 12;
            return `${h12}:${minutes} ${ampm}`;
        } catch {
            return timeStr;
        }
    }

    // ============ VIEW EVENT DETAILS ============
    async function viewEventDetails(eventId) {
        document.getElementById('viewLoading').style.display = 'block';
        document.getElementById('viewEventContent').style.display = 'none';
        
        viewModal.show();
        
        try {
            // Fetch event details
            const eventResponse = await fetch(`/admin/events/${eventId}/details`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            if (eventResponse.ok) {
                const event = await eventResponse.json();
                
                // Populate event info
                document.getElementById('viewEventTitle').textContent = event.title || 'Event';
                document.getElementById('viewEventDescription').textContent = event.description || 'No description available';
                document.getElementById('viewEventDate').textContent = event.event_date ? new Date(event.event_date).toLocaleDateString('en-US', { 
                    weekday: 'short', 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                }) : '-';
                document.getElementById('viewEventTime').textContent = event.start_time && event.end_time ? 
                    `${formatTime(event.start_time)} - ${formatTime(event.end_time)}` : '-';
                document.getElementById('viewEventLocation').textContent = event.location || '-';
                document.getElementById('viewEventPrice').textContent = event.price > 0 ? `$${parseFloat(event.price).toFixed(2)}` : 'Free';
                document.getElementById('viewEventRegistrations').textContent = event.registrations_count || 0;
                
                // Set status
                const statusBadge = document.getElementById('viewEventStatus');
                const now = new Date();
                const eventDate = new Date(event.event_date);
                if (eventDate > now) {
                    statusBadge.textContent = '📅 Upcoming';
                    statusBadge.className = 'status-badge upcoming';
                } else if (eventDate.toDateString() === now.toDateString()) {
                    statusBadge.textContent = '🔴 Today';
                    statusBadge.className = 'status-badge today';
                } else {
                    statusBadge.textContent = '📆 Past';
                    statusBadge.className = 'status-badge past';
                }
                
                // Load attendees with status
                await loadAttendeesWithStatus(eventId);
                
                document.getElementById('viewLoading').style.display = 'none';
                document.getElementById('viewEventContent').style.display = 'block';
                
            } else {
                document.getElementById('viewLoading').innerHTML = `
                    <div style="font-size: 48px; color: #ef4444;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <p style="color: #f87171; margin-top: 10px;">Failed to load event details</p>
                    <button class="btn btn-sm btn-secondary" onclick="viewEventDetails(${eventId})">
                        <i class="fas fa-redo"></i> Retry
                    </button>
                `;
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('viewLoading').innerHTML = `
                <div style="font-size: 48px; color: #ef4444;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <p style="color: #f87171; margin-top: 10px;">Network error. Please try again.</p>
                <button class="btn btn-sm btn-secondary" onclick="viewEventDetails(${eventId})">
                    <i class="fas fa-redo"></i> Retry
                </button>
            `;
        }
    }

    // ============ FIXED: loadAttendeesWithStatus with correct time from database ============
    async function loadAttendeesWithStatus(eventId) {
    try {
        const response = await fetch(`/admin/events/${eventId}/registrations`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const attendees = await response.json();
            const list = document.getElementById('viewAttendeesList');
            
            if (!attendees || !attendees.length) {
                list.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash" style="font-size: 30px; color: #94a3b8;"></i>
                        <p class="text-muted mt-2">No registered attendees yet</p>
                    </div>
                `;
                return;
            }
            
            list.innerHTML = attendees.map((user) => {
                let status = user.status || 'registered';
                let checkedInAt = user.checked_in_at || null;
                
                if (!user.status && user.checked_in_at) {
                    status = 'present';
                    checkedInAt = user.checked_in_at;
                }
                
                if (!user.status && user.cancelled_at) {
                    status = 'cancelled';
                }
                
                let badgeHtml = '';
                let statusText = '';
                let statusClass = '';
                
                if (status === 'present' || status === 'checked_in') {
                    statusClass = 'present';
                    statusText = '✅ Checked In';
                    
                    if (checkedInAt) {
                        try {
                            const date = new Date(checkedInAt);
                            
                            if (!isNaN(date.getTime())) {
                                // ============ FIX: Use local timezone ============
                                const options = {
                                    month: 'short',
                                    day: 'numeric',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true,
                                    timeZone: 'Asia/Beirut'  // Force Lebanon timezone
                                };
                                const formattedDate = date.toLocaleString('en-US', options);
                                statusText += ` on ${formattedDate}`;
                            }
                        } catch (e) {
                            statusText += ` on ${checkedInAt}`;
                        }
                    }
                } else if (status === 'cancelled') {
                    statusClass = 'cancelled';
                    statusText = '❌ Cancelled';
                } else {
                    statusClass = 'registered';
                    statusText = '⏳ Registered';
                }
                
                badgeHtml = `<span class="status-badge-sm ${statusClass}">${statusText}</span>`;
                
                return `
                    <div class="attendee-item">
                        <div>
                            <span class="name">${escapeHtml(user.name || 'Unknown')}</span>
                            <div class="email">${escapeHtml(user.email || 'No email')}</div>
                        </div>
                        ${badgeHtml}
                    </div>
                `;
            }).join('');
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('viewAttendeesList').innerHTML = `
            <div class="text-center py-3 text-danger">
                <i class="fas fa-exclamation-circle"></i> Error loading attendees
            </div>
        `;
    }
}
    // ============ DELETE CONFIRMATION ============
    function openDeleteModal(eventId, eventTitle) {
        Swal.fire({
            title: '<span style="color:#fca5a5">Delete Event</span>',
            html: `<div style="text-align:center"><i class="fas fa-trash-alt" style="font-size: 64px; color: #ef4444; margin: 20px 0;"></i><p style="font-size: 16px;">Are you sure you want to delete "${escapeHtml(eventTitle)}"?</p><p style="font-size: 13px; color: #94a3b8;">This action cannot be undone.</p></div>`,
            background: '#1e293b', color: '#e2e8f0', confirmButtonColor: '#ef4444', cancelButtonColor: '#475569', confirmButtonText: 'Delete Event', cancelButtonText: 'Cancel', showCancelButton: true
        }).then((result) => { if (result.isConfirmed) confirmDeleteEvent(eventId); });
    }

    async function confirmDeleteEvent(eventId) {
        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), background: '#1e293b' });
        try {
            const response = await fetch(`/admin/events/${eventId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' } });
            const data = await response.json();
            if (response.ok && data.success) Swal.fire({ title: 'Deleted!', text: 'Event deleted.', icon: 'success', background: '#1e293b', timer: 1500 }).then(() => location.reload());
            else Swal.fire({ title: 'Error!', text: data.message || 'Failed', icon: 'error', background: '#1e293b' });
        } catch (error) { Swal.fire({ title: 'Error!', text: 'Network error', icon: 'error', background: '#1e293b' }); }
    }

    // ============ REVIEW FUNCTIONS ============
    async function loadReviewsForEvent(eventId, eventTitle) {
        document.getElementById('reviewsEventName').textContent = eventTitle;
        document.getElementById('reviewsList').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
        try {
            const response = await fetch(`/admin/events/${eventId}/reviews`, { headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
            const reviews = await response.json(); 
            const reviewsArray = Array.isArray(reviews) ? reviews : (reviews?.data || []);
            if (!reviewsArray.length) { document.getElementById('reviewsList').innerHTML = '<div class="text-center py-4"><i class="fas fa-star fa-3x mb-2" style="opacity:0.3;"></i><p>No reviews yet.</p></div>'; return; }
            const totalRating = reviewsArray.reduce((sum, r) => sum + (r.rating || 0), 0); 
            const avg = (totalRating / reviewsArray.length).toFixed(1);
            document.getElementById('avgRating').textContent = avg; 
            document.getElementById('avgStars').innerHTML = getStars(parseFloat(avg)); 
            document.getElementById('totalReviews').textContent = `${reviewsArray.length} review${reviewsArray.length !== 1 ? 's' : ''}`;
            document.getElementById('reviewsList').innerHTML = reviewsArray.map(r => {
                const userName = r.user_name || r.user?.name || 'Anonymous'; 
                const userInitial = userName.charAt(0).toUpperCase();
                return `<div class="review-item"><button class="review-delete-btn" onclick="deleteReview(${r.id}, ${eventId})" ${canDeleteReview ? '' : 'disabled'}><i class="fas fa-trash"></i></button><div class="d-flex align-items-start gap-3 mb-2"><div style="width:32px;height:32px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">${userInitial}</div><div><strong>${escapeHtml(userName)}</strong><div class="star-rating">${getStars(r.rating || 0)}</div></div><small style="color:#94a3b8;">${r.created_at ? new Date(r.created_at).toLocaleDateString() : 'N/A'}</small></div>${r.comment ? `<p style="color:#e2e8f0;margin:0;padding-left:44px;">${escapeHtml(r.comment)}</p>` : ''}</div>`;
            }).join('');
        } catch (error) { document.getElementById('reviewsList').innerHTML = '<div class="text-center py-4 text-danger"><p>Failed to load reviews.</p></div>'; }
    }

    window.deleteReview = async function(reviewId, eventId) {
        const result = await Swal.fire({ title: 'Delete Review?', text: 'Cannot be undone.', icon: 'warning', background: '#1e293b', confirmButtonColor: '#ef4444', confirmButtonText: 'Delete', showCancelButton: true });
        if (result.isConfirmed) {
            try { 
                await fetch(`/admin/reviews/${reviewId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } }); 
                showToast('Review deleted'); loadReviewsForEvent(eventId, ''); 
            } catch (error) { showToast('Error', 'error'); }
        }
    };

    // ============ MESSAGE FUNCTIONS ============
    function getRepliedMessageIds() {
        return JSON.parse(localStorage.getItem('replied_message_ids') || '[]');
    }
    
    function markMessageAsReplied(messageId) {
        let ids = getRepliedMessageIds();
        if (!ids.includes(messageId)) {
            ids.push(messageId);
            localStorage.setItem('replied_message_ids', JSON.stringify(ids));
        }
    }
    
    function isMessageReplied(messageId) {
        return getRepliedMessageIds().includes(messageId);
    }
    
    function openReplyModal(email, name, subject, originalMessage, messageId, eventId) {
        if (isMessageReplied(messageId)) {
            showToast('You have already replied to this message.', 'error');
            return;
        }
        
        currentReplyData = { id: messageId, eventId: eventId, toEmail: email, toName: name, originalSubject: subject, originalMessage: originalMessage };
        
        document.getElementById('replyToEmail').value = email;
        document.getElementById('replyToName').value = name;
        document.getElementById('replyOriginalMessageId').value = messageId || '';
        document.getElementById('replyEventId').value = eventId || '';
        document.getElementById('replyEmailDisplay').value = `${escapeHtml(name)} <${escapeHtml(email)}>`;
        
        let replySubject = subject;
        if (!replySubject.toLowerCase().startsWith('re:')) replySubject = 'RE: ' + replySubject;
        document.getElementById('replySubject').value = replySubject;
        document.getElementById('replyMessage').value = '';
        
        replyModal.show();
    }

    async function sendReply(event) {
        event.preventDefault();
        
        const btn = document.getElementById('sendReplyBtn');
        const originalText = btn.innerHTML;
        const messageId = parseInt(document.getElementById('replyOriginalMessageId').value);
        
        if (isMessageReplied(messageId)) {
            showToast('Already replied to this message.', 'error');
            replyModal.hide();
            return;
        }
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
        
        const replyData = {
            to_email: document.getElementById('replyToEmail').value,
            to_name: document.getElementById('replyToName').value,
            subject: document.getElementById('replySubject').value,
            message: document.getElementById('replyMessage').value,
            original_message_id: messageId,
            event_id: document.getElementById('replyEventId').value,
            is_admin_reply: true,
            sender_type: 'admin'
        };
        
        try {
            const response = await fetch('/admin/messages/reply', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify(replyData)
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                markMessageAsReplied(messageId);
                replyModal.hide();
                showToast('✅ Reply sent! Reply button will disappear.', 'success');
                await loadAllMessages();
            } else {
                showToast(data.message || 'Failed to send reply', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Network error. Please try again.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    async function loadAllMessages() {
        const container = document.getElementById('messagesContainer'); 
        container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading messages...</p></div>';
        try {
            const response = await fetch('/admin/messages', { headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
            const data = await response.json(); 
            let messages = Array.isArray(data) ? data : (data?.data || []);
            
            messages = messages.filter(msg => {
                const isAdminReply = msg.is_admin_reply === true || 
                                   msg.sender_type === 'admin' ||
                                   msg.name === 'Admin' ||
                                   msg.name === 'Admin Support' ||
                                   (msg.subject && msg.subject.toString().toLowerCase().startsWith('re:'));
                return !isAdminReply;
            });
            
            if (!messages.length) { 
                container.innerHTML = '<div class="empty-messages"><i class="fas fa-inbox"></i><p>No messages found.</p></div>'; 
                return; 
            }
            
            const repliedIds = getRepliedMessageIds();
            
            container.innerHTML = messages.map(msg => {
                const isReplied = repliedIds.includes(msg.id);
                const shouldHideReply = isReplied;
                
                const replyButton = !shouldHideReply ? 
                    `<button class="btn-reply" onclick="openReplyModal('${escapeHtml(msg.email)}', '${escapeHtml(msg.name)}', '${escapeHtml(msg.subject || 'General Inquiry')}', '${escapeHtml(msg.message || msg.content || '')}', ${msg.id}, null)">
                        <i class="fas fa-reply"></i> Reply
                    </button>` : '';
                
                const badge = isReplied ? '<span class="replied-badge"><i class="fas fa-check-circle"></i> Replied</span>' : '';
                const cardClass = isReplied ? 'user-message-replied' : '';
                
                return `<div class="message-card ${cardClass}" data-message-id="${msg.id}">
                    <div class="message-header">
                        <div class="message-sender">
                            <div class="message-sender-icon"><i class="fas fa-user"></i></div>
                            <div class="message-sender-info">
                                <span class="message-sender-name">${escapeHtml(msg.name || 'Anonymous')}</span>
                                <span class="message-sender-email"><i class="fas fa-envelope"></i> ${escapeHtml(msg.email || 'No email')}</span>
                            </div>
                        </div>
                        <div class="message-date"><i class="far fa-clock"></i> ${msg.created_at ? new Date(msg.created_at).toLocaleString() : 'N/A'} ${badge}</div>
                    </div>
                    <div class="message-subject"><i class="fas fa-tag"></i> Subject: ${escapeHtml(msg.subject || 'General Inquiry')}</div>
                    <div class="message-content">${escapeHtml(msg.message || msg.content || 'No message content')}</div>
                    <div class="message-actions">
                        ${replyButton}
                        <button class="btn-delete-message" onclick="deleteMessage(${msg.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>`;
            }).join('');
        } catch (error) { 
            console.error('Error:', error);
            container.innerHTML = '<div class="empty-messages"><i class="fas fa-exclamation-triangle"></i><p>Failed to load messages.</p></div>'; 
        }
    }

    window.deleteMessage = async function(messageId) {
        const result = await Swal.fire({ title: 'Delete Message?', text: 'Cannot be undone.', icon: 'warning', background: '#1e293b', confirmButtonColor: '#ef4444', confirmButtonText: 'Delete', showCancelButton: true });
        if (result.isConfirmed) {
            try { 
                const response = await fetch(`/admin/messages/${messageId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                if (response.ok) { loadAllMessages(); showToast('Message deleted', 'success'); } 
                else showToast('Failed', 'error'); 
            } catch (error) { showToast('Error', 'error'); }
        }
    };

    // ============ QR SCANNER FUNCTIONS ============
    let html5QrCode = null;

    function openQRScanner() {
        const modalHtml = `
            <div id="qrScannerModal" class="qr-scanner-modal">
                <div class="qr-scanner-box">
                    <h3><i class="fas fa-qrcode"></i> Scan QR Code</h3>
                    <div id="qr-reader"></div>
                    <div id="qr-result"></div>
                    <div id="qrEventInfo" class="qr-info-box">
                        <div class="event-title" id="qrEventTitle">Loading...</div>
                        <div class="qr-event-status" id="qrEventStatus">Status</div>
                        <div class="qr-info-grid" style="margin-top: 12px;">
                            <div class="info-item"><i class="fas fa-calendar-day"></i><span><span class="label">Date:</span> <span id="qrEventDate">-</span></span></div>
                            <div class="info-item"><i class="fas fa-clock"></i><span><span class="label">Time:</span> <span id="qrEventTime">-</span></span></div>
                            <div class="info-item"><i class="fas fa-map-marker-alt"></i><span><span class="label">Location:</span> <span id="qrEventLocation">-</span></span></div>
                            <div class="info-item"><i class="fas fa-tag"></i><span><span class="label">Price:</span> <span id="qrEventPrice">-</span></span></div>
                        </div>
                        <div style="margin-top: 10px; padding: 8px 12px; background: rgba(102,126,234,0.1); border-radius: 8px; font-size: 13px; color: #94a3b8; text-align: center;">
                            <i class="fas fa-users"></i> <span id="qrEventRegistrations">0</span> registered attendees
                        </div>
                        <button onclick="window.location.href=document.getElementById('qrCheckinUrl').value" class="qr-checkin-btn">
                            <i class="fas fa-check-circle"></i> Check In Now
                        </button>
                        <input type="hidden" id="qrCheckinUrl">
                    </div>
                    <button class="btn-close-scanner" onclick="closeQRScanner()">
                        <i class="fas fa-times"></i> Close Scanner
                    </button>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        if (typeof Html5Qrcode !== 'undefined') {
            html5QrCode = new Html5Qrcode("qr-reader");
            const qrCodeSuccessCallback = async (decodedText, decodedResult) => {
                html5QrCode.stop();
                
                const resultDiv = document.getElementById('qr-result');
                const eventInfoDiv = document.getElementById('qrEventInfo');
                resultDiv.style.display = 'block';
                
                if (decodedText.includes('/checkin/')) {
                    try {
                        const match = decodedText.match(/\/checkin\/(\d+)/);
                        const eventId = match ? match[1] : null;
                        
                        if (eventId) {
                            const response = await fetch(`/admin/events/${eventId}/details`, {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            });
                            
                            if (response.ok) {
                                const eventData = await response.json();
                                
                                document.getElementById('qrEventTitle').textContent = eventData.title || 'Event';
                                document.getElementById('qrEventDate').textContent = eventData.event_date ? new Date(eventData.event_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
                                document.getElementById('qrEventTime').textContent = eventData.start_time && eventData.end_time ? 
                                    `${formatTime(eventData.start_time)} - ${formatTime(eventData.end_time)}` : 'N/A';
                                document.getElementById('qrEventLocation').textContent = eventData.location || 'N/A';
                                document.getElementById('qrEventPrice').textContent = eventData.price > 0 ? `$${parseFloat(eventData.price).toFixed(2)}` : 'Free';
                                document.getElementById('qrEventRegistrations').textContent = eventData.registrations_count || 0;
                                document.getElementById('qrCheckinUrl').value = decodedText;
                                
                                const statusBadge = document.getElementById('qrEventStatus');
                                const now = new Date();
                                const eventDate = new Date(eventData.event_date);
                                if (eventDate > now) {
                                    statusBadge.textContent = '📅 Upcoming';
                                    statusBadge.className = 'qr-event-status upcoming';
                                } else if (eventDate.toDateString() === now.toDateString()) {
                                    statusBadge.textContent = '🔴 Today';
                                    statusBadge.className = 'qr-event-status today';
                                } else {
                                    statusBadge.textContent = '📆 Past';
                                    statusBadge.className = 'qr-event-status past';
                                }
                                
                                resultDiv.innerHTML = `<div class="qr-success" style="padding: 12px; border-radius: 12px;">✅ QR Code scanned successfully! Event details loaded below.</div>`;
                                eventInfoDiv.style.display = 'block';
                            } else {
                                resultDiv.innerHTML = `<div class="qr-error" style="padding: 12px; border-radius: 12px;">❌ Could not load event details. Please try again.</div>`;
                            }
                        } else {
                            resultDiv.innerHTML = `<div class="qr-error" style="padding: 12px; border-radius: 12px;">❌ Invalid QR code format. Could not extract event ID.</div>`;
                        }
                    } catch (error) {
                        console.error('Error fetching event details:', error);
                        resultDiv.innerHTML = `<div class="qr-error" style="padding: 12px; border-radius: 12px;">❌ Error loading event details: ${error.message}</div>`;
                    }
                } else {
                    resultDiv.innerHTML = `<div class="qr-error" style="padding: 12px; border-radius: 12px;">⚠️ Invalid check-in QR code. Please scan a valid EventHub QR code.</div>`;
                    setTimeout(() => {
                        html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, qrCodeSuccessCallback)
                            .catch(err => console.log('Restart error:', err));
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

    // ============ EVENT HANDLERS ============
    document.getElementById('eventsTableBody')?.addEventListener('click', function(e) {
        // View Button
        if (e.target.closest('.view-btn')) { 
            const btn = e.target.closest('.view-btn'); 
            const eventId = btn.getAttribute('data-event-id');
            viewEventDetails(eventId);
        }
        
        // Reviews Button
        if (e.target.closest('.review-btn') && !e.target.closest('.review-btn').disabled) { 
            const btn = e.target.closest('.review-btn'); 
            loadReviewsForEvent(btn.dataset.eventId, btn.dataset.eventTitle); 
            reviewsModal.show(); 
        }
        
        // Edit Button
        if (e.target.closest('.edit-btn')) { 
            const btn = e.target.closest('.edit-btn'); 
            document.getElementById('editId').value = btn.dataset.eventId; 
            document.getElementById('editTitle').value = btn.dataset.title; 
            document.getElementById('editDescription').value = btn.dataset.description || ''; 
            document.getElementById('editDate').value = btn.dataset.date; 
            document.getElementById('editLocation').value = btn.dataset.location; 
            document.getElementById('editStartTime').value = btn.dataset.startTime; 
            document.getElementById('editEndTime').value = btn.dataset.endTime; 
            document.getElementById('editPrice').value = btn.dataset.price; 
            editModal.show(); 
        }
        
        // Delete Button
        if (e.target.closest('.delete-btn')) { 
            const btn = e.target.closest('.delete-btn'); 
            openDeleteModal(btn.dataset.eventId, btn.dataset.eventTitle);
        }
    });

    document.getElementById('saveEditBtn')?.addEventListener('click', function() { 
        const id = document.getElementById('editId').value; 
        if (!id) return; 
        const btn = this; 
        btn.disabled = true; 
        btn.innerHTML = 'Saving...'; 
        fetch(`/admin/events/${id}`, { 
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/x-www-form-urlencoded' }, 
            body: new URLSearchParams({ 
                _method: 'PUT', 
                _token: csrfToken, 
                title: document.getElementById('editTitle').value, 
                description: document.getElementById('editDescription').value, 
                event_date: document.getElementById('editDate').value, 
                location: document.getElementById('editLocation').value, 
                start_time: document.getElementById('editStartTime').value, 
                end_time: document.getElementById('editEndTime').value, 
                price: document.getElementById('editPrice').value 
            }) 
        })
        .then(response => response.json())
        .then(data => { 
            if (data.success) {
                editModal.hide(); 
                showToast('Event updated successfully');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Update failed', 'error');
            }
        })
        .catch(() => showToast('Update failed', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = 'Save Changes'; }); 
    });

    document.getElementById('replyMessageForm')?.addEventListener('submit', sendReply);

    window.filterEvents = filterEvents;
    window.loadAllMessages = loadAllMessages;
    window.openReplyModal = openReplyModal;
    window.openDeleteModal = openDeleteModal;
    window.openQRScanner = openQRScanner;
    window.closeQRScanner = closeQRScanner;
    window.viewEventDetails = viewEventDetails;
</script>
</body>
</html><?php /**PATH C:\Users\User\backend\resources\views/admin/events/index.blade.php ENDPATH**/ ?>