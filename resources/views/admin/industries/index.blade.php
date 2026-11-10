@extends('layouts.admin')

@section('title', 'Industries | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Industries & Verticals
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Manage industry categories for grouping clients and partners.
        </p>
    </div>

    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.industries.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Add Industry</span>
        </a>

        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
            <span>View Clients →</span>
        </a>
    </div>
</div>

<!-- Workflow Notice Banner -->
<div style="background: rgba(34, 197, 94, 0.06); border: 1px solid var(--color-secondary-border); border-radius: var(--radius-lg); padding: 16px 20px; margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
            </svg>
        </div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: #fff;">
                Dropdown Integration:
            </div>
            <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 2px;">
                Every industry created here automatically appears in the <strong style="color: #4ade80;">Industry Dropdown</strong> when creating or editing a Client.
            </div>
        </div>
    </div>
    <div style="display: flex; gap: 16px; font-size: 13px;">
        <span style="color: var(--color-text-secondary);">Total Industries: <strong style="color: #fff;">{{ $totalIndustries }}</strong></span>
    </div>
</div>

<!-- Industries Table -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
    @if($industries->isEmpty())
        <div style="text-align: center; padding: 60px 20px; color: var(--color-text-muted);">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.04); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <p style="font-size: 15px; margin-bottom: 12px;">No industries created yet.</p>
            <a href="{{ route('admin.industries.create') }}" class="btn btn-primary btn-sm" style="text-decoration: none;">+ Add First Industry</a>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--color-dark-border); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 14px 18px;">Industry Name</th>
                        <th style="padding: 14px 18px;">API Slug</th>
                        <th style="padding: 14px 18px;">Clients</th>
                        <th style="padding: 14px 18px;">Order</th>
                        <th style="padding: 14px 18px;">Status</th>
                        <th style="padding: 14px 18px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($industries as $industry)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s ease;">
                            <!-- Name & Description -->
                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 700; color: #fff; font-size: 15px; font-family: var(--font-clash);">
                                    {{ $industry->name }}
                                </div>
                                @if($industry->description)
                                    <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 2px;">
                                        {{ Str::limit($industry->description, 60) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Slug -->
                            <td style="padding: 14px 18px;">
                                <code style="color: #4ade80; background: rgba(0,0,0,0.4); padding: 3px 8px; border-radius: 4px; font-size: 12px;">
                                    {{ $industry->slug }}
                                </code>
                            </td>

                            <!-- Clients Count -->
                            <td style="padding: 14px 18px;">
                                <a href="{{ route('admin.clients.index', ['industry_id' => $industry->id]) }}" style="text-decoration: none;">
                                    <span style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80; padding: 3px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600;">
                                        {{ $industry->clients_count }} clients
                                    </span>
                                </a>
                            </td>

                            <!-- Order -->
                            <td style="padding: 14px 18px;">
                                <span style="background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; font-size: 12px; color: #e5e7eb;">
                                    #{{ $industry->order }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td style="padding: 14px 18px;">
                                @if($industry->is_active)
                                    <span class="badge-pill badge-published">✓ Active</span>
                                @else
                                    <span class="badge-pill badge-draft">Hidden</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td style="padding: 14px 18px; text-align: right;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <a href="{{ route('admin.industries.edit', $industry->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px; text-decoration: none; border-radius: 6px;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.industries.destroy', $industry->id) }}" method="POST" onsubmit="return confirm('Delete industry {{ $industry->name }}? Associated clients will be unassigned.');" style="display: inline;">
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

        @if($industries->hasPages())
            <div style="padding: 20px; border-top: 1px solid var(--color-dark-border);">
                {{ $industries->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

