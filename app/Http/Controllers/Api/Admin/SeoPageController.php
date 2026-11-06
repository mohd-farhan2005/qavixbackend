<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeoPageController extends Controller
{
    /**
     * List all configured SEO page entries
     */
    public function index(Request $request): JsonResponse
    {
        $query = SeoPage::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('page_name', 'like', "%{$search}%")
                  ->orWhere('path', 'like', "%{$search}%")
                  ->orWhere('meta_title', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%");
            });
        }

        $pages = $query->orderBy('path')->get();

        return response()->json([
            'success' => true,
            'data' => $pages,
        ]);
    }

    /**
     * Create a new page SEO configuration
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'path' => 'required|string|max:255|unique:seo_pages,path',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|string',
            'og_type' => 'nullable|string|max:50',
            'twitter_card' => 'nullable|string|max:50',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|string',
            'schema_markup' => 'nullable|array',
            'extra_meta_tags' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        // Normalize path (ensure leading slash unless external or wildcard)
        if (!str_starts_with($validated['path'], '/')) {
            $validated['path'] = '/' . $validated['path'];
        }

        $seoPage = SeoPage::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'SEO configuration for page created successfully',
            'data' => $seoPage,
        ], 201);
    }

    /**
     * Show single page SEO configuration
     */
    public function show(int $id): JsonResponse
    {
        $seoPage = SeoPage::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $seoPage,
        ]);
    }

    /**
     * Update page SEO configuration
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $seoPage = SeoPage::findOrFail($id);

        $validated = $request->validate([
            'page_name' => 'sometimes|required|string|max:255',
            'path' => 'sometimes|required|string|max:255|unique:seo_pages,path,' . $seoPage->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|string',
            'og_type' => 'nullable|string|max:50',
            'twitter_card' => 'nullable|string|max:50',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|string',
            'schema_markup' => 'nullable|array',
            'extra_meta_tags' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['path']) && !str_starts_with($validated['path'], '/')) {
            $validated['path'] = '/' . $validated['path'];
        }

        $seoPage->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'SEO configuration updated successfully',
            'data' => $seoPage,
        ]);
    }

    /**
     * Delete page SEO configuration
     */
    public function destroy(int $id): JsonResponse
    {
        $seoPage = SeoPage::findOrFail($id);
        $seoPage->delete();

        return response()->json([
            'success' => true,
            'message' => 'SEO configuration deleted successfully',
        ]);
    }
}

