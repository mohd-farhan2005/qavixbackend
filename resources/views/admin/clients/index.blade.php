@extends('layouts.admin')

@section('title', 'Clients & Partners | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Clients & Brands
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Manage client logos, company details, and assigned industries.
        </p>
    </div>

    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Add Client</span>
        </a>

        <a href="{{ route('admin.industries.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
            </svg>
            <span>Manage Industries</span>
        </a>
    </div>
</div>

<!-- API & Quick Stats Banner -->
<div style="background: rgba(34, 197, 94, 0.06); border: 1px solid var(--color-secondary-border); border-radius: var(--radius-lg); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
        </div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: #fff;">
                Frontend REST API:
            </div>
            <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 2px;">
                Fetch all clients via <code style="color: #4ade80; background: rgba(0,0,0,0.5); padding: 2px 6px; border-radius: 4px;">GET /api/clients</code> or filter by industry: <code style="color: #4ade80; background: rgba(0,0,0,0.5); padding: 2px 6px; border-radius: 4px;">GET /api/clients?industry={slug}</code>
            </div>
        </div>
    </div>
    <div style="display: flex; gap: 16px; font-size: 13px;">
        <span style="color: var(--color-text-secondary);">Total Clients: <strong style="color: #fff;">{{ $totalClients }}</strong></span>
        <span style="color: var(--color-text-secondary);">Active: <strong style="color: #4ade80;">{{ $activeClients }}</strong></span>
    </div>
</div>

<!-- Filters: Industry Dropdown & Search -->
<div style="background: rgba(255,255,255,0.02); border: 1px solid var(--color-dark-border); border-radius: 10px; padding: 14px 16px; margin-bottom: 20px;">
    <form action="{{ route('admin.clients.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <!-- Search Input -->
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client name..." style="width: 100%; padding: 10px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px; outline: none;">
        </div>

        <!-- Industry Filter Dropdown -->
        <div style="min-width: 200px;">
            <select name="industry_id" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; background: #090b09; border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px; outline: none; cursor: pointer;">
                <option value="">All Industries</option>
                @foreach($industries as $ind)
                    <option value="{{ $ind->id }}" {{ request('industry_id') == $ind->id ? 'selected' : '' }}>
                        {{ $ind->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-secondary btn-sm" style="padding: 10px 16px; border-radius: 6px; cursor: pointer;">
            Filter
        </button>

        @if(request('search') || request('industry_id'))
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; color: var(--color-text-muted); text-decoration: none; padding: 10px 14px;">
                Reset
            </a>
        @endif
    </form>
</div>

<!-- Clients Table -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
    @if($clients->isEmpty())
        <div style="text-align: center; padding: 60px 20px; color: var(--color-text-muted);">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.04); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
            <p style="font-size: 15px; margin-bottom: 12px;">No clients found.</p>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm" style="text-decoration: none;">+ Add First Client</a>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--color-dark-border); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 14px 18px;">Logo</th>
                        <th style="padding: 14px 18px;">Client Name</th>
                        <th style="padding: 14px 18px;">Industry Vertical</th>
                        <th style="padding: 14px 18px;">Website</th>
                        <th style="padding: 14px 18px;">Order</th>
                        <th style="padding: 14px 18px;">Status</th>
                        <th style="padding: 14px 18px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s ease;">
                            <!-- Logo Preview -->
                            <td style="padding: 14px 18px; width: 64px;">
                                <div style="width: 48px; height: 48px; border-radius: 10px; overflow: hidden; background: #060706; border: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; padding: 4px;">
                                    <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                            </td>

                            <!-- Name & Featured Pill -->
                            <td style="padding: 14px 18px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-weight: 700; color: #fff; font-size: 15px; font-family: var(--font-clash);">
                                        {{ $client->name }}
                                    </span>
                                    @if($client->featured)
                                        <span style="background: rgba(234, 179, 8, 0.15); border: 1px solid rgba(234, 179, 8, 0.3); color: #facc15; padding: 2px 7px; border-radius: 9999px; font-size: 10px; font-weight: 700; text-transform: uppercase;">
                                            Featured
                                        </span>
                                    @endif
                                </div>
                                @if($client->description)
                                    <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 2px;">
                                        {{ Str::limit($client->description, 50) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Industry Vertical Dropdown Result -->
                            <td style="padding: 14px 18px;">
                                @if($client->industry)
                                    <a href="{{ route('admin.clients.index', ['industry_id' => $client->industry_id]) }}" style="text-decoration: none;">
                                        <span style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            {{ $client->industry->name }}
                                        </span>
                                    </a>
                                @else
                                    <span style="color: var(--color-text-muted); font-size: 12px;">Unassigned</span>
                                @endif
                            </td>

                            <!-- Website -->
                            <td style="padding: 14px 18px;">
                                @if($client->website_url)
                                    <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-secondary-bright); text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 4px;">
                                        <span>Visit</span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                @else
                                    <span style="color: var(--color-text-muted); font-size: 12px;">—</span>
                                @endif
                            </td>

                            <!-- Order -->
                            <td style="padding: 14px 18px;">
                                <span style="background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; font-size: 12px; color: #e5e7eb;">
                                    #{{ $client->order }}
                                </span>
                            </td>

                            <!-- Status & Toggle -->
                            <td style="padding: 14px 18px;">
                                <form action="{{ route('admin.clients.toggle-active', $client->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        @if($client->is_active)
                                            <span class="badge-pill badge-published" title="Click to hide from frontend">✓ Active</span>
                                        @else
                                            <span class="badge-pill badge-draft" title="Click to show on frontend">Hidden</span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td style="padding: 14px 18px; text-align: right;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px; text-decoration: none; border-radius: 6px;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Delete client {{ $client->name }}?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px; color: #f87171; border-color: rgba(239,68,68,0.2); border-radius: 6px; cursor: pointer;">
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

        @if($clients->hasPages())
            <div style="padding: 20px; border-top: 1px solid var(--color-dark-border);">
                {{ $clients->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
