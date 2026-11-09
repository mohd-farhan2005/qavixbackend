@extends('layouts.admin')

@section('title', 'Launchpad Applications | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Startup Ecosystem
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Launchpad Leads & Applications
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Founder applications submitted from the React frontend (<code>/launchpad</code>).
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ url('/api/launchpad') }}" target="_blank" class="btn btn-secondary" style="font-size: 13px; text-decoration: none;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
            </svg>
            <span>Public API Endpoint: POST /api/launchpad</span>
        </a>
    </div>
</div>

<!-- STATS OVERVIEW -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 18px; margin-bottom: 28px;">
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 20px 24px;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">Total Applicants</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #fff;">{{ $totalLeads }}</div>
        <div style="font-size: 11px; color: var(--color-secondary-bright); margin-top: 4px;">Goal: First 100 Founders</div>
    </div>
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 20px 24px;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">New / Unread</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #4ade80;">{{ $unreadLeads }}</div>
        <div style="font-size: 11px; color: #86efac; margin-top: 4px;">{{ $newLeads }} awaiting initial contact</div>
    </div>
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 20px 24px;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">Contacted on WhatsApp</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #38bdf8;">{{ $contactedLeads }}</div>
        <div style="font-size: 11px; color: #7dd3fc; margin-top: 4px;">In discovery discussions</div>
    </div>
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 20px 24px;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">Qualified / Converted</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #facc15;">{{ $qualifiedLeads }}</div>
        <div style="font-size: 11px; color: #fde68a; margin-top: 4px;">Selected for cohort</div>
    </div>
</div>

<!-- FILTERS & SEARCH -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 18px 24px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.launchpad.index') }}" style="display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 260px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--color-text-muted);">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by founder, email, startup, or WhatsApp..." style="background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; padding: 8px 12px; color: #fff; width: 100%; font-size: 13px;">
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <select name="status" style="background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; padding: 8px 12px; color: #fff; font-size: 13px;">
                <option value="all">All Statuses</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
                <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>

            <select name="industry" style="background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; padding: 8px 12px; color: #fff; font-size: 13px;">
                <option value="all">All Industries</option>
                @foreach($industriesList as $ind)
                    <option value="{{ $ind }}" {{ request('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px; background: var(--color-secondary); color: #fff; font-weight: 600; font-size: 13px; border: none; border-radius: 6px; cursor: pointer;">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status', 'industry']))
                <a href="{{ route('admin.launchpad.index') }}" class="btn btn-secondary btn-sm" style="padding: 8px 12px; font-size: 13px; text-decoration: none;">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

<!-- LEADS TABLE -->
<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-dark-border); background: rgba(255,255,255,0.02); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 14px 18px;">Founder & Startup</th>
                    <th style="padding: 14px 18px;">Direct Contact (WhatsApp / Email)</th>
                    <th style="padding: 14px 18px;">Industry</th>
                    <th style="padding: 14px 18px;">Status</th>
                    <th style="padding: 14px 18px;">Applied</th>
                    <th style="padding: 14px 18px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr style="border-bottom: 1px solid var(--color-dark-border); transition: background 0.15s ease; {{ !$lead->is_read ? 'background: rgba(39, 184, 21, 0.04);' : '' }}">
                        <!-- Founder Name & Startup -->
                        <td style="padding: 16px 18px; vertical-align: middle;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(39, 184, 21, 0.15); color: #4ade80; display: flex; align-items: center; justify-content: center; font-weight: 700; font-family: var(--font-clash); flex-shrink: 0;">
                                    {{ strtoupper(substr($lead->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #fff; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                                        <span>{{ $lead->full_name }}</span>
                                        @if(!$lead->is_read)
                                            <span style="background: rgba(34, 197, 94, 0.2); color: #4ade80; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(34, 197, 94, 0.4);">NEW</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 13px; color: var(--color-text-secondary); margin-top: 2px;">
                                        @if($lead->startup_name)
                                            🚀 <strong style="color: #e5e7eb;">{{ $lead->startup_name }}</strong>
                                        @else
                                            <span style="color: var(--color-text-muted); font-style: italic;">No startup name provided</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Contact (WhatsApp & Email) -->
                        <td style="padding: 16px 18px; vertical-align: middle;">
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <!-- 1-Click WhatsApp Button -->
                                <a href="{{ $lead->whatsapp_link }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: #4ade80; text-decoration: none; font-size: 13px; font-weight: 600; width: fit-content;" title="Open WhatsApp Chat directly">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>{{ $lead->whatsapp_number }}</span>
                                    <span style="font-size: 11px; opacity: 0.8;">↗ Chat</span>
                                </a>

                                <!-- Email -->
                                <a href="mailto:{{ $lead->email }}" style="display: inline-flex; align-items: center; gap: 6px; color: var(--color-text-secondary); text-decoration: none; font-size: 13px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <span>{{ $lead->email }}</span>
                                </a>
                            </div>
                        </td>

                        <!-- Industry -->
                        <td style="padding: 16px 18px; vertical-align: middle;">
                            @if($lead->industry)
                                <span style="background: rgba(255, 255, 255, 0.06); border: 1px solid var(--color-dark-border); padding: 4px 10px; border-radius: 6px; font-size: 12px; color: #e5e7eb;">
                                    {{ $lead->industry }}
                                </span>
                            @else
                                <span style="color: var(--color-text-muted); font-size: 13px;">—</span>
                            @endif
                        </td>

                        <!-- Status Pill / Changer -->
                        <td style="padding: 16px 18px; vertical-align: middle;">
                            <form action="{{ route('admin.launchpad.update-status', $lead->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; padding: 4px 8px; font-size: 12px; font-weight: 600; cursor: pointer; color: 
                                    @if($lead->status === 'new') #4ade80
                                    @elseif($lead->status === 'contacted') #38bdf8
                                    @elseif($lead->status === 'qualified') #facc15
                                    @elseif($lead->status === 'converted') #a78bfa
                                    @else #94a3b8 @endif
                                ;">
                                    <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>● New</option>
                                    <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>● Contacted</option>
                                    <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>● Qualified</option>
                                    <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>● Converted</option>
                                    <option value="archived" {{ $lead->status === 'archived' ? 'selected' : '' }}>○ Archived</option>
                                </select>
                            </form>
                        </td>

                        <!-- Date -->
                        <td style="padding: 16px 18px; vertical-align: middle; font-size: 13px; color: var(--color-text-muted);">
                            {{ $lead->created_at->diffForHumans() }}
                        </td>

                        <!-- Actions -->
                        <td style="padding: 16px 18px; vertical-align: middle; text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 8px;">
                                <a href="{{ route('admin.launchpad.show', $lead->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">
                                    View
                                </a>

                                <form action="{{ route('admin.launchpad.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Delete this founder application?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="padding: 6px 10px; color: #f87171; border-color: rgba(239, 68, 68, 0.2);" title="Delete">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--color-text-muted);">
                            <div style="font-size: 32px; margin-bottom: 8px;">🚀</div>
                            <div style="font-size: 16px; color: #fff; font-weight: 600; margin-bottom: 4px;">No Launchpad applications found</div>
                            <p style="font-size: 13px;">When founders submit the form at <code>/launchpad</code>, their details will appear here instantly.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leads->hasPages())
        <div style="padding: 18px 24px; border-top: 1px solid var(--color-dark-border);">
            {{ $leads->links() }}
        </div>
    @endif
</div>
@endsection

