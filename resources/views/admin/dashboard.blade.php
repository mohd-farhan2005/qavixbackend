@extends('layouts.admin')

@section('title', 'Admin Dashboard | QAVIX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            System Dashboard
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Overview of blog articles, page meta tags, search indexation status, and leads.
        </p>
    </div>

    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 8px; background: var(--color-secondary); color: #fff; text-decoration: none; padding: 10px 18px; border-radius: var(--radius-sm); font-weight: 600; font-size: 13px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Write New Blog</span>
        </a>

        <a href="{{ route('admin.seo.pages.create') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); color: #fff; text-decoration: none; padding: 10px 18px; border-radius: var(--radius-sm); font-weight: 600; font-size: 13px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <span>Add Route SEO</span>
        </a>
    </div>
</div>

<!-- Quick Stat Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <!-- Card 1: Blog Articles -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 600;">Blog Articles</div>
                <div style="font-family: var(--font-clash); font-size: 32px; font-weight: 700; color: #fff; margin-top: 4px;">{{ $totalBlogs }}</div>
            </div>
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(39, 184, 21, 0.15); display: flex; align-items: center; justify-content: center; color: var(--color-secondary-bright);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
        </div>
        <div style="display: flex; gap: 12px; font-size: 12px; color: var(--color-text-secondary);">
            <span><strong style="color: #4ade80;">{{ $publishedBlogs }}</strong> Published</span>
            <span>•</span>
            <span><strong style="color: #facc15;">{{ $draftBlogs }}</strong> Drafts</span>
        </div>
    </div>

    <!-- Card 2: Total Post Views -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 600;">Reader Views</div>
                <div style="font-family: var(--font-clash); font-size: 32px; font-weight: 700; color: #fff; margin-top: 4px;">{{ number_format($totalViews) }}</div>
            </div>
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(59, 130, 246, 0.15); display: flex; align-items: center; justify-content: center; color: #60a5fa;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
        </div>
        <div style="font-size: 12px; color: var(--color-text-secondary);">
            Cumulative reads across all published articles
        </div>
    </div>

    <!-- Card 3: Route SEO Configs -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 600;">Configured SEO Routes</div>
                <div style="font-family: var(--font-clash); font-size: 32px; font-weight: 700; color: #fff; margin-top: 4px;">{{ $totalSeoPages }}</div>
            </div>
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(168, 85, 247, 0.15); display: flex; align-items: center; justify-content: center; color: #c084fc;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
        </div>
        <div style="font-size: 12px; color: var(--color-text-secondary);">
            Meta tags configured for React routes
        </div>
    </div>

    <!-- Card 4: Customer Leads -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 600;">Customer Leads</div>
                <div style="font-family: var(--font-clash); font-size: 32px; font-weight: 700; color: #fff; margin-top: 4px;">{{ $unreadContacts }}</div>
            </div>
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(239, 68, 68, 0.15); display: flex; align-items: center; justify-content: center; color: #f87171;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
        </div>
        <div style="font-size: 12px; color: var(--color-text-secondary);">
            <strong style="color: #f87171;">{{ $unreadContacts }}</strong> unread messages from React contact form
        </div>
    </div>

    <!-- Card 5: Launchpad Founders -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 600;">Launchpad Founders</div>
                <div style="font-family: var(--font-clash); font-size: 32px; font-weight: 700; color: #fff; margin-top: 4px;">{{ $totalLaunchpad }}</div>
            </div>
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(34, 197, 94, 0.15); display: flex; align-items: center; justify-content: center; color: #4ade80;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
        </div>
        <div style="font-size: 12px; color: var(--color-text-secondary); display: flex; justify-content: space-between; align-items: center;">
            <span><strong style="color: #4ade80;">{{ $unreadLaunchpad }}</strong> new applicants</span>
            <a href="{{ route('admin.launchpad.index') }}" style="color: var(--color-secondary-bright); text-decoration: none; font-weight: 600;">View leads →</a>
        </div>
    </div>
</div>

<!-- Quick Link / Integration Helper -->
<div style="background: rgba(39, 184, 21, 0.06); border: 1px solid var(--color-secondary-border); border-radius: var(--radius-lg); padding: 20px 24px; margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="16 18 22 12 16 6"></polyline>
                <polyline points="8 6 2 12 8 18"></polyline>
            </svg>
        </div>
        <div>
            <div style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff;">
                React REST API Integration is Live
            </div>
            <div style="font-size: 13px; color: var(--color-text-secondary); margin-top: 2px;">
                Your React frontend can fetch route SEO via <code style="color: #4ade80; background: rgba(0,0,0,0.4); padding: 2px 6px; border-radius: 4px;">GET /api/seo?path=/about</code> and published blogs via <code style="color: #4ade80; background: rgba(0,0,0,0.4); padding: 2px 6px; border-radius: 4px;">GET /api/blogs</code>.
            </div>
        </div>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ url('/api/sitemap.xml') }}" target="_blank" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; padding: 8px 14px; background: rgba(255,255,255,0.08); color: #fff; border: 1px solid var(--color-dark-border); text-decoration: none; border-radius: 6px;">
            <span>View XML Sitemap</span> ↗
        </a>
        <a href="{{ route('admin.seo.settings') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; padding: 8px 14px; background: var(--color-secondary); color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600;">
            <span>Configure Global Meta</span>
        </a>
    </div>
