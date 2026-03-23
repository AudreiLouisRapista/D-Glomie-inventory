<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=DM+Sans:wght@300;400;500&display=swap"
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
            --maroon-deep: #2e0509;
            --red: #c0392b;
            --yellow: #f0c040;
            --cream: #fdf6f0;
            --text-dark: #1a0a0d;
            --text-mid: #6b2d37;
            --text-muted: #a07070;
            --border: rgba(107, 15, 26, 0.1);
        }

        html,
        body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--maroon-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        /* ── Ambient background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 50%, rgba(107, 15, 26, 0.6) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 50%, rgba(74, 10, 18, 0.4) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* Subtle dot grid */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Card ── */
        .card {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            max-width: 920px;
            min-height: 560px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 40px 80px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.06);
            animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Left Panel ── */
        .left {
            width: 45%;
            flex-shrink: 0;
            background: linear-gradient(145deg, #6b0f1a 0%, #4a0a12 50%, #2e0509 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 44px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative rings */
        .left::before {
            content: '';
            position: absolute;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            border: 1px solid rgba(240, 192, 64, 0.12);
            bottom: -120px;
            right: -120px;
            pointer-events: none;
        }

        .left::after {
            content: '';
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            border: 1px solid rgba(240, 192, 64, 0.08);
            bottom: -60px;
            right: -60px;
            pointer-events: none;
        }

        /* Gold top accent line */
        .left-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--yellow), rgba(240, 192, 64, 0.3), transparent);
        }

        /* Brand */
        .brand {
            position: relative;
            z-index: 1;
            animation: fadeUp 0.5s 0.2s ease both;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 900;
            color: white;
            line-height: 1;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }

        .brand-logo span {
            color: var(--yellow);
        }

        .brand-sub {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.45);
        }

        /* Center text */
        .left-body {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 32px 0;
        }

        .left-tagline {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: white;
            line-height: 1.3;
            margin-bottom: 16px;
            animation: fadeUp 0.5s 0.3s ease both;
        }

        .left-tagline em {
            font-style: italic;
            color: var(--yellow);
        }

        .left-desc {
            font-size: 13px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.4);
            max-width: 280px;
            animation: fadeUp 0.5s 0.35s ease both;
        }

        /* Stats */
        .left-stats {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 0;
            animation: fadeUp 0.5s 0.45s ease both;
        }

        .stat {
            flex: 1;
            padding-right: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .stat:last-child {
            border-right: none;
            padding-right: 0;
            padding-left: 20px;
        }

        .stat:not(:first-child):not(:last-child) {
            padding-left: 20px;
        }

        .stat-val {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--yellow);
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 10px;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.3);
        }

        /* ── Right Panel ── */
        .right {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 52px 48px;
            position: relative;
        }

        /* Yellow bottom accent */
        .right::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--red), var(--yellow));
        }

        .form-wrap {
            width: 100%;
            max-width: 340px;
        }

        /* Accent bar */
        .accent-bar {
            display: flex;
            gap: 5px;
            margin-bottom: 28px;
            animation: fadeUp 0.5s 0.1s ease both;
        }

        .accent-bar span {
            height: 3px;
            border-radius: 2px;
        }

        .bar-1 {
            width: 36px;
            background: var(--maroon-dark);
        }

        .bar-2 {
            width: 18px;
            background: var(--red);
        }

        .bar-3 {
            width: 9px;
            background: var(--yellow);
        }

        /* Form header */
        .form-header {
            margin-bottom: 32px;
            animation: fadeUp 0.5s 0.15s ease both;
        }

        .form-eyebrow {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: 10px;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.15;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Fields */
        .field {
            margin-bottom: 16px;
            animation: fadeUp 0.5s ease both;
        }

        .field:nth-child(1) {
            animation-delay: 0.2s;
        }

        .field:nth-child(2) {
            animation-delay: 0.27s;
        }

        .field-label {
            display: block;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-mid);
            margin-bottom: 7px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            background: var(--cream);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field-input::placeholder {
            color: #c9a8ad;
        }

        .field-input:focus {
            border-color: var(--maroon);
            background: white;
            box-shadow: 0 0 0 3px rgba(107, 15, 26, 0.07);
        }

        .field-wrap:focus-within .field-icon {
            color: var(--maroon);
        }

        .toggle-pass {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 12px;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }

        .toggle-pass:hover {
            color: var(--maroon);
        }

        /* Meta row */
        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            animation: fadeUp 0.5s 0.34s ease both;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        .remember input[type="checkbox"] {
            appearance: none;
            width: 14px;
            height: 14px;
            border: 1.5px solid var(--border);
            border-radius: 3px;
            background: var(--cream);
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .remember input[type="checkbox"]:checked {
            background: var(--maroon-dark);
            border-color: var(--maroon-dark);
        }

        .remember input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 2px;
            top: 0px;
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
            opacity: 0.65;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--maroon-dark);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Playfair Display', serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            animation: fadeUp 0.5s 0.4s ease both;
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

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 24px 0 18px;
            animation: fadeUp 0.5s 0.48s ease both;
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

        /* Socials */
        .socials {
            display: flex;
            gap: 8px;
            animation: fadeUp 0.5s 0.54s ease both;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 6px;
            background: var(--cream);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--text-mid);
            font-size: 11px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .social-btn:hover {
            border-color: var(--maroon);
            color: var(--maroon);
            background: white;
        }

        /* Footer */
        .form-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            animation: fadeUp 0.5s 0.6s ease both;
        }

        /* Alert */
        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
            border: 1px solid transparent;
        }

        .alert-danger {
            background: rgba(192, 57, 43, 0.07);
            border-color: rgba(192, 57, 43, 0.18);
            color: var(--red);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Responsive ── */
        @media (max-width: 760px) {
            .left {
                display: none;
            }

            .right {
                padding: 52px 32px;
            }

            .card {
                border-radius: 20px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
            }

            .card {
                border-radius: 0;
                min-height: 100vh;
            }

            .right {
                padding: 48px 28px;
                align-items: flex-start;
                padding-top: 72px;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        {{-- ── Left Panel ── --}}
        <div class="left">
            <div class="left-accent"></div>

            <div class="brand">
                <div class="brand-logo"><span>D'</span>Glomie</div>
                <div class="brand-sub">Inventory Management System</div>
            </div>

            <div class="left-body">
                <h2 class="left-tagline">
                    Efficiently manage<br>your stock with <em>precision.</em>
                </h2>
                <p class="left-desc">
                    A centralized platform to track, manage, and optimize
                    your inventory — keeping your operations accurate every day.
                </p>
            </div>

            <div class="left-stats">
                <div class="stat">
                    <div class="stat-val">100%</div>
                    <div class="stat-label">Accuracy</div>
                </div>
                <div class="stat">
                    <div class="stat-val">Live</div>
                    <div class="stat-label">Stock Updates</div>
                </div>
                <div class="stat">
                    <div class="stat-val">Secure</div>
                    <div class="stat-label">Access</div>
                </div>
            </div>
        </div>

        {{-- ── Right Panel ── --}}
        <div class="right">
            <div class="form-wrap">

                <div class="accent-bar">
                    <span class="bar-1"></span>
                    <span class="bar-2"></span>
                    <span class="bar-3"></span>
                </div>

                <div class="form-header">
                    <div class="form-eyebrow">Secure Access</div>
                    <h2 class="form-title">Welcome<br>back.</h2>
                    <p class="form-subtitle">Log in to your account to continue.</p>
                </div>

                @include('layout.partials.alerts')

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
                        Log In &nbsp;<i class="fas fa-arrow-right"></i>
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
