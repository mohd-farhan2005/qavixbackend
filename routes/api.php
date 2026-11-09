<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Api\Admin\LaunchpadController as AdminLaunchpadController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\SeoPageController;
use App\Http\Controllers\Api\Admin\SeoSettingController;
use App\Http\Controllers\Api\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Api\Public\BlogController;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Public\ContactController;
use App\Http\Controllers\Api\Public\FaqController as PublicFaqController;
use App\Http\Controllers\Api\Public\LaunchpadController as PublicLaunchpadController;
use App\Http\Controllers\Api\Public\SeoController;
use App\Http\Controllers\Api\Public\TeamController as PublicTeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (For React Frontend)
|--------------------------------------------------------------------------
*/

// Admin Authentication (Public)
Route::post('/admin/login', [AuthController::class, 'login']);

// Blogs & Articles
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/featured', [BlogController::class, 'featured']);
Route::get('/blogs/recent', [BlogController::class, 'recent']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// FAQs (with Google FAQPage JSON-LD schema)
Route::get('/faqs', [PublicFaqController::class, 'index']);

// SEO Metadata for React Pages & Routes
Route::get('/seo', [SeoController::class, 'getByPath']);
Route::get('/seo/pages', [SeoController::class, 'allPages']);
Route::get('/seo-settings', [SeoController::class, 'globalSettings']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemapXml']);
Route::get('/sitemap', [SeoController::class, 'sitemapJson']);

// Contact / Leads Form Submission
Route::post('/contact', [ContactController::class, 'store']);

// Launchpad Founder Application (/launchpad)
Route::post('/launchpad', [PublicLaunchpadController::class, 'store']);
Route::post('/launchpad/apply', [PublicLaunchpadController::class, 'store']);

// Team & Directors (/api/team & /api/team-members)
Route::get('/team', [PublicTeamController::class, 'index']);
Route::get('/team-members', [PublicTeamController::class, 'index']);
Route::get('/team/{id}', [PublicTeamController::class, 'show']);


/*
|--------------------------------------------------------------------------
| Protected Admin Routes (Requires Sanctum Token)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Auth & Profile
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Dashboard Overview
    Route::get('/dashboard', [DashboardController::class, 'stats']);

    // Blog Posts CRUD
    Route::get('/blogs', [AdminBlogController::class, 'index']);
    Route::post('/blogs', [AdminBlogController::class, 'store']);
    Route::get('/blogs/{id}', [AdminBlogController::class, 'show']);
    Route::put('/blogs/{id}', [AdminBlogController::class, 'update']);
    Route::delete('/blogs/{id}', [AdminBlogController::class, 'destroy']);
    Route::post('/blogs/{id}/toggle-publish', [AdminBlogController::class, 'togglePublish']);

    // Categories CRUD
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::get('/categories/{id}', [AdminCategoryController::class, 'show']);
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy']);

    // FAQs CRUD
    Route::get('/faqs', [AdminFaqController::class, 'index']);
    Route::post('/faqs', [AdminFaqController::class, 'store']);
    Route::get('/faqs/{id}', [AdminFaqController::class, 'show']);
    Route::put('/faqs/{id}', [AdminFaqController::class, 'update']);
    Route::delete('/faqs/{id}', [AdminFaqController::class, 'destroy']);
    Route::post('/faqs/{id}/toggle-active', [AdminFaqController::class, 'toggleActive']);

    // Page-Level SEO Management (Meta tags, Meta descriptions, OpenGraph, Twitter, Schema)
    Route::get('/seo-pages', [SeoPageController::class, 'index']);
    Route::post('/seo-pages', [SeoPageController::class, 'store']);
    Route::get('/seo-pages/{id}', [SeoPageController::class, 'show']);
    Route::put('/seo-pages/{id}', [SeoPageController::class, 'update']);
    Route::delete('/seo-pages/{id}', [SeoPageController::class, 'destroy']);

    // Global SEO Settings
    Route::get('/seo-settings', [SeoSettingController::class, 'show']);
    Route::put('/seo-settings', [SeoSettingController::class, 'update']);

    // Media Library & File Upload
    Route::get('/media', [MediaController::class, 'index']);
    Route::post('/media/upload', [MediaController::class, 'upload']);
    Route::delete('/media/{id}', [MediaController::class, 'destroy']);

    // Contact Inquiries
    Route::get('/contacts', [AdminContactController::class, 'index']);
    Route::patch('/contacts/{id}/read', [AdminContactController::class, 'markAsRead']);
    Route::delete('/contacts/{id}', [AdminContactController::class, 'destroy']);

    // Launchpad Founder Leads
    Route::get('/launchpad', [AdminLaunchpadController::class, 'index']);
    Route::get('/launchpad/{id}', [AdminLaunchpadController::class, 'show']);
    Route::patch('/launchpad/{id}/status', [AdminLaunchpadController::class, 'updateStatus']);
    Route::delete('/launchpad/{id}', [AdminLaunchpadController::class, 'destroy']);

    // Team Members & Leadership CRUD
    Route::get('/team', [AdminTeamController::class, 'index']);
    Route::post('/team', [AdminTeamController::class, 'store']);
    Route::get('/team/{id}', [AdminTeamController::class, 'show']);
    Route::put('/team/{id}', [AdminTeamController::class, 'update']);
    Route::post('/team/{id}', [AdminTeamController::class, 'update']);
    Route::delete('/team/{id}', [AdminTeamController::class, 'destroy']);
    Route::post('/team/{id}/toggle-active', [AdminTeamController::class, 'toggleActive']);
});
