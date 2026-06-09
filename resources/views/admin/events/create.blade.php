<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - EventHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:wght@700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-width: 280px;
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #ede9fd;
            --accent: #f97316;
            --bg: #f5f4f9;
            --surface: #ffffff;
            --text: #1a1730;
            --muted: #7c7a8d;
            --border: #e4e2ef;
            --danger: #ef4444;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            color: var(--text);
        }

        /* =====================
           SIDEBAR  (from Events Table page)
        ===================== */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 100;
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 0;
        }

        .logo {
            padding: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            gap: 10px;
            margin: 0;
        }

.logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }

       

        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-size: 18px;
            line-height: 1;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(102, 126, 234, 0.4);
            border-color: #667eea;
        }

        .sidebar-open-btn {
            position: fixed;
            top: 20px;
            left: 15px;
            z-index: 200;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.2s ease;
        }

        .sidebar-open-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .sidebar-open-btn.visible {
            display: flex;
        }

        .nav-menu {
            list-style: none;
            padding: 20px 0;
            flex: 1;
            min-width: var(--sidebar-width);
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 30px;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-left-color: #667eea;
        }

        .nav-link.active {
            background: rgba(102, 126, 234, 0.1);
            color: #fff;
            border-left-color: #667eea;
        }

        .nav-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        /* =====================
           MAIN CONTENT
        ===================== */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* =====================
           TOPBAR
        ===================== */
        .topbar {
            background: var(--surface);
            padding: 18px 36px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar-left h2 {
            color: var(--text);
            font-family: 'Fraunces', serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .breadcrumb-custom {
            color: var(--muted);
            font-size: 13px;
            margin-top: 3px;
        }

        .breadcrumb-custom a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            color: var(--text);
            font-weight: 600;
            font-size: 13px;
        }

        .user-role {
            color: var(--muted);
            font-size: 11px;
        }

        .btn-logout {
            padding: 10px 20px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* =====================
           PAGE CONTENT
        ===================== */
        .content-area {
            padding: 36px;
            flex: 1;
        }

        .page-container {
            max-width: 780px;
            margin: 0 auto;
        }

        .form-card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            padding: 32px 36px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -60px; right: 60px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .card-header h1 {
            font-family: 'Fraunces', serif;
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            color: rgba(255,255,255,0.75);
            font-size: 14px;
            margin-top: 6px;
            position: relative;
            z-index: 1;
        }

        .form-body {
            padding: 36px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
            font-size: 13px;
        }

        .required {
            color: var(--accent);
            margin-left: 2px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        textarea,
        select {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: #fafaf9;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        input::placeholder,
        textarea::placeholder {
            color: #bbb;
        }

        textarea {
            resize: vertical;
            min-height: 110px;
        }

        .helper-text {
            font-size: 12px;
            color: var(--muted);
            margin-top: 5px;
        }

        /* File Input */
        .file-input-wrapper {
            position: relative;
        }

        .file-drop-area {
            border: 2px dashed var(--border);
            border-radius: 9px;
            padding: 24px 16px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            background: #fafaf9;
        }

        .file-drop-area:hover,
        .file-drop-area.dragover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .file-drop-area input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-drop-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .file-drop-text {
            font-size: 13px;
            color: var(--muted);
        }

        .file-drop-text strong {
            color: var(--primary);
            font-weight: 600;
        }

        .image-preview {
            margin-top: 12px;
            border-radius: 10px;
            overflow: hidden;
            display: none;
            border: 1.5px solid var(--border);
        }

        .image-preview img {
            width: 100%;
            max-height: 240px;
            object-fit: cover;
            display: block;
        }

        /* Form Section Divider */
        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 28px 0;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 18px;
        }

        /* Buttons */
        .btn-container {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .btn-primary {
            flex: 1;
            padding: 13px 24px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            padding: 13px 24px;
            background: transparent;
            color: var(--muted);
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: var(--bg);
            color: var(--text);
            border-color: #ccc;
        }

        /* Error & validation styles */
        .field-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 5px;
            display: none;
        }

        input.invalid,
        textarea.invalid,
        select.invalid {
            border-color: var(--danger);
        }

        /* =====================
           RESPONSIVE
        ===================== */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            .topbar {
                padding: 14px 18px;
            }
            .user-details {
                display: none;
            }
            .content-area {
                padding: 18px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .form-body {
                padding: 24px;
            }
            .card-header {
                padding: 24px;
            }
            .card-header h1 {
                font-size: 22px;
            }
            .btn-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Open sidebar button (visible when collapsed) -->
    <button class="sidebar-open-btn" id="sidebarOpenBtn" onclick="openSidebar()" title="Open Sidebar">
        &#9776;
    </button>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <h1>
                <span class="logo-icon">🎯</span>
                <span>EventHub</span>
            </h1>
            <button class="sidebar-toggle-btn" onclick="closeSidebar()" title="Hide Sidebar">&#171;</button>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <span class="nav-icon">📊</span><span>Dashboard</span>
            </a>
        </li>
            <li class="nav-item">
                <a href="{{ route('admin.events.index') }}" class="nav-link">
                    <span class="nav-icon">🎫</span><span>Events</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.admins.index') }}" class="nav-link">
                    <span class="nav-icon">👥</span><span>Admins</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <span class="nav-icon">🙋</span><span>Users</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content" id="mainContent">

        <!-- Topbar -->
        <div class="topbar">
            <div style="display:flex; align-items:center; gap:15px;">
                <div class="topbar-left">
                    <h2>Create Event</h2>
                    <div class="breadcrumb-custom">
                        <a href="{{ route('admin.dashboard') }}">Home</a> / <a href="{{ route('admin.events.index') }}">Events</a> / Create
                    </div>
                </div>
            </div>
            <div class="topbar-right">
                <div class="user-info">
                    <div class="user-avatar">SA</div>
                    <div class="user-details">
                        <span class="user-name">Super Admin</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            <div class="page-container">
                <div class="form-card">

                    <div class="card-header">
                        <h1>✨ Create New Event</h1>
                        <p>Fill in the details below to publish your event</p>
                    </div>

                    <div class="form-body">
                        <form id="eventForm" action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            {{-- Pass real authenticated admin ID from backend --}}
                            <input type="hidden" name="admin_id" value="{{ auth('admin')->id() }}">

                            <!-- Basic Info -->
                            <p class="section-title">Basic Information</p>

                            <div class="form-group">
                                <label for="title">Event Title <span class="required">*</span></label>
                                <input type="text" id="title" name="title" required
                                       value="{{ old('title') }}"
                                       placeholder="e.g. Annual Tech Summit 2025"
                                       maxlength="255">
                                @error('title')
                                    <div class="field-error" style="display:block;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span class="required">*</span></label>
                                <textarea id="description" name="description" required
                                          placeholder="Describe your event — what attendees can expect, highlights, agenda…">{{ old('description') }}</textarea>
                                <div class="helper-text">Minimum 20 characters. The more detail, the better.</div>
                                @error('description')
                                    <div class="field-error" style="display:block;">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="section-divider">
                            <p class="section-title">Date, Time & Location</p>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="event_date">Event Date <span class="required">*</span></label>
                                    <input type="date" id="event_date" name="event_date" required
                                           value="{{ old('event_date') }}">
                                    @error('event_date')
                                        <div class="field-error" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="location">Location <span class="required">*</span></label>
                                    <input type="text" id="location" name="location" required
                                           value="{{ old('location') }}"
                                           placeholder="Venue name or full address"
                                           maxlength="255">
                                    @error('location')
                                        <div class="field-error" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_time">Start Time <span class="required">*</span></label>
                                    <input type="time" id="start_time" name="start_time" required
                                           value="{{ old('start_time') }}">
                                    @error('start_time')
                                        <div class="field-error" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="end_time">End Time <span class="required">*</span></label>
                                    <input type="time" id="end_time" name="end_time" required
                                           value="{{ old('end_time') }}">
                                    <div class="field-error" id="timeError">End time must be after start time.</div>
                                    @error('end_time')
                                        <div class="field-error" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="section-divider">
                            <p class="section-title">Ticketing & Media</p>

                            <div class="form-group">
                                <label for="price">Ticket Price (USD) <span class="required">*</span></label>
                                <input type="number" id="price" name="price"
                                       step="0.01" min="0" max="99999.99" required
                                       value="{{ old('price', '0.00') }}"
                                       placeholder="0.00">
                                <div class="helper-text">Enter 0 for free events.</div>
                                @error('price')
                                    <div class="field-error" style="display:block;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Event Banner Image <span class="required">*</span></label>
                                <div class="file-input-wrapper">
                                    <div class="file-drop-area" id="fileDropArea">
                                        <input type="file" id="image" name="image"
                                               accept="image/jpeg,image/png,image/webp,image/gif"
                                               required>
                                        <div class="file-drop-icon">🖼️</div>
                                        <div class="file-drop-text">
                                            <strong>Click to upload</strong> or drag & drop<br>
                                            JPG, PNG, WEBP — max 5 MB
                                        </div>
                                    </div>
                                    <div class="image-preview" id="imagePreview">
                                        <img id="previewImg" src="" alt="Preview">
                                    </div>
                                </div>
                                @error('image')
                                    <div class="field-error" style="display:block;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Actions -->
                            <div class="btn-container">
                                <button type="submit" class="btn-primary">🚀 Create Event</button>
                                <button type="button" class="btn-secondary" onclick="window.history.back()">Cancel</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.main-content -->

    <script>
        /* ---- Sidebar ---- */
        const sidebar        = document.getElementById('sidebar');
        const mainContent    = document.getElementById('mainContent');
        const sidebarOpenBtn = document.getElementById('sidebarOpenBtn');

        function closeSidebar() {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            sidebarOpenBtn.classList.add('visible');
        }

        function openSidebar() {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
            sidebarOpenBtn.classList.remove('visible');
        }

        /* ---- Min date = today ---- */
        document.getElementById('event_date').setAttribute(
            'min', new Date().toISOString().split('T')[0]
        );

        /* ---- Image preview + drag-over feedback ---- */
        const imageInput   = document.getElementById('image');
        const previewBox   = document.getElementById('imagePreview');
        const previewImg   = document.getElementById('previewImg');
        const fileDropArea = document.getElementById('fileDropArea');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewBox.style.display = 'block';
            };
            reader.readAsDataURL(file);
            fileDropArea.querySelector('.file-drop-text').innerHTML =
                `<strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        });

        fileDropArea.addEventListener('dragover',  e => { e.preventDefault(); fileDropArea.classList.add('dragover'); });
        fileDropArea.addEventListener('dragleave', () => fileDropArea.classList.remove('dragover'));
        fileDropArea.addEventListener('drop',      e => { e.preventDefault(); fileDropArea.classList.remove('dragover'); });

        /* ---- Client-side validation on submit ---- */
        document.getElementById('eventForm').addEventListener('submit', function (e) {
            const startTime = document.getElementById('start_time').value;
            const endTime   = document.getElementById('end_time').value;
            const timeError = document.getElementById('timeError');

            if (startTime && endTime && startTime >= endTime) {
                e.preventDefault();
                document.getElementById('end_time').classList.add('invalid');
                timeError.style.display = 'block';
                document.getElementById('end_time').scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                document.getElementById('end_time').classList.remove('invalid');
                timeError.style.display = 'none';
            }
        });

        document.getElementById('end_time').addEventListener('change', function () {
            document.getElementById('timeError').style.display = 'none';
            this.classList.remove('invalid');
        });
    </script>
</body>
</html>