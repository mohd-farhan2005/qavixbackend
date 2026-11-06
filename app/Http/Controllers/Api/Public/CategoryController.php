<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * List all categories that have active blog posts
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount(['blogs' => function ($q) {
            $q->published();
        }])
        ->orderBy('name')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get single category with its published posts
     */
    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->withCount(['blogs' => function ($q) {
                $q->published();
            }])
            ->firstOrFail();

        $blogs = $category->blogs()
            ->published()
            ->latest('published_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'blogs' => $blogs->items(),
                'meta' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'total' => $blogs->total(),
                ],
            ],
            'seo' => [
                'title' => $category->meta_title ?: ($category->name . ' - Articles & Insights'),
                'description' => $category->meta_description ?: ($category->description ?: "Explore the latest insights and articles in {$category->name}."),
            ],
        ]);
    }
}

