<!DOCTYPE html>
<html lang="en" dir="ltr" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>EventHub — Discover & Join Events</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎯</text></svg>">
<style>
/* ============================================
   CSS COMPLET - FULL RESPONSIVE
   ============================================ */
:root[data-theme="dark"] {
  --bg: #08080e; --bg2: #0e0e18; --bg3: #141420; --bg4: #1c1c2c; --bg5: #242436;
  --border: rgba(255,255,255,0.07); --border2: rgba(255,255,255,0.12);
  --txt: #f2f0ff; --txt2: #8a88a8; --txt3: #484860;
  --accent: #6c63ff; --accent2: #8b85ff; --accent-glow: rgba(108,99,255,0.20); --accent-dim: rgba(108,99,255,0.10);
  --green: #22d87a; --green-glow: rgba(34,216,122,0.18); --green-dim: rgba(34,216,122,0.10);
  --red: #ff4d6d; --red-dim: rgba(255,77,109,0.12); --red-glow: rgba(255,77,109,0.22);
  --gold: #f5c542; --gold-dim: rgba(245,197,66,0.13);
  --card-shadow:0 0 0 1px rgba(255,255,255,0.05), 0 8px 40px rgba(0,0,0,0.55);
  --card-hover: 0 0 0 1px rgba(108,99,255,0.5), 0 20px 60px rgba(108,99,255,0.18);
  --header-bg: rgba(8,8,14,0.80);
}
:root[data-theme="light"] {
  --bg: #f0effe; --bg2: #e8e6fc; --bg3: #ffffff; --bg4: #f5f4fe; --bg5: #eeecfd;
  --border: rgba(100,90,255,0.10); --border2: rgba(100,90,255,0.18);
  --txt: #16143a; --txt2: #5c5a82; --txt3: #a8a6c8;
  --accent: #5046e5; --accent2: #6c63ff; --accent-glow: rgba(80,70,229,0.15); --accent-dim: rgba(80,70,229,0.07);
  --green: #16a35a; --green-glow: rgba(22,163,90,0.14); --green-dim: rgba(22,163,90,0.08);
  --red: #e11d48; --red-dim: rgba(225,29,72,0.09); --red-glow: rgba(225,29,72,0.18);
  --gold: #d4a017; --gold-dim: rgba(212,160,23,0.12);
  --card-shadow:0 0 0 1px rgba(100,90,255,0.08), 0 4px 24px rgba(80,70,229,0.08);
  --card-hover: 0 0 0 1px rgba(80,70,229,0.35), 0 12px 40px rgba(80,70,229,0.14);
  --header-bg: rgba(240,239,254,0.85);
}
* { margin:0; padding:0; box-sizing:border-box; }
body {
  font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
  background: var(--bg); color: var(--txt);
  transition: background .35s, color .35s;
  overflow-x: hidden;
  font-weight: 500;
  letter-spacing: -0.01em;
}
body::before {
  content: ''; position: fixed; inset: 0; z-index: -1;
  background: radial-gradient(ellipse 80% 60% at 20% 10%, rgba(108,99,255,0.12) 0%, transparent 60%),
              radial-gradient(ellipse 60% 50% at 80% 80%, rgba(34,216,122,0.07) 0%, transparent 55%),
              var(--bg);
  pointer-events: none;
}
.wrap { max-width: 1480px; margin: 0 auto; padding: 0 32px; }

/* ============================================
   CAROUSEL / AUTO-SCROLL STYLES - SMALLER
   ============================================ */
.carousel-section {
  margin-bottom: 40px;
  background: var(--bg3);
  border-radius: 28px;
  padding: 24px 28px;
  border: 1px solid var(--border);
  box-shadow: var(--card-shadow);
}

.carousel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  flex-wrap: wrap;
  gap: 12px;
}

.carousel-header h2 {
  font-weight: 800;
  color: var(--txt);
  font-size: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.carousel-header h2 i {
  color: var(--accent);
}

.carousel-controls {
  display: flex;
  gap: 6px;
}

.carousel-btn-mini {
  padding: 6px 14px;
  border-radius: 10px;
  background: var(--bg4);
  border: 1px solid var(--border2);
  color: var(--txt2);
  cursor: pointer;
  transition: all 0.3s;
  font-size: 13px;
  display: flex;
  align-items: center;
  gap: 4px;
}

.carousel-btn-mini:hover {
  background: var(--accent);
  color: white;
  border-color: var(--accent);
  transform: scale(1.05);
}

.carousel-wrapper {
  position: relative;
  overflow: hidden;
  width: 100%;
  border-radius: 14px;
}

.carousel-track {
  display: flex;
  gap: 0;
  transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  will-change: transform;
  padding: 2px 1px;
}

.carousel-track .event-card {
  flex: 0 0 100%;
  min-width: 100%;
  cursor: pointer;
  transition: all 0.3s;
  background: var(--bg3);
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--card-shadow);
  position: relative;
  display: flex;
  flex-direction: row;
  max-height: 180px;
}

.carousel-track .event-card:hover {
  transform: translateY(-3px) scale(1.01);
  box-shadow: var(--card-hover);
  border-color: var(--accent);
}

.carousel-track .event-card .card-img-wrap {
  width: 280px;
  min-width: 280px;
  height: 180px;
  overflow: hidden;
  background: var(--bg5);
  position: relative;
  flex-shrink: 0;
}

.carousel-track .event-card .card-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .55s;
}

.carousel-track .event-card:hover .card-img-wrap img {
  transform: scale(1.06);
}

.carousel-track .event-card .card-body {
  padding: 14px 18px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: 1;
}

.carousel-track .event-card .card-title {
  font-size: 17px;
  margin-bottom: 4px;
  -webkit-line-clamp: 1;
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
}

.carousel-track .event-card .card-desc {
  font-size: 13px;
  -webkit-line-clamp: 2;
  margin-bottom: 6px;
  color: var(--txt2);
}

.carousel-track .event-card .card-meta {
  font-size: 12px;
  margin-bottom: 2px;
}

.carousel-track .event-card .card-date-chip {
  font-size: 11px;
  padding: 3px 10px;
  margin-bottom: 6px;
}

.carousel-track .event-card .card-foot {
  margin-top: 6px;
  padding-top: 6px;
  border-top: 1px solid var(--border);
}

.carousel-track .event-card .card-stats .attendees {
  font-size: 12px;
}

.carousel-track .event-card .btn-reg {
  padding: 5px 14px;
  font-size: 12px;
}

.carousel-track .event-card .price-badge {
  font-size: 11px;
  padding: 4px 12px;
  top: 10px;
  right: 10px;
}

.carousel-track .event-card .fav-badge {
  width: 30px;
  height: 30px;
  font-size: 13px;
  bottom: 10px;
  right: 10px;
}

.carousel-track .event-card .event-weather {
  font-size: 11px;
  padding: 3px 10px;
  margin-top: 4px;
}

.carousel-track .event-card .card-rating-mini {
  font-size: 11px;
}

.carousel-track .event-card .reg-badge {
  font-size: 10px;
  padding: 3px 10px;
  top: 10px;
  left: 10px;
}

.carousel-track .event-card .finished-badge {
  font-size: 10px;
  padding: 4px 10px;
  top: 10px;
  left: 10px;
}

.carousel-track .event-card .share-modal-btn {
  display: none;
}

.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: var(--bg3);
  border: 1px solid var(--border2);
  color: var(--txt2);
  cursor: pointer;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  transition: all 0.3s;
  box-shadow: 0 4px 16px rgba(0,0,0,0.25);
  backdrop-filter: blur(10px);
}

.carousel-btn:hover {
  background: var(--accent);
  color: white;
  border-color: var(--accent);
  transform: translateY(-50%) scale(1.1);
}

.carousel-btn.prev { left: 12px; }
.carousel-btn.next { right: 12px; }

.carousel-dots {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 14px;
}

.carousel-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--bg4);
  border: 2px solid var(--border2);
  cursor: pointer;
  transition: all 0.3s;
}

.carousel-dot.active {
  background: var(--accent);
  border-color: var(--accent);
  transform: scale(1.3);
}

.carousel-progress {
  width: 100%;
  height: 2px;
  background: var(--bg4);
  border-radius: 2px;
  margin-top: 12px;
  overflow: hidden;
}

.carousel-progress-bar {
  height: 100%;
  background: linear-gradient(90deg, var(--accent), var(--accent2));
  border-radius: 2px;
  width: 0%;
  transition: width 0.1s linear;
}

/* Weather Popup Style */
.event-weather {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--bg4);
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 12px;
  margin-top: 8px;
  border: 1px solid var(--border2);
  width: fit-content;
  transition: all 0.3s ease;
  cursor: pointer;
}
.event-weather:hover {
  background: var(--accent-dim);
  transform: translateY(-1px);
  border-color: var(--accent);
}
.event-weather i { font-size: 14px; }
.event-weather .weather-temp { font-weight: 700; color: var(--accent); }

/* Weather Popup Modal */
.weather-popup-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.85);
  backdrop-filter: blur(12px);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}
.weather-popup-modal.active {
  opacity: 1;
  visibility: visible;
}
.weather-popup-content {
  background: var(--bg3);
  border-radius: 28px;
  max-width: 400px;
  width: 90%;
  transform: scale(0.9);
  transition: transform 0.3s ease;
  border: 1px solid var(--border2);
  box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
.weather-popup-modal.active .weather-popup-content {
  transform: scale(1);
}
.weather-popup-header {
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  padding: 24px 28px;
  border-radius: 28px 28px 0 0;
  color: white;
  position: relative;
}
.weather-popup-header h2 {
  font-size: 20px;
  margin-bottom: 4px;
}
.weather-popup-header p {
  font-size: 13px;
  opacity: 0.9;
}
.weather-popup-close {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: rgba(255,255,255,0.2);
  border: none;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  transition: all 0.2s;
}
.weather-popup-close:hover {
  background: var(--red);
  transform: scale(1.05);
}
.weather-popup-body {
  padding: 28px;
}
.weather-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 20px;
}
.weather-card {
  background: var(--bg4);
  padding: 14px;
  border-radius: 16px;
  text-align: center;
  border: 1px solid var(--border);
}
.weather-card i {
  font-size: 22px;
  color: var(--accent);
  margin-bottom: 6px;
  display: block;
}
.weather-card .value {
  font-size: 20px;
  font-weight: 800;
  color: var(--accent);
}
.weather-card .label {
  font-size: 11px;
  color: var(--txt3);
  margin-top: 4px;
}
.weather-icon-big {
  font-size: 64px;
  text-align: center;
  margin-bottom: 12px;
}
.weather-condition {
  font-size: 18px;
  font-weight: 700;
  text-align: center;
  margin-bottom: 20px;
  color: var(--txt);
}
.weather-location, .weather-date {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: var(--txt2);
  font-size: 13px;
  margin-bottom: 8px;
}
.temp-main {
  font-size: 48px;
  font-weight: 800;
  color: var(--accent);
  text-align: center;
}
.temp-range {
  text-align: center;
  color: var(--txt2);
  font-size: 14px;
  margin-bottom: 16px;
}

/* QR Button */
.qr-btn {
  background: var(--green-dim) !important;
  border-color: var(--green) !important;
  color: var(--green) !important;
}
.qr-btn:hover {
  background: var(--green) !important;
  color: white !important;
}

