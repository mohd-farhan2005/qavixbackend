@extends('layouts.admin')

@section('title', 'Global SEO & Verification Settings | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Site Configuration
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Global SEO Defaults & Webmaster Tools
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Configure site-wide fallback metadata, search console verification codes, analytics, and sitemap settings.
        </p>
    </div>
</div>

<form action="{{ route('admin.seo.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px;">
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Brand & Default Titles -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px;">
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 20px;">
                    Site Identity & Title Formula
                </h2>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label for="site_name" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            Site Name <span style="color: var(--color-secondary-bright);">*</span>
                        </label>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings->site_name) }}" required style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                    </div>

                    <div>
                        <label for="title_separator" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            Title Separator <span style="color: var(--color-secondary-bright);">*</span>
                        </label>
                        <input type="text" name="title_separator" id="title_separator" value="{{ old('title_separator', $settings->title_separator) }}" placeholder=" | " required style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px; text-align: center;">
                    </div>
                </div>

                <!-- Default Meta Title -->
                <div style="margin-bottom: 20px;">
                    <label for="default_meta_title" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Default Meta Title (Used when page title is not set)
                    </label>
                    <input type="text" name="default_meta_title" id="default_meta_title" value="{{ old('default_meta_title', $settings->default_meta_title) }}" placeholder="e.g. QAVYX - Cutting-Edge Digital & Web Solutions" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                </div>

                <!-- Default Meta Description -->
                <div style="margin-bottom: 20px;">
                    <label for="default_meta_description" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Default Meta Description
                    </label>
                    <textarea name="default_meta_description" id="default_meta_description" rows="3" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px; font-family: inherit;">{{ old('default_meta_description', $settings->default_meta_description) }}</textarea>
                </div>

                <!-- Default Meta Keywords -->
                <div style="margin-bottom: 20px;">
                    <label for="default_meta_keywords" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Default Meta Keywords
                    </label>
                    <input type="text" name="default_meta_keywords" id="default_meta_keywords" value="{{ old('default_meta_keywords', $settings->default_meta_keywords) }}" placeholder="QAVYX, web development, react, laravel" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                </div>

                <!-- Default OG Image & Twitter Handle -->
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <div>
                        <label for="default_og_image" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            Default Social Share Image (OG Image)
                        </label>
                        <input type="text" name="default_og_image" id="default_og_image" value="{{ old('default_og_image', $settings->default_og_image) }}" placeholder="https://... (1200x630)" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                    </div>
                    <div>
                        <label for="twitter_handle" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            Twitter Handle
                        </label>
                        <input type="text" name="twitter_handle" id="twitter_handle" value="{{ old('twitter_handle', $settings->twitter_handle) }}" placeholder="@QAVYX" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Search Console & Verification Tags -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px;">
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 20px;">
                    Webmaster Search Verification Tags
                </h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label for="google_site_verification" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            Google Search Console Verification Token
                        </label>
                        <input type="text" name="google_site_verification" id="google_site_verification" value="{{ old('google_site_verification', $settings->google_site_verification) }}" placeholder="e.g. vF... (from google-site-verification meta tag)" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 13px;">
                    </div>

                    <div>
                        <label for="bing_site_verification" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                            Bing Webmaster Verification Token
                        </label>
                        <input type="text" name="bing_site_verification" id="bing_site_verification" value="{{ old('bing_site_verification', $settings->bing_site_verification) }}" placeholder="e.g. 7A... (from msvalidate.01 meta tag)" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 13px;">
                    </div>
                </div>
            </div>

            <!-- Custom Analytics & Header Scripts -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 28px;">
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 8px;">
                    Custom Header & Analytics Scripts
                </h2>
                <p style="font-size: 13px; color: var(--color-text-secondary); margin-bottom: 20px;">
                    Injected into your React application head/body via the <code style="color: #4ade80;">/api/seo</code> payload.
                </p>

                <div style="margin-bottom: 20px;">
                    <label for="custom_head_scripts" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Custom &lt;head&gt; Scripts (Google Tag Manager, Google Analytics 4, Pixel)
                    </label>
                    <textarea name="custom_head_scripts" id="custom_head_scripts" rows="5" placeholder="<!-- Google tag (gtag.js) -->&#10;<script async src='https://www.googletagmanager.com/gtag/js?id=G-...'></script>" style="width: 100%; padding: 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 12px;">{{ old('custom_head_scripts', $settings->custom_head_scripts) }}</textarea>
                </div>

                <div>
                    <label for="custom_body_scripts" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Custom &lt;body&gt; Scripts (NoScript tags, live chat widgets)
                    </label>
                    <textarea name="custom_body_scripts" id="custom_body_scripts" rows="3" placeholder="<!-- GTM NoScript or Chatbot -->" style="width: 100%; padding: 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #4ade80; font-family: monospace; font-size: 12px;">{{ old('custom_body_scripts', $settings->custom_body_scripts) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Sitemap & Save -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Save Button Box -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
                <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px;">
                    Apply Changes
                </h3>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #e5e7eb; cursor: pointer;">
                        <input type="checkbox" name="sitemap_enabled" value="1" {{ $settings->sitemap_enabled ? 'checked' : '' }} style="accent-color: var(--color-secondary-bright);">
                        <span>Enable XML Sitemap Generation</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; background: var(--color-secondary); color: #fff; border: none; border-radius: var(--radius-sm); font-weight: 700; font-family: var(--font-clash); font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Save Global SEO Settings</span>
                </button>
            </div>

            <!-- Sitemap Quick Links Box -->
            <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px;">
                <h3 style="font-family: var(--font-clash); font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 14px;">
                    Live Sitemaps
                </h3>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ url('/api/sitemap.xml') }}" target="_blank" style="padding: 10px 14px; background: rgba(255,255,255,0.04); border: 1px solid var(--color-dark-border); border-radius: 6px; text-decoration: none; color: #fff; font-size: 13px; display: flex; align-items: center; justify-content: space-between;">
                        <span>XML Sitemap (Google / Bing)</span>
                        <span style="color: var(--color-secondary-bright);">↗</span>
                    </a>

                    <a href="{{ url('/api/sitemap') }}" target="_blank" style="padding: 10px 14px; background: rgba(255,255,255,0.04); border: 1px solid var(--color-dark-border); border-radius: 6px; text-decoration: none; color: #fff; font-size: 13px; display: flex; align-items: center; justify-content: space-between;">
                        <span>JSON Sitemap (React Crawler)</span>
                        <span style="color: var(--color-secondary-bright);">↗</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

