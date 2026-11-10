@extends('layouts.admin')

@section('title', 'Add Industry | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Add New Industry
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Create an industry vertical to group your clients on the frontend.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.industries.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
            ← Back to Industries
        </a>
    </div>
</div>

<div style="max-width: 680px;">
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.6);">
        <form action="{{ route('admin.industries.store') }}" method="POST">
            @csrf

            <!-- Name -->
            <div style="margin-bottom: 22px;">
                <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Industry Name <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Fintech & Web3, AI & SaaS, Healthcare & MedTech" required autofocus style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
            </div>

            <!-- Slug -->
            <div style="margin-bottom: 22px;">
                <label for="slug" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    API Slug (Optional)
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="e.g. fintech-web3 (leave blank to auto-generate)" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #4ade80; font-size: 13px; outline: none;">
                <span style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px; display: block;">Used in API queries like <code>/api/clients?industry=fintech-web3</code></span>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 22px;">
                <label for="description" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Description (Optional)
                </label>
                <textarea name="description" id="description" rows="3" placeholder="Brief summary of this industry sector..." style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; font-family: inherit; outline: none;">{{ old('description') }}</textarea>
            </div>

            <!-- Display Order & Active Toggle -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;">
                <div>
                    <label for="order" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Display Order
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                    <span style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px; display: block;">Lower number appears first (0, 1, 2...)</span>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Status
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; margin-top: 12px; cursor: pointer; color: #e5e7eb; font-size: 14px;">
                        <input type="checkbox" name="is_active" value="1" checked style="accent-color: var(--color-secondary-bright); width: 18px; height: 18px;">
                        <span>Active (Available in dropdown & API)</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid var(--color-dark-border);">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; border: none; cursor: pointer;">
                    <span>Save Industry</span>
                </button>
                <a href="{{ route('admin.industries.index') }}" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

