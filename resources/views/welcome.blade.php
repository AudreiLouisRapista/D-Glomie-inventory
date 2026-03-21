<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOME Inventory Management — Login</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --maroon: #6b0f1a;
            --maroon-dark: #4a0a12;
            --maroon-light: #8b1a28;
            --red: #c0392b;
            --red-light: #e74c3c;
            --yellow: #f0c040;
            --yellow-light: #f5d060;
            --cream: #fdf6f0;
            --text-dark: #1a0a0d;
            --text-mid: #6b2d37;
            --text-muted: #a07070;
            --border: rgba(107, 15, 26, 0.12);
            --input-bg: rgba(107, 15, 26, 0.04);
        }

        html,
        body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
        }

        .page {
            display: flex;
            min-height: 100vh;
        }

        /* ── Left Panel ── */
        .left-panel {
            width: 550px;
            flex-shrink: 0;
            background: var(--maroon-dark);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 52px 48px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            border: 60px solid rgba(240, 192, 64, 0.07);
            bottom: -140px;
            right: -140px;
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 40px solid rgba(192, 57, 43, 0.12);
            top: -60px;
            left: -60px;
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            animation: fadeUp 0.5s ease both;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--yellow);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            color: var(--maroon-dark);
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: white;
            letter-spacing: 0.5px;
        }

        .brand-name span {
            color: var(--yellow);
        }

        .left-center {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 0 32px;
        }

        .left-tag {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--yellow);
            margin-bottom: 20px;
            animation: fadeUp 0.5s 0.1s ease both;
        }

        .left-headline {
            font-family: 'Syne', sans-serif;
            font-size: 38px;
            font-weight: 800;
            line-height: 1.1;
            color: white;
            margin-bottom: 20px;
            animation: fadeUp 0.5s 0.15s ease both;
        }

        .left-headline em {
            font-style: normal;
            color: var(--yellow);
        }

        .left-desc {
            font-size: 13px;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.45);
            max-width: 300px;
            margin-bottom: 44px;
            animation: fadeUp 0.5s 0.2s ease both;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            animation: fadeUp 0.5s ease both;
        }

        .feature-item:nth-child(1) {
            animation-delay: 0.25s;
        }

        .feature-item:nth-child(2) {
            animation-delay: 0.32s;
        }

        .feature-item:nth-child(3) {
            animation-delay: 0.39s;
        }

        .feature-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--yellow);
            flex-shrink: 0;
            margin-top: 6px;
        }

        .feature-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            line-height: 1.5;
        }

        .feature-text strong {
            display: block;
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .left-footer {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.2);
            letter-spacing: 0.5px;
            animation: fadeUp 0.5s 0.5s ease both;
        }

        /* ── Right Panel ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--cream);
            position: relative;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--maroon), var(--red), var(--yellow));
        }

        .form-wrap {
            width: 100%;
            max-width: 380px;
        }

        .accent-bar {
            display: flex;
            gap: 4px;
            margin-bottom: 32px;
            animation: fadeUp 0.5s ease both;
        }

        .accent-bar span {
            height: 3px;
            border-radius: 2px;
        }

        .accent-bar .bar-1 {
            width: 32px;
            background: var(--maroon);
        }

        .accent-bar .bar-2 {
            width: 16px;
            background: var(--red);
        }

        .accent-bar .bar-3 {
            width: 8px;
            background: var(--yellow);
        }

        .form-header {
            margin-bottom: 36px;
            animation: fadeUp 0.5s 0.1s ease both;
        }

        .form-eyebrow {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: 12px;
        }

        .form-title {
            font-family: 'Syne', sans-serif;
            font-size: 34px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Fields */
        .field {
            margin-bottom: 18px;
            animation: fadeUp 0.5s ease both;
        }

        .field:nth-child(1) {
            animation-delay: 0.2s;
        }

        .field:nth-child(2) {
            animation-delay: 0.28s;
        }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-mid);
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field-input::placeholder {
            color: #c9a8ad;
        }

        .field-input:focus {
            border-color: var(--maroon-light);
            box-shadow: 0 0 0 3px rgba(107, 15, 26, 0.08);
        }

        .field-wrap:focus-within .field-icon {
            color: var(--maroon-light);
        }

        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 13px;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }

        .toggle-pass:hover {
            color: var(--maroon);
        }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            animation: fadeUp 0.5s 0.36s ease both;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        .remember input[type="checkbox"] {
            appearance: none;
            width: 15px;
            height: 15px;
            border: 1.5px solid var(--border);
            border-radius: 4px;
            background: white;
            cursor: pointer;
            position: relative;
            transition: background 0.2s, border-color 0.2s;
            flex-shrink: 0;
        }

        .remember input[type="checkbox"]:checked {
            background: var(--maroon);
            border-color: var(--maroon);
        }

        .remember input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 1px;
            width: 5px;
            height: 8px;
            border: 2px solid white;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .forgot-link {
            font-size: 12px;
            color: var(--red);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .forgot-link:hover {
            opacity: 0.7;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--maroon-dark);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.4px;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            animation: fadeUp 0.5s 0.42s ease both;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--red), var(--yellow));
        }

        .btn-login:hover {
            background: var(--maroon);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0 20px;
            animation: fadeUp 0.5s 0.5s ease both;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider span {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .socials {
            display: flex;
            gap: 10px;
            animation: fadeUp 0.5s 0.56s ease both;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 8px;
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            color: var(--text-mid);
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, color 0.2s;
        }

        .social-btn:hover {
            border-color: var(--maroon-light);
            color: var(--maroon);
        }

        .form-footer {
            margin-top: 36px;
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.3px;
            animation: fadeUp 0.5s 0.62s ease both;
        }

        .alert {
            padding: 11px 14px;
            border-radius: 9px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-danger {
            background: rgba(192, 57, 43, 0.08);
            border-color: rgba(192, 57, 43, 0.2);
            color: var(--red);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 860px) {
            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 60px 32px;
            }
        }

        @media (max-width: 480px) {
            .right-panel {
                padding: 48px 24px;
            }

            .form-title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

    <div class="page">

        <!-- Left Panel -->
        <div class="left-panel">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="brand-name">LOME <span>IMS</span></div>
            </div>

            <div class="left-center">
                <div class="left-tag">Inventory Management System</div>
                <h1 class="left-headline">
                    Stock smart.<br>Manage <em>better.</em>
                </h1>
                <p class="left-desc">
                    A centralized platform to track, manage, and optimize
                    your inventory — keeping your operations accurate and efficient every day.
                </p>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-dot"></div>
                        <div class="feature-text">
                            <strong>Real-time Stock Tracking</strong>
                            Monitor inventory levels across all categories instantly.
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-dot"></div>
                        <div class="feature-text">
                            <strong>Product Management</strong>
                            Organize products by category, type, and bundle size.
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-dot"></div>
                        <div class="feature-text">
                            <strong>Stock Reports & Alerts</strong>
                            Clear insights on stock levels and out-of-stock warnings.
                        </div>
                    </div>
                </div>
            </div>

            <div class="left-footer">
                &copy; 2025 LOME Inventory Management System
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="form-wrap">

                <div class="accent-bar">
                    <span class="bar-1"></span>
                    <span class="bar-2"></span>
                    <span class="bar-3"></span>
                </div>

                <div class="form-header">
                    <div class="form-eyebrow">Secure Access</div>
                    <h2 class="form-title">Sign in to<br>your account.</h2>
                    <p class="form-subtitle">Enter your credentials to continue.</p>
                </div>

                {{-- @include('layout.partials.alerts') --}}

                <form action="{{ route('auth_user') }}" method="POST">
                    @csrf

                    <div class="field">
                        <label class="field-label">Email Address</label>
                        <div class="field-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input type="email" name="email" class="field-input" placeholder="you@example.com"
                                required value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Password</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" name="password" id="passwordField" class="field-input"
                                placeholder="••••••••" required>
                            <button type="button" class="toggle-pass" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="meta-row">
                        <label class="remember">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        Sign In &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="divider">
                    <span>connect with developer</span>
                </div>

                <div class="socials">
                    <a href="https://www.facebook.com/al.rapista" class="social-btn" target="_blank">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://www.instagram.com/a.lmongs/" class="social-btn" target="_blank">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                    <a href="https://www.tiktok.com/@audreil43" class="social-btn" target="_blank">
                        <i class="fab fa-tiktok"></i> TikTok
                    </a>
                </div>

                <div class="form-footer">
                    LOME Inventory Management System &copy; 2025
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordField');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>

</html>