.finished-badge {
  position: absolute;
  top: 14px;
  left: 14px;
  padding: 6px 14px;
  border-radius: 30px;
  background: var(--red);
  color: white;
  font-weight: 800;
  font-size: 12px;
  z-index: 3;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.event-card.finished {
  opacity: 0.75;
  filter: grayscale(0.1);
}
.event-card.finished .btn-reg,
.event-card.finished .fav-badge,
.event-card.finished .share-modal-btn {
  pointer-events: none;
  opacity: 0.5;
}
.event-card.finished .card-rating-mini {
  pointer-events: auto;
  cursor: pointer;
}
.stats-modal-btn {
  background: var(--accent-dim);
  border: 1px solid var(--accent);
  border-radius: 40px;
  padding: 6px 14px;
  color: var(--accent);
  font-weight: 600;
  cursor: pointer;
  font-size: 12px;
  transition: all 0.2s;
}
.stats-modal-btn:hover { background: var(--accent); color: white; transform: scale(1.05); }
.sidebar {
  position: fixed; top: 0; left: -340px; width: 340px; height: 100vh;
  background: var(--bg3); z-index: 1000;
  border-right: 1px solid var(--border2);
  transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex; flex-direction: column;
  box-shadow: 4px 0 40px rgba(0,0,0,0.5);
  overflow-y: auto;
}
.sidebar.open { left: 0; }
.sidebar-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,0.6);
  z-index: 999; opacity: 0; visibility: hidden;
  transition: all 0.3s;
}
.sidebar-overlay.active { opacity: 1; visibility: visible; }
.sidebar-header { padding: 28px 28px 20px; display: flex; align-items: center; justify-content: space-between; }
.sidebar-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.sidebar-logo-icon { width: 42px; height: 42px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 14px var(--accent-glow); }
.sidebar-logo-text { font-size: 24px; font-weight: 800; color: var(--txt); letter-spacing: -.02em; }
.sidebar-logo-text span { color: var(--accent); }
.sidebar-close-btn { width: 40px; height: 40px; border-radius: 12px; background: var(--bg4); border: 1px solid var(--border2); color: var(--txt2); cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.sidebar-close-btn:hover { background: var(--accent-dim); color: var(--accent); }
.sidebar-nav { flex: 1; padding: 12px 20px; display: flex; flex-direction: column; gap: 6px; }
.sidebar-nav a { display: flex; align-items: center; gap: 14px; padding: 15px 20px; border-radius: 14px; color: var(--txt2); text-decoration: none; font-weight: 600; font-size: 16px; transition: all 0.2s; }
.sidebar-nav a:hover, .sidebar-nav a.active { background: var(--accent-dim); color: var(--accent); }
.sidebar-footer { padding: 20px 28px 28px; border-top: 1px solid var(--border); }
.sidebar-user-info { display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: var(--bg4); border-radius: 14px; border: 1px solid var(--border2); cursor: pointer; transition: all 0.2s; margin-bottom: 12px; }
.sidebar-user-info:hover { border-color: var(--accent); background: var(--accent-dim); }
.sidebar-avatar { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent2)); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; font-size: 18px; flex-shrink: 0; }
.sidebar-user-name { font-weight: 700; color: var(--txt); font-size: 15px; }
.sidebar-user-email { font-size: 13px; color: var(--txt2); }
.sidebar-btn { width: 100%; padding: 14px; border-radius: 14px; background: var(--bg4); border: 1px solid var(--border2); color: var(--txt2); cursor: pointer; font-weight: 600; font-size: 15px; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
.sidebar-btn:hover { background: var(--accent-dim); color: var(--accent); border-color: var(--accent); }
.sidebar-btn.primary { background: linear-gradient(135deg, var(--accent), var(--accent2)); border: none; color: #fff; box-shadow: 0 4px 12px var(--accent-glow); }
.sidebar-btn.danger { background: transparent; border: 1px solid var(--red); color: var(--red); }
.sidebar-btn.danger:hover { background: var(--red); color: #fff; }
.floating-hamburger {
  position: fixed; top: 20px; left: 20px; z-index: 950;
  width: 48px; height: 48px; border-radius: 14px;
  background: var(--bg3); border: 1px solid var(--border2);
  color: var(--txt2); cursor: pointer; font-size: 24px;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.floating-hamburger:hover { background: var(--accent-dim); color: var(--accent); border-color: var(--accent); }
.hero-strip { padding: 72px 0 56px; text-align: center; }
.hero-eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 8px 22px; border-radius: 30px; border: 1px solid var(--border2); background: var(--bg4); font-weight: 700; color: var(--txt2); margin-bottom: 28px; letter-spacing: 0.3px; }
.hero-strip h1 { font-weight: 800; line-height: 1.08; font-size: clamp(36px, 8vw, 80px); }
.hero-strip h1 em { background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 50%, var(--green) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-style: normal; }
.hero-sub { color: var(--txt2); max-width: 700px; margin: 0 auto; line-height: 1.7; font-size: 18px; }
.stats-bar { display: flex; flex-wrap: wrap; border-radius: 24px; overflow: hidden; border: 1px solid var(--border2); background: var(--bg3); box-shadow: var(--card-shadow); margin-top: 48px; justify-content: center; }
.stat-item { padding: 32px 50px; text-align: center; flex: 1; min-width: 120px; }
.stat-item:not(:last-child) { border-right: 1px solid var(--border); }
.stat-num { font-weight: 900; color: var(--accent); line-height: 1; font-size: clamp(40px, 6vw, 80px); }
.stat-lbl { font-weight: 700; color: var(--txt3); text-transform: uppercase; margin-top: 14px; letter-spacing: 2px; font-size: 14px; }
.filter-panel { background: var(--bg3); border: 1px solid var(--border); border-radius: 24px; padding: 24px 28px; margin-bottom: 36px; box-shadow: var(--card-shadow); }
.filter-row { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
.search-wrap { position: relative; flex: 1; min-width: 240px; }
.search-wrap i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--txt3); font-size: 18px; }
.search-wrap input { width: 100%; padding: 14px 18px 14px 48px; background: var(--bg4); border: 1px solid var(--border2); border-radius: 14px; color: var(--txt); font-size: 15px; }
.search-wrap input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-dim); background: var(--bg3); }
.fselect { padding: 14px 18px; background: var(--bg4); border: 1px solid var(--border2); border-radius: 14px; color: var(--txt); min-width: 160px; font-size: 14px; cursor: pointer; }
.pill-row { display: flex; gap: 10px; flex-wrap: wrap; }
.pill { padding: 8px 20px; border-radius: 30px; border: 1px solid var(--border2); background: var(--bg4); color: var(--txt2); font-weight: 600; cursor: pointer; transition: all .2s; font-size: 14px; white-space: nowrap; }
.pill.active { background: var(--accent); border-color: var(--accent); color: #fff; box-shadow: 0 4px 12px var(--accent-glow); }
.guest-banner { background: linear-gradient(135deg, var(--accent-dim), var(--bg4)); border: 1px solid var(--accent); border-radius: 24px; padding: 28px 32px; margin-bottom: 36px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
.guest-banner p { margin: 0; color: var(--txt2); font-size: 15px; }
.guest-banner strong { color: var(--accent); }
.guest-banner .btn { background: linear-gradient(135deg, var(--accent), var(--accent2)); border: none; padding: 12px 28px; border-radius: 30px; color: white; font-weight: 600; cursor: pointer; transition: all .2s; font-size: 14px; }
.guest-banner .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px var(--accent-glow); }
.user-dashboard { background: var(--bg3); border: 1px solid var(--border); border-radius: 28px; padding: 32px 36px; margin-bottom: 36px; box-shadow: var(--card-shadow); }
.dashboard-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; margin-bottom: 24px; }
.dashboard-title { font-size: 24px; font-weight: 800; color: var(--txt); letter-spacing: -.01em; }
.dashboard-title i { color: var(--accent); margin-right: 10px; }
.dashboard-stats { background: var(--bg4); padding: 10px 24px; border-radius: 40px; border: 1px solid var(--border2); font-weight: 600; color: var(--accent); font-size: 14px; }
.dashboard-events { display: flex; flex-wrap: wrap; gap: 16px; }
.dash-event-card { background: var(--bg4); border: 1px solid var(--border2); border-radius: 18px; padding: 16px 20px; width: calc(33% - 16px); min-width: 200px; transition: all .2s; }
.dash-event-card:hover { border-color: var(--accent); transform: translateY(-2px); }
.dash-event-title { font-weight: 700; color: var(--txt); margin-bottom: 8px; font-size: 18px; }
.dash-event-date { color: var(--txt2); display: flex; align-items: center; gap: 6px; font-size: 13px; }
.empty-dash { text-align: center; padding: 56px; color: var(--txt2); background: var(--bg4); border-radius: 20px; }
.empty-dash i { font-size: 40px; margin-bottom: 16px; display: block; }
.events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 28px; margin-bottom: 64px; }
.event-card { background: var(--bg3); border: 1px solid var(--border); border-radius: 22px; overflow: hidden; box-shadow: var(--card-shadow); transition: all .3s; cursor: pointer; position: relative; }
.event-card:not(.finished):hover { transform: translateY(-6px); box-shadow: var(--card-hover); border-color: var(--accent); }
.card-img-wrap { position: relative; height: 180px; overflow: hidden; background: var(--bg5); }
.card-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .55s; }
.event-card:not(.finished):hover .card-img-wrap img { transform: scale(1.06); }
.price-badge { position: absolute; top: 14px; right: 14px; padding: 6px 14px; border-radius: 30px; font-weight: 800; backdrop-filter: blur(10px); z-index: 3; font-size: 13px; }
.price-badge.free { background: var(--green-dim); border: 1px solid var(--green); color: var(--green); }
.price-badge.paid { background: var(--accent-dim); border: 1px solid var(--accent); color: var(--accent); }
.reg-badge { position: absolute; top: 14px; left: 14px; padding: 5px 12px; border-radius: 30px; background: var(--green); color: #fff; font-weight: 800; display: flex; align-items: center; gap: 6px; z-index: 3; font-size: 11px; }
.fav-badge { 
  position: absolute; bottom: 14px; right: 14px; 
  width: 36px; height: 36px; border-radius: 50%; 
  background: rgba(0,0,0,0.65); backdrop-filter: blur(8px); 
  display: flex; align-items: center; justify-content: center; 
  cursor: pointer; z-index: 3; transition: all 0.25s ease; 
  color: #fff; border: 1px solid rgba(255,255,255,0.2);
  font-size: 16px;
}
.fav-badge:hover { transform: scale(1.12); background: var(--accent); border-color: var(--accent); color: #fff; }
.fav-badge.active { background: var(--red); border-color: var(--red); color: #fff; box-shadow: 0 0 12px rgba(255,77,109,0.5); }
.fav-badge.fav-disabled { opacity: 0.4; cursor: not-allowed; filter: grayscale(1); }
.card-body { padding: 18px 20px 22px; display: flex; flex-direction: column; flex: 1; }
.card-date-chip { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 20px; background: var(--bg4); border: 1px solid var(--border2); font-weight: 700; color: var(--txt2); width: fit-content; margin-bottom: 12px; font-size: 12px; }
.card-title { font-weight: 800; color: var(--txt); margin-bottom: 8px; line-height: 1.25; font-size: 18px; }
.card-desc { color: var(--txt2); line-height: 1.5; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 14px; }
.card-meta { display: flex; align-items: center; gap: 6px; color: var(--txt2); margin-bottom: 5px; font-size: 13px; }
.card-meta i { color: var(--accent); width: 16px; font-size: 13px; }
.card-foot { display: flex; align-items: center; justify-content: space-between; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); gap: 12px; flex-wrap: wrap; }
.card-stats { display: flex; flex-direction: column; gap: 5px; }
.attendees { display: flex; align-items: center; gap: 6px; color: var(--txt2); font-size: 13px; font-weight: 600; }
.card-rating-mini { display: flex; align-items: center; gap: 4px; color: var(--gold); font-size: 12px; cursor: pointer; }
.card-rating-mini.disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none; }
.card-btns-row { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
.btn-reg { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 12px; border: none; background: linear-gradient(135deg, var(--green) 0%, #16a35a 100%); color: #fff; font-weight: 700; cursor: pointer; transition: all .25s; font-size: 13px; }
.btn-reg.done { background: var(--bg5); color: var(--txt3); border: 1px solid var(--border2); cursor: default; box-shadow: none; }
.btn-cancel-reg { display: none; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 12px; border: 1px solid var(--red); background: var(--red-dim); color: var(--red); font-weight: 700; cursor: pointer; transition: all .25s; font-size: 13px; }
.btn-cancel-reg:hover { background: var(--red); color: #fff; }
.btn-cancel-reg.visible { display: inline-flex; }
.empty-state { grid-column: 1/-1; text-align: center; padding: 80px 40px; background: var(--bg3); border: 1px dashed var(--border2); border-radius: 28px; }
.section-block { background: var(--bg3); border: 1px solid var(--border); border-radius: 28px; padding: 56px 60px; margin-bottom: 36px; box-shadow: var(--card-shadow); }
.sec-tag { display: inline-flex; align-items: center; gap: 8px; font-weight: 800; color: var(--accent); margin-bottom: 16px; letter-spacing: 0.5px; font-size: 14px; }
.sec-title { font-size: clamp(28px, 4vw, 44px); font-weight: 800; color: var(--txt); margin-bottom: 12px; }
.sec-title span { color: var(--accent); }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
.about-grid p { line-height: 1.6; font-size: 15px; }
.feat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 28px; }
.feat-card { display: flex; gap: 14px; padding: 18px; background: var(--bg4); border: 1px solid var(--border); border-radius: 16px; }
.feat-card h4 { font-size: 16px; margin-bottom: 4px; }
.feat-card p { font-size: 13px; color: var(--txt2); }
.about-img-wrap img { width: 100%; border-radius: 24px; }
.contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
.ci-list { display: flex; flex-direction: column; gap: 12px; margin-top: 24px; }
.ci { display: flex; align-items: center; gap: 16px; padding: 18px 22px; background: var(--bg4); border: 1px solid var(--border); border-radius: 16px; }
.ci h5 { font-size: 14px; margin-bottom: 4px; color: var(--txt3); }
.ci p { font-size: 14px; color: var(--txt); }
.contact-form-card { background: var(--bg4); border: 1px solid var(--border); border-radius: 20px; padding: 32px; position: relative; }
.contact-guest-overlay { display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.85); border-radius: 20px; z-index: 10; flex-direction: column; align-items: center; justify-content: center; gap: 16px; text-align: center; color: white; backdrop-filter: blur(8px); }
.contact-guest-overlay.active { display: flex; }
.contact-guest-overlay p { font-size: 14px; margin-bottom: 8px; }
.contact-guest-overlay button { padding: 10px 24px; border-radius: 30px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border: none; color: white; font-weight: 600; cursor: pointer; font-size: 13px; }
.contact-form-card h4 { font-size: 18px; margin-bottom: 20px; }
.contact-form-card input, .contact-form-card textarea { width: 100%; padding: 14px 16px; background: var(--bg3); border: 1px solid var(--border2); border-radius: 12px; color: var(--txt); margin-bottom: 14px; font-size: 14px; }
.contact-form-card input:disabled, .contact-form-card textarea:disabled, .contact-form-card button:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-send { width: 100%; padding: 14px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border: none; border-radius: 14px; color: #fff; font-weight: 700; cursor: pointer; font-size: 14px; }
.footer-block { background: var(--bg3); border: 1px solid var(--border); border-radius: 28px; padding: 48px 56px 36px; margin-bottom: 24px; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; }
.footer-grid h3 { font-size: 20px; margin-bottom: 12px; }
.footer-grid h5 { font-size: 14px; margin-bottom: 12px; color: var(--accent); }
.footer-grid p { font-size: 13px; color: var(--txt2); line-height: 1.5; }
.footer-grid ul { list-style: none; }
.footer-grid ul li { margin-bottom: 8px; }
.footer-grid ul li a { color: var(--txt2); text-decoration: none; font-size: 13px; transition: color 0.2s; }
.footer-grid ul li a:hover { color: var(--accent); }
.social-row a { display: inline-flex; width: 36px; height: 36px; background: var(--bg4); border-radius: 10px; align-items: center; justify-content: center; color: var(--txt2); margin-right: 8px; text-decoration: none; font-size: 16px; transition: all 0.2s; }
.social-row a:hover { background: var(--accent); color: #fff; }
.footer-bottom { border-top: 1px solid var(--border); padding-top: 24px; margin-top: 24px; display: flex; justify-content: space-between; color: var(--txt3); font-size: 13px; flex-wrap: wrap; gap: 10px; }
.modal-overlay { position: fixed; inset: 0; z-index: 500; background: rgba(0,0,0,0.72); backdrop-filter: blur(12px); display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: all .3s; }
.modal-overlay.active { opacity: 1; visibility: visible; }
.modal-box { width: 100%; max-width: 560px; background: var(--bg3); border: 1px solid var(--border2); border-radius: 28px; transform: scale(.93); transition: transform .38s; max-height: 90vh; overflow-y: auto; }
.modal-overlay.active .modal-box { transform: scale(1); }
.modal-head { padding: 28px 32px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: 28px 28px 0 0; position: relative; color: white; }
.modal-head h2 { font-size: 22px; }
.modal-head p { font-size: 14px; opacity: 0.9; }
.modal-x { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; color: white; font-size: 16px; }
.modal-body { padding: 32px; }
.mf { margin-bottom: 20px; }
.mf label { display: block; font-weight: 800; color: var(--txt2); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-size: 12px; }
.mf input, .mf select, .mf textarea { width: 100%; padding: 14px 16px; background: var(--bg4); border: 1px solid var(--border2); border-radius: 14px; color: var(--txt); font-family: inherit; font-size: 14px; }
.mf input:focus, .mf select:focus, .mf textarea:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-dim); background: var(--bg3); }
.modal-btns { display: flex; gap: 12px; margin-top: 24px; }
.btn-cancel { flex: 1; padding: 14px; background: var(--bg4); border: 1px solid var(--border2); border-radius: 14px; color: var(--txt2); cursor: pointer; font-weight: 600; font-size: 14px; }
.btn-submit { flex: 2; padding: 14px; background: linear-gradient(135deg, var(--green), #16a35a); border: none; border-radius: 14px; color: #fff; font-weight: 700; cursor: pointer; font-size: 14px; }
.summary-card { background: var(--green-dim); border: 1px solid var(--green); border-radius: 16px; padding: 20px; margin: 20px 0; }
.summary-card h4 { font-size: 16px; margin-bottom: 12px; }
.sr { display: flex; justify-content: space-between; color: var(--txt2); margin-bottom: 8px; font-size: 13px; }
.toast-wrap { position: fixed; bottom: 24px; right: 24px; z-index: 600; display: flex; flex-direction: column; gap: 10px; }
.toast { display: flex; align-items: center; gap: 10px; padding: 14px 22px; background: var(--bg3); border: 1px solid var(--border2); border-radius: 14px; animation: fadein .3s; font-size: 14px; }
@keyframes fadein { from { opacity:0; transform: translateX(60px); } to { opacity:1; transform: translateX(0); } }
.cancel-modal-head { padding: 28px 32px; background: linear-gradient(135deg, var(--red), #c0143c); border-radius: 28px 28px 0 0; position: relative; color: white; }
.btn-confirm-cancel { flex: 2; padding: 14px; background: linear-gradient(135deg, var(--red), #c0143c); border: none; border-radius: 14px; color: #fff; font-weight: 700; cursor: pointer; font-size: 14px; }
.detail-overlay { position: fixed; inset: 0; z-index: 400; background: rgba(0,0,0,0.82); backdrop-filter: blur(16px); display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: all .3s; padding: 20px; }
.detail-overlay.active { opacity: 1; visibility: visible; }
.detail-box { width: 100%; max-width: 1000px; max-height: 90vh; overflow-y: auto; background: var(--bg3); border: 1px solid var(--border2); border-radius: 28px; transform: scale(.93) translateY(20px); transition: transform .4s; position: relative; }
.detail-overlay.active .detail-box { transform: scale(1) translateY(0); }
.detail-close { position: absolute; top: 16px; right: 16px; width: 40px; height: 40px; border-radius: 50%; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.18); color: #fff; cursor: pointer; z-index: 10; font-size: 18px; display: flex; align-items: center; justify-content: center; transition: all .2s; }
.detail-close:hover { background: var(--red); border-color: var(--red); transform: scale(1.08); }
.dg-hero { height: 380px; background: var(--bg5); border-radius: 28px 28px 0 0; position: relative; overflow: hidden; }
.dg-main-img { width: 100%; height: 100%; object-fit: cover; transition: opacity .25s; }
.dg-main-img.fade { opacity: 0; }
.dg-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; background: rgba(0,0,0,0.55); backdrop-filter: blur(6px); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; border: 1px solid rgba(255,255,255,0.15); transition: all .2s; font-size: 18px; }
.dg-arrow:hover { background: var(--accent); border-color: var(--accent); }
.dg-arrow.prev { left: 16px; }
.dg-arrow.next { right: 16px; }
.dg-counter { position: absolute; bottom: 16px; right: 16px; background: rgba(0,0,0,0.6); backdrop-filter: blur(6px); padding: 6px 16px; border-radius: 20px; color: white; font-weight: 600; font-size: 12px; }
.dg-price-badge { position: absolute; top: 16px; left: 16px; padding: 8px 20px; border-radius: 30px; backdrop-filter: blur(10px); font-weight: 800; font-size: 13px; }
.dg-price-badge.free { background: var(--green-dim); border: 1px solid var(--green); color: var(--green); }
.dg-price-badge.paid { background: var(--accent-dim); border: 1px solid var(--accent); color: var(--accent); }
.dg-thumbs { display: flex; gap: 10px; padding: 16px 24px 0; overflow-x: auto; scrollbar-width: none; }
.dg-thumbs::-webkit-scrollbar { display: none; }
.dg-thumb { width: 80px; height: 60px; flex-shrink: 0; border-radius: 12px; object-fit: cover; border: 2px solid transparent; cursor: pointer; opacity: .6; transition: all .2s; }
.dg-thumb.active { opacity: 1; border-color: var(--accent); }
.dg-thumb:hover { opacity: 1; }
.dg-content { padding: 32px 36px 40px; }
.dg-header-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
.dg-title { font-weight: 800; color: var(--txt); line-height: 1.2; flex: 1; font-size: 22px; }
.dg-reg-badge-big { display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 24px; background: var(--green-dim); border: 1px solid var(--green); color: var(--green); font-weight: 800; white-space: nowrap; flex-shrink: 0; font-size: 12px; }
.dg-chips { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
.dg-chip { display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; background: var(--bg4); border: 1px solid var(--border2); border-radius: 24px; color: var(--txt2); font-size: 13px; }
.dg-chip i { color: var(--accent); font-size: 14px; }
.dg-desc { color: var(--txt2); line-height: 1.7; margin-bottom: 28px; font-size: 15px; }
.dg-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 32px; }
.dg-info-card { display: flex; align-items: center; gap: 16px; padding: 18px 22px; background: var(--bg4); border: 1px solid var(--border); border-radius: 18px; transition: border-color .2s; }
.dg-info-card:hover { border-color: var(--accent); }
.dg-info-ico { width: 50px; height: 50px; flex-shrink: 0; background: var(--accent-dim); border-radius: 14px; display: flex; align-items: center; justify-content: center; }
.dg-info-ico i { color: var(--accent); font-size: 22px; }
.dg-info-card h5 { font-weight: 800; text-transform: uppercase; color: var(--txt3); margin-bottom: 4px; letter-spacing: .05em; font-size: 11px; }
.dg-info-card p { font-weight: 600; color: var(--txt); font-size: 15px; }
.dg-divider { height: 1px; background: var(--border); margin: 0 0 28px; }
.dg-actions { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; }
.dg-attendees { display: flex; align-items: center; gap: 10px; color: var(--txt2); font-size: 14px; }
.dg-attendees i { color: var(--accent); font-size: 18px; }
.dg-attendees strong { color: var(--txt); }
.dg-buttons-horizontal { display: flex; flex-direction: row; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
.btn-dg-close, .btn-dg-fav, .btn-dg-calendar, .btn-dg-cancel { flex: 0 0 auto; padding: 10px 20px; font-size: 13px; white-space: nowrap; border-radius: 14px; font-weight: 600; cursor: pointer; }
.btn-dg-close { background: var(--bg4); border: 1px solid var(--border2); color: var(--txt2); }
.btn-dg-fav { background: var(--bg4); border: 1px solid var(--border2); color: var(--txt2); }
.btn-dg-calendar { background: var(--accent-dim); border: 1px solid var(--accent); color: var(--accent); }
.btn-dg-cancel { background: var(--red-dim); border: 1px solid var(--red); color: var(--red); }
.btn-dg-fav.fav-disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; filter: grayscale(1); }
.btn-dg-calendar:disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none; }
.review-item { padding: 14px 0; border-bottom: 1px solid var(--border); }
.review-header { display: flex; justify-content: space-between; margin-bottom: 6px; flex-wrap: wrap; gap: 8px; }
.review-author { font-weight: 600; color: var(--accent); font-size: 13px; }
.review-date { font-size: 11px; color: var(--txt3); }
.review-rating { color: var(--gold); margin-bottom: 6px; font-size: 12px; letter-spacing: 2px; }
.review-comment { color: var(--txt2); font-size: 13px; line-height: 1.5; }
.star-rating { font-size: 24px; cursor: pointer; color: var(--txt3); transition: all 0.2s; margin-right: 4px; display: inline-block; }
.star-rating:hover, .star-rating.active { color: var(--gold); }
.profile-modal-box { width: 100%; max-width: 600px; background: var(--bg3); border: 1px solid var(--border2); border-radius: 28px; transform: scale(.93); transition: transform .38s; position: relative; max-height: 90vh; overflow-y: auto; }
.modal-overlay.active .profile-modal-box { transform: scale(1); }
.profile-modal-head { padding: 24px 28px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: 28px 28px 0 0; color: white; position: relative; }
.profile-modal-body { padding: 24px 28px; }
.info-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 12px; }
.info-row-left { display: flex; align-items: center; gap: 14px; }
.info-row-ico { width: 40px; height: 40px; background: var(--bg4); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--accent); }
.info-row-label { font-size: 11px; text-transform: uppercase; color: var(--txt3); margin-bottom: 4px; }
.info-row-value { font-weight: 600; color: var(--txt); font-size: 14px; }
.info-row-edit { background: transparent; border: 1px solid var(--border2); padding: 5px 14px; border-radius: 20px; color: var(--txt2); cursor: pointer; font-size: 11px; font-weight: 600; transition: all 0.2s; }
.info-row-edit:hover { border-color: var(--accent); color: var(--accent); }
.edit-form { margin: 14px 0; padding: 14px; background: var(--bg4); border-radius: 16px; }
.edit-form-btns { display: flex; gap: 10px; justify-content: flex-end; margin-top: 14px; }
.btn-ef-cancel { padding: 8px 16px; background: var(--bg3); border: 1px solid var(--border2); border-radius: 10px; color: var(--txt2); cursor: pointer; font-size: 12px; }
.btn-ef-save { padding: 8px 20px; background: linear-gradient(135deg, var(--green), #16a35a); border: none; border-radius: 10px; color: white; cursor: pointer; font-weight: 600; font-size: 12px; }

@media (max-width: 768px) {
  .carousel-section {
    padding: 12px 10px;
    border-radius: 16px;
  }
  .carousel-header h2 {
    font-size: 16px;
  }
  .carousel-btn-mini {
    padding: 4px 10px;
    font-size: 11px;
  }
  .carousel-track .event-card {
    flex-direction: column;
    max-height: none;
  }
  .carousel-track .event-card .card-img-wrap {
    width: 100%;
    min-width: auto;
    height: 140px;
  }
  .carousel-track .event-card .card-body {
    padding: 12px 14px;
  }
  .carousel-track .event-card .card-title {
    font-size: 15px;
  }
  .carousel-track .event-card .card-desc {
    font-size: 12px;
    -webkit-line-clamp: 1;
  }
  .carousel-btn {
    width: 30px;
    height: 30px;
    font-size: 13px;
  }
  .carousel-btn.prev { left: 6px; }
  .carousel-btn.next { right: 6px; }
  .carousel-dot {
    width: 8px;
    height: 8px;
  }
  .wrap { padding: 0 16px; }
  .hero-strip { padding: 48px 0 32px; }
  .stats-bar { flex-direction: column; width: 100%; }
  .stat-item { border-right: none; border-bottom: 1px solid var(--border); padding: 24px; }
  .stat-item:last-child { border-bottom: none; }
  .events-grid { grid-template-columns: 1fr; gap: 20px; }
  .section-block { padding: 28px 20px; }
  .about-grid, .contact-grid, .footer-grid { grid-template-columns: 1fr; gap: 32px; }
  .dg-hero { height: 240px; }
  .dg-info-grid { grid-template-columns: 1fr; }
  .dg-content { padding: 20px; }
  .dg-buttons-horizontal { flex-direction: column; width: 100%; }
  .dg-buttons-horizontal button { width: 100%; justify-content: center; }
  .dg-actions { flex-direction: column; align-items: stretch; }
  .floating-hamburger { top: 12px; left: 12px; width: 42px; height: 42px; }
  .filter-panel { padding: 20px; }
  .pill { padding: 6px 16px; font-size: 12px; }
  .user-dashboard { padding: 24px; }
  .dash-event-card { width: 100%; }
}
@media (max-width: 480px) {
  .stat-num { font-size: 36px; }
  .stat-lbl { font-size: 11px; }
  .hero-strip h1 { font-size: 28px; }
  .hero-sub { font-size: 15px; }
  .modal-body { padding: 20px; }
  .modal-head { padding: 20px 24px; }
  .dg-info-card { padding: 12px 16px; }
  .dg-info-ico { width: 40px; height: 40px; }
  .dg-info-ico i { font-size: 18px; }
  .card-title { font-size: 16px; }
  .carousel-track .event-card .card-img-wrap {
    height: 120px;
  }
  .carousel-track .event-card .card-title {
    font-size: 14px;
  }
  .carousel-track .event-card .card-body {
    padding: 10px 12px;
  }
  .carousel-track .event-card .card-desc {
    display: none;
  }
}
</style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-logo"><div class="sidebar-logo-icon">🎯</div><span class="sidebar-logo-text">Event<span>Hub</span></span></a>
    <button class="sidebar-close-btn" onclick="closeSidebar()"><i class="bi bi-x-lg"></i></button>
  </div>
  <nav class="sidebar-nav">
    <a href="#events-section" class="active" onclick="navigateTo('events-section')"><i class="bi bi-calendar-event"></i> Events</a>
    <a href="#favorites-section" onclick="navigateTo('favorites-section')"><i class="bi bi-heart-fill"></i> Favorites</a>
    <a href="#recommendations-section" onclick="navigateTo('recommendations-section')"><i class="bi bi-stars"></i> For You</a>
    <a href="#about-section" onclick="navigateTo('about-section')"><i class="bi bi-info-circle"></i> About</a>
    <a href="#contact-section" onclick="navigateTo('contact-section')"><i class="bi bi-envelope"></i> Contact</a>
    <div style="height:1px;background:var(--border);margin:8px 0;"></div>
    <a href="#" id="sidebarProfileLink" style="display:none;" onclick="openProfileModal(); closeSidebar();"><i class="bi bi-person-circle"></i> My Profile</a>
    <a href="#" id="sidebarMyEventsLink" style="display:none;" onclick="setTab('registered'); closeSidebar();"><i class="bi bi-calendar-check"></i> My Events</a>
    <a href="#" id="sidebarCalendarLink" style="display:none;" onclick="closeSidebar(); toast('📅', 'Check your Google Calendar!');"><i class="bi bi-google"></i> Google Calendar</a>
    <a href="#" id="sidebarNotificationsLink" style="display:none;" onclick="toggleNotificationsSidebar(); event.preventDefault();"><i class="bi bi-bell-fill"></i> Notifications<span id="notifCountBadge" style="background:#ef4444; color:white; border-radius:50%; padding:2px 6px; font-size:10px; margin-left:auto; display:none;">0</span></a>
    <div id="notificationsDropdown" style="display:none; background:var(--bg4); border-radius:12px; margin:8px 12px; border:1px solid var(--border2); overflow:hidden;"><div style="padding:10px 12px; border-bottom:1px solid var(--border2); font-weight:700; color:var(--accent); font-size:13px;"><i class="bi bi-bell-fill"></i> Notifications<button onclick="markAllNotificationsAsRead()" style="float:right; background:none; border:none; color:var(--txt2); font-size:10px; cursor:pointer;">Mark all read</button></div><div id="notificationsListContainer" style="max-height:300px; overflow-y:auto;"><div style="color:var(--txt2); padding:12px;">Loading...</div></div></div>
  </nav>
  <div class="sidebar-footer">
    <div id="sidebarUserInfo" class="sidebar-user-info" onclick="openProfileModal()" style="display:none;">
      <div class="sidebar-avatar" id="sidebarAvatar">?</div><div><div class="sidebar-user-name" id="sidebarUserName">—</div><div class="sidebar-user-email" id="sidebarUserEmail">—</div></div>
    </div>
    <button id="sidebarGetStartedBtn" class="sidebar-btn primary" onclick="openLoginModal(); closeSidebar();"><i class="bi bi-rocket-takeoff"></i> Get Started</button>
    <button id="sidebarLogoutBtn" class="sidebar-btn danger" style="display:none; margin-top: 8px;" onclick="logout(); closeSidebar();"><i class="bi bi-box-arrow-right"></i> Logout</button>
    <button class="sidebar-btn" style="margin-top: 8px;" onclick="toggleTheme()"><i class="bi bi-moon-stars" id="sidebarThemeIcon"></i><span id="sidebarThemeLabel">Dark Mode</span></button>
  </div>
</aside>

<button class="floating-hamburger" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

<div class="wrap">
  <div class="hero-strip">
    <div class="hero-eyebrow"><i class="bi bi-stars"></i> Discover · Register · Experience</div>
    <h1>Your Next<br><em>Unforgettable</em> Event Awaits</h1>
    <p class="hero-sub">Browse curated events — from tech summits to community gatherings. One click to register, show up and make memories.</p>
    <div style="display:flex;justify-content:center;">
      <div class="stats-bar">
        <div class="stat-item"><div class="stat-num" id="totalEventsCount">{{ count($events) }}</div><div class="stat-lbl">Total Events</div></div>
        <div class="stat-item"><div class="stat-num" id="freeEventsCount">{{ $events->where('price', 0)->count() }}</div><div class="stat-lbl">Free Events</div></div>
        <div class="stat-item"><div class="stat-num" id="paidEventsCount">{{ $events->where('price', '>', 0)->count() }}</div><div class="stat-lbl">Paid Events</div></div>
        <div class="stat-item"><div class="stat-num" id="myRegCount">0</div><div class="stat-lbl">My Registered</div></div>
        <div class="stat-item"><div class="stat-num" id="favCount">0</div><div class="stat-lbl">Favorites</div></div>
      </div>
    </div>
  </div>

  <div id="guestBanner" class="guest-banner" style="display: none;"><p><i class="bi bi-star-fill"></i> <strong>Join EventHub today!</strong> <span>Log in or create an account to register for events and see your personal dashboard.</span></p><button class="btn" onclick="openLoginModal()">Get Started <i class="bi bi-arrow-right"></i></button></div>

  <!-- ============================================
       CAROUSEL / AUTO-SCROLL SECTION - SMALLER
       ============================================ -->
  <div class="carousel-section" id="carouselSection">
    <div class="carousel-header">
      <h2><i class="bi bi-stars"></i> Featured Events</h2>
      <div class="carousel-controls">
        <button class="carousel-btn-mini" onclick="prevSlide()">
          <i class="bi bi-chevron-left"></i> Prev
        </button>
        <button class="carousel-btn-mini" onclick="nextSlide()">
          Next <i class="bi bi-chevron-right"></i>
        </button>
      </div>
    </div>
    <div class="carousel-wrapper">
      <div class="carousel-track" id="carouselTrack">
        <!-- Events will be inserted here by JavaScript -->
      </div>
      <button class="carousel-btn prev" onclick="prevSlide()"><i class="bi bi-chevron-left"></i></button>
      <button class="carousel-btn next" onclick="nextSlide()"><i class="bi bi-chevron-right"></i></button>
    </div>
    <div class="carousel-dots" id="carouselDots"></div>
    <div class="carousel-progress">
      <div class="carousel-progress-bar" id="carouselProgressBar"></div>
    </div>
  </div>

  <!-- FILTER PANEL -->
  <div class="filter-panel" id="events-section">
    <div class="filter-row">
      <div class="search-wrap"><i class="bi bi-search"></i><input type="text" id="searchInput" placeholder="Search events by title or location…"></div>
      <select id="filterMonth" class="fselect">
        <option value="">All Months</option>
        <option value="01">January</option><option value="02">February</option><option value="03">March</option>
        <option value="04">April</option><option value="05">May</option><option value="06">June</option>
        <option value="07">July</option><option value="08">August</option><option value="09">September</option>
        <option value="10">October</option><option value="11">November</option><option value="12">December</option>
      </select>
      <select id="sortBy" class="fselect">
        <option value="date-asc">Date (Earliest)</option><option value="date-desc">Date (Latest)</option>
        <option value="price-asc">Price (Low–High)</option><option value="price-desc">Price (High–Low)</option>
        <option value="title">Title (A–Z)</option>
      </select>
    </div>
    <div class="pill-row">
      <button class="pill active" data-tab="all">All Events</button>
      <button class="pill" data-tab="free">🎟 Free Events</button>
      <button class="pill" data-tab="paid">💳 Paid Events</button>
      <button class="pill" data-tab="registered" id="registeredTab" style="display:none;">✅ My Registered</button>
      <button class="pill" data-tab="favorites" id="favoritesTab" style="display:none;">❤️ Favorites</button>
    </div>
  </div>

  <!-- RECOMMENDATIONS -->
  <div class="section-block" id="recommendations-section">
    <div class="sec-tag"><i class="bi bi-stars"></i> AI-Powered Recommendations</div>
    <h2 class="sec-title">Events You Might <span>Love</span></h2>
    <div id="recommendationsGrid" class="events-grid"></div>
  </div>

  <!-- USER DASHBOARD -->
  <div id="userDashboard" class="user-dashboard" style="display: none;">
    <div class="dashboard-header"><div class="dashboard-title"><i class="bi bi-person-square"></i> <span id="dashboardWelcome"></span></div><div class="dashboard-stats"><i class="bi bi-calendar-check"></i> <span id="dashboardCount">0</span> event(s) registered</div></div>
    <div id="dashboardEventsList" class="dashboard-events"></div>
  </div>

  <!-- MAIN EVENTS GRID -->
  <div class="events-grid" id="eventsGrid">
    @forelse($events as $event)
    @php
      $eventDate = \Carbon\Carbon::parse($event->event_date);
      $isFinished = $eventDate->isPast();
    @endphp
    <div class="event-card {{ $isFinished ? 'finished' : '' }}" data-id="{{ $event->id }}" data-title="{{ strtolower($event->title) }}" data-full-title="{{ $event->title }}" data-description="{{ $event->description }}" data-location="{{ $event->location }}" data-date="{{ $event->event_date }}" data-start-time="{{ $event->start_time }}" data-end-time="{{ $event->end_time }}" data-price="{{ $event->price }}" data-attendees="{{ $event->users_count ?? 0 }}" data-tab-free="{{ $event->price == 0 ? 'true' : 'false' }}" data-tab-paid="{{ $event->price > 0 ? 'true' : 'false' }}" data-images='@json($event->images->map(fn($img) => asset("storage/".$img->image_path)))' data-category="{{ $event->category ?? 'general' }}" data-lat="{{ $event->latitude ?? 37.7749 }}" data-lng="{{ $event->longitude ?? -122.4194 }}" onclick="openDetail(this)">
      <div class="card-img-wrap">
        @if($event->images->count()) <img src="{{ asset('storage/' . $event->images->first()->image_path) }}" alt="{{ $event->title }}"> @else <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800" alt="{{ $event->title }}"> @endif
        <div class="price-badge price-badge-tag {{ $event->price > 0 ? 'paid' : 'free' }}">{{ $event->price > 0 ? '$'.number_format($event->price, 2) : 'FREE' }}</div>
        <div class="reg-badge" style="display:none;" id="reg-badge-{{ $event->id }}"><i class="bi bi-check-circle-fill"></i> Registered</div>
        @if($isFinished)
          <div class="finished-badge"><i class="bi bi-check2-circle"></i> Finished</div>
        @endif
        <div class="share-modal-btn" onclick="event.stopPropagation(); shareEvent({{ $event->id }}, '{{ addslashes($event->title) }}')"><i class="bi bi-share-fill"></i></div>
        <div class="fav-badge fav-disabled" id="fav-badge-{{ $event->id }}" onclick="event.stopPropagation(); toggleFavorite({{ $event->id }})"><i class="bi bi-heart" id="fav-icon-{{ $event->id }}"></i></div>
      </div>
      <div class="card-body">
        <div class="card-date-chip"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
        <h3 class="card-title">{{ $event->title }}</h3>
        <p class="card-desc">{{ $event->description }}</p>
        <div class="card-meta"><i class="bi bi-clock"></i>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</div>
        <div class="card-meta"><i class="bi bi-geo-alt-fill"></i>{{ $event->location }}</div>
        
        <!-- WEATHER BUTTON -->
        <div class="event-weather" id="weather-{{ $event->id }}" data-event-id="{{ $event->id }}" data-event-date="{{ $event->event_date }}" data-event-title="{{ addslashes($event->title) }}" data-location="{{ $event->location }}" data-lat="{{ $event->latitude ?? 37.7749 }}" data-lng="{{ $event->longitude ?? -122.4194 }}">
          <i class="bi bi-cloud-sun"></i> <span>Loading weather...</span>
        </div>
        
        <div class="card-foot">
          <div class="card-stats"><div class="attendees"><i class="bi bi-people-fill"></i> <span id="attendees-count-{{ $event->id }}">{{ $event->users_count ?? 0 }}</span> attending</div>
          <div class="card-rating-mini" id="card-rating-{{ $event->id }}" data-event-id="{{ $event->id }}" data-event-title="{{ addslashes($event->title) }}"><i class="bi bi-star-fill"></i> <span id="rating-value-{{ $event->id }}">0.0</span> <span id="rating-count-{{ $event->id }}">(0)</span></div></div>
          <div class="card-btns-row">
            <button class="btn-cancel-reg" id="cancel-btn-{{ $event->id }}" onclick="event.stopPropagation(); openCancelModal({{ $event->id }}, '{{ addslashes($event->title) }}')" {{ $isFinished ? 'disabled style="opacity:0.5;"' : '' }}><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-reg" id="reg-btn-{{ $event->id }}" onclick="event.stopPropagation(); handleCardRegClick({{ $event->id }}, '{{ addslashes($event->title) }}', '{{ $event->event_date }}', '{{ $event->start_time }}', '{{ $event->end_time }}', '{{ addslashes($event->location) }}', {{ $event->price }})" {{ $isFinished ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' }}><i class="bi bi-calendar-plus"></i> Register</button>
          </div>
        </div>
      </div>
    </div>
    @empty <div class="empty-state"><i class="bi bi-calendar-x"></i><h3>No events yet</h3><p>Check back soon for upcoming events!</p></div> @endforelse
  </div>

  <!-- FAVORITES SECTION -->
  <div class="section-block" id="favorites-section" style="display: none;"><div class="sec-tag"><i class="bi bi-heart-fill"></i> Your Favorites</div><h2 class="sec-title">Saved Events</h2><div id="favoritesGrid" class="events-grid"></div></div>

  <!-- ABOUT SECTION -->
  <div class="section-block" id="about-section">
    <div class="about-grid"><div><div class="sec-tag"><i class="bi bi-info-circle-fill"></i> About Us</div><h2 class="sec-title">Built for event lovers</h2><p>EventHub is the premier platform for discovering and joining amazing events. Tech summits, music festivals, community drives — we have something for everyone.</p><div class="feat-grid"><div class="feat-card"><div><h4>Easy Registration</h4><p>One-click sign-up</p></div></div><div class="feat-card"><div><h4>Community</h4><p>Meet like-minded people</p></div></div></div></div><div class="about-img-wrap"><img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800" alt="Event crowd"></div></div>
  </div>

  <!-- CONTACT SECTION -->
  <div class="section-block" id="contact-section">
    <div class="contact-grid"><div><div class="sec-tag"><i class="bi bi-envelope-fill"></i> Contact</div><h2 class="sec-title">Get in Touch</h2><div class="ci-list"><div class="ci"><div><h5>Visit Us</h5><p>123 Event Street, San Francisco, CA 94105</p></div></div><div class="ci"><div><h5>Email</h5><p>hello@eventhub.com</p></div></div><div class="ci"><div><h5>Phone</h5><p>+1 (555) 123-4567</p></div></div></div></div>
      <div class="contact-form-card" id="contactFormCard"><div class="contact-guest-overlay" id="contactGuestOverlay"><i class="bi bi-lock-fill" style="font-size:48px;"></i><p><strong>Login required</strong><br>Please sign in to send us a message</p><button onclick="openLoginModal()">Login / Sign Up</button></div><h4>Send a Message</h4><form action="/messages" method="POST" onsubmit="sendContact(event)">@csrf<input type="text" id="contactName" placeholder="Your Name" required disabled><input type="email" id="contactEmail" placeholder="Your Email" required disabled><textarea id="contactMessage" placeholder="Your message…" rows="4" required disabled></textarea><button type="submit" class="btn-send" disabled>Send Message <i class="bi bi-send-fill"></i></button></form></div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer-block"><div class="footer-grid"><div><h3>🎯EventHub</h3><p>Your premier destination for discovering events.</p><div class="social-row"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-twitter-x"></i></a><a href="#"><i class="bi bi-instagram"></i></a></div></div><div><h5>Navigation</h5><ul><li><a href="#events-section">Events</a></li><li><a href="#about-section">About</a></li><li><a href="#contact-section">Contact</a></li></ul></div><div><h5>Event Types</h5><ul><li><a href="#" onclick="setTab('free');return false">Free Events</a></li><li><a href="#" onclick="setTab('paid');return false">Paid Events</a></li></ul></div><div><h5>Support</h5><ul><li><a href="#">FAQ</a></li><li><a href="#">Terms</a></li><li><a href="#">Privacy</a></li></ul></div></div><div class="footer-bottom"><span>© 2025 EventHub. All rights reserved.</span><span>Made with ❤️ for event lovers</span></div></div>
</div>

<!-- QR CODE MODAL -->
<div id="qrModal" class="modal-overlay" style="display: none;">
  <div class="modal-box" style="max-width: 450px;">
    <div class="modal-head" style="background: linear-gradient(135deg, var(--accent), var(--accent2));">
      <h2><i class="bi bi-qr-code"></i> Check‑in QR Code</h2>
      <p id="qrEventTitle">Event</p>
      <button class="modal-x" onclick="closeQRModal()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-body" style="text-align: center;">
      <div id="qrCodeCanvas" style="display: flex; justify-content: center; margin: 15px 0;"></div>
      <p style="font-size:13px; color:var(--txt2);">Admin can scan this QR code to check you in.</p>
      <p style="font-size:11px; color:var(--txt3);" id="qrTokenText"></p>
      <div style="display: flex; gap: 10px; margin-top: 15px;">
        <button class="btn-cancel" onclick="closeQRModal()">Close</button>
        <button class="btn-submit" onclick="downloadQRImage()"><i class="bi bi-download"></i> Download</button>
      </div>
    </div>
  </div>
</div>

<!-- WEATHER POPUP MODAL -->
<div id="weatherPopup" class="weather-popup-modal">
  <div class="weather-popup-content">
    <div class="weather-popup-header">
      <h2><i class="bi bi-cloud-sun"></i> Weather Forecast</h2>
      <p id="weatherEventTitle">Event Name</p>
      <button class="weather-popup-close" onclick="closeWeatherPopup()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="weather-popup-body">
      <div class="weather-icon-big" id="weatherEmoji">☀️</div>
      <div class="weather-condition" id="weatherCondition">Sunny</div>
      <div class="weather-location"><i class="bi bi-geo-alt-fill"></i> <span id="weatherLocation">Location</span></div>
      <div class="weather-date"><i class="bi bi-calendar3"></i> <span id="weatherDate">Date</span></div>
      <div class="temp-main" id="weatherTemp">0°C</div>
      <div class="temp-range" id="weatherTempRange">Min: 0°C / Max: 0°C</div>
      <div class="weather-grid">
        <div class="weather-card"><i class="bi bi-wind"></i><div class="value" id="weatherWind">0 km/h</div><div class="label">Wind Speed</div></div>
        <div class="weather-card"><i class="bi bi-droplet"></i><div class="value" id="weatherHumidity">0%</div><div class="label">Humidity</div></div>
        <div class="weather-card"><i class="bi bi-sun"></i><div class="value" id="weatherUV">0</div><div class="label">UV Index</div></div>
        <div class="weather-card"><i class="bi bi-thermometer-half"></i><div class="value" id="weatherFeelsLike">0°C</div><div class="label">Feels Like</div></div>
      </div>
    </div>
  </div>
</div>

<!-- DETAIL MODAL -->
<div class="detail-overlay" id="detailModal"><div class="detail-box"><button class="detail-close" onclick="closeDetail()"><i class="bi bi-x-lg"></i></button><div class="dg-hero"><img class="dg-main-img" id="dgMainImg" src=""><button class="dg-arrow prev" id="dgPrev" onclick="dgNav(-1)"><i class="bi bi-chevron-left"></i></button><button class="dg-arrow next" id="dgNext" onclick="dgNav(1)"><i class="bi bi-chevron-right"></i></button><div class="dg-counter" id="dgCounter">1/1</div><div class="dg-price-badge" id="dgPriceBadge"></div></div><div class="dg-thumbs" id="dgThumbs"></div><div class="dg-content"><div class="dg-header-row"><h2 class="dg-title" id="dgTitle"></h2><div class="dg-reg-badge-big" id="dgRegBadgeBig" style="display:none;"><i class="bi bi-check-circle-fill"></i> You're Registered!</div></div><div class="dg-chips" id="dgChips"></div><p class="dg-desc" id="dgDesc"></p><div class="dg-info-grid"><div class="dg-info-card"><div><h5>Date</h5><p id="dgDate"></p></div></div><div class="dg-info-card"><div><h5>Time</h5><p id="dgTime"></p></div></div><div class="dg-info-card"><div><h5>Location</h5><p id="dgLoc"></p></div></div><div class="dg-info-card"><div><h5>Price</h5><p id="dgPriceInfo"></p></div></div></div><div class="dg-divider"></div><div class="dg-actions"><div class="dg-attendees"><i class="bi bi-people-fill"></i><span><strong id="dgAttendeesNum"></strong> people attending</span></div><div class="dg-buttons-horizontal"><button class="btn-dg-close" onclick="closeDetail()"><i class="bi bi-x"></i> Close</button><button class="btn-dg-fav fav-disabled" id="dgFavBtn" onclick="toggleFavoriteFromDetail()"><i class="bi bi-heart" id="dgFavIcon"></i> Favorite</button><button class="btn-dg-calendar" id="dgCalendarBtn" onclick="addToGoogleCalendar()" disabled><i class="bi bi-google"></i> Add to Google Calendar</button><button class="btn-dg-cancel" id="dgCancelBtn" onclick="triggerCancelFromDetail()" style="display:none;"><i class="bi bi-x-circle"></i> Cancel Registration</button></div></div></div></div></div>

<!-- REVIEWS MODAL -->
<div class="modal-overlay" id="reviewsModal"><div class="modal-box" style="max-width: 650px;"><div class="modal-head" style="background: linear-gradient(135deg, var(--gold), #b8860b);"><h2 id="reviewsModalTitle">Rate & Review</h2><p>Share your experience with stars and a comment</p><button class="modal-x" onclick="closeReviewsModal()"><i class="bi bi-x-lg"></i></button></div><div class="modal-body"><div id="reviewsList"></div><div id="writeReviewSection" style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 24px;"><h4>Write a Review</h4><div id="reviewAuthRequired" style="display:none; text-align:center; padding:20px; background:var(--bg4); border-radius:16px;"><i class="bi bi-lock-fill" style="font-size: 24px;"></i><p>Please <a href="#" onclick="closeReviewsModal(); openLoginModal(); return false;">login</a> to leave a review</p></div><div id="reviewRegisterRequired" style="display:none; text-align:center; padding:20px; background:var(--bg4); border-radius:16px;"><i class="bi bi-calendar-check" style="font-size: 24px;"></i><p>You need to register for this event before leaving a review.</p></div><div id="reviewAlreadySubmitted" style="display:none; text-align:center; padding:20px; background:var(--bg4); border-radius:16px;"><i class="bi bi-check-circle-fill" style="color:var(--green);font-size:24px;"></i><p style="margin-top:10px;">You have already reviewed this event. Thank you!</p></div><div id="reviewFormContainer" style="display:none;"><div class="mf"><label>Rate this event (1-5 stars)</label><div class="review-stars" id="ratingStars"><span class="star-rating" data-value="1">★</span><span class="star-rating" data-value="2">★</span><span class="star-rating" data-value="3">★</span><span class="star-rating" data-value="4">★</span><span class="star-rating" data-value="5">★</span></div><input type="hidden" id="reviewRating" value="0"></div><div class="mf"><label>Your Comment</label><textarea id="reviewComment" rows="3" placeholder="Tell others about your experience..."></textarea></div><div class="modal-btns"><button type="button" class="btn-cancel" onclick="closeReviewsModal()">Cancel</button><button type="button" class="btn-submit" id="submitReviewBtn" onclick="submitReview()"><i class="bi bi-star-fill"></i> Submit Review</button></div></div></div></div></div></div>

<!-- REGISTER MODAL -->
<div class="modal-overlay" id="registerModal"><div class="modal-box"><div class="modal-head"><h2>Register for Event</h2><p>Secure your spot in seconds</p><button class="modal-x" onclick="closeModal()"><i class="bi bi-x-lg"></i></button></div><div class="modal-body"><form onsubmit="submitReg(event)"><div class="mf"><label><i class="bi bi-person-fill"></i> Full Name *</label><input type="text" id="rName" required></div><div class="mf"><label><i class="bi bi-envelope-fill"></i> Email *</label><input type="email" id="rEmail" required></div><div id="regPasswordWrapper" class="mf" style="display: none;"><label><i class="bi bi-lock-fill"></i> Password *</label><input type="password" id="rPass"></div><div class="summary-card"><h4><i class="bi bi-info-circle-fill"></i> Event Summary</h4><div class="sr"><span>Event</span><span id="sName">—</span></div><div class="sr"><span>Date</span><span id="sDate">—</span></div><div class="sr"><span>Time</span><span id="sTime">—</span></div><div class="sr"><span>Location</span><span id="sLoc">—</span></div><div class="sr"><span>Price</span><span id="sPrice">—</span></div></div><div class="modal-btns"><button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button><button type="submit" class="btn-submit"><i class="bi bi-check-circle-fill"></i> Register Now</button></div></form></div></div></div>

<!-- CANCEL MODAL -->
<div class="modal-overlay" id="cancelModal"><div class="modal-box"><div class="cancel-modal-head modal-head"><h2><i class="bi bi-exclamation-triangle-fill"></i> Cancel Registration</h2><p>Are you sure you want to cancel?</p><button class="modal-x" onclick="closeCancelModal()"><i class="bi bi-x-lg"></i></button></div><div class="modal-body"><p style="color:var(--txt2); margin-bottom:16px;">You are about to cancel your registration for:</p><div class="summary-card" style="background:var(--red-dim); border-color:var(--red);"><div class="sr"><span>Event</span><span id="cancelEventName" style="color:var(--red); font-weight:700;">—</span></div></div><p style="color:var(--txt3); font-size:13px; margin-top:8px;">This action cannot be undone. You may re-register later if spots are available.</p><div class="modal-btns"><button type="button" class="btn-cancel" onclick="closeCancelModal()">Keep Registration</button><button type="button" class="btn-confirm-cancel" id="confirmCancelBtn" onclick="confirmCancel()"><i class="bi bi-x-circle-fill"></i> Yes, Cancel</button></div></div></div></div>

<!-- LOGIN MODAL -->
<div class="modal-overlay" id="loginModal"><div class="modal-box"><div class="modal-head" style="background: linear-gradient(135deg, var(--accent), var(--accent2));"><h2>Welcome Back</h2><p>Login to your account</p><button class="modal-x" onclick="closeLoginModal()"><i class="bi bi-x-lg"></i></button></div><div class="modal-body"><form onsubmit="loginUser(event)"><div class="mf"><label>Email</label><input type="email" id="loginEmail" required></div><div class="mf"><label>Password</label><input type="password" id="loginPassword" required></div><div class="modal-btns"><button type="button" class="btn-cancel" onclick="closeLoginModal()">Cancel</button><button type="submit" class="btn-submit">Login</button></div><p style="text-align:center; margin-top:12px; font-size:14px; color:var(--txt2);">No account? <a href="#" onclick="switchToRegister()" style="color:var(--accent);">Create one</a></p></form></div></div></div>

<!-- REGISTER USER MODAL -->
<div class="modal-overlay" id="registerUserModal"><div class="modal-box"><div class="modal-head" style="background: linear-gradient(135deg, var(--green), #16a35a);"><h2>Create Account</h2><p>Join EventHub today</p><button class="modal-x" onclick="closeRegisterUserModal()"><i class="bi bi-x-lg"></i></button></div><div class="modal-body"><form onsubmit="registerUser(event)"><div class="mf"><label>Full Name</label><input type="text" id="regUserName" required></div><div class="mf"><label>Email</label><input type="email" id="regUserEmail" required></div><div class="mf"><label>Password</label><input type="password" id="regUserPassword" required minlength="6"></div><div class="modal-btns"><button type="button" class="btn-cancel" onclick="closeRegisterUserModal()">Cancel</button><button type="submit" class="btn-submit">Register</button></div><p style="text-align:center; margin-top:12px; font-size:14px; color:var(--txt2);">Already have an account? <a href="#" onclick="switchToLogin()" style="color:var(--accent);">Login</a></p></form></div></div></div>

<!-- PROFILE MODAL -->
<div class="modal-overlay" id="profileModal">
  <div class="profile-modal-box">
    <div class="profile-modal-head">
      <h2>My Profile</h2>
      <p id="profileHeadSub">Manage your account</p>
      <button class="modal-x" onclick="closeProfileModal()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="profile-modal-body">
      <div style="text-align:center; margin-bottom:20px;"><div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:inline-flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff;" id="profileAvatar">?</div><h3 style="margin-top:10px;" id="profileNameDisplay">—</h3><p style="color:var(--txt2);" id="profileEmailDisplay">—</p></div>
      <div style="display:flex;gap:12px;margin-bottom:20px;"><div style="flex:1;text-align:center;background:var(--bg4);border-radius:14px;padding:16px;"><div style="font-size:24px;font-weight:800;color:var(--accent);" id="profileStatRegs">0</div><div style="font-size:12px;color:var(--txt3);">Events</div></div><div style="flex:1;text-align:center;background:var(--bg4);border-radius:14px;padding:16px;"><div style="font-size:24px;font-weight:800;color:var(--accent);" id="profileStatMember">—</div><div style="font-size:12px;color:var(--txt3);">Member Since</div></div></div>
      <div id="nameDisplayRow" class="info-row"><div class="info-row-left"><div class="info-row-ico"><i class="bi bi-person"></i></div><div><div class="info-row-label">Full name</div><div class="info-row-value" id="profileNameValue">—</div></div></div><button class="info-row-edit" onclick="showEditForm('name')">Edit</button></div>
      <div id="nameEditForm" class="edit-form" style="display:none;"><div class="mf"><label>New name</label><input type="text" id="editNameInput"></div><div class="edit-form-btns"><button class="btn-ef-cancel" onclick="hideEditForm('name')">Cancel</button><button class="btn-ef-save" onclick="saveName()">Save changes</button></div></div>
      <div id="emailDisplayRow" class="info-row"><div class="info-row-left"><div class="info-row-ico"><i class="bi bi-envelope"></i></div><div><div class="info-row-label">Email address</div><div class="info-row-value" id="profileEmailValue">—</div></div></div><button class="info-row-edit" onclick="showEditForm('email')">Edit</button></div>
      <div id="emailEditForm" class="edit-form" style="display:none;"><div class="mf"><label>New email</label><input type="email" id="editEmailInput"></div><div class="edit-form-btns"><button class="btn-ef-cancel" onclick="hideEditForm('email')">Cancel</button><button class="btn-ef-save" onclick="saveEmail()">Save changes</button></div></div>
      <div id="passwordDisplayRow" class="info-row"><div class="info-row-left"><div class="info-row-ico"><i class="bi bi-lock"></i></div><div><div class="info-row-label">Password</div><div class="info-row-value">••••••••</div></div></div><button class="info-row-edit" onclick="showEditForm('password')">Change</button></div>
      <div id="passwordEditForm" class="edit-form" style="display:none;"><div class="mf"><label>Current password</label><input type="password" id="editCurrentPass"></div><div class="mf"><label>New password</label><input type="password" id="editNewPass" minlength="6"></div><div class="mf"><label>Confirm new password</label><input type="password" id="editConfirmPass"></div><div class="edit-form-btns"><button class="btn-ef-cancel" onclick="hideEditForm('password')">Cancel</button><button class="btn-ef-save" onclick="savePassword()">Update password</button></div></div>
      <div style="margin-top:20px;"><div style="font-weight:700;color:var(--txt3);text-transform:uppercase;font-size:11px;margin-bottom:10px;">Your Registered Events</div><div id="profileEventList" style="max-height:200px;overflow-y:auto;"></div></div>
      <button onclick="logoutFromProfile()" style="width:100%;padding:12px;border-radius:14px;background:transparent;border:1px solid var(--red);color:var(--red);font-weight:600;cursor:pointer;margin-top:20px;"><i class="bi bi-box-arrow-right"></i> Sign Out</button>
    </div>
  </div>
</div>

<!-- SHARE MODAL -->
<div class="modal-overlay" id="shareModal"><div class="modal-box"><div class="modal-head" style="background: linear-gradient(135deg, var(--accent), var(--accent2));"><h2><i class="bi bi-share-fill"></i> Share Event</h2><p>Invite your friends!</p><button class="modal-x" onclick="closeShareModal()"><i class="bi bi-x-lg"></i></button></div><div class="modal-body"><div style="text-align:center; margin-bottom:20px;"><div style="font-size:48px;" id="shareEventEmoji">🎉</div><div style="font-weight:800; font-size:18px;" id="shareEventTitle">Event Title</div></div><div style="display:flex; gap:12px; justify-content:center; margin-bottom:20px;"><button class="btn-submit" style="flex:1;" onclick="shareViaWhatsApp()"><i class="bi bi-whatsapp"></i> WhatsApp</button><button class="btn-submit" style="flex:1;" onclick="shareViaTwitter()"><i class="bi bi-twitter-x"></i> X</button><button class="btn-submit" style="flex:1;" onclick="shareViaFacebook()"><i class="bi bi-facebook"></i> Facebook</button></div><div class="mf"><label>Copy Link</label><div style="display:flex; gap:8px;"><input type="text" id="shareLinkInput" readonly style="flex:1;"><button onclick="copyShareLink()" class="btn-submit" style="flex:0;">Copy</button></div></div></div></div></div>

<div class="toast-wrap" id="toastWrap"></div>

<script>
// ======================================================
// FULL JAVASCRIPT WITH CAROUSEL - SMALLER
// ======================================================

let registered = [], favorites = [], currentUser = null, users = JSON.parse(localStorage.getItem('eventhub_users') || '[]');
let currentEventId = null, cancelTargetId = null, calendarEvents = JSON.parse(localStorage.getItem('eventhub_calendar_events') || '[]');
let activeTab = 'all', currentReviewEventId = null, dgCurrentCard = null, dgCurrentEventId = null, dgImages = [], dgIdx = 0;
let shareEventId = null, shareEventTitle = null;

// ============================================
// CAROUSEL VARIABLES
// ============================================
let currentSlide = 0;
let carouselInterval = null;
let isCarouselPaused = false;
let carouselEvents = [];
let progressInterval = null;
let progressWidth = 0;

function escapeHtml(s) { if (!s) return ''; return s.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]); }
function toast(icon, msg) { let w = document.getElementById('toastWrap'); let te = document.createElement('div'); te.className = 'toast'; te.innerHTML = `<span style="font-size:18px">${icon}</span><span>${msg}</span>`; w.appendChild(te); setTimeout(() => te.remove(), 3800); }
function fmtTime(t) { if (!t) return ''; let p = t.split(':'); let h = parseInt(p[0]); return `${h%12||12}:${p[1]||'00'} ${h>=12?'PM':'AM'}`; }
function fmtDateLong(dateStr) { return new Date(dateStr).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); }

// ============================================
// QR CODE FUNCTIONS
// ============================================
async function showQRCodeForEvent(eventId, eventTitle, eventDate, eventLocation, eventTime) {
    if (!currentUser) {
        toast('🔐', 'Please login first');
        openLoginModal();
        return;
    }
    if (!registered.includes(parseInt(eventId))) {
        toast('❌', 'You are not registered for this event');
        return;
    }
    const token = `${currentUser.id}_${eventId}`;
    const baseUrl = 'https://pending-peddling-recount.ngrok-free.dev';
    const checkinUrl = `${baseUrl}/checkin/${token}?ngrok-skip-browser-warning=true`;
    document.getElementById('qrEventTitle').innerHTML = escapeHtml(eventTitle);
    document.getElementById('qrTokenText').innerHTML = `ID: ${token}`;
    const container = document.getElementById('qrCodeCanvas');
    container.innerHTML = '';
    try {
        new QRCode(container, {
            text: checkinUrl,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    } catch(e) {
        container.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(checkinUrl)}" alt="QR Code">`;
    }
    document.getElementById('qrModal').style.display = 'flex';
    document.getElementById('qrModal').classList.add('active');
    window.currentQRToken = token;
}
function closeQRModal() {
    document.getElementById('qrModal').classList.remove('active');
    document.getElementById('qrModal').style.display = 'none';
}
function downloadQRImage() {
    const canvas = document.querySelector('#qrCodeCanvas canvas, #qrCodeCanvas img');
    if (!canvas) { toast('❌', 'QR not ready'); return; }
    const link = document.createElement('a');
    link.download = `checkin_${window.currentQRToken}.png`;
    link.href = canvas.src || canvas.toDataURL();
    link.click();
    toast('📱', 'QR code saved');
}

// ============================================
// WEATHER FUNCTIONS
// ============================================
function getWeatherByMonth(month, location) {
  const weatherPatterns = [
    { emoji: '❄️', condition: 'Cold winter day', temp: 5, tempMin: 0, tempMax: 8, wind: 15, humidity: 75, uv: 1, feelsLike: 2 },
    { emoji: '❄️', condition: 'Cold winter day', temp: 6, tempMin: 1, tempMax: 9, wind: 14, humidity: 72, uv: 2, feelsLike: 3 },
    { emoji: '🌤️', condition: 'Mild spring day', temp: 11, tempMin: 6, tempMax: 15, wind: 12, humidity: 65, uv: 4, feelsLike: 10 },
    { emoji: '☀️', condition: 'Pleasant spring', temp: 15, tempMin: 10, tempMax: 19, wind: 11, humidity: 60, uv: 6, feelsLike: 15 },
    { emoji: '☀️', condition: 'Warm sunny day', temp: 20, tempMin: 14, tempMax: 25, wind: 10, humidity: 55, uv: 8, feelsLike: 21 },
    { emoji: '☀️', condition: 'Hot summer day', temp: 25, tempMin: 18, tempMax: 30, wind: 9, humidity: 50, uv: 9, feelsLike: 27 },
    { emoji: '☀️', condition: 'Hot summer day', temp: 28, tempMin: 21, tempMax: 33, wind: 8, humidity: 48, uv: 10, feelsLike: 31 },
    { emoji: '☀️', condition: 'Hot summer day', temp: 28, tempMin: 21, tempMax: 33, wind: 8, humidity: 50, uv: 9, feelsLike: 31 },
    { emoji: '☀️', condition: 'Warm late summer', temp: 24, tempMin: 17, tempMax: 28, wind: 10, humidity: 55, uv: 7, feelsLike: 25 },
    { emoji: '🌤️', condition: 'Mild autumn day', temp: 18, tempMin: 12, tempMax: 22, wind: 12, humidity: 62, uv: 4, feelsLike: 18 },
    { emoji: '🌧️', condition: 'Cool autumn day', temp: 12, tempMin: 7, tempMax: 16, wind: 14, humidity: 70, uv: 2, feelsLike: 10 },
    { emoji: '❄️', condition: 'Cold winter day', temp: 7, tempMin: 2, tempMax: 10, wind: 15, humidity: 78, uv: 1, feelsLike: 4 }
  ];
  let weather = { ...weatherPatterns[month] };
  const locationLower = location.toLowerCase();
  if (locationLower.includes('beach') || locationLower.includes('coast')) {
    weather.temp += 3; weather.tempMin += 2; weather.tempMax += 3; weather.wind += 5; weather.humidity += 10; weather.feelsLike = weather.temp;
    if (month >= 5 && month <= 8) { weather.condition = 'Sunny beach weather'; weather.emoji = '🏖️'; }
  } else if (locationLower.includes('mountain') || locationLower.includes('hill')) {
    weather.temp -= 5; weather.tempMin -= 5; weather.tempMax -= 5; weather.wind += 8; weather.feelsLike = weather.temp - 3;
    if (month >= 11 || month <= 2) { weather.condition = 'Snowy mountain'; weather.emoji = '⛷️'; }
  } else if (locationLower.includes('hall') || locationLower.includes('center') || locationLower.includes('indoor')) {
    weather.condition = 'Indoor event'; weather.temp = 22; weather.tempMin = 20; weather.tempMax = 24; weather.wind = 0; weather.humidity = 40; weather.uv = 0; weather.feelsLike = 22; weather.emoji = '🏢';
  }
  return weather;
}

function getBootstrapIconFromCondition(condition) {
  const c = condition.toLowerCase();
  if (c.includes('sunny') || c.includes('hot') || c.includes('warm')) return 'sun';
  if (c.includes('cloud') || c.includes('mild')) return 'cloud-sun';
  if (c.includes('rain') || c.includes('cool')) return 'cloud-rain';
  if (c.includes('snow') || c.includes('cold')) return 'snow';
  if (c.includes('indoor')) return 'building';
  return 'cloud-sun';
}

async function loadWeatherForAllEvents() {
  const cards = document.querySelectorAll('.event-card');
  for (const card of cards) {
    const eventId = card.getAttribute('data-id');
    const eventDate = card.getAttribute('data-date');
    const location = card.getAttribute('data-location');
    const eventTitle = card.getAttribute('data-full-title');
    if (eventId && eventDate && location) {
      const dateObj = new Date(eventDate);
      const month = dateObj.getMonth();
      const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
      const weather = getWeatherByMonth(month, location);
      const weatherContainer = document.getElementById(`weather-${eventId}`);
      if (weatherContainer) {
        weatherContainer.setAttribute('data-weather-emoji', weather.emoji);
        weatherContainer.setAttribute('data-weather-condition', weather.condition);
        weatherContainer.setAttribute('data-weather-temp', weather.temp);
        weatherContainer.setAttribute('data-weather-temp-min', weather.tempMin);
        weatherContainer.setAttribute('data-weather-temp-max', weather.tempMax);
        weatherContainer.setAttribute('data-weather-wind', weather.wind);
        weatherContainer.setAttribute('data-weather-humidity', weather.humidity);
        weatherContainer.setAttribute('data-weather-uv', weather.uv);
        weatherContainer.setAttribute('data-weather-feels-like', weather.feelsLike);
        weatherContainer.setAttribute('data-event-date-full', dateObj.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }));
        weatherContainer.setAttribute('data-event-location', location);
        weatherContainer.setAttribute('data-event-title', eventTitle);
        weatherContainer.innerHTML = `<i class="bi bi-${getBootstrapIconFromCondition(weather.condition)}"></i><span class="weather-temp">${weather.emoji} ${weather.temp}°C</span><small style="font-size: 10px; margin-left: 4px; color: var(--txt3);">${formattedDate}</small>`;
        weatherContainer.onclick = (e) => { e.stopPropagation(); showWeatherPopup(eventId); };
      }
    }
  }
}

function showWeatherPopup(eventId) {
  const wc = document.getElementById(`weather-${eventId}`);
  if (!wc) return;
  document.getElementById('weatherEmoji').textContent = wc.getAttribute('data-weather-emoji');
  document.getElementById('weatherCondition').textContent = wc.getAttribute('data-weather-condition');
  document.getElementById('weatherLocation').textContent = wc.getAttribute('data-event-location');
  document.getElementById('weatherDate').textContent = wc.getAttribute('data-event-date-full');
  document.getElementById('weatherEventTitle').textContent = wc.getAttribute('data-event-title');
  document.getElementById('weatherTemp').textContent = `${wc.getAttribute('data-weather-temp')}°C`;
  document.getElementById('weatherTempRange').textContent = `Min: ${wc.getAttribute('data-weather-temp-min')}°C / Max: ${wc.getAttribute('data-weather-temp-max')}°C`;
  document.getElementById('weatherWind').textContent = `${wc.getAttribute('data-weather-wind')} km/h`;
  document.getElementById('weatherHumidity').textContent = `${wc.getAttribute('data-weather-humidity')}%`;
  document.getElementById('weatherUV').textContent = wc.getAttribute('data-weather-uv');
  document.getElementById('weatherFeelsLike').textContent = `${wc.getAttribute('data-weather-feels-like')}°C`;
  document.getElementById('weatherPopup').classList.add('active');
}

function closeWeatherPopup() { document.getElementById('weatherPopup').classList.remove('active'); }

// ============================================
// EVENT FUNCTIONS
// ============================================
function isEventFinished(eventDate) {
  const today = new Date(); today.setHours(0,0,0,0);
  const ed = new Date(eventDate); ed.setHours(0,0,0,0);
  return ed < today;
}

function updateFinishedEvents() {
  document.querySelectorAll('.event-card').forEach(card => {
    const finished = isEventFinished(card.getAttribute('data-date'));
    if(finished && !card.classList.contains('finished')) card.classList.add('finished');
  });
}

const levelDiscounts = { bronze:0, silver:10, gold:20, platinum:30 };
function getUserLevelInfo(cnt) {
  if(cnt>=20) return { name:'💎 Platinum', level:'platinum', color:'#e5e4e2', discount:30 };
  if(cnt>=10) return { name:'🥇 Gold', level:'gold', color:'#ffd700', discount:20 };
  if(cnt>=5) return { name:'🥈 Silver', level:'silver', color:'#c0c0c0', discount:10 };
  return { name:'🥉 Bronze', level:'bronze', color:'#cd7f32', discount:0 };
}
function getProgressToNextLevel(cnt) {
  if(cnt>=20) { const nm=Math.ceil((cnt+1)/5)*5; return { percentage:Math.min(100,((cnt-20)/5)*100), needed:nm-cnt, nextLevelName:`Platinum +${Math.floor(cnt/5)-3}` }; }
  if(cnt>=10) return { percentage:Math.min(100,((cnt-10)/10)*100), needed:20-cnt, nextLevelName:'💎 Platinum' };
  if(cnt>=5) return { percentage:Math.min(100,((cnt-5)/5)*100), needed:10-cnt, nextLevelName:'🥇 Gold' };
  return { percentage:(cnt/5)*100, needed:5-cnt, nextLevelName:'🥈 Silver' };
}
function calculateDiscountedPrice(price, level) { const d=levelDiscounts[level]||0; return { originalPrice:price, discountPercent:d, discountAmount:(price*d)/100, finalPrice:Math.max(0,price-(price*d)/100) }; }

function getFavoritesForUser(uid) { return JSON.parse(localStorage.getItem('eventhub_favorites')||'{}')[uid]||[]; }
function saveFavoritesForUser(uid,favs) { let all=JSON.parse(localStorage.getItem('eventhub_favorites')||'{}'); all[uid]=favs; localStorage.setItem('eventhub_favorites',JSON.stringify(all)); }
function updateFavCount() { let el=document.getElementById('favCount'); if(el) el.textContent=favorites.length; }
function updateFavUI(eid,isfav) {
  let badge=document.getElementById('fav-badge-'+eid), icon=document.getElementById('fav-icon-'+eid);
  if(badge&&icon) { const isReg=currentUser&&registered.includes(parseInt(eid)); if(!isReg) { badge.classList.add('fav-disabled'); badge.classList.remove('active'); icon.className='bi bi-heart'; } else if(isfav) { badge.classList.remove('fav-disabled'); badge.classList.add('active'); icon.className='bi bi-heart-fill'; } else { badge.classList.remove('fav-disabled','active'); icon.className='bi bi-heart'; } }
}
function refreshAllFavBadgeStates() { document.querySelectorAll('.event-card').forEach(c=>{ const id=parseInt(c.dataset.id); updateFavUI(id,favorites.includes(id)); }); }
function toggleFavorite(eid) {
  if(!currentUser) { toast('🔐','Please login to add favorites'); openLoginModal(); return false; }
  if(!registered.includes(parseInt(eid))) { toast('📋','Please register for this event first!'); return false; }
  let idx=favorites.indexOf(parseInt(eid));
  if(idx===-1) { favorites.push(parseInt(eid)); toast('❤️','Added to favorites!'); } else { favorites.splice(idx,1); toast('💔','Removed from favorites'); }
  saveFavoritesForUser(currentUser.id,favorites); updateFavUI(eid,idx===-1); updateFavCount(); renderFavoritesSection(); if(activeTab==='favorites') applyFilters();
}
function renderFavoritesSection() {
  let c=document.getElementById('favoritesGrid'); if(!c) return;
  if(!favorites.length) { c.innerHTML='<div class="empty-state"><i class="bi bi-heart"></i><h3>No events yet</h3><p>Add your favorite events here!</p></div>'; return; }
  c.innerHTML='';
  favorites.forEach(fid=>{ let oc=document.querySelector(`#eventsGrid .event-card[data-id="${fid}"]`); if(oc&&!oc.classList.contains('finished')){ let cl=oc.cloneNode(true); cl.onclick=()=>openDetail(cl); c.appendChild(cl); } });
}
function getAllRegistrationsForUser(uid) { return JSON.parse(localStorage.getItem('eventhub_user_regs')||'{}')[uid]||[]; }
function saveUserRegistrations(uid,regs) { let all=JSON.parse(localStorage.getItem('eventhub_user_regs')||'{}'); all[uid]=regs; localStorage.setItem('eventhub_user_regs',JSON.stringify(all)); }
function markRegistered(id) {
  let btn=document.getElementById('reg-btn-'+id), cb=document.getElementById('cancel-btn-'+id), bdg=document.getElementById('reg-badge-'+id);
  if(btn&&!btn.disabled) { btn.classList.add('done'); btn.disabled=true; }
  if(cb) cb.classList.add('visible');
  if(bdg) bdg.style.display='flex';
  updateFavUI(id,favorites.includes(id));
  updateAttendeesCount(id,1);
}
function unmarkRegistered(id) {
  let btn=document.getElementById('reg-btn-'+id), cb=document.getElementById('cancel-btn-'+id), bdg=document.getElementById('reg-badge-'+id);
  if(btn&&!btn.disabled) { btn.classList.remove('done'); btn.disabled=false; }
  if(cb) cb.classList.remove('visible');
  if(bdg) bdg.style.display='none';
  updateFavUI(id,false);
  if(currentUser&&favorites.includes(parseInt(id))) { favorites=favorites.filter(f=>f!==parseInt(id)); saveFavoritesForUser(currentUser.id,favorites); updateFavCount(); }
  updateAttendeesCount(id,-1);
}
function updateAttendeesCount(eid,ch) { let sp=document.getElementById('attendees-count-'+eid); if(sp) sp.textContent=Math.max(0,(parseInt(sp.textContent)||0)+ch); }
function updateAllRegistrationUI() { registered.forEach(id=>{ const card=document.querySelector(`.event-card[data-id="${id}"]`); if(card&&!isEventFinished(card.dataset.date)) markRegistered(id); }); refreshAllFavBadgeStates(); }
function updateCount() { let el=document.getElementById('myRegCount'); if(el) el.textContent=registered.length; }

function updateDashboard() {
  let d=document.getElementById('userDashboard');
  if(!currentUser) { d.style.display='none'; return; }
  d.style.display='block';
  document.getElementById('dashboardWelcome').innerHTML='Welcome back, '+escapeHtml(currentUser.name)+'!';
  let regs=getAllRegistrationsForUser(currentUser.id);
  document.getElementById('dashboardCount').textContent=regs.length;
  let l=document.getElementById('dashboardEventsList');
  let upcoming=[];
  document.querySelectorAll('.event-card').forEach(c=>{ let id=parseInt(c.dataset.id), date=c.dataset.date; if(regs.includes(id)&&!isEventFinished(date)) upcoming.push({id,title:c.dataset.fullTitle,date,location:c.dataset.location,startTime:c.dataset.startTime,endTime:c.dataset.endTime}); });
  if(!upcoming.length) { l.innerHTML='<div class="empty-dash"><i class="bi bi-calendar-check"></i>No upcoming events registered</div>'; return; }
  l.innerHTML=upcoming.map(e=>`<div class="dash-event-card"><div class="dash-event-title">${escapeHtml(e.title)}</div><div class="dash-event-date"><i class="bi bi-calendar3"></i> ${new Date(e.date).toLocaleDateString()}</div><div class="dash-event-date"><i class="bi bi-geo-alt-fill"></i> ${escapeHtml(e.location)}</div><div style="margin-top:12px; display:flex; gap:8px;"><button class="stats-modal-btn qr-btn" onclick="showQRCodeForEvent(${e.id}, '${escapeHtml(e.title).replace(/'/g, "\\'")}', '${e.date}', '${escapeHtml(e.location).replace(/'/g, "\\'")}', '${fmtTime(e.startTime)} - ${fmtTime(e.endTime)}')"><i class="bi bi-qr-code"></i> Show QR Code</button></div></div>`).join('');
}

function updateSidebarUI() {
  let info=document.getElementById('sidebarUserInfo'), gs=document.getElementById('sidebarGetStartedBtn'), lo=document.getElementById('sidebarLogoutBtn'), pl=document.getElementById('sidebarProfileLink'), mel=document.getElementById('sidebarMyEventsLink'), cal=document.getElementById('sidebarCalendarLink');
  if(currentUser){
    info.style.display='flex'; gs.style.display='none'; lo.style.display='flex'; pl.style.display='flex'; mel.style.display='flex'; cal.style.display='flex';
    document.getElementById('sidebarAvatar').textContent=(currentUser.name[0]+(currentUser.name.split(' ')[1]?.[0]||'')).toUpperCase();
    document.getElementById('sidebarUserName').textContent=currentUser.name;
    document.getElementById('sidebarUserEmail').textContent=currentUser.email;
  } else { info.style.display='none'; gs.style.display='block'; lo.style.display='none'; pl.style.display='none'; mel.style.display='none'; cal.style.display='none'; }
  refreshAllFavBadgeStates();
}

// ============================================
// CAROUSEL FUNCTIONS - SMALLER
// ============================================
function initCarousel() {
  const track = document.getElementById('carouselTrack');
  if (!track) return;
  
  const allCards = document.querySelectorAll('#eventsGrid .event-card');
  if (allCards.length === 0) {
    track.innerHTML = '<div class="empty-state" style="padding:40px;width:100%;"><i class="bi bi-calendar-x"></i><h3>No events available</h3></div>';
    return;
  }
  
  track.innerHTML = '';
  const maxDisplay = Math.min(allCards.length, 12);
  carouselEvents = Array.from(allCards).slice(0, maxDisplay);
  
  carouselEvents.forEach(card => {
    const clone = card.cloneNode(true);
    clone.classList.remove('finished');
    clone.onclick = function(e) {
      if (!e.target.closest('.btn-reg') && !e.target.closest('.fav-badge') && !e.target.closest('.share-modal-btn') && !e.target.closest('.btn-cancel-reg') && !e.target.closest('.card-rating-mini') && !e.target.closest('.event-weather')) {
        const orig = document.querySelector(`#eventsGrid .event-card[data-id="${clone.dataset.id}"]`);
        if (orig) openDetail(orig);
      }
    };
    const regBtn = clone.querySelector('.btn-reg');
    if (regBtn) {
      regBtn.onclick = function(e) {
        e.stopPropagation();
        const orig = document.querySelector(`#eventsGrid .event-card[data-id="${clone.dataset.id}"]`);
        if (orig) {
          const id = parseInt(orig.dataset.id);
          handleCardRegClick(id, orig.dataset.fullTitle, orig.dataset.date, orig.dataset.startTime, orig.dataset.endTime, orig.dataset.location, parseFloat(orig.dataset.price)||0);
        }
      };
    }
    const favBtn = clone.querySelector('.fav-badge');
    if (favBtn) {
      favBtn.onclick = function(e) {
        e.stopPropagation();
        toggleFavorite(parseInt(clone.dataset.id));
      };
    }
    const shareBtn = clone.querySelector('.share-modal-btn');
    if (shareBtn) {
      shareBtn.onclick = function(e) {
        e.stopPropagation();
        shareEvent(parseInt(clone.dataset.id), clone.dataset.fullTitle);
      };
    }
    const cancelBtn = clone.querySelector('.btn-cancel-reg');
    if (cancelBtn) {
      cancelBtn.onclick = function(e) {
        e.stopPropagation();
        openCancelModal(parseInt(clone.dataset.id), clone.dataset.fullTitle);
      };
    }
    const ratingBtn = clone.querySelector('.card-rating-mini');
    if (ratingBtn) {
      ratingBtn.onclick = function(e) {
        e.stopPropagation();
        const orig = document.querySelector(`#eventsGrid .event-card[data-id="${clone.dataset.id}"]`);
        if (orig) openReviewsModal(parseInt(orig.dataset.id), orig.dataset.fullTitle);
      };
    }
    const weatherBtn = clone.querySelector('.event-weather');
    if (weatherBtn) {
      weatherBtn.onclick = function(e) {
        e.stopPropagation();
        const orig = document.querySelector(`#eventsGrid .event-card[data-id="${clone.dataset.id}"]`);
        if (orig) showWeatherPopup(parseInt(orig.dataset.id));
      };
    }
    track.appendChild(clone);
  });
  
  setupDots();
  goToSlide(0);
  startAutoScroll();
  
  track.addEventListener('mouseenter', pauseAutoScroll);
  track.addEventListener('mouseleave', resumeAutoScroll);
  track.addEventListener('touchstart', pauseAutoScroll);
  track.addEventListener('touchend', resumeAutoScroll);
}

function setupDots() {
  const dotsContainer = document.getElementById('carouselDots');
  if (!dotsContainer) return;
  
  const totalSlides = carouselEvents.length;
  
  dotsContainer.innerHTML = '';
  for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement('span');
    dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
    dot.dataset.index = i;
    dot.onclick = () => goToSlide(i);
    dotsContainer.appendChild(dot);
  }
}

function goToSlide(index) {
  const track = document.getElementById('carouselTrack');
  if (!track) return;
  
  const totalSlides = carouselEvents.length;
  if (totalSlides === 0) return;
  
  if (index >= totalSlides) index = 0;
  if (index < 0) index = totalSlides - 1;
  
  currentSlide = index;
  
  const offset = index * 100;
  track.style.transform = `translateX(-${offset}%)`;
  
  document.querySelectorAll('.carousel-dot').forEach((dot, i) => {
    dot.classList.toggle('active', i === index);
  });
  
  progressWidth = 0;
  updateProgressBar();
}

function nextSlide() {
  const totalSlides = carouselEvents.length;
  if (totalSlides === 0) return;
  goToSlide((currentSlide + 1) % totalSlides);
}

function prevSlide() {
  const totalSlides = carouselEvents.length;
  if (totalSlides === 0) return;
  goToSlide((currentSlide - 1 + totalSlides) % totalSlides);
}

function updateProgressBar() {
  const bar = document.getElementById('carouselProgressBar');
  if (!bar) return;
  bar.style.width = `${progressWidth}%`;
}

function startAutoScroll() {
  if (carouselInterval) clearInterval(carouselInterval);
  if (progressInterval) clearInterval(progressInterval);
  
  const intervalTime = 5000;
  
  carouselInterval = setInterval(() => {
    if (!isCarouselPaused) {
      nextSlide();
    }
  }, intervalTime);
  
  progressWidth = 0;
  const step = 0.5;
  progressInterval = setInterval(() => {
    if (!isCarouselPaused) {
      progressWidth += step;
      if (progressWidth >= 100) {
        progressWidth = 0;
      }
      updateProgressBar();
    }
  }, intervalTime / 200);
}

function pauseAutoScroll() {
  isCarouselPaused = true;
}

function resumeAutoScroll() {
  isCarouselPaused = false;
  progressWidth = 0;
  updateProgressBar();
}

// ============================================
// USER SESSION FUNCTIONS
// ============================================
async function loadUserSession() {
  let stored=localStorage.getItem('eventhub_current_user');
  if(stored){
    currentUser=JSON.parse(stored);
    registered=getAllRegistrationsForUser(currentUser.id);
    favorites=getFavoritesForUser(currentUser.id);
    updateAllRegistrationUI(); updateDashboard(); updateSidebarUI(); updateRegisteredTabVisibility(); updateGuestBannerAndButtons(); updateContactFormAccess(); updateFavCount(); updateCount(); getRecommendations();
  } else { currentUser=null; registered=[]; favorites=[]; updateAllRegistrationUI(); updateDashboard(); updateSidebarUI(); updateRegisteredTabVisibility(); updateGuestBannerAndButtons(); updateContactFormAccess(); updateFavCount(); updateCount(); refreshAllFavBadgeStates(); getRecommendations(); }
  loadWeatherForAllEvents(); updateFinishedEvents();
}

async function registerUser(e){
  e.preventDefault();
  let name=document.getElementById('regUserName').value.trim(), email=document.getElementById('regUserEmail').value.trim(), pwd=document.getElementById('regUserPassword').value;
  if(pwd.length<6){ toast('⚠️','Password must be at least 6 characters'); return; }
  try{
    let r=await fetch('/api/user/register',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify({name,email,password:pwd})});
    let d=await r.json();
    if(d.success){ toast('🎉','Account created successfully!'); closeRegisterUserModal(); openLoginModal(); } else toast('❌',d.message||'Registration failed');
  }catch(e){ toast('❌','Network error'); }
}

async function loginUser(e){
  e.preventDefault();
  let email=document.getElementById('loginEmail').value.trim(), pwd=document.getElementById('loginPassword').value;
  try{
    let r=await fetch('/api/user/login',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify({email,password:pwd})});
    let d=await r.json();
    if(d.success){
      currentUser=d.user;
      localStorage.setItem('eventhub_current_user',JSON.stringify(currentUser));
      let regRes=await fetch(`/api/user/${currentUser.id}/registrations`);
      let regData=await regRes.json();
      registered=regData.success?regData.registrations:[];
      saveUserRegistrations(currentUser.id,registered);
      favorites=getFavoritesForUser(currentUser.id);
      closeLoginModal();
      toast('✅','Welcome back '+currentUser.name+'!');
      updateDashboard(); updateRegisteredTabVisibility(); updateGuestBannerAndButtons(); updateSidebarUI(); applyFilters(); loadUserNotifications(); updateContactFormAccess(); updateAllRegistrationUI(); updateFavCount(); updateCount(); getRecommendations(); loadWeatherForAllEvents();
      setTimeout(initCarousel, 500);
    } else toast('❌',d.message||'Invalid credentials');
  }catch(e){ toast('❌','Network error'); }
}

