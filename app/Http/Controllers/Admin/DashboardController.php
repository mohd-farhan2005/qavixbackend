<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\LaunchpadLead;
use App\Models\MediaUpload;
use App\Models\SeoPage;
use App\Models\SeoSetting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $draftBlogs = Blog::where('status', 'draft')->count();
        $totalViews = (int) Blog::sum('views_count');
        $totalCategories = Category::count();
        $totalSeoPages = SeoPage::count();
        $totalMedia = MediaUpload::count();
        $totalFaqs = Faq::count();
        $activeFaqs = Faq::active()->count();
        $unreadContacts = Contact::where('is_read', false)->count();
        $totalLaunchpad = LaunchpadLead::count();
        $unreadLaunchpad = LaunchpadLead::where('is_read', false)->count();

        $recentBlogs = Blog::with('category')->latest()->take(5)->get();
        $recentContacts = Contact::latest()->take(5)->get();
        $recentLaunchpad = LaunchpadLead::latest()->take(5)->get();
        $seoSetting = SeoSetting::instance();

        return view('admin.dashboard', compact(
            'totalBlogs',
            'publishedBlogs',
            'draftBlogs',
            'totalViews',
            'totalCategories',
            'totalSeoPages',
            'totalMedia',
            'totalFaqs',
            'activeFaqs',
            'unreadContacts',
            'totalLaunchpad',
            'unreadLaunchpad',
            'recentBlogs',
            'recentContacts',
            'recentLaunchpad',
            'seoSetting'
        ));
    }
}

