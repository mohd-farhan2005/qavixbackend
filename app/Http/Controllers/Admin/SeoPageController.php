<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoPageController extends Controller
{
    public function index(): View
    {
        $pages = SeoPage::orderBy('path')->get();

        return view('admin.seo.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.seo.pages.create');
    }

    public function store(Request $request): RedirectResponse
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
            'schema_markup_json' => 'nullable|string',
        ]);

        if (!str_starts_with($validated['path'], '/')) {
            $validated['path'] = '/' . $validated['path'];
        }

        if (!empty($validated['schema_markup_json'])) {
            $decoded = json_decode($validated['schema_markup_json'], true);
            if ($decoded) {
                $validated['schema_markup'] = $decoded;
            }
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $seoPage = SeoPage::create($validated);

        return redirect()->route('admin.seo.pages.index')
            ->with('success', 'Page SEO configuration for "' . $seoPage->page_name . '" (' . $seoPage->path . ') created successfully!');
    }

    public function edit(int $id): View
    {
        $page = SeoPage::findOrFail($id);

        return view('admin.seo.pages.edit', compact('page'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $page = SeoPage::findOrFail($id);

        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'path' => 'required|string|max:255|unique:seo_pages,path,' . $page->id,
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
            'schema_markup_json' => 'nullable|string',
        ]);

        if (!str_starts_with($validated['path'], '/')) {
            $validated['path'] = '/' . $validated['path'];
        }

        if (!empty($validated['schema_markup_json'])) {
            $decoded = json_decode($validated['schema_markup_json'], true);
            if ($decoded) {
                $validated['schema_markup'] = $decoded;
            }
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $page->update($validated);

        return redirect()->route('admin.seo.pages.index')
            ->with('success', 'SEO configuration for "' . $page->page_name . '" updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $page = SeoPage::findOrFail($id);
        $name = $page->page_name;
        $page->delete();

        return redirect()->route('admin.seo.pages.index')
            ->with('success', 'SEO configuration for "' . $name . '" deleted.');
    }
}

