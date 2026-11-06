<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoSettingController extends Controller
{
    public function edit(): View
    {
        $settings = SeoSetting::instance();

        return view('admin.seo.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = SeoSetting::instance();

        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'title_separator' => 'required|string|max:10',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string',
            'default_meta_keywords' => 'nullable|string',
            'default_og_image' => 'nullable|string',
            'twitter_handle' => 'nullable|string|max:100',
            'google_site_verification' => 'nullable|string|max:255',
            'bing_site_verification' => 'nullable|string|max:255',
            'custom_head_scripts' => 'nullable|string',
            'custom_body_scripts' => 'nullable|string',
            'sitemap_enabled' => 'nullable|boolean',
        ]);

        $validated['sitemap_enabled'] = $request->has('sitemap_enabled');

        $settings->update($validated);

        return redirect()->route('admin.seo.settings')
            ->with('success', 'Global SEO and Verification settings saved successfully!');
    }
}

