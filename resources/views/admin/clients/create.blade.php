@extends('layouts.admin')

@section('title', 'Add Client & Case Study Page | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 4px;">
            Add Client Page & Case Study
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Create a complete frontend client detail page with banner image, impact metrics, story, and testimonial.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
            ← Back to Clients
        </a>
    </div>
</div>

<div style="max-width: 900px;">
    <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- SECTION 1: Basic Identity & Industry Dropdown -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">1</div>
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 700; color: #fff;">Client Identity & Classification</h2>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <!-- Client Name -->
                <div>
                    <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Client / Company Name <span style="color: var(--color-secondary-bright);">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Nexus Pay, HyperScale AI" required autofocus oninput="generateSlug(this.value)" style="width: 100%; padding: 11px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                </div>

                <!-- URL Slug -->
                <div>
                    <label for="slug" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Frontend URL Slug
                    </label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="e.g. nexus-pay" style="width: 100%; padding: 11px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #4ade80; font-family: monospace; font-size: 13px; outline: none;">
                    <span style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px; display: block;">Frontend Route: <code>/clients/<span id="slug-preview">client-slug</span></code></span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <!-- Industry Dropdown -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="industry_id" style="font-size: 13px; font-weight: 600; color: #e5e7eb;">
                            Industry Vertical <span style="color: var(--color-secondary-bright);">*</span>
                        </label>
                        <a href="{{ route('admin.industries.create') }}" target="_blank" style="font-size: 11px; color: var(--color-secondary-bright); text-decoration: none;">
                            + Add New Industry
                        </a>
                    </div>
                    <select name="industry_id" id="industry_id" style="width: 100%; padding: 11px 14px; background: #090b09; border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none; cursor: pointer;">
                        <option value="">-- Select Industry Vertical --</option>
                        @foreach($industries as $industry)
                            <option value="{{ $industry->id }}" {{ old('industry_id') == $industry->id ? 'selected' : '' }}>
                                {{ $industry->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tagline -->
                <div>
                    <label for="tagline" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Tagline / Subheading
                    </label>
                    <input type="text" name="tagline" id="tagline" value="{{ old('tagline') }}" placeholder="e.g. Next-Gen Institutional Digital Asset Custody" style="width: 100%; padding: 11px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 14px; outline: none;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <!-- Location -->
                <div>
                    <label for="location" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Headquarters / Location
                    </label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="e.g. San Francisco, CA" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>

                <!-- Project Year -->
                <div>
                    <label for="project_year" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Project Year / Timeline
                    </label>
                    <input type="text" name="project_year" id="project_year" value="{{ old('project_year', '2026') }}" placeholder="e.g. 2026" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>

                <!-- Client Engagement Type -->
                <div>
                    <label for="client_type" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Engagement Type
                    </label>
                    <input type="text" name="client_type" id="client_type" value="{{ old('client_type') }}" placeholder="e.g. Fullstack Retainer" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>
            </div>

            <!-- Website URL -->
            <div style="margin-top: 18px;">
                <label for="website_url" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Client Website URL
                </label>
                <input type="url" name="website_url" id="website_url" value="{{ old('website_url') }}" placeholder="https://company.com" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
            </div>
        </div>

        <!-- SECTION 2: Visual Media, Hero Banner & Logo -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">2</div>
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 700; color: #fff;">Visual Media & Banner Image</h2>
            </div>

            <!-- Banner Image (Hero Cover for Frontend Client Page) -->
            <div style="margin-bottom: 24px; padding: 20px; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 12px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                    <label style="font-size: 13px; font-weight: 700; color: #4ade80;">
                        ★ Hero Banner Image (Client Detail Page Cover)
                    </label>
                    <span style="font-size: 11px; color: var(--color-text-muted);">Recommended: 1920x800 px</span>
                </div>
                <p style="font-size: 12px; color: var(--color-text-secondary); margin-bottom: 14px;">
                    This banner will appear as the hero visual header at the top of the client's frontend page.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label for="banner_image" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Upload Banner File (JPG, PNG, WebP)
                        </label>
                        <input type="file" name="banner_image" id="banner_image" accept="image/*" onchange="previewBannerFile(event)" style="width: 100%; font-size: 12px; color: var(--color-text-secondary);">
                    </div>
                    <div>
                        <label for="banner_image_url" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Or Direct Banner Image URL
                        </label>
                        <input type="url" name="banner_image_url" id="banner_image_url" value="{{ old('banner_image_url') }}" placeholder="https://images.unsplash.com/photo-..." oninput="previewBannerUrl(this.value)" style="width: 100%; padding: 8px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 12px; outline: none;">
                    </div>
                </div>

                <!-- Banner Preview -->
                <div style="margin-top: 14px;">
                    <div style="width: 100%; height: 160px; border-radius: 10px; background: #080a08; border: 1px dashed rgba(255, 255, 255, 0.15); overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">
                        <img id="banner-preview" src="" alt="Banner preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <span id="banner-placeholder" style="font-size: 12px; color: var(--color-text-muted);">
                            Banner image preview will appear here
                        </span>
                    </div>
                </div>
            </div>

            <!-- Client Logo -->
            <div style="margin-bottom: 20px; padding: 18px; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 4px;">
                    Client Logo (SVG / Transparent PNG)
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 10px;">
                    <div>
                        <label for="logo" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Upload Logo File
                        </label>
                        <input type="file" name="logo" id="logo" accept="image/*" onchange="previewLogoFile(event)" style="width: 100%; font-size: 12px; color: var(--color-text-secondary);">
                    </div>
                    <div>
                        <label for="logo_url" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                            Or Logo URL
                        </label>
                        <input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url') }}" placeholder="https://example.com/logo.svg" oninput="previewLogoUrl(this.value)" style="width: 100%; padding: 8px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 12px; outline: none;">
                    </div>
                </div>

                <div style="margin-top: 12px; display: flex; align-items: center; gap: 14px;">
                    <div style="width: 90px; height: 50px; border-radius: 8px; background: #060706; border: 1px solid rgba(255, 255, 255, 0.1); padding: 6px; display: flex; align-items: center; justify-content: center;">
                        <img id="logo-preview" src="https://ui-avatars.com/api/?name=Client&background=0d0f0d&color=22c55e&size=128" alt="Logo preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <span style="font-size: 11px; color: var(--color-text-muted);">Logo on dark card preview</span>
                </div>
            </div>

            <!-- Video & Gallery URLs -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label for="video_url" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Showcase Video URL (YouTube / Vimeo / MP4)
                    </label>
                    <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>
                <div>
                    <label for="gallery_urls" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Gallery Images (Comma or line separated URLs)
                    </label>
                    <textarea name="gallery_urls" id="gallery_urls" rows="2" placeholder="https://images.unsplash.com/... , https://..." style="width: 100%; padding: 8px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 12px; outline: none;">{{ old('gallery_urls') }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Detailed Case Study & Story -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">3</div>
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 700; color: #fff;">Story, Case Study & Services</h2>
            </div>

            <!-- Short Summary (Excerpt) -->
            <div style="margin-bottom: 18px;">
                <label for="description" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Short Excerpt / Grid Card Description
                </label>
                <textarea name="description" id="description" rows="2" placeholder="One or two sentences summarizing the client partnership for cards..." style="width: 100%; padding: 10px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">{{ old('description') }}</textarea>
            </div>

            <!-- Detailed About Story -->
            <div style="margin-bottom: 18px;">
                <label for="about" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Detailed Project Story / Overview (Full Write-Up)
                </label>
                <textarea name="about" id="about" rows="5" placeholder="In-depth narrative of how we worked together, technical architecture, and impact..." style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none; line-height: 1.6;">{{ old('about') }}</textarea>
            </div>

            <!-- Challenge & Solution Columns -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label for="challenge" style="display: block; font-size: 13px; font-weight: 600; color: #f87171; margin-bottom: 6px;">
                        The Challenge
                    </label>
                    <textarea name="challenge" id="challenge" rows="4" placeholder="What obstacles or scalability issues was the client facing?" style="width: 100%; padding: 10px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">{{ old('challenge') }}</textarea>
                </div>
                <div>
                    <label for="solution" style="display: block; font-size: 13px; font-weight: 600; color: #4ade80; margin-bottom: 6px;">
                        Our Solution
                    </label>
                    <textarea name="solution" id="solution" rows="4" placeholder="How Qavyx architected and engineered the resolution..." style="width: 100%; padding: 10px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">{{ old('solution') }}</textarea>
                </div>
            </div>

            <!-- Services Provided -->
            <div>
                <label for="services" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Services Delivered (Comma Separated)
                </label>
                <input type="text" name="services" id="services" value="{{ old('services') }}" placeholder="e.g. Product Strategy, UI/UX Design, React Frontend, Laravel API, Cloud Architecture" style="width: 100%; padding: 11px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
            </div>
        </div>

        <!-- SECTION 4: Impact Metrics (High Converting Stats) -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">4</div>
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 700; color: #fff;">Key Impact Results & Metrics (Stats Bar)</h2>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <!-- Stat 1 -->
                <div style="padding: 16px; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 10px;">
                    <span style="font-size: 11px; font-weight: 700; color: #4ade80; text-transform: uppercase;">Metric #1</span>
                    <input type="text" name="stat_1_value" value="{{ old('stat_1_value') }}" placeholder="e.g. +340%" style="width: 100%; margin-top: 8px; margin-bottom: 6px; padding: 8px 10px; background: rgba(0,0,0,0.6); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-size: 15px; font-weight: bold; outline: none;">
                    <input type="text" name="stat_1_label" value="{{ old('stat_1_label') }}" placeholder="e.g. Conversion Lift" style="width: 100%; padding: 6px 10px; background: rgba(0,0,0,0.6); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #9ca3af; font-size: 12px; outline: none;">
                </div>

                <!-- Stat 2 -->
                <div style="padding: 16px; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 10px;">
                    <span style="font-size: 11px; font-weight: 700; color: #4ade80; text-transform: uppercase;">Metric #2</span>
                    <input type="text" name="stat_2_value" value="{{ old('stat_2_value') }}" placeholder="e.g. $18M" style="width: 100%; margin-top: 8px; margin-bottom: 6px; padding: 8px 10px; background: rgba(0,0,0,0.6); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-size: 15px; font-weight: bold; outline: none;">
                    <input type="text" name="stat_2_label" value="{{ old('stat_2_label') }}" placeholder="e.g. Capital Raised" style="width: 100%; padding: 6px 10px; background: rgba(0,0,0,0.6); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #9ca3af; font-size: 12px; outline: none;">
                </div>

                <!-- Stat 3 -->
                <div style="padding: 16px; background: rgba(0,0,0,0.3); border: 1px solid var(--color-dark-border); border-radius: 10px;">
                    <span style="font-size: 11px; font-weight: 700; color: #4ade80; text-transform: uppercase;">Metric #3</span>
                    <input type="text" name="stat_3_value" value="{{ old('stat_3_value') }}" placeholder="e.g. 99.99%" style="width: 100%; margin-top: 8px; margin-bottom: 6px; padding: 8px 10px; background: rgba(0,0,0,0.6); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-size: 15px; font-weight: bold; outline: none;">
                    <input type="text" name="stat_3_label" value="{{ old('stat_3_label') }}" placeholder="e.g. System Uptime" style="width: 100%; padding: 6px 10px; background: rgba(0,0,0,0.6); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #9ca3af; font-size: 12px; outline: none;">
                </div>
            </div>
        </div>

        <!-- SECTION 5: Testimonial & Endorsement -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">5</div>
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 700; color: #fff;">Client Testimonial & Review</h2>
            </div>

            <!-- Testimonial Quote -->
            <div style="margin-bottom: 18px;">
                <label for="testimonial_quote" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Testimonial Quote
                </label>
                <textarea name="testimonial_quote" id="testimonial_quote" rows="3" placeholder="&quot;Qavyx transformed our technical capabilities. They operate with the urgency of true co-founders.&quot;" style="width: 100%; padding: 11px 14px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none; font-style: italic;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label for="testimonial_author" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Author Name
                    </label>
                    <input type="text" name="testimonial_author" id="testimonial_author" value="{{ old('testimonial_author') }}" placeholder="e.g. Elena Rostova" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>
                <div>
                    <label for="testimonial_position" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Author Position / Title
                    </label>
                    <input type="text" name="testimonial_position" id="testimonial_position" value="{{ old('testimonial_position') }}" placeholder="e.g. Chief Technology Officer, Nexus" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label for="testimonial_avatar" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                        Author Photo File
                    </label>
                    <input type="file" name="testimonial_avatar" id="testimonial_avatar" accept="image/*" style="width: 100%; font-size: 12px; color: var(--color-text-secondary);">
                </div>
                <div>
                    <label for="testimonial_avatar_url" style="display: block; font-size: 12px; color: var(--color-text-muted); margin-bottom: 6px;">
                        Or Author Photo URL
                    </label>
                    <input type="url" name="testimonial_avatar_url" id="testimonial_avatar_url" value="{{ old('testimonial_avatar_url') }}" placeholder="https://images.unsplash.com/..." style="width: 100%; padding: 8px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 12px; outline: none;">
                </div>
            </div>
        </div>

        <!-- SECTION 6: SEO & Publishing Settings -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--color-secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">6</div>
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 700; color: #fff;">SEO & Display Controls</h2>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div>
                    <label for="meta_title" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Page Meta Title (Google SERP)
                    </label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" placeholder="e.g. Nexus Pay Case Study | Qavyx" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>
                <div>
                    <label for="order" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Sort / Display Order (Lower = First)
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label for="meta_description" style="display: block; font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Meta Description (Under 160 characters)
                </label>
                <textarea name="meta_description" id="meta_description" rows="2" placeholder="Search engine description for Google..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--color-dark-border); border-radius: 8px; color: #fff; font-size: 13px; outline: none;">{{ old('meta_description') }}</textarea>
            </div>

            <div style="display: flex; gap: 28px; padding-top: 10px;">
                <label style="display: inline-flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--color-secondary-bright);">
                    <div>
                        <span style="font-size: 13px; font-weight: 600; color: #fff; display: block;">★ Feature on Homepage</span>
                        <span style="font-size: 11px; color: var(--color-text-muted);">Displays in featured client carousel or highlight grid</span>
                    </div>
                </label>

                <label style="display: inline-flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--color-secondary-bright);">
                    <div>
                        <span style="font-size: 13px; font-weight: 600; color: #fff; display: block;">Active / Published</span>
                        <span style="font-size: 11px; color: var(--color-text-muted);">Visible in public APIs and frontend</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 14px;">
                ✓ Save & Publish Client Page
            </button>
        </div>
    </form>
</div>

<script>
    function generateSlug(name) {
        const slug = name.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim();
        const slugInput = document.getElementById('slug');
        if (slugInput && (!slugInput.dataset.touched || slugInput.value === '')) {
            slugInput.value = slug;
        }
        document.getElementById('slug-preview').textContent = slug || 'client-slug';
    }

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.touched = 'true';
        document.getElementById('slug-preview').textContent = this.value || 'client-slug';
    });

    // Logo Previews
    function previewLogoFile(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewLogoUrl(url) {
        if (url && url.trim().length > 5) {
            document.getElementById('logo-preview').src = url;
        }
    }

    // Banner Previews
    function previewBannerFile(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('banner-preview');
                img.src = e.target.result;
                img.style.display = 'block';
                document.getElementById('banner-placeholder').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewBannerUrl(url) {
        const img = document.getElementById('banner-preview');
        const placeholder = document.getElementById('banner-placeholder');
        if (url && url.trim().length > 5) {
            img.src = url;
            img.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            img.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }
</script>
@endsection
