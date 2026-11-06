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

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #050605; padding: 24px; position: relative; overflow: hidden;">
    <!-- Ambient Background Glows -->
    <div style="position: absolute; width: 450px; height: 450px; border-radius: 50%; background: radial-gradient(circle, rgba(39, 184, 21, 0.12) 0%, transparent 70%); top: 10%; left: 15%; pointer-events: none;"></div>
    <div style="position: absolute; width: 550px; height: 550px; border-radius: 50%; background: radial-gradient(circle, rgba(26, 122, 14, 0.08) 0%, transparent 70%); bottom: 5%; right: 10%; pointer-events: none;"></div>

    <div style="width: 100%; max-width: 440px; position: relative; z-index: 2;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--color-secondary); display: inline-flex; align-items: center; justify-content: center; font-family: var(--font-clash); font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 16px; box-shadow: 0 10px 25px rgba(26, 122, 14, 0.35);">
                Q
            </div>

            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-secondary-bright); font-weight: 700; margin-bottom: 6px;">
                Restricted Access Portal
            </div>

            <h1 style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 8px;">
                Admin Control Center
            </h1>
            <p style="color: var(--color-text-secondary); font-size: 14px;">
                Sign in to manage SEO metadata, blogs, and site analytics.
            </p>
        </div>

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div style="font-size: 14px; font-weight: 600;">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
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
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-xl); padding: 36px 32px; box-shadow: 0 30px 60px rgba(0,0,0,0.8);">
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <!-- Username / Email -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="username" class="form-label" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Username or Email <span style="color: var(--color-secondary-bright);">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" name="username" id="username" class="form-control" placeholder="admin" value="{{ old('username', 'admin') }}" required autofocus style="width: 100%; padding: 12px 14px 12px 42px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: var(--radius-sm); color: #fff; font-size: 14px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="password" class="form-label" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Password <span style="color: var(--color-secondary-bright);">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required style="width: 100%; padding: 12px 14px 12px 42px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: var(--radius-sm); color: #fff; font-size: 14px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Remember Me & Default Hint -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; font-size: 13px;">
                    <label style="display: flex; align-items: center; gap: 8px; color: var(--color-text-secondary); cursor: pointer;">
                        <input type="checkbox" name="remember" checked style="accent-color: var(--color-secondary-bright);">
                        <span>Remember session</span>
                    </label>
                    <span style="color: var(--color-text-muted); font-size: 12px;">Admin default: admin / qavix@2026</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; border: none; border-radius: var(--radius-sm); background: linear-gradient(135deg, var(--color-secondary-bright), var(--color-secondary)); color: #fff; font-family: var(--font-clash); font-size: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 20px rgba(39, 184, 21, 0.25);">
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

