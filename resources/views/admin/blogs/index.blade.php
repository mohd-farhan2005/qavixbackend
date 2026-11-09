@extends('layouts.admin')

@section('title', 'Manage Blog Posts | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Blog & Article Management
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Publish content, monitor reader views, and configure search engine meta tags.
        </p>
    </div>

    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--color-secondary); color: #fff; text-decoration: none; padding: 10px 18px; border-radius: var(--radius-sm); font-weight: 600; font-size: 13px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Add New Article</span>
    </a>
</div>

<!-- Filters & Search Bar -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 18px 24px; margin-bottom: 24px;">
    <form action="{{ route('admin.blogs.index') }}" method="GET" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
        <!-- Search Input -->
        <div style="flex: 2; min-width: 240px; position: relative;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, excerpt..." style="width: 100%; padding: 10px 14px 10px 38px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>

        <!-- Status Filter -->
        <div style="flex: 1; min-width: 150px;">
            <select name="status" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        <!-- Category Filter -->
        <div style="flex: 1; min-width: 170px;">
            <select name="category_id" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" style="padding: 10px 18px; background: var(--color-secondary); color: #fff; border: none; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">
            Filter
        </button>

        @if(request()->hasAny(['search', 'status', 'category_id']))
            <a href="{{ route('admin.blogs.index') }}" style="padding: 10px 14px; background: rgba(255,255,255,0.06); color: var(--color-text-secondary); text-decoration: none; border-radius: 6px; font-size: 13px;">
                Reset
            </a>
        @endif
    </form>
</div>

<!-- Blog Table -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
    @if($blogs->isEmpty())
        <div style="text-align: center; padding: 60px 20px; color: var(--color-text-muted);">
            No articles match your query. <a href="{{ route('admin.blogs.create') }}" style="color: var(--color-secondary-bright);">Create a new post</a>.
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--color-dark-border); background: rgba(255,255,255,0.02); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 14px 18px;">Article Details</th>
                        <th style="padding: 14px 18px;">Category</th>
                        <th style="padding: 14px 18px;">SEO Title / Status</th>
                        <th style="padding: 14px 18px;">Views</th>
                        <th style="padding: 14px 18px;">Date</th>
                        <th style="padding: 14px 18px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $blog)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                            <!-- Title & Image -->
                            <td style="padding: 16px 18px;">
                                <div style="display: flex; align-items: center; gap: 14px;">
                                    @if($blog->featured_image)
                                        <img src="{{ $blog->featured_image }}" alt="" style="width: 52px; height: 52px; border-radius: 8px; object-fit: cover; flex-shrink: 0; border: 1px solid var(--color-dark-border);">
                                    @else
                                        <div style="width: 52px; height: 52px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); flex-shrink: 0;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: #fff; font-size: 15px; margin-bottom: 4px;">
                                            {{ $blog->title }}
                                        </div>
                                        <div style="font-size: 12px; color: var(--color-text-muted); display: flex; align-items: center; gap: 8px;">
                                            <span>/blog/{{ $blog->slug }}</span>
                                            <span>•</span>
                                            <span>{{ $blog->reading_time }} min read</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td style="padding: 16px 18px;">
                                <span style="background: rgba(255,255,255,0.06); padding: 4px 10px; border-radius: 6px; font-size: 12px; color: #e5e7eb;">
                                    {{ $blog->category?->name ?? 'Uncategorized' }}
                                </span>
                            </td>

                            <!-- SEO Meta Title & Status -->
                            <td style="padding: 16px 18px;">
                                <div style="margin-bottom: 6px;">
                                    @if($blog->status === 'published')
                                        <span class="badge-pill badge-published">Published</span>
                                    @else
                                        <span class="badge-pill badge-draft">Draft</span>
                                    @endif

                                    @if($blog->is_featured)
                                        <span style="background: rgba(234, 179, 8, 0.15); color: #facc15; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin-left: 6px; font-weight: 600;">★ Featured</span>
                                    @endif
                                </div>
                                <div style="font-size: 12px; color: var(--color-text-secondary); max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    @if($blog->meta_title)
                                        <span style="color: #4ade80;">SEO:</span> {{ $blog->meta_title }}
                                    @else
                                        <span style="color: var(--color-text-muted);">Auto-derived from title</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Views -->
                            <td style="padding: 16px 18px; color: #fff; font-weight: 600;">
                                {{ number_format($blog->views_count) }}
                            </td>

                            <!-- Date -->
                            <td style="padding: 16px 18px; font-size: 13px; color: var(--color-text-secondary);">
                                {{ $blog->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td style="padding: 16px 18px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                    <!-- Quick Publish Toggle -->
                                    <form action="{{ route('admin.blogs.toggle-publish', $blog->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" title="Toggle Publish / Draft" style="padding: 6px 10px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #e5e7eb; font-size: 12px; cursor: pointer;">
                                            {{ $blog->status === 'published' ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" style="padding: 6px 12px; background: var(--color-secondary); color: #fff; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                        Edit
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog post?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 10px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; color: #f87171; font-size: 12px; cursor: pointer;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 18px 24px; border-top: 1px solid var(--color-dark-border);">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
@endsection

