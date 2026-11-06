<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    /**
     * Get global SEO settings
     */
    public function show(): JsonResponse
    {
        $settings = SeoSetting::instance();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update global SEO settings
     */
    public function update(Request $request): JsonResponse
    {
        $settings = SeoSetting::instance();

        $validated = $request->validate([
            'site_name' => 'sometimes|required|string|max:255',
            'title_separator' => 'sometimes|required|string|max:10',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string',
            'default_meta_keywords' => 'nullable|string',
            'default_og_image' => 'nullable|string',
            'twitter_handle' => 'nullable|string|max:100',
            'google_site_verification' => 'nullable|string|max:255',
            'bing_site_verification' => 'nullable|string|max:255',
            'custom_head_scripts' => 'nullable|string',
            'custom_body_scripts' => 'nullable|string',
            'sitemap_enabled' => 'boolean',
        ]);

        $settings->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Global SEO settings updated successfully',
            'data' => $settings,
        ]);
    }
}

