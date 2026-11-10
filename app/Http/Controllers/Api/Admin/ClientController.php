<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * List all clients for admin
     */
    public function index(Request $request): JsonResponse
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

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    /**
     * Store new client
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:clients,slug',
            'tagline' => 'nullable|string|max:255',
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable',
            'banner_image' => 'nullable',
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
            'testimonial_avatar' => 'nullable',
            'gallery' => 'nullable',
            'video_url' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clients/logos', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('clients/banners', 'public');
        }

        if ($request->hasFile('testimonial_avatar')) {
            $validated['testimonial_avatar'] = $request->file('testimonial_avatar')->store('clients/testimonials', 'public');
        }

        if (!empty($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (isset($validated['services']) && is_string($validated['services'])) {
            $validated['services'] = array_values(array_filter(array_map('trim', explode(',', $validated['services']))));
        }

        if (isset($validated['gallery']) && is_string($validated['gallery'])) {
            $validated['gallery'] = array_values(array_filter(array_map('trim', explode(',', $validated['gallery']))));
        }

        $client = Client::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Client created successfully with full frontend page fields',
            'data' => $client->load('industry'),
        ], 201);
    }

    /**
     * Show single client
     */
    public function show(int $id): JsonResponse
    {
        $client = Client::with('industry')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $client,
        ]);
    }

    /**
     * Update client
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => "nullable|string|max:255|unique:clients,slug,{$id}",
            'tagline' => 'nullable|string|max:255',
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable',
            'banner_image' => 'nullable',
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
            'testimonial_avatar' => 'nullable',
            'gallery' => 'nullable',
            'video_url' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($client->logo && !str_starts_with($client->logo, 'http') && Storage::disk('public')->exists($client->logo)) {
                Storage::disk('public')->delete($client->logo);
            }
            $validated['logo'] = $request->file('logo')->store('clients/logos', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($client->banner_image && !str_starts_with($client->banner_image, 'http') && Storage::disk('public')->exists($client->banner_image)) {
                Storage::disk('public')->delete($client->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('clients/banners', 'public');
        }

        if ($request->hasFile('testimonial_avatar')) {
            if ($client->testimonial_avatar && !str_starts_with($client->testimonial_avatar, 'http') && Storage::disk('public')->exists($client->testimonial_avatar)) {
                Storage::disk('public')->delete($client->testimonial_avatar);
            }
            $validated['testimonial_avatar'] = $request->file('testimonial_avatar')->store('clients/testimonials', 'public');
        }

        if (isset($validated['services']) && is_string($validated['services'])) {
            $validated['services'] = array_values(array_filter(array_map('trim', explode(',', $validated['services']))));
        }

        if (isset($validated['gallery']) && is_string($validated['gallery'])) {
            $validated['gallery'] = array_values(array_filter(array_map('trim', explode(',', $validated['gallery']))));
        }

        if ($request->has('featured')) {
            $validated['featured'] = $request->boolean('featured');
        }

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $client->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Client updated successfully',
            'data' => $client->fresh()->load('industry'),
        ]);
    }

    /**
     * Delete client
     */
    public function destroy(int $id): JsonResponse
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

        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully',
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->is_active = !$client->is_active;
        $client->save();

        return response()->json([
            'success' => true,
            'message' => 'Client status updated to ' . ($client->is_active ? 'active' : 'inactive'),
            'is_active' => $client->is_active,
        ]);
    }
}