function logout() {
  fetch('/api/user/logout',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')}}).catch(()=>{});
  currentUser=null;
  localStorage.removeItem('eventhub_current_user');
  registered=[]; favorites=[];
  updateAllRegistrationUI(); updateDashboard(); updateRegisteredTabVisibility(); updateGuestBannerAndButtons(); updateSidebarUI(); updateContactFormAccess(); updateCount(); updateFavCount(); refreshAllFavBadgeStates();
  activeTab='all';
  let allTab=document.querySelector('.pill[data-tab="all"]');
  if(allTab){ document.querySelectorAll('.pill').forEach(p=>p.classList.remove('active')); allTab.classList.add('active'); }
  document.getElementById('eventsGrid').style.display='grid';
  document.getElementById('favorites-section').style.display='none';
  applyFilters(); getRecommendations(); loadWeatherForAllEvents(); toast('👋','Logged out');
  setTimeout(initCarousel, 500);
}

async function confirmCancel(){
  if(!cancelTargetId||!currentUser) return;
  let btn=document.getElementById('confirmCancelBtn'), orig=btn.innerHTML;
  btn.disabled=true; btn.innerHTML='...';
  try{
    let r=await fetch('/events/cancel',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify({event_id:cancelTargetId,email:currentUser.email})});
    let d=await r.json();
    if(!r.ok||!d.success) throw new Error(d.message||'Cancel failed');
    let regs=getAllRegistrationsForUser(currentUser.id);
    regs=regs.filter(id=>id!==cancelTargetId);
    saveUserRegistrations(currentUser.id,regs); registered=regs;
    calendarEvents=calendarEvents.filter(ce=>ce.eventId!==cancelTargetId);
    localStorage.setItem('eventhub_calendar_events',JSON.stringify(calendarEvents));
    unmarkRegistered(cancelTargetId);
    updateCount(); updateDashboard(); closeCancelModal(); applyFilters(); getRecommendations(); toast('✅','Registration cancelled');
    setTimeout(initCarousel, 300);
  } catch(e){ toast('❌',e.message); } finally{ btn.disabled=false; btn.innerHTML=orig; }
}

