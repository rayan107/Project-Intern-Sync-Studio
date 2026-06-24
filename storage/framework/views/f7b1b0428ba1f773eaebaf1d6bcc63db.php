<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password · EventHub Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
   
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', 'Segoe UI', sans-serif;
      background: radial-gradient(circle at 20% 30%, #a78bfa20 0%, transparent 35%),
                  radial-gradient(circle at 80% 70%, #818cf820 0%, transparent 40%),
                  linear-gradient(145deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      padding: 2rem;
    }
    .login-container {
      position: relative; z-index: 10;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(30px);
      border-radius: 28px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
      width: 100%; max-width: 500px;
      overflow: hidden;
    }
    .login-header {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
      padding: 2.5rem 2rem 2rem;
      text-align: center; color: #fff;
      clip-path: ellipse(120% 100% at 50% 0%);
    }
    .login-header .icon-circle {
      background: rgba(255, 255, 255, 0.18);
      width: 70px; height: 70px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1rem; font-size: 2.2rem;
      border: 1px solid rgba(255,255,255,0.3);
    }
    .login-header h2 { font-size: 2.1rem; font-weight: 700; }
    .login-header p { font-size: 1rem; opacity: 0.85; margin-top: 5px; }
    .login-body { padding: 2.5rem 2.2rem 2.2rem; }
    .alert {
      backdrop-filter: blur(16px); padding: 0.9rem 1.2rem;
      margin-bottom: 1.5rem; border-radius: 14px; font-size: 0.9rem;
      display: flex; align-items: center; gap: 0.7rem; font-weight: 500;
    }
    .alert-error {
      background: rgba(239, 68, 68, 0.15);
      border-left: 5px solid #ef4444; color: #fecaca;
      border: 1px solid rgba(239,68,68,0.5);
    }
    .form-group { margin-bottom: 1.8rem; }
    .form-label {
      display: flex; align-items: center; gap: 0.4rem;
      margin-bottom: 0.6rem; color: #e2e8f0;
      font-weight: 600; font-size: 0.9rem;
      text-transform: uppercase; letter-spacing: 0.3px;
    }
    .form-control {
      width: 100%; padding: 1rem 1.2rem;
      background: rgba(15, 23, 42, 0.45);
      backdrop-filter: blur(12px);
      border: 1.5px solid rgba(255, 255, 255, 0.25);
      border-radius: 14px; font-size: 1rem; color: #f1f5f9;
      outline: none;
    }
    .form-control:focus {
      border-color: #a78bfa;
      box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.35);
    }
    .btn-login {
      width: 100%; padding: 1rem;
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
      border: none; border-radius: 50px;
      font-size: 1.1rem; font-weight: 700; color: white;
      cursor: pointer; transition: all 0.3s ease;
      display: flex; align-items: center; justify-content: center; gap: 0.5rem;
      border: 1px solid rgba(255,255,255,0.25);
    }
    .btn-login:hover { transform: translateY(-3px); }
    .btn-login:disabled { opacity: 0.6; cursor: not-allowed; }
    .password-toggle {
      position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
      background: transparent; border: none; color: #cbd5e1;
      font-size: 1.3rem; cursor: pointer;
    }
    .password-input { position: relative; }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <div class="icon-circle">
        <i class="fas fa-lock"></i>
      </div>
      <h2>Reset Password</h2>
      <p>Choose a new password</p>
    </div>

    <div class="login-body">
      <?php if(session('error')): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?php echo e(session('error')); ?></span>
      </div>
      <?php endif; ?>

      <?php if($errors->any()): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?php echo e($errors->first()); ?></span>
      </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(route('admin.password.update')); ?>" id="resetForm">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="token" value="<?php echo e($token); ?>">
        <input type="hidden" name="email" value="<?php echo e($email); ?>">

        <div class="form-group">
          <label class="form-label" for="password">
            <i class="fas fa-key"></i> New Password
          </label>
          <div class="password-input">
            <input 
              type="password" 
              id="password"
              name="password" 
              class="form-control" 
              placeholder="Min. 8 characters"
              required
              minlength="8"
            >
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirmation">
            <i class="fas fa-check-circle"></i> Confirm Password
          </label>
          <div class="password-input">
            <input 
              type="password" 
              id="password_confirmation"
              name="password_confirmation" 
              class="form-control" 
              placeholder="Repeat password"
              required
            >
          </div>
        </div>

        <button type="submit" class="btn-login" id="submitBtn">
          <i class="fas fa-save"></i> Reset Password
        </button>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('resetForm').addEventListener('submit', function() {
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
    });
  </script>
</body>
</html><?php /**PATH C:\Users\User\backend\resources\views/admin/auth/reset-password.blade.php ENDPATH**/ ?>