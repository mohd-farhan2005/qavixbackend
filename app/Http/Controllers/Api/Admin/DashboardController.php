<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\MediaUpload;
use App\Models\SeoPage;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Return admin overview metrics
     */
    public function stats(): JsonResponse
    {
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $draftBlogs = Blog::where('status', 'draft')->count();
        $totalViews = (int) Blog::sum('views_count');
        $totalCategories = Category::count();
        $totalSeoPages = SeoPage::count();
        $totalMedia = MediaUpload::count();
        $unreadContacts = Contact::where('is_read', false)->count();

        $recentBlogs = Blog::with('category')
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'slug', 'status', 'views_count', 'category_id', 'created_at', 'published_at']);

        $recentContacts = Contact::latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'subject', 'is_read', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => [
                'counts' => [
                    'total_blogs' => $totalBlogs,
                    'published_blogs' => $publishedBlogs,
                    'draft_blogs' => $draftBlogs,
                    'total_views' => $totalViews,
                    'total_categories' => $totalCategories,
                    'total_seo_pages' => $totalSeoPages,
                    'total_media' => $totalMedia,
                    'total_faqs' => Faq::count(),
                    'active_faqs' => Faq::active()->count(),
                    'unread_contacts' => $unreadContacts,
                ],
                'recent_blogs' => $recentBlogs,
                'recent_contacts' => $recentContacts,
            ],
        ]);
    }
}

