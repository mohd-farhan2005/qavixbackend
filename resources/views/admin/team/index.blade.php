@extends('layouts.admin')

@section('title', 'Team & Leadership | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Team & Directors
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Manage team members and leadership displayed on your React frontend.
        </p>
    </div>

    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.team.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--color-secondary-bright), var(--color-secondary)); color: #fff; padding: 10px 18px; border-radius: var(--radius-sm); font-weight: 600; font-size: 13px; box-shadow: 0 4px 16px var(--color-secondary-glow); text-decoration: none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Add Team Member</span>
        </a>
    </div>
</div>

<!-- Team API Quick Info -->
<div style="background: rgba(34, 197, 94, 0.06); border: 1px solid var(--color-secondary-border); border-radius: var(--radius-lg); padding: 16px 20px; margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: #fff;">
                Frontend REST API Endpoint:
            </div>
            <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 2px;">
                Fetch active team members via <code style="color: #4ade80; background: rgba(0,0,0,0.5); padding: 2px 6px; border-radius: 4px;">GET /api/team</code>
            </div>
        </div>
    </div>
    <div style="display: flex; gap: 16px; font-size: 13px;">
        <span style="color: var(--color-text-secondary);">Total: <strong style="color: #fff;">{{ $totalMembers }}</strong></span>
        <span style="color: var(--color-text-secondary);">Active: <strong style="color: #4ade80;">{{ $activeMembers }}</strong></span>
    </div>
</div>

<!-- Search & Filters -->
<div style="margin-bottom: 20px;">
    <form action="{{ route('admin.team.index') }}" method="GET" style="display: flex; gap: 12px; max-width: 420px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, position or mail..." style="flex: 1; padding: 10px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px; outline: none;">
        <button type="submit" class="btn btn-secondary btn-sm" style="background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); color: #fff; padding: 10px 16px; border-radius: 6px; cursor: pointer;">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('admin.team.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; color: var(--color-text-muted); text-decoration: none; padding: 10px 12px;">Clear</a>
        @endif
    </form>
</div>

<!-- Members Table -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
    @if($members->isEmpty())
        <div style="text-align: center; padding: 60px 20px; color: var(--color-text-muted);">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.04); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
            <p style="font-size: 15px; margin-bottom: 12px;">No team members found.</p>
            <a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-sm" style="text-decoration: none;">+ Add First Member</a>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--color-dark-border); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 14px 18px;">Photo</th>
                        <th style="padding: 14px 18px;">Name & Position</th>
                        <th style="padding: 14px 18px;">Mail</th>
                        <th style="padding: 14px 18px;">Order</th>
                        <th style="padding: 14px 18px;">Status</th>
                        <th style="padding: 14px 18px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s ease;">
                            <!-- Photo -->
                            <td style="padding: 14px 18px; width: 70px;">
                                <div style="width: 52px; height: 60px; border-radius: 8px 8px 4px 4px; overflow: hidden; background: #060706; border: 1px solid rgba(34, 197, 94, 0.3); display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=16a34a&color=fff';">
                                </div>
                            </td>

                            <!-- Name & Position -->
                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 700; color: #fff; font-size: 15px; font-family: var(--font-clash);">
                                    {{ $member->name }}
                                </div>
                                <div style="font-size: 12px; color: var(--color-secondary-bright); margin-top: 2px; font-weight: 500;">
                                    {{ $member->position }}
                                </div>
                            </td>

                            <!-- Mail -->
                            <td style="padding: 14px 18px;">
                                @if($member->email)
                                    <a href="mailto:{{ $member->email }}" style="color: #4ade80; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                        <span>{{ $member->email }}</span>
                                    </a>
                                @else
                                    <span style="color: var(--color-text-muted); font-size: 12px;">—</span>
                                @endif
                            </td>

                            <!-- Order -->
                            <td style="padding: 14px 18px;">
                                <span style="background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; font-size: 12px; color: #e5e7eb;">
                                    #{{ $member->order }}
                                </span>
                            </td>

                            <!-- Status & Toggle -->
                            <td style="padding: 14px 18px;">
                                <form action="{{ route('admin.team.toggle-active', $member->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        @if($member->is_active)
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
                                    <a href="{{ route('admin.team.edit', $member->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px; text-decoration: none; border-radius: 6px;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.team.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $member->name }}?');" style="display: inline;">
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

        @if($members->hasPages())
            <div style="padding: 20px; border-top: 1px solid var(--color-dark-border);">
                {{ $members->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
