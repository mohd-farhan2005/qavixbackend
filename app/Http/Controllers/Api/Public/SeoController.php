<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\SeoPage;
use App\Models\SeoSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Resolve SEO tags for any given route/path in the React application
     * Example query: GET /api/seo?path=/about or GET /api/seo?path=/
     */
    public function getByPath(Request $request): JsonResponse
    {
        $path = $request->query('path', '/');
        
        // Ensure path starts with slash
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        $globalSettings = SeoSetting::instance();
        
        // Look up custom page SEO configuration
        $page = SeoPage::where('is_active', true)
            ->where(function ($q) use ($path) {
                $q->where('path', $path)
                  ->orWhere('path', rtrim($path, '/'));
            })
            ->first();

        $siteName = $globalSettings->site_name ?: 'Qavyx';
        $separator = $globalSettings->title_separator ?: ' | ';

        $rawTitle = $page?->meta_title ?: ($page?->page_name ?: $globalSettings->default_meta_title);
        $fullTitle = $rawTitle ? ($rawTitle . $separator . $siteName) : $siteName;
        $description = $page?->meta_description ?: $globalSettings->default_meta_description;
        $keywords = $page?->meta_keywords ?: $globalSettings->default_meta_keywords;
        $ogImage = $page?->og_image ?: $globalSettings->default_og_image;
        $canonical = $page?->canonical_url;
        $robots = $page?->robots ?: 'index, follow';

        // Default Organization Schema
        $defaultSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => config('app.url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('app.url') . '/blogs?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'path' => $path,
                'page_name' => $page?->page_name,
                'title' => $fullTitle,
                'raw_title' => $rawTitle,
                'description' => $description,
                'keywords' => $keywords,
                'canonical' => $canonical,
                'robots' => $robots,
                'openGraph' => [
                    'site_name' => $siteName,
                    'title' => $page?->og_title ?: $rawTitle,
                    'description' => $page?->og_description ?: $description,
                    'image' => $ogImage,
                    'type' => $page?->og_type ?: 'website',
                ],
                'twitter' => [
                    'card' => $page?->twitter_card ?: 'summary_large_image',
                    'title' => $page?->twitter_title ?: ($page?->og_title ?: $rawTitle),
                    'description' => $page?->twitter_description ?: ($page?->og_description ?: $description),
                    'image' => $page?->twitter_image ?: $ogImage,
                    'site' => $globalSettings->twitter_handle,
                ],
                'schema' => $page?->schema_markup ?: $defaultSchema,
                'extraMeta' => $page?->extra_meta_tags ?: [],
                'verification' => [
                    'google' => $globalSettings->google_site_verification,
                    'bing' => $globalSettings->bing_site_verification,
                ],
                'scripts' => [
                    'head' => $globalSettings->custom_head_scripts,
                    'body' => $globalSettings->custom_body_scripts,
                ],
            ],
        ]);
    }

    /**
     * Get list of all configured active SEO pages
     */
    public function allPages(): JsonResponse
    {
        $pages = SeoPage::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $pages,
        ]);
    }

    /**
     * Get public site SEO settings
     */
    public function globalSettings(): JsonResponse
    {
        $settings = SeoSetting::instance();

        return response()->json([
            'success' => true,
            'data' => [
                'site_name' => $settings->site_name,
                'title_separator' => $settings->title_separator,
                'default_meta_title' => $settings->default_meta_title,
                'default_meta_description' => $settings->default_meta_description,
                'default_meta_keywords' => $settings->default_meta_keywords,
                'default_og_image' => $settings->default_og_image,
                'twitter_handle' => $settings->twitter_handle,
                'google_site_verification' => $settings->google_site_verification,
                'bing_site_verification' => $settings->bing_site_verification,
                'custom_head_scripts' => $settings->custom_head_scripts,
                'custom_body_scripts' => $settings->custom_body_scripts,
                'sitemap_enabled' => $settings->sitemap_enabled,
            ],
        ]);
    }

    /**
     * Generate dynamic XML sitemap for search engines
     */
    public function sitemapXml(): Response
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $pages = SeoPage::where('is_active', true)->where('robots', 'like', '%index%')->get();
        $blogs = Blog::published()->get(['slug', 'updated_at', 'published_at']);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static / Configured Pages
        foreach ($pages as $page) {
            $loc = $baseUrl . ($page->path === '/' ? '' : $page->path);
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>" . ($page->updated_at ? $page->updated_at->toAtomString() : date('c')) . "</lastmod>\n";
            $xml .= "    <changefreq>" . ($page->path === '/' ? 'daily' : 'weekly') . "</changefreq>\n";
            $xml .= "    <priority>" . ($page->path === '/' ? '1.0' : '0.8') . "</priority>\n";
            $xml .= "  </url>\n";
        }

        // Blog Posts
        foreach ($blogs as $blog) {
            $loc = $baseUrl . '/blog/' . $blog->slug;
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>" . ($blog->updated_at ? $blog->updated_at->toAtomString() : $blog->published_at?->toAtomString()) . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * JSON sitemap representation for React frontend navigation or crawlers
     */
    public function sitemapJson(): JsonResponse
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $pages = SeoPage::where('is_active', true)->get();
        $blogs = Blog::published()->get(['title', 'slug', 'updated_at', 'published_at']);

        $pageItems = $pages->map(function ($page) use ($baseUrl) {
            return [
                'type' => 'page',
                'title' => $page->page_name,
                'url' => $baseUrl . ($page->path === '/' ? '' : $page->path),
                'path' => $page->path,
                'lastmod' => $page->updated_at?->toIso8601String(),
                'priority' => $page->path === '/' ? 1.0 : 0.8,
            ];
        });

        $blogItems = $blogs->map(function ($blog) use ($baseUrl) {
            return [
                'type' => 'blog',
                'title' => $blog->title,
                'url' => $baseUrl . '/blog/' . $blog->slug,
                'path' => '/blog/' . $blog->slug,
                'lastmod' => $blog->updated_at?->toIso8601String(),
                'priority' => 0.7,
            ];
        });

        return response()->json([
            'success' => true,
            'pages' => $pageItems,
            'blogs' => $blogItems,
        ]);
    }
}