function handleCardRegClick(id,title,date,start,end,loc,price){
  if(!currentUser){ toast('🔐','Login required'); openLoginModal(); return; }
  if(registered.includes(id)){ toast('⚠️','You are already registered!'); return; }
  if(isEventFinished(date)){ toast('⛔','This event has already finished!'); return; }
  openModal(id,title,date,start,end,loc,price);
}

function closeModal(){ document.getElementById('registerModal').classList.remove('active'); }
function openCancelModal(id,name){ cancelTargetId=id; document.getElementById('cancelEventName').textContent=name; document.getElementById('cancelModal').classList.add('active'); }
function closeCancelModal(){ document.getElementById('cancelModal').classList.remove('active'); cancelTargetId=null; }

function addToGoogleCalendar(){
  if(!dgCurrentCard||!dgCurrentEventId) return;
  if(!currentUser){ toast('🔐','Login required'); return; }
  if(!registered.includes(dgCurrentEventId)){ toast('⚠️','You must register first'); return; }
  let card=dgCurrentCard;
  let title=encodeURIComponent(card.dataset.fullTitle), date=card.dataset.date, start=card.dataset.startTime, end=card.dataset.endTime, loc=encodeURIComponent(card.dataset.location), desc=encodeURIComponent(card.dataset.description);
  let st=date.replace(/-/g,'')+'T'+start.replace(/:/g,'')+'00', et=date.replace(/-/g,'')+'T'+end.replace(/:/g,'')+'00';
  window.open(`https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${st}/${et}&details=${desc}&location=${loc}&sf=true&output=xml`,'_blank');
  toast('📅','Added to Google Calendar!');
}

