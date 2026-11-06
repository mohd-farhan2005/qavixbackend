@extends('layouts.admin')

@section('title', 'Application #' . $lead->id . ' - ' . $lead->full_name . ' | Launchpad')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Founder Profile
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            {{ $lead->full_name }}
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Applied on {{ $lead->created_at->format('M d, Y \a\t h:i A') }} ({{ $lead->created_at->diffForHumans() }})
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.launchpad.index') }}" class="btn btn-secondary" style="font-size: 13px; text-decoration: none;">
            ← Back to All Applications
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px; align-items: start;">
    <!-- LEFT: Lead Details & Notes -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Founder Card -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid var(--color-dark-border);">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 52px; height: 52px; border-radius: 12px; background: rgba(39, 184, 21, 0.15); color: #4ade80; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; font-family: var(--font-clash);">
                        {{ strtoupper(substr($lead->full_name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 style="font-family: var(--font-clash); font-size: 20px; font-weight: 700; color: #fff; margin: 0;">
                            {{ $lead->full_name }}
                        </h2>
                        <div style="font-size: 13px; color: var(--color-secondary-bright); margin-top: 2px;">
                            Launchpad Cohort Applicant #{{ $lead->id }}
                        </div>
                    </div>
                </div>

                <!-- 1-Click WhatsApp Direct Chat Button -->
                <a href="{{ $lead->whatsapp_link }}" target="_blank" class="btn btn-primary" style="background: #25D366; color: #000; font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; padding: 10px 18px; border-radius: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>Message on WhatsApp</span>
                </a>
            </div>

            <!-- Details Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">Startup Name</div>
                    <div style="font-size: 16px; color: #fff; font-weight: 600;">
                        {{ $lead->startup_name ?: 'Not provided' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">Industry</div>
                    <div style="font-size: 16px; color: #fff; font-weight: 600;">
                        {{ $lead->industry ?: 'General' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">WhatsApp Number</div>
                    <div style="font-size: 15px; color: #4ade80; font-family: monospace; font-weight: 600;">
                        {{ $lead->whatsapp_number }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">Email Address</div>
                    <a href="mailto:{{ $lead->email }}" style="font-size: 15px; color: #60a5fa; text-decoration: none;">
                        {{ $lead->email }}
                    </a>
                </div>

                <div>
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">IP Address</div>
                    <div style="font-size: 13px; color: var(--color-text-secondary); font-family: monospace;">
                        {{ $lead->ip_address ?: 'Unknown' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">Received Date</div>
                    <div style="font-size: 13px; color: var(--color-text-secondary);">
                        {{ $lead->created_at->toDayDateTimeString() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Internal CRM Notes -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px;">
            <h3 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 12px;">
                Internal Team Notes
            </h3>
            <p style="font-size: 13px; color: var(--color-text-muted); margin-bottom: 16px;">
                Add private notes regarding your calls, pitches, founder evaluation, and onboarding status.
            </p>

            <form action="{{ route('admin.launchpad.update-status', $lead->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $lead->status }}">

                <div style="margin-bottom: 16px;">
                    <textarea name="notes" rows="5" placeholder="Write internal notes here (e.g. Founder pitched AI analytics product, scheduled demo for Friday)..." style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px; font-family: inherit; line-height: 1.6;">{{ old('notes', $lead->notes) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; background: var(--color-secondary); color: #fff; font-weight: 600; font-size: 13px; border: none; border-radius: 6px; cursor: pointer;">
                    Save Notes
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT: Status Control & Actions -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
            <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                Application Status
            </h3>

            <form action="{{ route('admin.launchpad.update-status', $lead->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="notes" value="{{ $lead->notes }}">

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">
                        Pipeline Stage
                    </label>
                    <select name="status" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; padding: 10px 12px; color: #fff; font-size: 13px; font-weight: 600;">
                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New Application</option>
                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted on WhatsApp</option>
                        <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>Qualified Founder</option>
                        <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted / Onboarded</option>
                        <option value="archived" {{ $lead->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; background: var(--color-secondary); color: #fff; font-weight: 600; font-size: 13px; border: none; border-radius: 6px; cursor: pointer;">
                    Update Stage
                </button>
            </form>
        </div>

        <!-- Quick Actions Card -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
            <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                Manage
            </h3>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <form action="{{ route('admin.launchpad.toggle-read', $lead->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="width: 100%; justify-content: center; font-size: 13px;">
                        {{ $lead->is_read ? 'Mark as Unread' : 'Mark as Read' }}
                    </button>
                </form>

                <form action="{{ route('admin.launchpad.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Permanently delete this founder application?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary" style="width: 100%; justify-content: center; font-size: 13px; color: #f87171; border-color: rgba(239, 68, 68, 0.2);">
                        Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

