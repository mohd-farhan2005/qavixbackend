<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | QAVIX Control Center</title>

    <!-- Clash Display & Plus Jakarta Sans Fonts -->
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Primary CSS -->
    <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
    <link rel="stylesheet" href="/css/style.css">

    <style>
        @import url('https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --font-clash: 'Clash Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --color-black: #050605;
            --color-dark-card: #0d0f0d;
            --color-dark-input: #060706;
            --color-dark-border: rgba(255, 255, 255, 0.08);
            --color-dark-border-input: rgba(255, 255, 255, 0.12);
            --color-green-primary: #16a34a;
            --color-green-bright: #22c55e;
            --color-green-dark: #15803d;
            --color-green-glow: rgba(34, 197, 94, 0.4);
            --color-text-primary: #ffffff;
            --color-text-secondary: #9ca3af;
            --color-text-muted: #6b7280;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--color-black);
            color: var(--color-text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient Glow Circles */
        .ambient-glow-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.14) 0%, transparent 70%);
            top: 5%;
            left: 12%;
            pointer-events: none;
            filter: blur(40px);
        }

        .ambient-glow-2 {
            position: absolute;
            width: 550px;
            height: 550px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(22, 163, 74, 0.1) 0%, transparent 70%);
            bottom: 5%;
            right: 10%;
            pointer-events: none;
            filter: blur(50px);
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--color-green-bright), var(--color-green-dark));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-clash);
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 18px;
            box-shadow: 0 0 35px var(--color-green-glow);
        }

        .restricted-badge {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--color-green-bright);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-title {
            font-family: var(--font-clash);
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .login-subtitle {
            color: var(--color-text-secondary);
            font-size: 14px;
            line-height: 1.5;
        }

        .login-card {
            background: var(--color-dark-card);
            border: 1px solid var(--color-dark-border);
            border-radius: 22px;
            padding: 36px 32px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.85);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #e5e7eb;
            margin-bottom: 8px;
        }

        .form-label span {
            color: var(--color-green-bright);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-muted);
            pointer-events: none;
        }

        .custom-input {
            width: 100%;
            padding: 13px 14px 13px 44px;
            background: var(--color-dark-input);
            border: 1px solid var(--color-dark-border-input);
            border-radius: 10px;
            color: #ffffff;
            font-size: 14px;
            font-family: var(--font-body);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
        }

        .custom-input:focus {
            outline: none;
            border-color: var(--color-green-bright);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
        }

        /* Prevent Chrome / Safari Autofill Ugly Blue/White Background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #060706 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            caret-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--color-text-secondary);
            cursor: pointer;
            font-weight: 500;
        }

        .remember-checkbox {
            accent-color: var(--color-green-bright);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .default-hint {
            color: var(--color-text-muted);
            font-size: 12px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 20px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--color-green-bright), var(--color-green-primary));
            color: #ffffff;
            font-family: var(--font-clash);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 24px var(--color-green-glow);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--color-green-primary), var(--color-green-dark));
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(34, 197, 94, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-box {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-box-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #4ade80;
        }

        .alert-box-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #f87171;
        }
    </style>
</head>
<body>
    <!-- Ambient Background Glows -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="login-wrapper">
        <!-- Header -->
        <div class="login-header">
            <div class="brand-badge">
                Q
            </div>

            <div class="restricted-badge">
                Restricted Access Portal
            </div>

            <h1 class="login-title">
                Admin Control Center
            </h1>
            <p class="login-subtitle">
                Sign in to manage SEO metadata, blogs, and site analytics.
            </p>
        </div>

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="alert-box alert-box-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div style="font-size: 14px; font-weight: 600;">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-box alert-box-danger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div style="font-size: 13px;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Card Container -->
        <div class="login-card">
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <!-- Username or Email -->
                <div class="form-group">
                    <label for="username" class="form-label">
                        Username or Email <span>*</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="text" name="username" id="username" class="custom-input" placeholder="admin" value="{{ old('username', 'admin') }}" required autofocus autocomplete="username">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        Password <span>*</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="custom-input" placeholder="••••••••" required autocomplete="current-password">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Remember Me & Default Hint -->
                <div class="form-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" class="remember-checkbox" checked>
                        <span>Remember session</span>
                    </label>
                    <span class="default-hint">Admin default: admin / qavix@2026</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    <span>Authenticate & Access Dashboard</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
