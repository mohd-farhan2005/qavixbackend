<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts for admin
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::with(['category', 'author']);

        // Search by title or content
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status (published, draft, archived)
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Sort order
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = (int) $request->input('per_page', 10);
        $blogs = $query->paginate($perPage);

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
     * Store a newly created blog post
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'author_name' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            
            // SEO Meta Fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            
            // OpenGraph / Social
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|string',
            'og_type' => 'nullable|string|max:50',

            // Twitter Cards
            'twitter_card' => 'nullable|string|max:50',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|string',

            // Custom Structured Data & Meta
            'schema_markup' => 'nullable|array',
            'extra_meta_tags' => 'nullable|array',
        ]);

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Blog::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Attach author if authenticated
        $validated['author_id'] = $request->user()?->id;
        if (empty($validated['author_name']) && $request->user()) {
            $validated['author_name'] = $request->user()->name;
        }

        // Automatically set published_at if publishing right away
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog = Blog::create($validated);
        $blog->load(['category', 'author']);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully',
            'data' => $blog,
        ], 201);
    }

    /**
     * Display the specified blog post
     */
    public function show(int $id): JsonResponse
    {
        $blog = Blog::with(['category', 'author'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $blog,
        ]);
    }

    /**
     * Update the specified blog post
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'featured_image' => 'nullable|string',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'author_name' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'status' => 'sometimes|required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            
            // SEO Meta Fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            
            // OpenGraph / Social
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|string',
            'og_type' => 'nullable|string|max:50',

            // Twitter Cards
            'twitter_card' => 'nullable|string|max:50',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|string',

            // Custom Structured Data & Meta
            'schema_markup' => 'nullable|array',
            'extra_meta_tags' => 'nullable|array',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'published' && empty($blog->published_at) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);
        $blog->load(['category', 'author']);

        return response()->json([
            'success' => true,
            'message' => 'Blog post updated successfully',
            'data' => $blog,
        ]);
    }

    /**
     * Remove the specified blog post
     */
    public function destroy(int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog post deleted successfully',
        ]);
    }

    /**
     * Quick toggle publish status
     */
    public function togglePublish(int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);

        if ($blog->status === 'published') {
            $blog->status = 'draft';
        } else {
            $blog->status = 'published';
            if (empty($blog->published_at)) {
                $blog->published_at = now();
            }
        }

        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Post status updated to ' . $blog->status,
            'data' => [
                'id' => $blog->id,
                'status' => $blog->status,
                'published_at' => $blog->published_at,
            ],
        ]);
    }
}

