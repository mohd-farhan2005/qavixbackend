@extends('layouts.admin')

@section('title', 'Customer Inquiries & Leads | QAVIX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Communications
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Customer Inquiries & Leads
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Messages submitted by potential clients via your React frontend contact form.
        </p>
    </div>
</div>

<div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
    @if($contacts->isEmpty())
        <div style="text-align: center; padding: 60px 20px; color: var(--color-text-muted);">
            No customer inquiries yet.
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-dark-border); background: rgba(255,255,255,0.02); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 14px 18px;">Sender</th>
                    <th style="padding: 14px 18px;">Email / Phone</th>
                    <th style="padding: 14px 18px;">Subject / Message</th>
                    <th style="padding: 14px 18px;">Status</th>
                    <th style="padding: 14px 18px;">Date</th>
                    <th style="padding: 14px 18px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); {{ !$contact->is_read ? 'background: rgba(39, 184, 21, 0.03);' : '' }}">
                        <td style="padding: 14px 18px; font-weight: 600; color: #fff;">
                            {{ $contact->name }}
                        </td>
                        <td style="padding: 14px 18px; font-size: 13px; color: var(--color-text-secondary);">
                            <div>{{ $contact->email }}</div>
                            @if($contact->phone)
                                <div style="color: var(--color-text-muted); font-size: 12px;">{{ $contact->phone }}</div>
                            @endif
                        </td>
                        <td style="padding: 14px 18px; max-width: 320px;">
                            <div style="font-weight: 600; color: #fff; font-size: 13px;">{{ $contact->subject ?: 'No Subject' }}</div>
                            <div style="color: var(--color-text-secondary); font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $contact->message }}</div>
                        </td>
                        <td style="padding: 14px 18px;">
                            @if($contact->is_read)
                                <span style="font-size: 11px; color: var(--color-text-muted);">Read</span>
                            @else
                                <span style="background: rgba(39, 184, 21, 0.15); color: #4ade80; border: 1px solid var(--color-secondary-border); padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">New Lead</span>
                            @endif
                        </td>
                        <td style="padding: 14px 18px; font-size: 12px; color: var(--color-text-muted);">
                            {{ $contact->created_at->format('M d, H:i') }}
                        </td>
                        <td style="padding: 14px 18px; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" style="padding: 6px 12px; background: var(--color-secondary); color: #fff; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    View
                                </a>

                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Delete inquiry?');" style="display: inline;">
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

        <div style="padding: 18px 24px; border-top: 1px solid var(--color-dark-border);">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection

