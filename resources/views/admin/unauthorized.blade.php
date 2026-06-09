<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>|EventHub</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700&family=Syne:wght@700;800&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:linear-gradient(145deg,#0f172a,#1e293b); min-height:100vh; display:flex; align-items:center; justify-content:center; text-align:center; }
        .wrap { padding:40px 24px; }
        .icon { width:100px; height:100px; background:rgba(239,68,68,0.15); border:2px solid rgba(239,68,68,0.3); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 28px; font-size:42px; }
        h1 { font-family:'Syne',sans-serif; font-size:80px; color:#ef4444; margin-bottom:10px; }
        h2 { font-family:'Syne',sans-serif; color:#fff; font-size:24px; margin-bottom:12px; }
        p { color:#94a3b8; font-size:15px; margin-bottom:32px; max-width:400px; margin-left:auto; margin-right:auto; }
        .btn { display:inline-flex; align-items:center; gap:8px; padding:12px 28px; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; border-radius:12px; text-decoration:none; font-weight:600; font-size:14px; transition:all 0.2s; }
        .btn:hover { opacity:0.9; transform:translateY(-2px); color:#fff; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="icon">🔒</div>
        <h1>403</h1>
        <h2>Unauthorized</h2>
        <p>{{ $message ?? 'You are not authorized to access this page. Please contact the Super Admin if you need additional permissions.' }}</p>
        <a href="{{ route('admin.dashboard') }}" class="btn">← Back to Dashboard</a>
    </div>
</body>
</html>