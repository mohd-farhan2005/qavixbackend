@extends('layouts.admin')

@section('title', 'Inquiry from ' . $contact->name . ' | QAVIX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Communications
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Inquiry from {{ $contact->name }}
        </h1>
    </div>

    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); border-radius: var(--radius-sm); color: #fff; text-decoration: none; font-size: 13px;">
        ← Back to Inquiries
    </a>
</div>

<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 32px; max-width: 800px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 20px; border-bottom: 1px solid var(--color-dark-border); margin-bottom: 24px;">
        <div>
            <h2 style="font-family: var(--font-clash); font-size: 20px; font-weight: 700; color: #fff;">
                {{ $contact->subject ?: 'General Inbound Inquiry' }}
            </h2>
            <div style="font-size: 13px; color: var(--color-text-secondary); margin-top: 4px;">
                From: <strong style="color: #fff;">{{ $contact->name }}</strong> (<a href="mailto:{{ $contact->email }}" style="color: var(--color-secondary-bright);">{{ $contact->email }}</a>)
                @if($contact->phone)
                    • Phone: <strong style="color: #fff;">{{ $contact->phone }}</strong>
                @endif
            </div>
        </div>

        <div style="text-align: right; font-size: 12px; color: var(--color-text-muted);">
            <div>Received {{ $contact->created_at->format('M d, Y - H:i') }}</div>
            <div style="margin-top: 4px;">IP: {{ $contact->ip_address ?: 'Unknown' }}</div>
        </div>
    </div>

    <div style="font-size: 15px; line-height: 1.8; color: #e5e7eb; white-space: pre-line; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 8px; padding: 24px; margin-bottom: 28px;">
        {{ $contact->message }}
    </div>

    <div style="display: flex; gap: 12px;">
        <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject ?: 'Your inquiry to Qavix') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--color-secondary); color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>Reply via Email</span>
        </a>

        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding: 12px 18px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; color: #f87171; font-size: 14px; font-weight: 600; cursor: pointer;">
                Delete Message
            </button>
        </form>
    </div>
</div>
@endsection

