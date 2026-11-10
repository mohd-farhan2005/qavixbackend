<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Industry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display listing of clients
     */
    public function index(Request $request): View
    {
        $query = Client::with('industry')->ordered();

        if ($request->filled('industry_id')) {
            $query->where('industry_id', (int) $request->industry_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('tagline', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $clients = $query->paginate(15);
        $totalClients = Client::count();
        $activeClients = Client::where('is_active', true)->count();
        $industries = Industry::active()->ordered()->get();

        return view('admin.clients.index', compact('clients', 'totalClients', 'activeClients', 'industries'));
    }

    /**
     * Show form for creating a new client
     */
    public function create(): View
    {
        $industries = Industry::active()->ordered()->get();

        return view('admin.clients.create', compact('industries'));
    }

    /**
     * Store a newly created client
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:clients,slug',
            'tagline' => 'nullable|string|max:255',
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable|image|max:5120',
            'logo_url' => 'nullable|url|max:1000',
            'banner_image' => 'nullable|image|max:10240',
            'banner_image_url' => 'nullable|url|max:1000',
            'website_url' => 'nullable|url|max:500',
            'location' => 'nullable|string|max:255',
            'project_year' => 'nullable|string|max:50',
            'client_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'about' => 'nullable|string',
            'challenge' => 'nullable|string',
            'solution' => 'nullable|string',
            'services' => 'nullable',
            'stat_1_value' => 'nullable|string|max:50',
            'stat_1_label' => 'nullable|string|max:100',
            'stat_2_value' => 'nullable|string|max:50',
            'stat_2_label' => 'nullable|string|max:100',
            'stat_3_value' => 'nullable|string|max:50',
            'stat_3_label' => 'nullable|string|max:100',
            'testimonial_quote' => 'nullable|string',
            'testimonial_author' => 'nullable|string|max:255',
            'testimonial_position' => 'nullable|string|max:255',
            'testimonial_avatar' => 'nullable|image|max:5120',
            'testimonial_avatar_url' => 'nullable|url|max:1000',
            'gallery_urls' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Process Logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('clients/logos', 'public');
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->input('logo_url');
        }

        // Process Banner Image (Frontend Hero Banner)
        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('clients/banners', 'public');
        } elseif ($request->filled('banner_image_url')) {
            $bannerPath = $request->input('banner_image_url');
        }

        // Process Testimonial Avatar
        $testimonialAvatarPath = null;
        if ($request->hasFile('testimonial_avatar')) {
            $testimonialAvatarPath = $request->file('testimonial_avatar')->store('clients/testimonials', 'public');
        } elseif ($request->filled('testimonial_avatar_url')) {
            $testimonialAvatarPath = $request->input('testimonial_avatar_url');
        }

        // Process Services (array or comma/newline separated)
        $services = $this->parseCommaList($request->input('services'));

        // Process Gallery URLs
        $gallery = $this->parseCommaList($request->input('gallery_urls'));

        Client::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'tagline' => $validated['tagline'] ?? null,
            'industry_id' => $validated['industry_id'] ?? null,
            'logo' => $logoPath,
            'banner_image' => $bannerPath,
            'website_url' => $validated['website_url'] ?? null,
            'location' => $validated['location'] ?? null,
            'project_year' => $validated['project_year'] ?? null,
            'client_type' => $validated['client_type'] ?? null,
            'description' => $validated['description'] ?? null,
            'about' => $validated['about'] ?? null,
            'challenge' => $validated['challenge'] ?? null,
            'solution' => $validated['solution'] ?? null,
            'services' => $services,
            'stat_1_value' => $validated['stat_1_value'] ?? null,
            'stat_1_label' => $validated['stat_1_label'] ?? null,
            'stat_2_value' => $validated['stat_2_value'] ?? null,
            'stat_2_label' => $validated['stat_2_label'] ?? null,
            'stat_3_value' => $validated['stat_3_value'] ?? null,
            'stat_3_label' => $validated['stat_3_label'] ?? null,
            'testimonial_quote' => $validated['testimonial_quote'] ?? null,
            'testimonial_author' => $validated['testimonial_author'] ?? null,
            'testimonial_position' => $validated['testimonial_position'] ?? null,
            'testimonial_avatar' => $testimonialAvatarPath,
            'gallery' => $gallery,
            'video_url' => $validated['video_url'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'featured' => $request->boolean('featured', false),
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', "Client '{$validated['name']}' added successfully with complete frontend page fields!");
    }

    /**
     * Show form for editing an existing client
     */
    public function edit(int $id): View
    {
        $client = Client::findOrFail($id);
        $industries = Industry::active()->ordered()->get();

        return view('admin.clients.edit', compact('client', 'industries'));
    }

    /**
     * Update existing client
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:clients,slug,{$id}",
            'tagline' => 'nullable|string|max:255',
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable|image|max:5120',
            'logo_url' => 'nullable|url|max:1000',
            'banner_image' => 'nullable|image|max:10240',
            'banner_image_url' => 'nullable|url|max:1000',
            'website_url' => 'nullable|url|max:500',
            'location' => 'nullable|string|max:255',
            'project_year' => 'nullable|string|max:50',
            'client_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'about' => 'nullable|string',
            'challenge' => 'nullable|string',
            'solution' => 'nullable|string',
            'services' => 'nullable',
            'stat_1_value' => 'nullable|string|max:50',
            'stat_1_label' => 'nullable|string|max:100',
            'stat_2_value' => 'nullable|string|max:50',
            'stat_2_label' => 'nullable|string|max:100',
            'stat_3_value' => 'nullable|string|max:50',
            'stat_3_label' => 'nullable|string|max:100',
            'testimonial_quote' => 'nullable|string',
            'testimonial_author' => 'nullable|string|max:255',
            'testimonial_position' => 'nullable|string|max:255',
            'testimonial_avatar' => 'nullable|image|max:5120',
            'testimonial_avatar_url' => 'nullable|url|max:1000',
            'gallery_urls' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Process Logo
        $logoPath = $client->logo;
        if ($request->hasFile('logo')) {
            if ($client->logo && !str_starts_with($client->logo, 'http') && Storage::disk('public')->exists($client->logo)) {
                Storage::disk('public')->delete($client->logo);
            }
            $logoPath = $request->file('logo')->store('clients/logos', 'public');
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->input('logo_url');
        }

        // Process Banner Image
        $bannerPath = $client->banner_image;
        if ($request->hasFile('banner_image')) {
            if ($client->banner_image && !str_starts_with($client->banner_image, 'http') && Storage::disk('public')->exists($client->banner_image)) {
                Storage::disk('public')->delete($client->banner_image);
            }
            $bannerPath = $request->file('banner_image')->store('clients/banners', 'public');
        } elseif ($request->filled('banner_image_url')) {
            $bannerPath = $request->input('banner_image_url');
        }

        // Process Testimonial Avatar
        $testimonialAvatarPath = $client->testimonial_avatar;
        if ($request->hasFile('testimonial_avatar')) {
            if ($client->testimonial_avatar && !str_starts_with($client->testimonial_avatar, 'http') && Storage::disk('public')->exists($client->testimonial_avatar)) {
                Storage::disk('public')->delete($client->testimonial_avatar);
            }
            $testimonialAvatarPath = $request->file('testimonial_avatar')->store('clients/testimonials', 'public');
        } elseif ($request->filled('testimonial_avatar_url')) {
            $testimonialAvatarPath = $request->input('testimonial_avatar_url');
        }

        // Process Services
        $services = $request->has('services') ? $this->parseCommaList($request->input('services')) : $client->services;

        // Process Gallery URLs
        $gallery = $request->has('gallery_urls') ? $this->parseCommaList($request->input('gallery_urls')) : $client->gallery;

        $client->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? ($client->slug ?: Str::slug($validated['name'])),
            'tagline' => $validated['tagline'] ?? null,
            'industry_id' => $validated['industry_id'] ?? null,
            'logo' => $logoPath,
            'banner_image' => $bannerPath,
            'website_url' => $validated['website_url'] ?? null,
            'location' => $validated['location'] ?? null,
            'project_year' => $validated['project_year'] ?? null,
            'client_type' => $validated['client_type'] ?? null,
            'description' => $validated['description'] ?? null,
            'about' => $validated['about'] ?? null,
            'challenge' => $validated['challenge'] ?? null,
            'solution' => $validated['solution'] ?? null,
            'services' => $services,
            'stat_1_value' => $validated['stat_1_value'] ?? null,
            'stat_1_label' => $validated['stat_1_label'] ?? null,
            'stat_2_value' => $validated['stat_2_value'] ?? null,
            'stat_2_label' => $validated['stat_2_label'] ?? null,
            'stat_3_value' => $validated['stat_3_value'] ?? null,
            'stat_3_label' => $validated['stat_3_label'] ?? null,
            'testimonial_quote' => $validated['testimonial_quote'] ?? null,
            'testimonial_author' => $validated['testimonial_author'] ?? null,
            'testimonial_position' => $validated['testimonial_position'] ?? null,
            'testimonial_avatar' => $testimonialAvatarPath,
            'gallery' => $gallery,
            'video_url' => $validated['video_url'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'featured' => $request->boolean('featured', false),
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', "Client '{$client->name}' updated successfully!");
    }

    /**
     * Delete client
     */
    public function destroy(int $id): RedirectResponse
    {
        $client = Client::findOrFail($id);

        if ($client->logo && !str_starts_with($client->logo, 'http') && Storage::disk('public')->exists($client->logo)) {
            Storage::disk('public')->delete($client->logo);
        }
        if ($client->banner_image && !str_starts_with($client->banner_image, 'http') && Storage::disk('public')->exists($client->banner_image)) {
            Storage::disk('public')->delete($client->banner_image);
        }
        if ($client->testimonial_avatar && !str_starts_with($client->testimonial_avatar, 'http') && Storage::disk('public')->exists($client->testimonial_avatar)) {
            Storage::disk('public')->delete($client->testimonial_avatar);
        }

        $clientName = $client->name;
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', "Client '{$clientName}' deleted successfully!");
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $client = Client::findOrFail($id);
        $client->is_active = !$client->is_active;
        $client->save();

        $statusText = $client->is_active ? 'active' : 'hidden';

        return redirect()->route('admin.clients.index')
            ->with('success', "Client '{$client->name}' is now {$statusText}!");
    }

    /**
     * Helper to parse comma/newline separated string or array into clean string array
     */
    protected function parseCommaList($input): ?array
    {
        if (is_array($input)) {
            return array_values(array_filter(array_map('trim', $input)));
        }

        if (is_string($input) && trim($input) !== '') {
            $items = preg_split('/[\r\n,]+/', $input);
            $clean = array_values(array_filter(array_map('trim', $items)));
            return count($clean) > 0 ? $clean : null;
        }

        return null;
    }
}
