<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable',
            'website_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('clients', 'public');
        } elseif ($request->filled('logo') && is_string($request->logo)) {
            $logoPath = $request->logo;
        }

        $client = Client::create([
            'name' => $validated['name'],
            'industry_id' => $validated['industry_id'] ?? null,
            'logo' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'featured' => $request->boolean('featured', false),
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client created successfully',
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
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable',
            'website_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($client->logo && !str_starts_with($client->logo, 'http') && Storage::disk('public')->exists($client->logo)) {
                Storage::disk('public')->delete($client->logo);
            }
            $validated['logo'] = $request->file('logo')->store('clients', 'public');
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
