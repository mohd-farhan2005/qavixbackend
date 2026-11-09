@extends('layouts.admin')

@section('title', 'Categories Management | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Content Taxonomy
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Blog Categories
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Organize articles into thematic clusters to boost SEO topical authority.
        </p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 28px;">
    <!-- Add Category Form -->
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px; height: fit-content;">
        <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 20px;">
            Add New Category
        </h2>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 16px;">
                <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Category Name <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="name" id="name" required placeholder="e.g. Artificial Intelligence" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label for="slug" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Slug (optional)
                </label>
                <input type="text" name="slug" id="slug" placeholder="auto-generated-if-empty" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 13px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label for="description" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Description
                </label>
                <textarea name="description" id="description" rows="2" placeholder="Brief description of this category..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px; font-family: inherit;"></textarea>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="meta_title" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Category SEO Title
                </label>
                <input type="text" name="meta_title" id="meta_title" placeholder="SEO title for category archive" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
            </div>

            <div style="margin-bottom: 24px;">
                <label for="meta_description" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Category SEO Description
                </label>
                <textarea name="meta_description" id="meta_description" rows="2" placeholder="Meta description for category archive..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px; font-family: inherit;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; background: var(--color-secondary); color: #fff; border: none; border-radius: 6px; font-weight: 600; font-family: var(--font-clash); font-size: 14px; cursor: pointer;">
                Add Category
            </button>
        </form>
    </div>

    <!-- Category List Table -->
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-dark-border); background: rgba(255,255,255,0.02); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 14px 18px;">Name</th>
                    <th style="padding: 14px 18px;">Slug</th>
                    <th style="padding: 14px 18px;">Articles</th>
                    <th style="padding: 14px 18px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                        <td style="padding: 14px 18px;">
                            <div style="font-weight: 600; color: #fff;">{{ $category->name }}</div>
                            @if($category->description)
                                <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 2px;">{{ Str::limit($category->description, 50) }}</div>
                            @endif
                        </td>
                        <td style="padding: 14px 18px; color: #4ade80; font-family: monospace; font-size: 13px;">
                            {{ $category->slug }}
                        </td>
                        <td style="padding: 14px 18px; color: #e5e7eb;">
                            <span style="background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; font-size: 12px;">
                                {{ $category->blogs_count }} posts
                            </span>
                        </td>
                        <td style="padding: 14px 18px; text-align: right;">
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete category? Posts will be detached.');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 10px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; color: #f87171; font-size: 12px; cursor: pointer;">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

