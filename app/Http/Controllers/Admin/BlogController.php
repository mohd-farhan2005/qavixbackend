<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Blog::with(['category', 'author']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $blogs = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
            'featured_image' => 'nullable|string',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'tags_input' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            
            // SEO Meta Fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'robots_index' => 'nullable|boolean',
            'robots_follow' => 'nullable|boolean',
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

        // Auto slug
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

        // Handle uploaded image
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $uploadDir = storage_path('app/public/uploads');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $fileName);
            $baseUrl = rtrim(config('app.url'), '/');
            $validated['featured_image'] = $baseUrl . '/storage/uploads/' . $fileName;
        }

        // Process tags
        if (!empty($validated['tags_input'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags_input']));
        }

        // Process schema markup json
        if (!empty($validated['schema_markup_json'])) {
            $decoded = json_decode($validated['schema_markup_json'], true);
            if ($decoded) {
                $validated['schema_markup'] = $decoded;
            }
        }

        $validated['author_id'] = Auth::id();
        $validated['author_name'] = Auth::user()?->name ?? 'Admin';
        $validated['is_featured'] = $request->has('is_featured');
        $validated['robots_index'] = $request->has('robots_index');
        $validated['robots_follow'] = $request->has('robots_follow');

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $blog = Blog::create($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post "' . $blog->title . '" created successfully with SEO tags!');
    }

    public function edit(int $id): View
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
            'featured_image' => 'nullable|string',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'tags_input' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            
            // SEO Meta Fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'robots_index' => 'nullable|boolean',
            'robots_follow' => 'nullable|boolean',
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

        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $uploadDir = storage_path('app/public/uploads');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $fileName);
            $baseUrl = rtrim(config('app.url'), '/');
            $validated['featured_image'] = $baseUrl . '/storage/uploads/' . $fileName;
        }

        if (isset($validated['tags_input'])) {
            $validated['tags'] = array_map('trim', array_filter(explode(',', $validated['tags_input'])));
        }

        if (!empty($validated['schema_markup_json'])) {
            $decoded = json_decode($validated['schema_markup_json'], true);
            if ($decoded) {
                $validated['schema_markup'] = $decoded;
            }
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['robots_index'] = $request->has('robots_index');
        $validated['robots_follow'] = $request->has('robots_follow');

        if ($validated['status'] === 'published' && empty($blog->published_at)) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post "' . $blog->title . '" updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);
        $title = $blog->title;
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Post "' . $title . '" deleted.');
    }

    public function togglePublish(int $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->status = $blog->status === 'published' ? 'draft' : 'published';
        if ($blog->status === 'published' && empty($blog->published_at)) {
            $blog->published_at = now();
        }
        $blog->save();

        return back()->with('success', 'Post status updated to ' . ucfirst($blog->status));
    }
}