async function openDetail(card){
  dgCurrentCard=card; dgCurrentEventId=parseInt(card.dataset.id);
  let title=card.dataset.fullTitle, desc=card.dataset.description, loc=card.dataset.location, date=card.dataset.date, start=card.dataset.startTime, end=card.dataset.endTime, price=parseFloat(card.dataset.price)||0, attendees=card.dataset.attendees||'0';
  try{ dgImages=JSON.parse(card.dataset.images); }catch(e){ dgImages=[]; }
  if(!dgImages.length) dgImages=['https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800'];
  dgIdx=0;
  document.getElementById('dgTitle').textContent=title;
  document.getElementById('dgDesc').textContent=desc;
  document.getElementById('dgLoc').textContent=loc;
  document.getElementById('dgDate').textContent=fmtDateLong(date);
  document.getElementById('dgTime').textContent=fmtTime(start)+' – '+fmtTime(end);
  document.getElementById('dgPriceInfo').textContent=price===0?'FREE':'$'+price.toFixed(2);
  document.getElementById('dgAttendeesNum').textContent=attendees;
  let pb=document.getElementById('dgPriceBadge'); pb.textContent=price===0?'FREE':'$'+price.toFixed(2); pb.className='dg-price-badge '+(price===0?'free':'paid');
  document.getElementById('dgChips').innerHTML=`<div class="dg-chip"><i class="bi bi-calendar3"></i> ${fmtDateLong(date)}</div><div class="dg-chip"><i class="bi bi-clock"></i> ${fmtTime(start)} – ${fmtTime(end)}</div><div class="dg-chip"><i class="bi bi-geo-alt-fill"></i> ${escapeHtml(loc)}</div><div class="dg-chip"><i class="bi bi-tag-fill"></i> ${price===0?'FREE':'$'+price.toFixed(2)}</div>`;
  let thumbs=document.getElementById('dgThumbs'); thumbs.innerHTML='';
  if(dgImages.length>1){ dgImages.forEach((src,i)=>{ let img=document.createElement('img'); img.className='dg-thumb'+(i===0?' active':''); img.src=src; img.onclick=()=>dgSetImg(i); thumbs.appendChild(img); }); }
  dgSetImg(0);
  let isReg=registered.includes(dgCurrentEventId), isFav=favorites.includes(dgCurrentEventId), finished=isEventFinished(date);
  document.getElementById('dgRegBadgeBig').style.display=isReg?'inline-flex':'none';
  document.getElementById('dgCancelBtn').style.display=(isReg&&!finished)?'inline-flex':'none';
  document.getElementById('dgCalendarBtn').disabled=!isReg||finished;
  document.getElementById('detailModal').classList.add('active');
}
function dgSetImg(idx){ dgIdx=idx; document.getElementById('dgMainImg').src=dgImages[idx]; document.getElementById('dgCounter').textContent=(idx+1)+' / '+dgImages.length; document.querySelectorAll('.dg-thumb').forEach((t,i)=>t.classList.toggle('active',i===idx)); }
function dgNav(dir){ if(!dgImages.length) return; dgSetImg((dgIdx+dir+dgImages.length)%dgImages.length); }
function closeDetail(){ document.getElementById('detailModal').classList.remove('active'); dgCurrentCard=null; dgCurrentEventId=null; }
function triggerCancelFromDetail(){ if(!dgCurrentCard) return; openCancelModal(parseInt(dgCurrentCard.dataset.id), dgCurrentCard.dataset.fullTitle); }

