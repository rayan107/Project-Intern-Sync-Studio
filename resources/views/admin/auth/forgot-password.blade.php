<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password · EventHub Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
      font-family: 'Inter', 'Segoe UI', sans-serif;
      background: radial-gradient(circle at 20% 30%, #a78bfa20 0%, transparent 35%),
                  radial-gradient(circle at 80% 70%, #818cf820 0%, transparent 40%),
                  linear-gradient(145deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .bg-blob {
      position: fixed;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.25;
      z-index: 0;
      pointer-events: none;
    }
    .blob1 {
      width: 500px; height: 500px; background: #667eea;
      top: -150px; left: -100px;
      animation: float1 18s infinite alternate ease-in-out;
    }
    .blob2 {
      width: 450px; height: 450px; background: #a78bfa;
      bottom: -120px; right: -80px;
      animation: float2 20s infinite alternate ease-in-out;
    }
    .blob3 {
      width: 350px; height: 350px; background: #f472b6;
      top: 60%; left: 60%;
      animation: float3 22s infinite alternate ease-in-out;
    }

    @keyframes float1 {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(70px, 60px) scale(1.2); }
    }
    @keyframes float2 {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(-60px, -40px) scale(1.3); }
    }
    @keyframes float3 {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(-50px, 70px) scale(1.15); }
    }

    .login-container {
      position: relative;
      z-index: 10;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(30px);
      border-radius: 28px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 520px;
      overflow: hidden;
    }

    .login-header {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
      padding: 2.5rem 2rem 2rem;
      text-align: center;
      color: #fff;
      clip-path: ellipse(120% 100% at 50% 0%);
    }

    .login-header .icon-circle {
      background: rgba(255, 255, 255, 0.18);
      width: 70px; height: 70px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1rem;
      font-size: 2.2rem;
      border: 1px solid rgba(255,255,255,0.3);
    }

    .login-header h2 {
      font-size: 2.1rem; font-weight: 700;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .login-header p {
      font-size: 1rem; opacity: 0.85;
      margin-top: 5px;
    }

    .login-body {
      padding: 2.5rem 2.2rem 2.2rem;
    }

    .alert {
      backdrop-filter: blur(16px);
      padding: 0.9rem 1.2rem;
      margin-bottom: 1.5rem;
      border-radius: 14px;
      font-size: 0.9rem;
      display: flex; align-items: center; gap: 0.7rem;
      font-weight: 500;
    }

    .alert-success {
      background: rgba(74, 222, 128, 0.15);
      border-left: 5px solid #4ade80;
      color: #bbf7d0;
      border: 1px solid rgba(74, 222, 128, 0.4);
    }

    .alert-error {
      background: rgba(239, 68, 68, 0.15);
      border-left: 5px solid #ef4444;
      color: #fecaca;
      border: 1px solid rgba(239,68,68,0.5);
    }

    .reset-link-box {
      background: rgba(74, 222, 128, 0.12);
      border: 1px solid rgba(74, 222, 128, 0.5);
      border-radius: 14px;
      padding: 16px;
      margin-bottom: 1.5rem;
      word-break: break-all;
    }

    .reset-link-box p {
      color: #bbf7d0;
      font-size: 13px;
      margin-bottom: 10px;
    }

    .reset-link-box a {
      color: #4ade80;
      font-size: 14px;
      font-weight: 600;
      text-decoration: underline;
    }

    .reset-link-box a:hover {
      color: #86efac;
    }

    .form-group {
      margin-bottom: 1.8rem;
    }

    .form-label {
      display: flex; align-items: center; gap: 0.4rem;
      margin-bottom: 0.6rem;
      color: #e2e8f0;
      font-weight: 600; font-size: 0.9rem;
      text-transform: uppercase; letter-spacing: 0.3px;
    }

    .form-control {
      width: 100%;
      padding: 1rem 1.2rem;
      background: rgba(15, 23, 42, 0.45);
      backdrop-filter: blur(12px);
      border: 1.5px solid rgba(255, 255, 255, 0.25);
      border-radius: 14px;
      font-size: 1rem;
      color: #f1f5f9;
      outline: none;
    }

    .form-control:focus {
      border-color: #a78bfa;
      box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.35);
    }

    .form-control::placeholder {
      color: #94a3b8;
    }

    .btn-login {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 700;
      color: white;
      cursor: pointer;
      box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.6);
      transition: all 0.3s ease;
      display: flex; align-items: center; justify-content: center; gap: 0.5rem;
      border: 1px solid rgba(255,255,255,0.25);
    }

    .btn-login:hover {
      background: linear-gradient(135deg, #8b5cf6, #6d28d9);
      transform: translateY(-3px);
    }

    .btn-login:disabled {
      opacity: 0.6; cursor: not-allowed; transform: none;
    }

    .login-footer {
      text-align: center;
      margin-top: 2rem;
      padding-top: 1.8rem;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      font-size: 0.9rem;
      color: #cbd5e1;
    }

    .login-footer a {
      color: #c4b5fd;
      text-decoration: none;
      font-weight: 600;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }

    .description-text {
      color: #cbd5e1;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      line-height: 1.6;
    }
  </style>
</head>
<body>
  <div class="bg-blob blob1"></div>
  <div class="bg-blob blob2"></div>
  <div class="bg-blob blob3"></div>

  <div class="login-container">
    <div class="login-header">
      <div class="icon-circle">
        <i class="fas fa-key"></i>
      </div>
      <h2>Forgot Password</h2>
      <p>We'll send you a reset link</p>
    </div>

    <div class="login-body">
      
     
      @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>

    
      @if(session('resetLink'))
      <div class="reset-link-box">
        <p><i class="fas fa-link"></i> <strong>Reset Password Link:</strong></p>
        <a href="{{ session('resetLink') }}" target="_blank">
          {{ session('resetLink') }}
        </a>
        <p style="color: #86efac; font-size: 11px; margin-top: 10px;">
          <i class="fas fa-info-circle"></i> Click the link above or copy it to your browser. This link expires in 60 minutes.
        </p>
      </div>
      @endif
      @endif

    
      @if(session('error'))
      <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ session('error') }}</span>
      </div>
      @endif

      @if($errors->any())
      <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ $errors->first() }}</span>
      </div>
      @endif

      <p class="description-text">
        Enter your admin email address and we'll send you a link to reset your password.
      </p>

      <form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm">
        @csrf
        
        <div class="form-group">
          <label class="form-label" for="email">
            <i class="fas fa-envelope"></i> Email Address
          </label>
          <input 
            type="email" 
            id="email"
            name="email" 
            class="form-control" 
            placeholder="admin@example.com"
            value="{{ old('email') }}"
            required
            autofocus
          >
        </div>

        <button type="submit" class="btn-login" id="submitBtn">
          <i class="fas fa-paper-plane"></i> Send Reset Link
        </button>
      </form>

      <div class="login-footer">
        <i class="fas fa-arrow-left"></i> 
        <a href="{{ route('admin.login') }}">Back to Login</a>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('forgotForm').addEventListener('submit', function() {
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    });
  </script>
</body>
</html>