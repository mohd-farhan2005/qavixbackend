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
    <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
    <link rel="stylesheet" href="/css/style.css">
    
    <style>
        @import url('https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --color-black: #050605;
            --color-black-pure: #000000;
            --color-dark-surface: #090b09;
            --color-dark-card: #0d0f0d;
            --color-dark-card-hover: #131713;
            --color-dark-border: rgba(255, 255, 255, 0.08);
            --color-dark-border-hover: rgba(34, 197, 94, 0.4);

            --color-secondary: #16a34a;
            --color-secondary-bright: #22c55e;
            --color-secondary-light: #4ade80;
            --color-secondary-subtle: rgba(34, 197, 94, 0.12);
            --color-secondary-glow: rgba(34, 197, 94, 0.35);
            --color-secondary-border: rgba(34, 197, 94, 0.3);

            --color-text-primary: #f5f6f8;
            --color-text-secondary: #9ca3af;
            --color-text-muted: #6b7280;

            --font-clash: 'Clash Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 18px;
            --radius-xl: 24px;
            --radius-full: 9999px;

            --transition-fast: 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            --transition-smooth: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
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
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-clash {
            font-family: var(--font-clash);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
            padding: 10px 18px;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition-fast);
            border: 1px solid transparent;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--color-secondary-bright), var(--color-secondary));
            color: #fff !important;
            box-shadow: 0 4px 16px var(--color-secondary-glow);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--color-secondary), #15803d);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.5);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--color-dark-border);
            color: #fff !important;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .btn-sm {
            padding: 8px 14px;
            font-size: 12px;
        }

        /* Alerts */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14px;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #4ade80;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.1);
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #f87171;
        }
        /* Admin Layout Structure */
        .admin-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
            background: #050605;
            position: relative;
        }

        /* Permanently Fixed/Sticky 280px Sidebar */
        .admin-sidebar {
            width: 280px !important;
            min-width: 280px !important;
            max-width: 280px !important;
            flex: 0 0 280px !important;
            background: #090b09 !important;
            border-right: 1px solid var(--color-dark-border) !important;
            padding: 24px 20px !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100vh !important;
            position: sticky !important;
            top: 0 !important;
            left: 0 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            z-index: 1000 !important;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-sizing: border-box !important;
        }

        /* Mobile Backdrop Overlay */
        .admin-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        /* Main Content Container */
        .admin-main {
            flex: 1 1 0% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            margin-left: 0 !important; /* Overrides style.css margin-left: 280px */
            padding: 36px 40px !important;
            background: #050605 !important;
            min-height: 100vh !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        /* Mobile Top Bar */
        .admin-mobile-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #090b09;
            border-bottom: 1px solid var(--color-dark-border);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .mobile-close-btn {
            display: none;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--color-dark-border);
            color: var(--color-text-primary);
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        @media (max-width: 992px) {
            .admin-layout {
                flex-direction: column !important;
            }
            .admin-mobile-header {
                display: flex !important;
            }
            .admin-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                height: 100vh !important;
                transform: translateX(-100%);
                box-shadow: 0 0 40px rgba(0,0,0,0.9) !important;
            }
            .admin-sidebar.open {
                transform: translateX(0) !important;
            }
            .admin-sidebar-backdrop.active {
                display: block !important;
            }
            .admin-main {
                padding: 24px 16px !important;
                width: 100% !important;
            }
            .mobile-close-btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
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
    <!-- Mobile Top Header -->
    <header class="admin-mobile-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--color-secondary); display: flex; align-items: center; justify-content: center; font-family: var(--font-clash); font-weight: 700; color: #fff; font-size: 15px;">
                Q
            </div>
            <div>
                <div style="font-family: var(--font-clash); font-size: 18px; font-weight: 700; color: #fff; line-height: 1.1;">QAVIX</div>
                <div style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-secondary-bright); font-weight: 700;">Control Panel</div>
            </div>
        </div>
        <button type="button" onclick="toggleSidebar()" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 13px; cursor: pointer; border-radius: 6px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); color: #fff;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            <span>Menu</span>
        </button>
    </header>

    <!-- Mobile Drawer Backdrop -->
    <div class="admin-sidebar-backdrop" onclick="closeSidebar()"></div>

    <div class="admin-layout">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--color-secondary); display: flex; align-items: center; justify-content: center; font-family: var(--font-clash); font-weight: 700; color: #fff; flex-shrink: 0;">
                        Q
                    </div>
                    <div>
                        <div style="font-family: var(--font-clash); font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -0.02em; white-space: nowrap;">QAVIX</div>
                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-secondary-bright); font-weight: 700; white-space: nowrap;">SEO Control Panel</div>
                    </div>
                </a>
                <button type="button" class="mobile-close-btn" onclick="closeSidebar()" title="Close menu">✕</button>
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

                <a href="{{ route('admin.launchpad.index') }}" class="admin-nav-item {{ request()->routeIs('admin.launchpad.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <span>Launchpad Leads</span>
                        @php $unreadLaunchpadCount = \App\Models\LaunchpadLead::where('is_read', false)->count(); @endphp
                        @if($unreadLaunchpadCount > 0)
                            <span style="background: var(--color-secondary-bright); color: #000; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 9999px;">{{ $unreadLaunchpadCount }}</span>
                        @endif
                    </span>
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

    <!-- Mobile Sidebar Controller Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            const backdrop = document.querySelector('.admin-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('active');
            }
        }

        function closeSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            const backdrop = document.querySelector('.admin-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.remove('open');
                backdrop.classList.remove('active');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

