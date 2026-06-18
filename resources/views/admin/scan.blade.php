<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Scan QR Code | EventHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(145deg, #0f172a, #1e293b);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .scan-container {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 48px;
            max-width: 450px;
            width: 100%;
            padding: 40px 28px;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }
        .scan-header { margin-bottom: 32px; }
        .scan-header h1 { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .scan-header p { color: #94a3b8; font-size: 14px; }
        .scanner-wrapper {
            position: relative;
            background: #1e293b;
            border-radius: 24px;
            overflow: hidden;
            aspect-ratio: 1/1;
            margin-bottom: 24px;
            border: 2px solid rgba(108,99,255,0.3);
        }
        #scanner { width: 100%; height: 100%; object-fit: cover; }
        .scan-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }
        .scan-overlay::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 70%;
            border: 2px solid rgba(108,99,255,0.6);
            border-radius: 16px;
            box-shadow: 0 0 0 4000px rgba(15,23,42,0.5);
        }
        .corner {
            position: absolute;
            width: 20px;
            height: 20px;
            border-color: #6c63ff;
            border-style: solid;
        }
        .corner.tl { top: 15%; left: 15%; border-width: 3px 0 0 3px; }
        .corner.tr { top: 15%; right: 15%; border-width: 3px 3px 0 0; }
        .corner.bl { bottom: 15%; left: 15%; border-width: 0 0 3px 3px; }
        .corner.br { bottom: 15%; right: 15%; border-width: 0 3px 3px 0; }
        .controls {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 16px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        .btn-primary { background: linear-gradient(135deg, #6c63ff, #8b85ff); color: #fff; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(108,99,255,0.4); }
        .btn-secondary { background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); }
        .btn-secondary:hover { background: rgba(255,255,255,0.2); }
        .status-message {
            margin-top: 12px;
            padding: 12px 20px;
            border-radius: 16px;
            font-size: 14px;
            display: none;
        }
        .status-message.show { display: block; }
        .status-message.success { background: rgba(34,216,122,0.15); border: 1px solid rgba(34,216,122,0.3); color: #22d87a; }
        .status-message.error { background: rgba(255,77,109,0.15); border: 1px solid rgba(255,77,109,0.3); color: #ff4d6d; }
        .status-message.info { background: rgba(108,99,255,0.15); border: 1px solid rgba(108,99,255,0.3); color: #8b85ff; }
        .manual-input {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }
        .manual-input input {
            flex: 1;
            padding: 12px 16px;
            border-radius: 40px;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.05);
            color: #fff;
            font-size: 14px;
            outline: none;
        }
        .manual-input input:focus { border-color: #6c63ff; }
        .manual-input input::placeholder { color: #64748b; }
        @media (max-width: 480px) {
            .scan-container { padding: 24px 16px; }
            .manual-input { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="scan-container">
        <div class="scan-header">
            <h1><i class="bi bi-qr-code-scan"></i> Scan QR Code</h1>
            <p>Point camera at QR code or enter token manually</p>
        </div>
        
        <div class="scanner-wrapper">
            <video id="scanner" autoplay playsinline></video>
            <div class="scan-overlay">
                <div class="corner tl"></div>
                <div class="corner tr"></div>
                <div class="corner bl"></div>
                <div class="corner br"></div>
            </div>
        </div>
        
        <div id="statusMessage" class="status-message"></div>
        
        <div class="controls">
            <button class="btn btn-secondary" id="startScanner"><i class="bi bi-camera"></i> Start</button>
            <button class="btn btn-secondary" id="stopScanner"><i class="bi bi-camera-off"></i> Stop</button>
        </div>
        
        <div class="manual-input">
            <input type="text" id="manualToken" placeholder="Paste token (e.g., 8_9)">
            <button class="btn btn-primary" id="manualCheckin"><i class="bi bi-check-lg"></i> Verify</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('scanner');
        const statusMsg = document.getElementById('statusMessage');
        const manualInput = document.getElementById('manualToken');
        
        let stream = null;
        let scanning = false;
        
        document.getElementById('startScanner').addEventListener('click', startScanner);
        document.getElementById('stopScanner').addEventListener('click', stopScanner);
        document.getElementById('manualCheckin').addEventListener('click', manualCheckin);
        
        manualInput.addEventListener('keypress', e => { if (e.key === 'Enter') manualCheckin(); });
        
        async function startScanner() {
            try {
                if (stream) stream.getTracks().forEach(t => t.stop());
                
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } }
                });
                
                video.srcObject = stream;
                await video.play();
                scanning = true;
                showStatus('✅ Scanner started', 'info');
                scanFrame();
            } catch (error) {
                showStatus('❌ Camera error: ' + error.message, 'error');
            }
        }
        
        function stopScanner() {
            if (stream) {
                stream.getTracks().forEach(t => t.stop());
                stream = null;
            }
            video.srcObject = null;
            scanning = false;
            showStatus('⏹️ Scanner stopped', 'info');
        }
        
        function scanFrame() {
            if (!scanning) return;
            
            try {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });
                
                if (code) {
                    showStatus('📱 QR Code detected!', 'info');
                    window.location.href = '/checkin/' + code.data;
                    return;
                }
                
                requestAnimationFrame(scanFrame);
            } catch (error) {
                requestAnimationFrame(scanFrame);
            }
        }
        
        function manualCheckin() {
            const token = manualInput.value.trim();
            if (!token) {
                showStatus('⚠️ Please enter a token', 'error');
                return;
            }
            if (token.includes('_')) {
                window.location.href = '/checkin/' + token;
            } else {
                showStatus('⚠️ Invalid format. Use: user_id_event_id', 'error');
            }
        }
        
        function showStatus(message, type) {
            statusMsg.textContent = message;
            statusMsg.className = 'status-message show ' + type;
            setTimeout(() => { statusMsg.className = 'status-message'; }, 5000);
        }
        
        // Auto-start
        if (navigator.mediaDevices) startScanner();
    </script>
</body>
</html>