window.openModal=function(id,title,date,start,end,loc,price){
  currentEventId=id;
  let finalPrice=price, discountInfo=null, userLevelInfo=null;
  if(currentUser){ const regs=getAllRegistrationsForUser(currentUser.id); userLevelInfo=getUserLevelInfo(regs.length); discountInfo=calculateDiscountedPrice(price,userLevelInfo.level); finalPrice=discountInfo.finalPrice; }
  document.getElementById('sName').textContent=title;
  document.getElementById('sDate').textContent=new Date(date+'T00:00:00').toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'});
  document.getElementById('sTime').textContent=fmtTime(start)+' – '+fmtTime(end);
  document.getElementById('sLoc').textContent=loc;
  if(discountInfo&&discountInfo.discountPercent>0&&price>0){ document.getElementById('sPrice').innerHTML=`<div style="display:flex;flex-direction:column;gap:5px;"><div><span style="text-decoration:line-through;color:var(--txt3);font-size:14px;">$${price.toFixed(2)}</span><span style="color:var(--green);font-size:18px;font-weight:800;margin-left:8px;">$${finalPrice.toFixed(2)}</span></div><div style="font-size:11px;background:var(--green-dim);padding:4px10px;border-radius:20px;display:inline-block;width:fit-content;">🎉 ${discountInfo.discountPercent}% off (${userLevelInfo.name} discount)</div></div>`; }
  else if(discountInfo&&discountInfo.discountPercent>0&&price===0){ document.getElementById('sPrice').innerHTML=`<div><span style="color:var(--green);font-size:18px;font-weight:800;">FREE</span><div style="font-size:11px;background:var(--green-dim);padding:4px10px;border-radius:20px;display:inline-block;margin-left:10px;">🎉 ${userLevelInfo.name} benefit</div></div>`; }
  else { document.getElementById('sPrice').innerHTML=price==0?'FREE':'<span style="font-size:18px;font-weight:800;">$'+finalPrice.toFixed(2)+'</span>'; }
  if(currentUser){ document.getElementById('rName').value=currentUser.name; document.getElementById('rEmail').value=currentUser.email; document.getElementById('regPasswordWrapper').style.display='none'; }
  else { document.getElementById('regPasswordWrapper').style.display='block'; }
  document.getElementById('registerModal').classList.add('active');
};

