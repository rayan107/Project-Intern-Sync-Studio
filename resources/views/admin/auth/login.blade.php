<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>EventHub Admin · Advanced Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --primary: #667eea;
      --primary-dark: #5a67d8;
      --secondary: #764ba2;
      --glass-bg: rgba(255, 255, 255, 0.18);
      --glass-border: rgba(255, 255, 255, 0.25);
      --glass-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
      --text-dark: #1e293b;
      --text-muted: #475569;
      --surface: #ffffff;
      --input-bg: rgba(248, 250, 252, 0.7);
      --error-bg: rgba(254, 226, 226, 0.9);
      --toast-error: #ef4444;
      --radius-xl: 28px;
      --radius-lg: 18px;
      --radius-md: 14px;
      --transition: all 0.25s ease;
    }

    body {
      font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: radial-gradient(circle at 20% 30%, #a78bfa20 0%, transparent 35%),
                  radial-gradient(circle at 80% 70%, #818cf820 0%, transparent 40%),
                  linear-gradient(145deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      margin: 0;
      position: relative;
      overflow-x: hidden;
    }

    .bg-blob {
      position: fixed;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.25;
      z-index: 0;
      pointer-events: none;
    }
    .blob1 { width: 500px; height: 500px; background: #667eea; top: -150px; left: -100px; animation: float1 18s infinite alternate ease-in-out; }
    .blob2 { width: 450px; height: 450px; background: #a78bfa; bottom: -120px; right: -80px; animation: float2 20s infinite alternate ease-in-out; }
    .blob3 { width: 350px; height: 350px; background: #f472b6; top: 60%; left: 60%; animation: float3 22s infinite alternate ease-in-out; }

    @keyframes float1 { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(70px, 60px) scale(1.2); } }
    @keyframes float2 { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-60px, -40px) scale(1.3); } }
    @keyframes float3 { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-50px, 70px) scale(1.15); } }

    .login-container {
      position: relative;
      z-index: 10;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(30px);
      -webkit-backdrop-filter: blur(30px);
      border-radius: var(--radius-xl);
      border: 1px solid var(--glass-border);
      box-shadow: var(--glass-shadow), 0 0 0 1px rgba(255, 255, 255, 0.15) inset;
      width: 100%;
      max-width: 500px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .login-container:hover {
      transform: translateY(-4px);
      box-shadow: 0 35px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.2) inset;
    }

    .login-header {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
      backdrop-filter: blur(12px);
      padding: 2.5rem 2rem 2rem;
      text-align: center;
      color: #fff;
      position: relative;
      clip-path: ellipse(120% 100% at 50% 0%);
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .login-header .icon-circle {
      background: rgba(255, 255, 255, 0.18);
      width: 70px;
      height: 70px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 2.2rem;
      backdrop-filter: blur(10px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.25);
      border: 1px solid rgba(255,255,255,0.3);
    }

    .login-header h2 { font-size: 2.1rem; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 0.25rem; text-shadow: 0 4px 12px rgba(0,0,0,0.3); }
    .login-header p { font-size: 1rem; opacity: 0.85; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 0.3rem; }

    .login-body {
      padding: 2.5rem 2.2rem 2.2rem;
      background: rgba(255, 255, 255, 0.04);
      backdrop-filter: blur(12px);
    }

    .alert {
      background: rgba(255, 243, 205, 0.15);
      backdrop-filter: blur(16px);
      border-left: 5px solid #fbbf24;
      padding: 0.9rem 1.2rem;
      margin-bottom: 2rem;
      border-radius: 14px;
      font-size: 0.9rem;
      color: #fef3c7;
      display: flex;
      align-items: center;
      gap: 0.7rem;
      background: rgba(251, 191, 36, 0.12);
      border: 1px solid rgba(251, 191, 36, 0.4);
      font-weight: 500;
    }

    .alert-error {
      background: rgba(239, 68, 68, 0.15);
      border-left: 5px solid #ef4444;
      color: #fecaca;
      border-color: rgba(239,68,68,0.5);
    }

    .alert-success {
      background: rgba(34, 197, 94, 0.15);
      border-left: 5px solid #22c55e;
      color: #bbf7d0;
      border-color: rgba(34, 197, 94, 0.5);
    }

    .alert-info {
      background: rgba(59, 130, 246, 0.15);
      border-left: 5px solid #3b82f6;
      color: #bfdbfe;
      border-color: rgba(59, 130, 246, 0.5);
    }

    .form-group { margin-bottom: 1.8rem; }
    .form-label { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.6rem; color: #e2e8f0; font-weight: 600; font-size: 0.9rem; letter-spacing: 0.3px; text-transform: uppercase; }
    .form-control { width: 100%; padding: 1rem 1.2rem; background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(12px); border: 1.5px solid rgba(255, 255, 255, 0.25); border-radius: var(--radius-md); font-size: 1rem; color: #f1f5f9; transition: var(--transition); outline: none; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .form-control::placeholder { color: #94a3b8; font-weight: 400; }
    .form-control:focus { border-color: #a78bfa; background: rgba(15, 23, 42, 0.65); box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.35); }

    .password-input { position: relative; display: flex; align-items: center; }
    .password-toggle { position: absolute; right: 16px; background: transparent; border: none; color: #cbd5e1; font-size: 1.3rem; cursor: pointer; padding: 6px; border-radius: 50%; transition: 0.2s; display: flex; align-items: center; }
    .password-toggle:hover { color: white; background: rgba(255,255,255,0.15); }

    .forgot-link { display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem; color: #c4b5fd; text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: 0.2s; margin-bottom: 2rem; margin-top: -0.5rem; }
    .forgot-link:hover { color: #e9d5ff; text-decoration: underline; }

    .btn-login { width: 100%; padding: 1rem; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 700; color: white; cursor: pointer; box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.6); transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; letter-spacing: 0.5px; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px); }
    .btn-login:hover { background: linear-gradient(135deg, #8b5cf6, #6d28d9); transform: translateY(-3px); box-shadow: 0 18px 30px -8px #7c3aed; }
    .btn-login:active { transform: translateY(2px); box-shadow: 0 8px 18px #7c3aed; }
    .btn-login:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .login-footer { text-align: center; margin-top: 2rem; padding-top: 1.8rem; border-top: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.9rem; color: #cbd5e1; display: flex; align-items: center; justify-content: center; gap: 0.3rem; }
    .login-footer a { color: #c4b5fd; text-decoration: none; font-weight: 600; }
    .login-footer a:hover { text-decoration: underline; }

    @media (max-width: 500px) { body { padding: 1rem; } .login-body { padding: 1.8rem; } }
  </style>
</head>
<body>
  <div class="bg-blob blob1"></div>
  <div class="bg-blob blob2"></div>
  <div class="bg-blob blob3"></div>

  <div class="login-container">
    <div class="login-header">
      <div class="icon-circle"><i class="fas fa-calendar-check"></i></div>
      <h2>EventHub</h2>
      <p><i class="fas fa-shield-alt"></i> Admin Gateway</p>
    </div>

    <div class="login-body">
      <!-- Default message (Administrator Access Only) -->
      <div id="defaultMessage" class="alert">
        <i class="fas fa-lock"></i>
        <span>Administrator Access Only</span>
      </div>

      <!-- Temporary messages (will disappear after 8 seconds) -->
      <div id="tempMessages">
        @if($errors->any())
        <div class="alert alert-error temp-message">
          <i class="fas fa-exclamation-triangle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error temp-message">
          <i class="fas fa-exclamation-triangle"></i>
          <span>{{ session('error') }}</span>
        </div>
        @endif

        @if(session('message'))
        <div class="alert alert-info temp-message">
          <i class="fas fa-info-circle"></i>
          <span>{{ session('message') }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success temp-message">
          <i class="fas fa-check-circle"></i>
          <span>{{ session('success') }}</span>
        </div>
        @endif
      </div>

      <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
        @csrf
        
        <div class="form-group">
          <label class="form-label" for="email"><i class="fas fa-envelope"></i> Email Address</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="admin@eventhub.com" value="{{ old('email') }}" required autocomplete="email">
        </div>

        <div class="form-group">
          <label class="form-label" for="password"><i class="fas fa-key"></i> Password</label>
          <div class="password-input">
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
            <button type="button" class="password-toggle" id="togglePasswordBtn"><i class="fas fa-eye"></i></button>
          </div>
        </div>

        <a href="{{ route('admin.password.request') }}" class="forgot-link"><i class="fas fa-question-circle"></i> Forgot password?</a>

        <button type="submit" class="btn-login" id="submitBtn"><i class="fas fa-arrow-right-to-bracket"></i> Sign In to Dashboard</button>
      </form>

      <div class="login-footer">
        <i class="fas fa-shield-heart"></i>
        <span>Secure Area • Authorized Personnel Only</span>
      </div>
    </div>
  </div>

  <script>
    (function() {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const defaultMessage = document.getElementById('defaultMessage');
        
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        if (loginForm && submitBtn) {
            loginForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
            });
        }

        // Hide default message initially if there are temporary messages
        const tempMessages = document.querySelectorAll('.temp-message');
        
        if (tempMessages.length > 0) {
            // Hide default message
            if (defaultMessage) {
                defaultMessage.style.display = 'none';
            }
            
            // Auto-hide temporary messages after 8 seconds
            tempMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-10px)';
                    setTimeout(function() {
                        if (message && message.parentNode) {
                            message.remove();
                            // After removing all temp messages, show default message again
                            const remainingTempMessages = document.querySelectorAll('.temp-message');
                            if (remainingTempMessages.length === 0 && defaultMessage) {
                                defaultMessage.style.display = 'flex';
                                defaultMessage.style.opacity = '1';
                                defaultMessage.style.transform = 'translateY(0)';
                            }
                        }
                    }, 500);
                }, 8000); // 8 seconds = 8000 milliseconds
            });
        }
    })();
  </script>
</body>
</html>