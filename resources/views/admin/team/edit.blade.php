@extends('layouts.admin')

@section('title', 'Edit Team Member | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Edit: {{ $member->name }}
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Update information, position, email, or photo for this team member.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.team.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
            ← Back to Team
        </a>
    </div>
</div>

<div style="max-width: 720px;">
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.6);">
        <form action="{{ route('admin.team.update', $member->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div style="margin-bottom: 22px;">
                <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Full Name <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required autofocus style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
            </div>

            <!-- Position -->
            <div style="margin-bottom: 22px;">
                <label for="position" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Position / Role <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="position" id="position" value="{{ old('position', $member->position) }}" required style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
            </div>

            <!-- Mail -->
            <div style="margin-bottom: 22px;">
                <label for="email" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Email Address (Mail)
                </label>
                <div style="position: relative;">
                    <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" placeholder="e.g. zack@qavyx.com" style="width: 100%; padding: 12px 14px 12px 42px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Photo Upload & URL Option -->
            <div style="margin-bottom: 24px; padding: 20px; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 4px;">
                    Member Photo
                </label>
                <p style="font-size: 12px; color: var(--color-text-secondary); margin-bottom: 16px;">
                    Upload a new image file to replace current photo, or provide an image URL.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <!-- Option A: File Upload -->
                    <div>
                        <label for="photo" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Upload New File
                        </label>
                        <input type="file" name="photo" id="photo" accept="image/*" onchange="previewFile(event)" style="width: 100%; font-size: 12px; color: var(--color-text-secondary);">
                    </div>

                    <!-- Option B: Direct URL -->
                    <div>
                        <label for="photo_url" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Or Image URL
                        </label>
                        <input type="url" name="photo_url" id="photo_url" value="{{ old('photo_url', str_starts_with($member->photo ?? '', 'http') ? $member->photo : '') }}" placeholder="https://example.com/photo.jpg" oninput="previewUrl(this.value)" style="width: 100%; padding: 8px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 12px; outline: none;">
                    </div>
                </div>

                <!-- Preview Area -->
                <div style="margin-top: 16px; display: flex; align-items: center; gap: 16px;">
                    <div style="width: 80px; height: 95px; border-radius: 12px; background: #060706; border: 1px solid rgba(34, 197, 94, 0.4); overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                        <img id="image-preview" src="{{ $member->photo_url }}" alt="{{ $member->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="font-size: 12px; color: var(--color-text-muted);">
                        Current photo preview.
                    </div>
                </div>
            </div>

            <!-- Display Order & Active Toggle -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;">
                <div>
                    <label for="order" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Display Order
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', $member->order) }}" min="0" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                    <span style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px; display: block;">Lower number appears first (0, 1, 2...)</span>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Frontend Visibility
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; margin-top: 12px; cursor: pointer; color: #e5e7eb; font-size: 14px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }} style="accent-color: var(--color-secondary-bright); width: 18px; height: 18px;">
                        <span>Publish to Frontend API</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid var(--color-dark-border);">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; border: none; cursor: pointer;">
                    <span>Update Team Member</span>
                </button>
                <a href="{{ route('admin.team.index') }}" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px; text-decoration: none;">
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
                document.getElementById('image-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function previewUrl(url) {
        if (url && url.length > 5) {
            document.getElementById('image-preview').src = url;
        }
    }
</script>
@endsection