</div>

<!-- 2-Column Content Section -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Recent Blogs -->
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff;">
                Recent Blog Posts & SEO Status
            </h2>
            <a href="{{ route('admin.blogs.index') }}" style="color: var(--color-secondary-bright); text-decoration: none; font-size: 13px; font-weight: 600;">
                View All Posts →
            </a>
        </div>

        @if($recentBlogs->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: var(--color-text-muted);">
                No blog posts added yet. <a href="{{ route('admin.blogs.create') }}" style="color: var(--color-secondary-bright);">Create your first article</a>.
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--color-dark-border); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                            <th style="padding: 10px 12px;">Title</th>
                            <th style="padding: 10px 12px;">Category</th>
                            <th style="padding: 10px 12px;">Status</th>
                            <th style="padding: 10px 12px;">Views</th>
                            <th style="padding: 10px 12px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBlogs as $blog)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <td style="padding: 12px;">
                                    <div style="font-weight: 600; color: #fff; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $blog->title }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">
                                        /blog/{{ $blog->slug }}
                                    </div>
                                </td>
                                <td style="padding: 12px;">
                                    <span style="background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; font-size: 12px; color: #e5e7eb;">
                                        {{ $blog->category?->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td style="padding: 12px;">
                                    @if($blog->status === 'published')
                                        <span class="badge-pill badge-published">Published</span>
                                    @else
                                        <span class="badge-pill badge-draft">Draft</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; color: #e5e7eb;">
                                    {{ $blog->views_count }}
                                </td>
                                <td style="padding: 12px; text-align: right;">
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" style="color: var(--color-secondary-bright); text-decoration: none; font-size: 13px; font-weight: 600;">
                                        Edit SEO & Content
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Right Sidebar Column -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Global SEO Status -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
            <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                Search Engine Health
            </h3>

            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--color-text-secondary);">Site Title:</span>
                    <strong style="color: #fff;">{{ $seoSetting->site_name }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--color-text-secondary);">Google Verification:</span>
                    @if($seoSetting->google_site_verification)
                        <span style="color: #4ade80; font-weight: 600;">✓ Configured</span>
                    @else
                        <span style="color: #facc15; font-weight: 600;">Not Set</span>
                    @endif
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--color-text-secondary);">Sitemap Indexing:</span>
                    <span style="color: #4ade80; font-weight: 600;">✓ Active</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--color-text-secondary);">Analytics Tag:</span>
                    @if($seoSetting->custom_head_scripts)
                        <span style="color: #4ade80; font-weight: 600;">✓ Injected</span>
                    @else
                        <span style="color: var(--color-text-muted);">None</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.seo.settings') }}" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; margin-top: 18px; display: inline-flex; text-decoration: none; padding: 10px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); border-radius: var(--radius-sm); color: #fff; font-size: 13px; font-weight: 600;">
                Manage SEO Settings
            </a>
        </div>

        <!-- Recent Contact Leads -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff;">
                    Recent Leads
                </h3>
                <a href="{{ route('admin.contacts.index') }}" style="color: var(--color-secondary-bright); text-decoration: none; font-size: 12px;">
                    All Leads →
                </a>
            </div>

            @if($recentContacts->isEmpty())
                <div style="text-align: center; padding: 24px 0; color: var(--color-text-muted); font-size: 13px;">
                    No inquiries received yet.
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($recentContacts as $lead)
                        <a href="{{ route('admin.contacts.show', $lead->id) }}" style="display: block; padding: 10px 12px; background: rgba(255,255,255,0.03); border: 1px solid var(--color-dark-border); border-radius: 8px; text-decoration: none; color: inherit;">
                            <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; color: #fff;">
                                <span>{{ $lead->name }}</span>
                                <span style="font-size: 11px; color: var(--color-text-muted);">{{ $lead->created_at->diffForHumans(null, true) }}</span>
                            </div>
                            <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $lead->subject ?: $lead->message }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