window.submitReg=async function(e){
  e.preventDefault();
  let name=document.getElementById('rName').value, email=document.getElementById('rEmail').value, pwd=document.getElementById('rPass').value, btn=e.target.querySelector('.btn-submit'), orig=btn.innerHTML;
  btn.innerHTML='...'; btn.disabled=true;
  try{
    let body={event_id:currentEventId,name,email};
    if(currentUser){
      body.user_id=currentUser.id;
      const regs=getAllRegistrationsForUser(currentUser.id);
      const ulvl=getUserLevelInfo(regs.length);
      body.discount_applied=ulvl.discount;
      let op=parseFloat(document.querySelector(`.event-card[data-id="${currentEventId}"]`)?.dataset.price)||0;
      body.original_price=op; body.final_price=calculateDiscountedPrice(op,ulvl.level).finalPrice;
    } else if(pwd&&pwd.length>=8){ body.password=pwd; } else { toast('⚠️','Password must be at least 8 characters'); btn.innerHTML=orig; btn.disabled=false; return; }
    let r=await fetch('/events/register',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify(body)});
    let d=await r.json();
    if(d.success){
      if(!currentUser&&d.user){ currentUser={id:d.user.id,name:d.user.name,email:d.user.email}; localStorage.setItem('eventhub_current_user',JSON.stringify(currentUser)); registered=[]; favorites=[]; saveUserRegistrations(currentUser.id,[]); updateSidebarUI(); updateContactFormAccess(); }
      if(currentUser){
        let regs=getAllRegistrationsForUser(currentUser.id);
        if(!regs.includes(currentEventId)){ regs.push(currentEventId); saveUserRegistrations(currentUser.id,regs); registered=regs; }
      }
      markRegistered(currentEventId); updateCount(); closeModal();
      if(body.discount_applied&&body.discount_applied>0){ const lname=getUserLevelInfo(registered.length).name; toast('🎉',`You saved ${body.discount_applied}% with your ${lname} level!`); } else toast('🎉',d.message);
      applyFilters(); updateDashboard(); updateFavUI(currentEventId,favorites.includes(currentEventId)); getRecommendations(); loadWeatherForAllEvents();
      setTimeout(initCarousel, 300);
    } else toast('❌',d.message||'Registration failed');
  } catch(e){ toast('❌','Network error'); } finally{ btn.innerHTML=orig; btn.disabled=false; }
};

function applyFilters(){
  let term=document.getElementById('searchInput').value.toLowerCase(), month=document.getElementById('filterMonth').value;
  if(activeTab==='favorites'){ document.getElementById('eventsGrid').style.display='none'; document.getElementById('favorites-section').style.display='block'; renderFavoritesSection(); return; }
  else { document.getElementById('eventsGrid').style.display='grid'; document.getElementById('favorites-section').style.display='none'; }
  document.querySelectorAll('#eventsGrid .event-card').forEach(c=>{
    let title=c.dataset.title||'', loc=(c.dataset.location||'').toLowerCase(), date=c.dataset.date||'', cm=date.split('-')[1]||'', isFree=c.dataset.tabFree==='true', isPaid=c.dataset.tabPaid==='true', isReg=registered.includes(parseInt(c.dataset.id));
    let matchSearch=(!term||title.includes(term)||loc.includes(term)), matchMonth=(!month||cm===month), matchType=(activeTab==='all'||(activeTab==='free'&&isFree)||(activeTab==='paid'&&isPaid)||(activeTab==='registered'&&isReg));
    c.style.display=(matchSearch&&matchMonth&&matchType)?'':'none';
  });
}
function sortCards(){
  let by=document.getElementById('sortBy').value, cont=document.getElementById('eventsGrid'), cards=Array.from(cont.querySelectorAll('.event-card'));
  cards.sort((a,b)=>{ let da=a.dataset.date, db=b.dataset.date, pa=parseFloat(a.dataset.price)||0, pb=parseFloat(b.dataset.price)||0, ta=a.dataset.title||'', tb=b.dataset.title||'';
    if(by==='date-asc') return new Date(da)-new Date(db); if(by==='date-desc') return new Date(db)-new Date(da);
    if(by==='price-asc') return pa-pb; if(by==='price-desc') return pb-pa; if(by==='title') return ta.localeCompare(tb); return 0;
  }); cards.forEach(c=>cont.appendChild(c));
}
function setupFilters(){
  document.getElementById('searchInput').addEventListener('input',applyFilters);
  document.getElementById('filterMonth').addEventListener('change',applyFilters);
  document.getElementById('sortBy').addEventListener('change',sortCards);
  document.querySelectorAll('.pill').forEach(p=>p.addEventListener('click',function(){ document.querySelectorAll('.pill').forEach(x=>x.classList.remove('active')); this.classList.add('active'); activeTab=this.dataset.tab; applyFilters(); sortCards(); }));
}
function updateRegisteredTabVisibility(){
  let t2=document.getElementById('registeredTab'), ft=document.getElementById('favoritesTab'), nl=document.getElementById('sidebarNotificationsLink');
  if(t2) t2.style.display=currentUser?'inline-flex':'none';
  if(ft) ft.style.display=currentUser?'inline-flex':'none';
  if(nl) nl.style.display=currentUser?'flex':'none';
  if(!currentUser&&(activeTab==='registered'||activeTab==='favorites')){ activeTab='all'; let at=document.querySelector('.pill[data-tab="all"]'); if(at){ document.querySelectorAll('.pill').forEach(p=>p.classList.remove('active')); at.classList.add('active'); } applyFilters(); }
}
function updateGuestBannerAndButtons(){ let b=document.getElementById('guestBanner'); if(b) b.style.display=currentUser?'none':'flex'; }
function updateContactFormAccess(){
  let cc=document.getElementById('contactFormCard'), go=document.getElementById('contactGuestOverlay'), inp=cc.querySelectorAll('input, textarea, button');
  if(!currentUser){ go.classList.add('active'); inp.forEach(i=>i.disabled=true); }
  else { go.classList.remove('active'); inp.forEach(i=>i.disabled=false); document.getElementById('contactName').value=currentUser.name; document.getElementById('contactEmail').value=currentUser.email; }
}
async function sendContact(e){
  e.preventDefault();
  if(!currentUser){ toast('🔐','Login required'); openLoginModal(); return; }
  try{
    let r=await fetch('/messages',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify({name:document.getElementById('contactName').value,email:document.getElementById('contactEmail').value,message:document.getElementById('contactMessage').value,subject:'Contact Form Message',user_id:currentUser.id})});
    if(r.ok){ toast('📧','Message sent!'); document.getElementById('contactMessage').value=''; }
  }catch(e){ toast('❌','Network error'); }
}
function openLoginModal(){ document.getElementById('loginModal').classList.add('active'); }
function closeLoginModal(){ document.getElementById('loginModal').classList.remove('active'); }
function openRegisterUserModal(){ document.getElementById('registerUserModal').classList.add('active'); }
function closeRegisterUserModal(){ document.getElementById('registerUserModal').classList.remove('active'); }
function switchToRegister(){ closeLoginModal(); openRegisterUserModal(); }
function switchToLogin(){ closeRegisterUserModal(); openLoginModal(); }
function setTab(t){ let p=document.querySelector(`.pill[data-tab="${t}"]`); if(p) p.click(); document.getElementById('events-section').scrollIntoView({behavior:'smooth'}); }
function toggleSidebar(){ let s=document.getElementById('sidebar'), o=document.getElementById('sidebarOverlay'); if(s.classList.contains('open')) closeSidebar(); else { s.classList.add('open'); o.classList.add('active'); } }
function closeSidebar(){ document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebarOverlay').classList.remove('active'); }
function navigateTo(sid){ closeSidebar(); let el=document.getElementById(sid); if(el) el.scrollIntoView({behavior:'smooth'}); }
function toggleTheme(){
  let theme=document.documentElement.getAttribute('data-theme')==='dark'?'light':'dark';
  document.documentElement.setAttribute('data-theme',theme); localStorage.setItem('eh-theme',theme);
  let icon=document.getElementById('sidebarThemeIcon'), lbl=document.getElementById('sidebarThemeLabel');
  if(theme==='dark'){ icon.className='bi bi-moon-stars'; lbl.textContent='Dark Mode'; }
  else{ icon.className='bi bi-sun'; lbl.textContent='Light Mode'; }
}
function loadUserNotifications(){
  if(!currentUser) return;
  fetch(`/notifications?user_id=${currentUser.id}`,{headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')}}).then(r=>r.json()).then(data=>{
    let notifs=Array.isArray(data)?data:(data.notifications||data.data||[]);
    let unread=notifs.filter(n=>!n.is_read).length;
    let badge=document.getElementById('notifCountBadge');
    if(badge){ badge.style.display=unread>0?'inline-block':'none'; badge.textContent=unread>99?'99+':unread; }
    let cont=document.getElementById('notificationsListContainer');
    if(cont) cont.innerHTML=notifs.length?notifs.map(n=>`<div style="padding:10px12px;border-bottom:1px solid var(--border2);${!n.is_read?'background:var(--accent-dim);':''}" onclick="markNotificationAsRead(${n.id})"><div style="font-weight:600;font-size:12px;color:var(--accent);">${escapeHtml(n.title)}</div><div style="font-size:11px;color:var(--txt2);margin-top:4px;">${escapeHtml(n.message.substring(0,80))}</div></div>`).join(''):'<div style="color:var(--txt2);padding:12px;">No notifications</div>';
  }).catch(e=>console.warn);
}
function markNotificationAsRead(id){ fetch(`/notifications/${id}/read`,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')}}).then(()=>loadUserNotifications()); }
function markAllNotificationsAsRead(){ if(!currentUser) return; fetch('/notifications/read-all',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),'Content-Type':'application/json'},body:JSON.stringify({user_id:currentUser.id})}).then(()=>loadUserNotifications()); }
function toggleNotificationsSidebar(){ let dd=document.getElementById('notificationsDropdown'); if(dd.style.display==='none'||dd.style.display===''){ dd.style.display='block'; loadUserNotifications(); }else dd.style.display='none'; }

