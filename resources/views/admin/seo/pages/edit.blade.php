@extends('layouts.admin')

@section('title', 'Edit Route SEO: ' . $page->page_name . ' | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            SEO Architecture
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Edit Meta Tags: {{ $page->page_name }} ({{ $page->path }})
        </h1>
    </div>

    <a href="{{ route('admin.seo.pages.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); border-radius: var(--radius-sm); color: #fff; text-decoration: none; font-size: 13px;">
        ← Back to Pages
    </a>
</div>

<form action="{{ route('admin.seo.pages.update', $page->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px;">
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Target Page Details -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px;">
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 20px;">
                    Target React Page / Route
                </h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label for="page_name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            Page Name <span style="color: var(--color-secondary-bright);">*</span>
                        </label>
                        <input type="text" name="page_name" id="page_name" value="{{ old('page_name', $page->page_name) }}" required style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                    </div>

                    <div>
                        <label for="path" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            URL Route Path <span style="color: var(--color-secondary-bright);">*</span>
                        </label>
                        <input type="text" name="path" id="path" value="{{ old('path', $page->path) }}" required style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Meta Tags & SERP Preview -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-secondary-border); border-radius: var(--radius-lg); padding: 28px;">
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 20px;">
                    Search Engine Meta Tags
                </h2>

                <!-- Live SERP Preview -->
                <div style="margin-bottom: 24px;">
                    <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 700; margin-bottom: 8px;">
                        Google Search Snippet Preview
                    </div>
                    <div class="live-preview-box">
                        <div class="serp-url" id="preview-url">http://localhost/QAVYX-back<span>{{ $page->path }}</span></div>
                        <div class="serp-title" id="preview-title">{{ $page->meta_title ?: $page->page_name }} | QAVYX</div>
                        <div class="serp-desc" id="preview-desc">{{ $page->meta_description ?: 'Enter a meta description to see how this page will appear in Google search results...' }}</div>
                    </div>
                </div>

                <!-- Meta Title -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="meta_title" style="font-size: 13px; font-weight: 600; color: #e5e7eb;">
                            Meta Title (&lt;title&gt;)
                        </label>
                        <span id="title-counter" style="font-size: 11px; color: var(--color-text-muted);">{{ strlen($page->meta_title ?? '') }} / 60 chars</span>
                    </div>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                </div>

                <!-- Meta Description -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="meta_description" style="font-size: 13px; font-weight: 600; color: #e5e7eb;">
                            Meta Description (&lt;meta name="description"&gt;)
                        </label>
                        <span id="desc-counter" style="font-size: 11px; color: var(--color-text-muted);">{{ strlen($page->meta_description ?? '') }} / 160 chars</span>
                    </div>
                    <textarea name="meta_description" id="meta_description" rows="3" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px; font-family: inherit;">{{ old('meta_description', $page->meta_description) }}</textarea>
                </div>

                <!-- Meta Keywords & Canonical -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label for="meta_keywords" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                            Meta Keywords
                        </label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                    <div>
                        <label for="canonical_url" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                            Canonical URL
                        </label>
                        <input type="url" name="canonical_url" id="canonical_url" value="{{ old('canonical_url', $page->canonical_url) }}" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                </div>

                <!-- Robots Directives -->
                <div style="margin-bottom: 24px;">
                    <label for="robots" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Robots Directives (&lt;meta name="robots"&gt;)
                    </label>
                    <select name="robots" id="robots" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                        <option value="index, follow" {{ $page->robots === 'index, follow' ? 'selected' : '' }}>index, follow (Allow Google to index and follow links)</option>
                        <option value="noindex, follow" {{ $page->robots === 'noindex, follow' ? 'selected' : '' }}>noindex, follow (Hide page from search results, follow links)</option>
                        <option value="noindex, nofollow" {{ $page->robots === 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow (Complete block from search engines)</option>
                    </select>
                </div>

                <!-- Social OpenGraph -->
                <h3 style="font-family: var(--font-clash); font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 14px;">
                    Social Media Sharing (OpenGraph & Twitter Card)
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label for="og_title" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                            OpenGraph Title
                        </label>
                        <input type="text" name="og_title" id="og_title" value="{{ old('og_title', $page->og_title) }}" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                    <div>
                        <label for="og_image" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                            OpenGraph Social Image URL
                        </label>
                        <input type="text" name="og_image" id="og_image" value="{{ old('og_image', $page->og_image) }}" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="og_description" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                        OpenGraph Description
                    </label>
                    <textarea name="og_description" id="og_description" rows="2" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">{{ old('og_description', $page->og_description) }}</textarea>
                </div>

                <!-- Structured Data (JSON-LD) -->
                <div>
                    <label for="schema_markup_json" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                        Custom Schema.org Structured Data (JSON-LD)
                    </label>
                    <textarea name="schema_markup_json" id="schema_markup_json" rows="4" placeholder="{{ '{ &quot;@context&quot;: &quot;https://schema.org&quot;, &quot;@type&quot;: &quot;WebPage&quot; }' }}" style="width: 100%; padding: 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 12px;">{{ old('schema_markup_json', $page->schema_markup ? json_encode($page->schema_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Save -->
        <div>
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px; position: sticky; top: 24px;">
                <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                    Update Configuration
                </h3>

                <p style="font-size: 13px; color: var(--color-text-secondary); line-height: 1.5; margin-bottom: 20px;">
                    Updates take effect immediately on your React frontend for route <code style="color: #4ade80;">{{ $page->path }}</code>.
                </p>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; background: var(--color-secondary); color: #fff; border: none; border-radius: var(--radius-sm); font-weight: 700; font-family: var(--font-clash); font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Update Page SEO</span>
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    const nameInput = document.getElementById('page_name');
    const pathInput = document.getElementById('path');
    const metaTitleInput = document.getElementById('meta_title');
    const metaDescInput = document.getElementById('meta_description');

    const previewTitle = document.getElementById('preview-title');
    const previewDesc = document.getElementById('preview-desc');
    const previewUrl = document.getElementById('preview-url').querySelector('span');
    const titleCounter = document.getElementById('title-counter');
    const descCounter = document.getElementById('desc-counter');

    function updatePreview() {
        const titleVal = metaTitleInput.value.trim() || nameInput.value.trim() || 'Page Name';
        const descVal = metaDescInput.value.trim() || 'Enter a meta description to see how this page will appear in Google search results...';
        const pathVal = pathInput.value.trim() || '/';

        previewTitle.textContent = titleVal + ' | QAVYX';
        previewDesc.textContent = descVal;
        previewUrl.textContent = pathVal;

        titleCounter.textContent = `${metaTitleInput.value.length} / 60 chars`;
        descCounter.textContent = `${metaDescInput.value.length} / 160 chars`;

        titleCounter.style.color = metaTitleInput.value.length > 60 ? '#f87171' : 'var(--color-text-muted)';
        descCounter.style.color = metaDescInput.value.length > 160 ? '#f87171' : 'var(--color-text-muted)';
    }

    nameInput.addEventListener('input', updatePreview);
    pathInput.addEventListener('input', updatePreview);
    metaTitleInput.addEventListener('input', updatePreview);
    metaDescInput.addEventListener('input', updatePreview);

    updatePreview();
</script>
@endpush
@endsection

