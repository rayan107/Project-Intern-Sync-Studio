<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Result | EventHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .result-card {
            background: rgba(255,255,255,0.98);
            border-radius: 48px;
            max-width: 500px;
            width: 100%;
            padding: 48px 32px;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .icon-circle.success { background: linear-gradient(135deg, #22d87a, #16a35a); }
        .icon-circle.error { background: linear-gradient(135deg, #ff4d6d, #c0143c); }
        .icon-circle i { color: white; font-size: 48px; }
        h1 { font-size: 28px; font-weight: 800; margin-bottom: 12px; color: #1a1a2e; }
        .message { font-size: 16px; color: #555; margin-bottom: 28px; line-height: 1.6; }
        .event-details {
            background: #f5f5ff;
            border-radius: 24px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .event-details h3 {
            font-size: 18px;
            color: #6c63ff;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .detail-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            color: #333;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row i { width: 24px; color: #6c63ff; font-size: 16px; }
        .checkin-time {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px dashed #ddd;
            font-size: 14px;
            color: #555;
            text-align: center;
        }
        .checkin-time i {
            color: #6c63ff;
            margin-right: 6px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 8px;
        }
        .status-badge.success {
            background: rgba(34, 216, 122, 0.15);
            color: #16a35a;
        }
        .status-badge.error {
            background: rgba(255, 77, 109, 0.15);
            color: #c0143c;
        }
        .status-badge.warning {
            background: rgba(255, 193, 7, 0.15);
            color: #b8860b;
        }
        .footer-note {
            margin-top: 24px;
            font-size: 11px;
            color: #aaa;
        }
        @media (max-width: 480px) {
            .result-card { padding: 32px 20px; }
            h1 { font-size: 22px; }
            .icon-circle { width: 80px; height: 80px; }
            .icon-circle i { font-size: 36px; }
        }
    </style>
</head>
<body>
    <div class="result-card">
        <?php if(isset($success) && $success): ?>
            <div class="icon-circle success">
                <i class="bi bi-check-lg"></i>
            </div>
            <h1>✅ Check-in Successful!</h1>
            <p class="message"><?php echo e($user->name ?? 'User'); ?> has been successfully checked in.</p>
            
            <div class="event-details">
                <h3><i class="bi bi-calendar-event"></i> Event Details</h3>
                
                
                <div class="detail-row">
                    <i class="bi bi-tag-fill"></i>
                    <span><strong><?php echo e($event->title ?? 'N/A'); ?></strong></span>
                </div>
                
                
                <div class="detail-row">
                    <i class="bi bi-calendar3"></i>
                    <span>
                        <?php if(isset($event->event_date)): ?>
                            <?php echo e(\Carbon\Carbon::parse($event->event_date)->format('l, F d, Y')); ?>

                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </span>
                </div>
                
                
                <?php if(isset($event->start_time) && isset($event->end_time)): ?>
                <div class="detail-row">
                    <i class="bi bi-clock"></i>
                    <span>
                        <?php echo e(\Carbon\Carbon::parse($event->start_time)->format('g:i A')); ?> 
                        - 
                        <?php echo e(\Carbon\Carbon::parse($event->end_time)->format('g:i A')); ?>

                    </span>
                </div>
                <?php endif; ?>
                
                
                <div class="detail-row">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span><?php echo e($event->location ?? 'N/A'); ?></span>
                </div>
                
                
                <div class="detail-row">
                    <i class="bi bi-person-circle"></i>
                    <span>
                        <strong><?php echo e($user->name ?? 'N/A'); ?></strong> 
                        <small style="color: #888;">(<?php echo e($user->email ?? 'N/A'); ?>)</small>
                    </span>
                </div>
                
                
                <div class="detail-row">
                    <i class="bi bi-person-check"></i>
                    <span>
                        <span class="status-badge success">✅ Registered</span>
                    </span>
                </div>
            </div>

            
            <div style="background: #e8f5e9; border-radius: 16px; padding: 16px; margin: 16px 0;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="bi bi-clock-history" style="color: #2e7d32; font-size: 20px;"></i>
                    <span style="color: #333; font-size: 14px;">Checked in at:</span>
                    <?php
                        // Get the current time in Lebanon timezone
                        $now = \Carbon\Carbon::now('Asia/Beirut');
                    ?>
                    <strong style="color: #1a1a2e; font-size: 16px;">
                        <?php echo e($now->format('g:i A')); ?>

                    </strong>
                    <span style="color: #888; font-size: 13px;">
                        (<?php echo e($now->format('l, F d, Y')); ?>)
                    </span>
                </div>
            </div>
            
        <?php else: ?>
            <div class="icon-circle error">
                <i class="bi bi-x-lg"></i>
            </div>
            <h1>❌ Check-in Failed</h1>
            <p class="message"><?php echo e($message ?? 'Unable to verify check-in'); ?></p>
            
            <?php if(isset($user) && isset($event)): ?>
            <div class="event-details" style="background: #fff5f5;">
                <h3 style="color: #dc3545;"><i class="bi bi-info-circle"></i> Details</h3>
                <div class="detail-row">
                    <i class="bi bi-person-circle"></i>
                    <span><?php echo e($user->name ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <i class="bi bi-envelope"></i>
                    <span><?php echo e($user->email ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <i class="bi bi-calendar-event"></i>
                    <span><?php echo e($event->title ?? 'N/A'); ?></span>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="footer-note">
            Powered by EventHub
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\User\backend\resources\views/checkin-result.blade.php ENDPATH**/ ?>