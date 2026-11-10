@extends('layouts.admin')

@section('title', 'Edit Industry | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Edit: {{ $industry->name }}
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Update industry details, slug, or display order.
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
        <form action="{{ route('admin.industries.update', $industry->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div style="margin-bottom: 22px;">
                <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Industry Name <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $industry->name) }}" required autofocus style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
            </div>

            <!-- Slug -->
            <div style="margin-bottom: 22px;">
                <label for="slug" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    API Slug
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $industry->slug) }}" required style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #4ade80; font-size: 13px; outline: none;">
            </div>

            <!-- Description -->
            <div style="margin-bottom: 22px;">
                <label for="description" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Description (Optional)
                </label>
                <textarea name="description" id="description" rows="3" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; font-family: inherit; outline: none;">{{ old('description', $industry->description) }}</textarea>
            </div>

            <!-- Display Order & Active Toggle -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;">
                <div>
                    <label for="order" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Display Order
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', $industry->order) }}" min="0" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Status
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; margin-top: 12px; cursor: pointer; color: #e5e7eb; font-size: 14px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $industry->is_active) ? 'checked' : '' }} style="accent-color: var(--color-secondary-bright); width: 18px; height: 18px;">
                        <span>Active</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid var(--color-dark-border);">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; border: none; cursor: pointer;">
                    <span>Update Industry</span>
                </button>
                <a href="{{ route('admin.industries.index') }}" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
