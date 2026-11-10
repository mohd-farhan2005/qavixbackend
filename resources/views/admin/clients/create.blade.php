@extends('layouts.admin')

@section('title', 'Add Client | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Add New Client
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Add a client or brand logo with assigned industry vertical.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
            ← Back to Clients
        </a>
    </div>
</div>

<div style="max-width: 740px;">
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.6);">
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Client Name -->
            <div style="margin-bottom: 22px;">
                <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Client / Company Name <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Stripe, Airbnb, Spotify, OpenAI" required autofocus style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
            </div>

            <!-- Industry Dropdown (As requested by user!) -->
            <div style="margin-bottom: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label for="industry_id" style="font-size: 13px; font-weight: 600; color: #e5e7eb;">
                        Industry Vertical <span style="color: var(--color-secondary-bright);">*</span>
                    </label>
                    <a href="{{ route('admin.industries.create') }}" target="_blank" style="font-size: 12px; color: var(--color-secondary-bright); text-decoration: none;">
                        + Create New Industry
                    </a>
                </div>
                <select name="industry_id" id="industry_id" style="width: 100%; padding: 12px 14px; background: #090b09; border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none; cursor: pointer;">
                    <option value="">-- Select Industry Vertical --</option>
                    @foreach($industries as $industry)
                        <option value="{{ $industry->id }}" {{ old('industry_id') == $industry->id ? 'selected' : '' }}>
                            {{ $industry->name }}
                        </option>
                    @endforeach
                </select>
                <span style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px; display: block;">
                    Choose which industry category this client belongs to.
                </span>
            </div>

            <!-- Logo Upload & URL Option -->
            <div style="margin-bottom: 24px; padding: 20px; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 4px;">
                    Client Logo
                </label>
                <p style="font-size: 12px; color: var(--color-text-secondary); margin-bottom: 16px;">
                    Upload a high-res SVG, PNG, or WebP logo file (transparent background recommended) or enter an image URL.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <!-- Option 1: File Upload -->
                    <div>
                        <label for="logo" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Option 1: Upload Logo File
                        </label>
                        <input type="file" name="logo" id="logo" accept="image/*" onchange="previewFile(event)" style="width: 100%; font-size: 12px; color: var(--color-text-secondary);">
                    </div>

                    <!-- Option 2: Image URL -->
                    <div>
                        <label for="logo_url" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Option 2: Direct Logo URL
                        </label>
                        <input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url') }}" placeholder="https://example.com/logo.svg" oninput="previewUrl(this.value)" style="width: 100%; padding: 8px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 12px; outline: none;">
                    </div>
                </div>

                <!-- Preview Area -->
                <div style="margin-top: 16px; display: flex; align-items: center; gap: 16px;">
                    <div style="width: 120px; height: 60px; border-radius: 10px; background: #060706; border: 1px solid rgba(255, 255, 255, 0.1); padding: 8px; display: flex; align-items: center; justify-content: center;">
                        <img id="logo-preview" src="https://ui-avatars.com/api/?name=Client&background=0d0f0d&color=22c55e&size=128" alt="Logo preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <div style="font-size: 12px; color: var(--color-text-muted);">
                        Logo preview on dark background.
                    </div>
                </div>
            </div>

            <!-- Website URL -->
            <div style="margin-bottom: 22px;">
                <label for="website_url" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Client Website URL (Optional)
                </label>
                <div style="position: relative;">
                    <input type="url" name="website_url" id="website_url" value="{{ old('website_url') }}" placeholder="https://client-website.com" style="width: 100%; padding: 12px 14px 12px 42px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 22px;">
                <label for="description" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Client Notes / Project Summary (Optional)
                </label>
                <textarea name="description" id="description" rows="3" placeholder="Brief note about the project or collaboration with this client..." style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; font-family: inherit; outline: none;">{{ old('description') }}</textarea>
            </div>

            <!-- Options: Featured, Order & Active -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 28px;">
                <div>
                    <label for="order" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Display Order
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Homepage Feature
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; margin-top: 12px; cursor: pointer; color: #e5e7eb; font-size: 13px;">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} style="accent-color: var(--color-secondary-bright); width: 18px; height: 18px;">
                        <span>Show in Carousel</span>
                    </label>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Status
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; margin-top: 12px; cursor: pointer; color: #e5e7eb; font-size: 13px;">
                        <input type="checkbox" name="is_active" value="1" checked style="accent-color: var(--color-secondary-bright); width: 18px; height: 18px;">
                        <span>Active</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid var(--color-dark-border);">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; border: none; cursor: pointer;">
                    <span>Save Client</span>
                </button>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function previewUrl(url) {
        if (url && url.length > 5) {
            document.getElementById('logo-preview').src = url;
        }
    }
</script>
@endsection
