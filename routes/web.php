<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SeoPageController;
use App\Http\Controllers\Admin\SeoSettingController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect to Admin or status
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Blog Posts & SEO Management
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{id}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::post('/blogs/{id}/toggle-publish', [BlogController::class, 'togglePublish'])->name('blogs.toggle-publish');

    // Page-Level SEO Management (For React routes: /, /about, /services, /portfolio, /contact, etc.)
    Route::get('/seo-pages', [SeoPageController::class, 'index'])->name('seo.pages.index');
    Route::get('/seo-pages/create', [SeoPageController::class, 'create'])->name('seo.pages.create');
    Route::post('/seo-pages', [SeoPageController::class, 'store'])->name('seo.pages.store');
    Route::get('/seo-pages/{id}/edit', [SeoPageController::class, 'edit'])->name('seo.pages.edit');
    Route::put('/seo-pages/{id}', [SeoPageController::class, 'update'])->name('seo.pages.update');
    Route::delete('/seo-pages/{id}', [SeoPageController::class, 'destroy'])->name('seo.pages.destroy');

    // Global SEO Settings
    Route::get('/seo-settings', [SeoSettingController::class, 'edit'])->name('seo.settings');
    Route::put('/seo-settings', [SeoSettingController::class, 'update'])->name('seo.settings.update');

    // Blog Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // FAQs Management
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{id}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{id}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{id}', [FaqController::class, 'destroy'])->name('faqs.destroy');
    Route::post('/faqs/{id}/toggle-active', [FaqController::class, 'toggleActive'])->name('faqs.toggle-active');

    // Media Library
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Contact Inquiries / Leads
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
});

// Storage fallback handler for Apache / Wamp environments
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!File::exists($filePath)) {
        $filePath = public_path('storage/' . $path);
    }

    if (File::exists($filePath)) {
        $mimeType = File::mimeType($filePath);
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    abort(404);
})->where('path', '.*')->name('storage.fallback');
