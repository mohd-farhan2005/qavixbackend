@extends('layouts.admin')

@section('title', 'Page Meta Tags & SEO Manager | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            SEO Architecture
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Page Meta Tags & OpenGraph Manager
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Configure meta titles, meta descriptions, and social share cards for each route in your React app.
        </p>
    </div>

    <a href="{{ route('admin.seo.pages.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--color-secondary); color: #fff; text-decoration: none; padding: 10px 18px; border-radius: var(--radius-sm); font-weight: 600; font-size: 13px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Add Route SEO</span>
    </a>
</div>

<!-- Table of Configured SEO Pages -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-dark-border); background: rgba(255,255,255,0.02); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 14px 18px;">Page Name / Route</th>
                    <th style="padding: 14px 18px;">Meta Title</th>
                    <th style="padding: 14px 18px;">Meta Description</th>
                    <th style="padding: 14px 18px;">Robots</th>
                    <th style="padding: 14px 18px;">OpenGraph Image</th>
                    <th style="padding: 14px 18px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                        <td style="padding: 16px 18px;">
                            <div style="font-weight: 600; color: #fff; font-size: 15px;">
                                {{ $page->page_name }}
                            </div>
                            <div style="font-size: 12px; color: #4ade80; font-family: monospace; margin-top: 2px;">
                                {{ $page->path }}
                            </div>
                        </td>

                        <td style="padding: 16px 18px; color: #e5e7eb; max-width: 260px;">
                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px;">
                                {{ $page->meta_title ?: 'Default Site Title' }}
                            </div>
                        </td>

                        <td style="padding: 16px 18px; color: var(--color-text-secondary); max-width: 300px;">
                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px;">
                                {{ $page->meta_description ?: 'Default site description' }}
                            </div>
                        </td>

                        <td style="padding: 16px 18px;">
                            <span style="background: rgba(39, 184, 21, 0.1); border: 1px solid var(--color-secondary-border); color: #4ade80; padding: 2px 8px; border-radius: 4px; font-size: 11px;">
                                {{ $page->robots ?: 'index, follow' }}
                            </span>
                        </td>

                        <td style="padding: 16px 18px;">
                            @if($page->og_image)
                                <img src="{{ $page->og_image }}" alt="" style="width: 44px; height: 32px; border-radius: 4px; object-fit: cover; border: 1px solid var(--color-dark-border);">
                            @else
                                <span style="font-size: 12px; color: var(--color-text-muted);">Default</span>
                            @endif
                        </td>

                        <td style="padding: 16px 18px; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('admin.seo.pages.edit', $page->id) }}" style="padding: 6px 12px; background: var(--color-secondary); color: #fff; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Edit Meta
                                </a>

                                <form action="{{ route('admin.seo.pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Delete SEO configuration for this route?');" style="display: inline;">
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
</div>
@endsection

