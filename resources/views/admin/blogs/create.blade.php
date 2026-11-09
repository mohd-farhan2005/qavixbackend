@extends('layouts.admin')

@section('title', 'Create New Blog Post | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Content Studio
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Add New Blog Post & Configure SEO
        </h1>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-dark-border); border-radius: var(--radius-sm); color: #fff; text-decoration: none; font-size: 13px;">
            ← Back to Posts
        </a>
    </div>
</div>

<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px;">
        <!-- Left Column: Content & SEO Details -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Card 1: Article Main Content -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px;">
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 20px;">
                    Article Information
                </h2>

                <!-- Title -->
                <div style="margin-bottom: 20px;">
                    <label for="title" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Article Title <span style="color: var(--color-secondary-bright);">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. Mastering SEO in Modern React Applications" required autofocus style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 15px;">
                </div>

                <!-- Slug -->
                <div style="margin-bottom: 20px;">
                    <label for="slug" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        URL Slug (React route: /blog/{slug})
                    </label>
                    <div style="display: flex; align-items: center; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; padding-left: 12px;">
                        <span style="color: var(--color-text-muted); font-size: 13px;">/blog/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="auto-generated-from-title" style="flex: 1; padding: 12px; background: transparent; border: none; color: #4ade80; font-size: 13px; outline: none;">
                    </div>
                </div>

                <!-- Excerpt -->
                <div style="margin-bottom: 20px;">
                    <label for="excerpt" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Summary / Short Excerpt
                    </label>
                    <textarea name="excerpt" id="excerpt" rows="2" placeholder="Brief summary of the article (also used as default meta description if none provided)..." style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px; font-family: inherit;">{{ old('excerpt') }}</textarea>
                </div>

                <!-- Main Content (Rich Text WYSIWYG) -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label for="content" style="font-size: 13px; font-weight: 600; color: #e5e7eb;">
                            Article Content (Rich Text Editor) <span style="color: var(--color-secondary-bright);">*</span>
                        </label>
                        <span style="font-size: 11px; color: var(--color-text-muted);">Format paragraphs, headings, bullet points, links & tables</span>
                    </div>
                    <textarea name="content" id="content" rows="16" placeholder="Write full article content here...">{{ old('content') }}</textarea>
                </div>
            </div>

            <!-- Card 2: Comprehensive SEO Studio -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-secondary-border); border-radius: var(--radius-lg); padding: 28px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--color-dark-border);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 6px; background: rgba(39, 184, 21, 0.15); color: var(--color-secondary-bright); display: flex; align-items: center; justify-content: center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff;">
                                SEO Metadata & Search Tags
                            </h2>
                            <p style="font-size: 12px; color: var(--color-text-secondary);">
                                Customize exactly how this article appears on Google, Facebook, LinkedIn, and Twitter.
                            </p>
                        </div>
                    </div>
                    <span style="background: rgba(39, 184, 21, 0.1); border: 1px solid var(--color-secondary-border); color: #4ade80; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                        SEO Engine Active
                    </span>
                </div>

                <!-- Real-Time Google SERP Simulator -->
                <div style="margin-bottom: 24px;">
                    <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 700; margin-bottom: 8px;">
                        Live Google Search Engine Snippet Preview
                    </div>
                    <div class="live-preview-box">
                        <div class="serp-url" id="preview-url">http://localhost/QAVYX-back/blog/<span>your-post-slug</span></div>
                        <div class="serp-title" id="preview-title">Post Title | QAVYX</div>
                        <div class="serp-desc" id="preview-desc">Enter a meta description to see how this article will appear in Google search results...</div>
                    </div>
                </div>

                <!-- Meta Title -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="meta_title" style="font-size: 13px; font-weight: 600; color: #e5e7eb;">
                            SEO Meta Title (&lt;title&gt;)
                        </label>
                        <span id="title-counter" style="font-size: 11px; color: var(--color-text-muted);">0 / 60 chars (Recommended)</span>
                    </div>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" placeholder="Leave blank to use Article Title" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                </div>

                <!-- Meta Description -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="meta_description" style="font-size: 13px; font-weight: 600; color: #e5e7eb;">
                            SEO Meta Description (&lt;meta name="description"&gt;)
                        </label>
                        <span id="desc-counter" style="font-size: 11px; color: var(--color-text-muted);">0 / 160 chars (Recommended)</span>
                    </div>
                    <textarea name="meta_description" id="meta_description" rows="3" placeholder="Compelling summary for search results (recommended: 150-160 characters)..." style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px; font-family: inherit;">{{ old('meta_description') }}</textarea>
                </div>

                <!-- Meta Keywords & Canonical -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label for="meta_keywords" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                            Meta Keywords
                        </label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="react, seo, laravel, web design" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                    <div>
                        <label for="canonical_url" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                            Canonical URL (optional)
                        </label>
                        <input type="url" name="canonical_url" id="canonical_url" value="{{ old('canonical_url') }}" placeholder="https://example.com/blog/..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                </div>

                <!-- Indexing Directives (Robots) -->
                <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--color-dark-border); border-radius: 8px; padding: 14px 18px; margin-bottom: 24px; display: flex; gap: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #e5e7eb; cursor: pointer;">
                        <input type="checkbox" name="robots_index" value="1" checked style="accent-color: var(--color-secondary-bright);">
                        <span>Allow Search Engines to Index (index)</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #e5e7eb; cursor: pointer;">
                        <input type="checkbox" name="robots_follow" value="1" checked style="accent-color: var(--color-secondary-bright);">
                        <span>Follow Links on Page (follow)</span>
                    </label>
                </div>

                <!-- OpenGraph (Social) & Twitter -->
                <h3 style="font-family: var(--font-clash); font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 14px;">
                    Social Media Sharing (OpenGraph & Twitter Card)
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label for="og_title" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                            OpenGraph Title
                        </label>
                        <input type="text" name="og_title" id="og_title" value="{{ old('og_title') }}" placeholder="Title for Facebook / LinkedIn" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                    <div>
                        <label for="og_image" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                            OpenGraph Image URL
                        </label>
                        <input type="text" name="og_image" id="og_image" value="{{ old('og_image') }}" placeholder="https://... (defaults to featured image)" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="og_description" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                        OpenGraph Description
                    </label>
                    <textarea name="og_description" id="og_description" rows="2" placeholder="Description for social shares (defaults to meta description)..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">{{ old('og_description') }}</textarea>
                </div>

                <!-- Structured Data (JSON-LD) -->
                <div>
                    <label for="schema_markup_json" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                        Custom Schema.org Structured Data (JSON-LD)
                    </label>
                    <textarea name="schema_markup_json" id="schema_markup_json" rows="4" placeholder="{{ '{ &quot;@context&quot;: &quot;https://schema.org&quot;, &quot;@type&quot;: &quot;BlogPosting&quot; }' }}" style="width: 100%; padding: 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 12px;">{{ old('schema_markup_json') }}</textarea>
                    <div style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px;">
                        Leave blank to use automatic BlogPosting Schema.
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Publishing Controls -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Publish Action Box -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
                <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                    Publish Settings
                </h3>

                <!-- Status -->
                <div style="margin-bottom: 18px;">
                    <label for="status" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Post Status
                    </label>
                    <select name="status" id="status" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                        <option value="published" selected>Published (Visible on React Frontend)</option>
                        <option value="draft">Draft (Admin Only)</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <!-- Featured Toggle -->
                <div style="margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #e5e7eb; cursor: pointer;">
                        <input type="checkbox" name="is_featured" value="1" style="accent-color: var(--color-secondary-bright);">
                        <span>Featured on Homepage / Hero</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; background: var(--color-secondary); color: #fff; border: none; border-radius: var(--radius-sm); font-weight: 700; font-family: var(--font-clash); font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 20px rgba(39, 184, 21, 0.25);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Save & Publish Article</span>
                </button>
            </div>

            <!-- Category & Taxonomy Box -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
                <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                    Category & Tags
                </h3>

                <!-- Category -->
                <div style="margin-bottom: 18px;">
                    <label for="category_id" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Primary Category
                    </label>
                    <select name="category_id" id="category_id" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tags Input -->
                <div>
                    <label for="tags_input" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Tags (comma-separated)
                    </label>
                    <input type="text" name="tags_input" id="tags_input" value="{{ old('tags_input') }}" placeholder="React, SEO, Performance, Cloud" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                </div>
            </div>

            <!-- Featured Image Box -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
                <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                    Featured Image & Media
                </h3>

                <!-- Upload File -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Upload Image File
                    </label>
                    <input type="file" name="featured_image_file" accept="image/*" style="width: 100%; font-size: 13px; color: var(--color-text-secondary);">
                </div>

                <!-- Or URL -->
                <div style="margin-bottom: 16px;">
                    <label for="featured_image" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                        Or Image URL
                    </label>
                    <input type="text" name="featured_image" id="featured_image" value="{{ old('featured_image') }}" placeholder="https://images.unsplash.com/..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                </div>

                <!-- Alt Text for SEO -->
                <div>
                    <label for="featured_image_alt" style="display: block; font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                        Image Alt Text (&lt;img alt="..."&gt;)
                    </label>
                    <input type="text" name="featured_image_alt" id="featured_image_alt" value="{{ old('featured_image_alt') }}" placeholder="Descriptive alt text for Google Image SEO" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<!-- TinyMCE Rich Text Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 440,
        menubar: false,
        statusbar: true,
        plugins: 'advlist autolink lists link image table charmap preview anchor searchreplace visualblocks code wordcount',
        toolbar: 'undo redo | blocks | bold italic | link table blockquote | bullist numlist outdent indent',
        placeholder: 'Write full article content here...',
        branding: true,
        promotion: false,
        content_style: `
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                font-size: 15px;
                line-height: 1.6;
                color: #222;
                padding: 14px;
                background-color: #ffffff;
            }
            h1, h2, h3, h4 { color: #111; font-weight: 700; margin-top: 1em; margin-bottom: 0.5em; }
            p { margin-bottom: 1em; }
            blockquote { border-left: 3px solid #1a7a0e; margin-left: 0; padding-left: 14px; color: #555; font-style: italic; }
            table { border-collapse: collapse; width: 100%; margin: 1em 0; }
            table, th, td { border: 1px solid #ddd; padding: 8px; }
            ul, ol { padding-left: 24px; margin: 1em 0; }
        `,
        setup: function(editor) {
            editor.on('change keyup NodeChange', function() {
                editor.save();
                updatePreview();
            });
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
            tinymce.get('content').save();
        }
    });

    // Live SERP Preview & Slug Generation
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const excerptInput = document.getElementById('excerpt');
    const metaTitleInput = document.getElementById('meta_title');
    const metaDescInput = document.getElementById('meta_description');

    const previewTitle = document.getElementById('preview-title');
    const previewDesc = document.getElementById('preview-desc');
    const previewUrl = document.getElementById('preview-url').querySelector('span');
    const titleCounter = document.getElementById('title-counter');
    const descCounter = document.getElementById('desc-counter');

    function updatePreview() {
        const titleVal = metaTitleInput.value.trim() || titleInput.value.trim() || 'Post Title';
        let descVal = metaDescInput.value.trim() || excerptInput.value.trim();
        
        if (!descVal && typeof tinymce !== 'undefined' && tinymce.get('content')) {
            const rawText = tinymce.get('content').getContent({ format: 'text' }).trim();
            if (rawText) {
                descVal = rawText.substring(0, 150) + (rawText.length > 150 ? '...' : '');
            }
        }
        
        if (!descVal) {
            descVal = 'Enter a meta description to see how this article will appear in Google search results...';
        }

        const slugVal = slugInput.value.trim() || 'your-post-slug';

        previewTitle.textContent = titleVal + ' | QAVYX';
        previewDesc.textContent = descVal;
        previewUrl.textContent = slugVal;

        // Counters
        titleCounter.textContent = `${metaTitleInput.value.length} / 60 chars (Recommended)`;
        descCounter.textContent = `${metaDescInput.value.length} / 160 chars (Recommended)`;

        if (metaTitleInput.value.length > 60) {
            titleCounter.style.color = '#f87171';
        } else {
            titleCounter.style.color = 'var(--color-text-muted)';
        }

        if (metaDescInput.value.length > 160) {
            descCounter.style.color = '#f87171';
        } else {
            descCounter.style.color = 'var(--color-text-muted)';
        }
    }

    titleInput.addEventListener('input', function() {
        if (!slugInput.dataset.manual) {
            const slug = titleInput.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)+/g, '');
            slugInput.value = slug;
        }
        updatePreview();
    });

    slugInput.addEventListener('input', function() {
        slugInput.dataset.manual = 'true';
        updatePreview();
    });

    metaTitleInput.addEventListener('input', updatePreview);
    metaDescInput.addEventListener('input', updatePreview);
    excerptInput.addEventListener('input', updatePreview);

    updatePreview();
</script>
@endpush
@endsection