function openProfileModal(){
  if(!currentUser) return;
  document.getElementById('profileAvatar').textContent=(currentUser.name[0]+(currentUser.name.split(' ')[1]?.[0]||'')).toUpperCase();
  document.getElementById('profileHeadSub').textContent=currentUser.email;
  document.getElementById('profileNameDisplay').textContent=currentUser.name;
  document.getElementById('profileEmailDisplay').textContent=currentUser.email;
  document.getElementById('profileNameValue').textContent=currentUser.name;
  document.getElementById('profileEmailValue').textContent=currentUser.email;
  document.getElementById('profileStatRegs').textContent=getAllRegistrationsForUser(currentUser.id).length;
  let fu=users.find(u=>u.id===currentUser.id);
  if(fu&&fu.joinedAt) document.getElementById('profileStatMember').textContent=new Date(fu.joinedAt).toLocaleDateString('en-US',{month:'short',year:'numeric'});
  else document.getElementById('profileStatMember').textContent=new Date().toLocaleDateString('en-US',{month:'short',year:'numeric'});
  ['name','email','password'].forEach(f=>hideEditForm(f));
  renderProfileEvents();
  document.getElementById('profileModal').classList.add('active');
}
function closeProfileModal(){ document.getElementById('profileModal').classList.remove('active'); }
function renderProfileEvents(){
  let l=document.getElementById('profileEventList'), regs=getAllRegistrationsForUser(currentUser.id);
  if(!regs.length){ l.innerHTML='<p style="text-align:center;color:var(--txt3);padding:20px;">No events registered yet</p>'; return; }
  let items=[];
  document.querySelectorAll('.event-card').forEach(c=>{ let id=parseInt(c.dataset.id), date=c.dataset.date; if(regs.includes(id)&&!isEventFinished(date)) items.push({id,title:c.dataset.fullTitle,date}); });
  if(items.length===0){ l.innerHTML='<p style="text-align:center;color:var(--txt3);padding:20px;">No upcoming events</p>'; return; }
  l.innerHTML=items.map(e=>`<div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--bg4);border-radius:12px;margin-bottom:8px;"><span style="color:var(--green);">●</span><span style="color:var(--txt);font-weight:600;">${escapeHtml(e.title)}</span><span style="color:var(--txt2);font-size:13px;margin-left:auto;">${new Date(e.date+'T00:00:00').toLocaleDateString()}</span></div>`).join('');
}
function showEditForm(f){ document.getElementById(f+'EditForm').style.display='block'; document.getElementById(f+'DisplayRow').style.display='none'; if(f==='name') document.getElementById('editNameInput').value=currentUser.name; if(f==='email') document.getElementById('editEmailInput').value=currentUser.email; if(f==='password'){ document.getElementById('editCurrentPass').value=''; document.getElementById('editNewPass').value=''; document.getElementById('editConfirmPass').value=''; } }
function hideEditForm(f){ document.getElementById(f+'EditForm').style.display='none'; document.getElementById(f+'DisplayRow').style.display='flex'; }
function saveName(){
  let n=document.getElementById('editNameInput').value.trim();
  if(!n){ toast('⚠️','Full name required'); return; }
  let i=users.findIndex(u=>u.id===currentUser.id);
  if(i>-1){ users[i].name=n; localStorage.setItem('eventhub_users',JSON.stringify(users)); }
  currentUser.name=n; localStorage.setItem('eventhub_current_user',JSON.stringify(currentUser));
  document.getElementById('profileNameDisplay').textContent=n; document.getElementById('profileNameValue').textContent=n;
  document.getElementById('profileAvatar').textContent=(n[0]+(n.split(' ')[1]?.[0]||'')).toUpperCase();
  document.getElementById('sidebarAvatar').textContent=(n[0]+(n.split(' ')[1]?.[0]||'')).toUpperCase();
  document.getElementById('sidebarUserName').textContent=n;
  hideEditForm('name'); updateDashboard(); toast('✅','Name updated!'); updateContactFormAccess();
}
function saveEmail(){
  let e2=document.getElementById('editEmailInput').value.trim();
  if(!e2||!e2.includes('@')){ toast('⚠️','Valid email required'); return; }
  if(users.find(u=>u.email===e2&&u.id!==currentUser.id)){ toast('⚠️','Email already in use'); return; }
  let i=users.findIndex(u=>u.id===currentUser.id);
  if(i>-1){ users[i].email=e2; localStorage.setItem('eventhub_users',JSON.stringify(users)); }
  currentUser.email=e2; localStorage.setItem('eventhub_current_user',JSON.stringify(currentUser));
  document.getElementById('profileEmailDisplay').textContent=e2; document.getElementById('profileEmailValue').textContent=e2; document.getElementById('profileHeadSub').textContent=e2; document.getElementById('sidebarUserEmail').textContent=e2;
  hideEditForm('email'); toast('✅','Email updated!'); updateContactFormAccess();
}
function savePassword(){
  let cur=document.getElementById('editCurrentPass').value, np=document.getElementById('editNewPass').value, cf=document.getElementById('editConfirmPass').value, fu=users.find(u=>u.id===currentUser.id);
  if(!fu||fu.password!==cur){ toast('❌','Current password is incorrect'); return; }
  if(np.length<6){ toast('⚠️','Password must be at least 6 characters'); return; }
  if(np!==cf){ toast('⚠️','Passwords do not match'); return; }
  let i=users.findIndex(u=>u.id===currentUser.id);
  if(i>-1){ users[i].password=np; localStorage.setItem('eventhub_users',JSON.stringify(users)); }
  hideEditForm('password'); toast('✅','Password updated!');
}
function logoutFromProfile(){ closeProfileModal(); logout(); }
function openReviewsModal(eid,title){
  if(!currentUser||!registered.includes(parseInt(eid))){ toast('🔒','You must register for this event first!'); return; }
  currentReviewEventId=parseInt(eid);
  document.getElementById('reviewsModalTitle').innerHTML='Rate & Review: '+title;
  document.getElementById('reviewsModal').classList.add('active');
  loadEventReviews(eid);
  document.getElementById('reviewAuthRequired').style.display='none';
  document.getElementById('reviewRegisterRequired').style.display='none';
  document.getElementById('reviewAlreadySubmitted').style.display='none';
  if(currentUser&&registered.includes(currentReviewEventId)) document.getElementById('reviewFormContainer').style.display='block';
  else document.getElementById('reviewFormContainer').style.display='none';
  document.getElementById('reviewRating').value=0; document.getElementById('reviewComment').value='';
  document.querySelectorAll('.star-rating').forEach(s=>s.classList.remove('active'));
}
function closeReviewsModal(){ document.getElementById('reviewsModal').classList.remove('active'); }
async function loadEventReviews(eid){
  try{
    let r=await fetch(`/events/${eid}/reviews`), reviews=await r.json(), container=document.getElementById('reviewsList');
    if(!reviews.length){ container.innerHTML='<div style="color:var(--txt2);padding:16px;">No reviews yet. Be the first to review!</div>'; return; }
    let avg=reviews.reduce((s,r)=>s+r.rating,0)/reviews.length;
    let rs=document.getElementById('rating-value-'+eid), cs=document.getElementById('rating-count-'+eid);
    if(rs) rs.textContent=avg.toFixed(1); if(cs) cs.textContent=`(${reviews.length})`;
    container.innerHTML=reviews.map(rv=>`<div class="review-item"><div class="review-header"><span class="review-author">${escapeHtml(rv.user_name||'Anonymous')}</span><span class="review-date">${new Date(rv.created_at).toLocaleDateString()}</span></div><div class="review-rating">${'★'.repeat(rv.rating)}${'☆'.repeat(5-rv.rating)}</div><div class="review-comment">${escapeHtml(rv.comment||'')}</div></div>`).join('');
  } catch(e){ document.getElementById('reviewsList').innerHTML='<div style="color:var(--red);padding:12px;">Failed to load reviews</div>'; }
}
async function submitReview(){
  let rating=parseInt(document.getElementById('reviewRating').value), comment=document.getElementById('reviewComment').value.trim();
  if(!rating||rating<1||rating>5){ toast('⭐','Please select a rating (1-5)'); return; }
  if(!comment){ toast('💬','Please write a comment'); return; }
  if(!currentUser){ toast('🔐','Login required'); closeReviewsModal(); openLoginModal(); return; }
  let btn=document.getElementById('submitReviewBtn'), orig=btn.innerHTML;
  btn.disabled=true; btn.innerHTML='...';
  try{
    let r=await fetch('/events/review',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify({event_id:currentReviewEventId,rating,comment,user_id:currentUser.id,user_name:currentUser.name})});
    let d=await r.json();
    if(d.success){ toast('⭐','Thank you for your review!'); closeReviewsModal(); await loadEventReviews(currentReviewEventId); } else toast('❌',d.message||'Failed');
  } catch(e){ toast('❌','Error'); } finally{ btn.disabled=false; btn.innerHTML=orig; }
}
function toggleFavoriteFromDetail(){ if(dgCurrentEventId){ toggleFavorite(dgCurrentEventId); document.getElementById('dgFavIcon').className=favorites.includes(dgCurrentEventId)?'bi bi-heart-fill':'bi bi-heart'; } }
function shareEvent(eid,title){
  if(!currentUser){ toast('🔐','Login required to share events'); openLoginModal(); return; }
  shareEventId=eid; shareEventTitle=title;
  document.getElementById('shareEventTitle').textContent=title;
  const url=`${window.location.origin}/events/${eid}`;
  document.getElementById('shareLinkInput').value=url;
  document.getElementById('shareModal').classList.add('active');
}
function closeShareModal(){ document.getElementById('shareModal').classList.remove('active'); }
function shareViaWhatsApp(){ const url=document.getElementById('shareLinkInput').value; window.open(`https://wa.me/?text=${encodeURIComponent(shareEventTitle+' on EventHub! '+url)}`,'_blank'); closeShareModal(); }
function shareViaTwitter(){ const url=document.getElementById('shareLinkInput').value; window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent('Check out '+shareEventTitle+' on EventHub!')}&url=${encodeURIComponent(url)}`,'_blank'); closeShareModal(); }
function shareViaFacebook(){ const url=document.getElementById('shareLinkInput').value; window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,'_blank'); closeShareModal(); }
function copyShareLink(){ const inp=document.getElementById('shareLinkInput'); inp.select(); document.execCommand('copy'); toast('🔗','Link copied to clipboard!'); }

function getRecommendations(){
  const all=document.querySelectorAll('#eventsGrid .event-card'), cards=Array.from(all).filter(c=>!c.classList.contains('finished'));
  if(!currentUser||registered.length===0){ const shuffled=cards.sort(()=>0.5-Math.random()); renderRecommendations(shuffled.slice(0,5)); return; }
  const cats=new Set();
  registered.forEach(rid=>{ const card=cards.find(c=>parseInt(c.dataset.id)===rid); if(card){ const cat=card.dataset.category; if(cat) cats.add(cat); } });
  let recs=cards.filter(c=>{ const cid=parseInt(c.dataset.id); if(registered.includes(cid)) return false; const cat=c.dataset.category; return cats.size===0||!cat||cats.has(cat); });
  recs.sort((a,b)=>(parseInt(b.dataset.attendees)||0)-(parseInt(a.dataset.attendees)||0));
  renderRecommendations(recs.slice(0,6));
}
function renderRecommendations(recs){
  const c=document.getElementById('recommendationsGrid');
  if(!c) return;
  if(recs.length===0){ c.innerHTML='<div class="empty-state"><i class="bi bi-stars"></i><h3>No recommendations yet</h3><p>Register for more events to get personalized suggestions!</p></div>'; return; }
  c.innerHTML=recs.map(card=>card.outerHTML).join('');
  c.querySelectorAll('.event-card').forEach(clone=>{ const oid=clone.dataset.id, orig=document.querySelector(`#eventsGrid .event-card[data-id="${oid}"]`); if(orig){ clone.onclick=()=>openDetail(orig); const fb=clone.querySelector('.fav-badge'); if(fb) fb.onclick=(e)=>{ e.stopPropagation(); toggleFavorite(parseInt(oid)); }; const rb=clone.querySelector('.btn-reg'); if(rb&&!rb.classList.contains('done')&&!orig.classList.contains('finished')){ rb.onclick=(e)=>{ e.stopPropagation(); const title=orig.dataset.fullTitle, date=orig.dataset.date, start=orig.dataset.startTime, end=orig.dataset.endTime, loc=orig.dataset.location, price=parseFloat(orig.dataset.price)||0; handleCardRegClick(parseInt(oid),title,date,start,end,loc,price); }; } } });
}

// ============================================
// DOM READY
// ============================================
document.addEventListener('DOMContentLoaded', async ()=>{
  let savedTheme=localStorage.getItem('eh-theme');
  if(savedTheme) document.documentElement.setAttribute('data-theme',savedTheme);
  await loadUserSession();
  setupFilters(); sortCards(); updateRegisteredTabVisibility(); updateGuestBannerAndButtons(); updateContactFormAccess(); refreshAllFavBadgeStates(); updateFinishedEvents();
  
  setTimeout(initCarousel, 800);
  
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {}, 250);
  });
  
  document.querySelectorAll('.card-rating-mini').forEach(star=>{
    star.removeEventListener('click',star._handler);
    star._handler=function(e){ e.stopPropagation(); if(this.classList.contains('disabled')){ if(!currentUser) toast('🔐','Login required'); else toast('📝','You must register for this event first'); return; } let card=this.closest('.event-card'); if(card){ let id=card.dataset.id, title=card.dataset.fullTitle; if(id&&!isNaN(parseInt(id))) openReviewsModal(parseInt(id),title); } };
    star.addEventListener('click',star._handler);
  });
  document.getElementById('ratingStars')?.addEventListener('click',function(e){ let star=e.target.closest('.star-rating'); if(star){ let val=parseInt(star.dataset.value); document.getElementById('reviewRating').value=val; document.querySelectorAll('.star-rating').forEach((s,idx)=>{ if(idx<val) s.classList.add('active'); else s.classList.remove('active'); }); } });
  document.querySelectorAll('.modal-overlay, .detail-overlay').forEach(o=>{ o.addEventListener('click',function(e){ if(e.target===this) this.classList.remove('active'); }); });
  document.addEventListener('keydown',e=>{ if(e.key==='Escape'){ document.querySelectorAll('.modal-overlay.active, .detail-overlay.active').forEach(m=>m.classList.remove('active')); closeSidebar(); } });
});
</script>
</body>
</html>