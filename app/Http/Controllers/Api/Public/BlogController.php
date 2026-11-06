<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Get published blog posts for React frontend
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::published()->with(['category:id,name,slug', 'author:id,name,avatar']);

        // Search in title, excerpt, content
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category slug
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by tag
        if ($tag = $request->input('tag')) {
            $query->whereJsonContains('tags', $tag);
        }

        // Filter featured only
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $perPage = (int) $request->input('per_page', 9);
        $blogs = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $blogs->items(),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
            ],
        ]);
    }

    /**
     * Get single blog post by slug with full SEO bundle and increment views
     */
    public function show(string $slug): JsonResponse
    {
        $blog = Blog::published()
            ->with(['category:id,name,slug', 'author:id,name,avatar'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count asynchronously/safely
        $blog->increment('views_count');

        // Fetch related posts from same category
        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category_id, function ($q) use ($blog) {
                $q->where('category_id', $blog->category_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'reading_time', 'published_at']);

        return response()->json([
            'success' => true,
            'data' => $blog,
            'related' => $related,
            'seo' => $blog->seo,
        ]);
    }

    /**
     * Get featured blog posts
     */
    public function featured(): JsonResponse
    {
        $blogs = Blog::published()
            ->featured()
            ->with(['category:id,name,slug', 'author:id,name,avatar'])
            ->latest('published_at')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ]);
    }

    /**
     * Get latest blog posts
     */
    public function recent(): JsonResponse
    {
        $blogs = Blog::published()
            ->with(['category:id,name,slug'])
            ->latest('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'reading_time', 'published_at', 'category_id']);

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ]);
    }
}

