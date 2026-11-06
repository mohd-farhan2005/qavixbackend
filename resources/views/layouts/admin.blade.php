<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Portal | QAVIX')</title>

    <!-- Clash Display & Plus Jakarta Sans Fonts -->
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .admin-sidebar {
            width: 280px;
            background: #090b09;
            border-right: 1px solid var(--color-dark-border);
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }
        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: var(--color-text-secondary);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition-fast);
        }
        .admin-nav-item:hover, .admin-nav-item.active {
            background: var(--color-secondary-subtle);
            color: #fff;
            border: 1px solid var(--color-secondary-border);
        }
        .admin-nav-item.active {
            color: var(--color-secondary-bright);
            font-weight: 600;
        }
        .admin-nav-item svg {
            flex-shrink: 0;
        }
        .admin-main {
            flex: 1;
            padding: 36px 40px;
            background: #050605;
            min-height: 100vh;
        }
        .top-navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--color-dark-border);
        }
        .stat-card {
            background: var(--color-dark-card);
            border: 1px solid var(--color-dark-border);
            border-radius: var(--radius-lg);
            padding: 24px;
            transition: var(--transition-smooth);
        }
        .stat-card:hover {
            border-color: var(--color-secondary-border);
            transform: translateY(-2px);
        }
        .live-preview-box {
            background: #ffffff;
            color: #1a0dab;
            border-radius: 8px;
            padding: 20px;
            font-family: Arial, sans-serif;
            margin-top: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .serp-url {
            color: #202124;
            font-size: 12px;
            line-height: 1.3;
            margin-bottom: 4px;
        }
        .serp-title {
            color: #1a0dab;
            font-size: 18px;
            line-height: 1.3;
            text-decoration: none;
            cursor: pointer;
            margin-bottom: 6px;
            font-weight: 400;
        }
        .serp-title:hover {
            text-decoration: underline;
        }
        .serp-desc {
            color: #4d5156;
            font-size: 13px;
            line-height: 1.5;
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-published {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .badge-draft {
            background: rgba(234, 179, 8, 0.15);
            color: #facc15;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body style="background: #050605; color: #f5f6f8;">
    <div style="display: flex; min-height: 100vh;">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <div style="margin-bottom: 28px;">
                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--color-secondary); display: flex; align-items: center; justify-content: center; font-family: var(--font-clash); font-weight: 700; color: #fff;">
                        Q
                    </div>
                    <div>
                        <div style="font-family: var(--font-clash); font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -0.02em;">QAVIX</div>
                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-secondary-bright); font-weight: 700;">SEO Control Panel</div>
                    </div>
                </a>
            </div>

            <nav style="flex: 1 0 auto; display: flex; flex-direction: column; gap: 4px;">
                <!-- Section: Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Section: Blog & Content -->
                <div style="font-size: 10px; text-transform: uppercase; color: var(--color-text-muted); letter-spacing: 0.08em; padding: 16px 12px 6px; font-weight: 700;">
                    Content & Articles
                </div>

                <a href="{{ route('admin.blogs.index') }}" class="admin-nav-item {{ request()->routeIs('admin.blogs.index') || request()->routeIs('admin.blogs.edit') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Blog Posts</span>
                </a>

                <a href="{{ route('admin.blogs.create') }}" class="admin-nav-item {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>New Blog Post</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}" class="admin-nav-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span>FAQs & Schema</span>
                </a>

                <!-- Section: SEO Engine -->
                <div style="font-size: 10px; text-transform: uppercase; color: var(--color-text-muted); letter-spacing: 0.08em; padding: 16px 12px 6px; font-weight: 700;">
                    SEO Engine
                </div>

                <a href="{{ route('admin.seo.pages.index') }}" class="admin-nav-item {{ request()->routeIs('admin.seo.pages.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Page Meta Tags</span>
                </a>

                <a href="{{ route('admin.seo.settings') }}" class="admin-nav-item {{ request()->routeIs('admin.seo.settings') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span>Global SEO & Scripts</span>
                </a>

                <!-- Section: Media & Inquiries -->
                <div style="font-size: 10px; text-transform: uppercase; color: var(--color-text-muted); letter-spacing: 0.08em; padding: 16px 12px 6px; font-weight: 700;">
                    Assets & Leads
                </div>

                <a href="{{ route('admin.media.index') }}" class="admin-nav-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span>Media Library</span>
                </a>

                <a href="{{ route('admin.contacts.index') }}" class="admin-nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span>Customer Inquiries</span>
                </a>

                <!-- External Links -->
                <div style="font-size: 10px; text-transform: uppercase; color: var(--color-text-muted); letter-spacing: 0.08em; padding: 16px 12px 6px; font-weight: 700;">
                    Live Feeds
                </div>

                <a href="{{ url('/api/sitemap.xml') }}" class="admin-nav-item" target="_blank">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    <span>XML Sitemap ↗</span>
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div style="padding-top: 20px; border-top: 1px solid var(--color-dark-border); margin-top: auto;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-family: var(--font-clash);">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-family: var(--font-clash); font-size: 14px; font-weight: 600; color: #fff;">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div style="font-size: 12px; color: var(--color-secondary-bright);">@ {{ Auth::user()->username ?? 'admin' }}</div>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; color: #f87171; border-color: rgba(239, 68, 68, 0.2);">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-main">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 24px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <div style="font-weight: 600;">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="margin-bottom: 24px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 24px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

