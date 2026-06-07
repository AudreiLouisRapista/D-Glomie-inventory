<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LOME Inventory Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            --maroon-deep: #1e0507;
            --maroon-brand: #540b13;
            --maroon-light: #7c131e;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --bg-field: #f8fafc;
            --border-color: #e2e8f0;
            --gold-accent: #f0c040;
        }

        html,
        body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background-color: var(--maroon-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient subtle background radial glow */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, rgba(124, 19, 30, 0.2) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* Centered Single Column Workspace Card */
        .card {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 480px;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: cardIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Top Brand Accent Strip */
        .card-accent {
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--maroon-light), var(--gold-accent));
        }

        /* Main Context Container */
        .main-content {
            background: white;
            padding: 48px 40px;
        }

        /* Centered Brand Layout */
        .brand-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-logo {
            font-size: 32px;
            font-weight: 700;
            color: var(--maroon-brand);
            letter-spacing: -0.5px;
        }

        .brand-logo span {
            color: var(--gold-accent);
        }

        .brand-sub {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Form Subtext Config */
        .form-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .form-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.3px;
            margin-bottom: 4px;
        }

        .form-subtitle {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* Form Interactive Fields Configuration */
        .field {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-dark);
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
            font-size: 14px;
            color: var(--text-light);
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .field-input {
            width: 100%;
            padding: 12px 16px 12px 46px;
            background: var(--bg-field);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            outline: none;
            transition: all 0.2s ease;
        }

        .field-input::placeholder {
            color: var(--text-light);
        }

        .field-input:focus {
            border-color: var(--maroon-brand);
            background: white;
            box-shadow: 0 0 0 4px rgba(84, 11, 19, 0.08);
        }

        .field-wrap:focus-within .field-icon {
            color: var(--maroon-brand);
        }

        .toggle-pass {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 14px;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s ease;
        }

        .toggle-pass:hover {
            color: var(--text-dark);
        }

        /* Meta Options Configuration */
        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
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
            width: 16px;
            height: 16px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background: var(--bg-field);
            cursor: pointer;
            position: relative;
            transition: all 0.15s ease;
        }

        .remember input[type="checkbox"]:checked {
            background: var(--maroon-brand);
            border-color: var(--maroon-brand);
        }

        .remember input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: 2px solid white;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .forgot-link {
            font-size: 13px;
            color: var(--maroon-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: var(--maroon-brand);
            text-decoration: underline;
        }

        /* Core Access Action Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--maroon-brand);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: var(--maroon-light);
            box-shadow: 0 4px 12px rgba(124, 19, 30, 0.2);
        }

        /* Developer Metadata Section */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0 20px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-light);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .socials {
            display: flex;
            gap: 8px;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .social-btn:hover {
            background: var(--bg-field);
            border-color: var(--text-light);
        }

        .social-btn i {
            font-size: 14px;
            color: var(--text-muted);
        }

        .form-footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: var(--text-light);
        }

        /* Viewport Optimization rules */
        @media (max-width: 480px) {
            body {
                padding: 0;
            }

            .card {
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none;
            }

            .main-content {
                padding: 48px 24px;
            }
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="card-accent"></div>

        <div class="main-content">
            <div class="brand-header">
                <div class="brand-logo">LOME <span>Convenience</span></div>
                <div class="brand-sub">Inventory System</div>
            </div>

            <div class="form-header">
                <h2 class="form-title">Welcome back</h2>
                <p class="form-subtitle">Log in to your dashboard instance</p>
            </div>

            @include('layout.partials.alerts')

            <form action="{{ route('auth_user') }}" method="POST">
                @csrf

                <div class="field">
                    <label class="field-label">Email Address</label>
                    <div class="field-wrap">
                        <i class="fas fa-envelope field-icon"></i>
                        <input type="email" name="email" class="field-input" placeholder="name@domain.com" required
                            value="{{ old('email') }}">
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
                        Remember session
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">
                    Sign In <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="divider">
                <span>Connect with Developer</span>
            </div>

            <div class="socials">
                <a href="https://www.facebook.com/al.rapista" class="social-btn" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/a.lmongs/" class="social-btn" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.tiktok.com/@audreil43" class="social-btn" target="_blank">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>

            <div class="form-footer">
                LOME Convenience Store &copy; 2026
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
