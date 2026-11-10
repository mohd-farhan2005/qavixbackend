<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IndustryController extends Controller
{
    /**
     * List all industries (active & inactive)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Industry::query()->withCount('clients')->ordered();

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
     * Store a newly created industry
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:industries,slug',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $industry = Industry::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Industry created successfully',
            'data' => $industry,
        ], 201);
    }

    /**
     * Show single industry
     */
    public function show(int $id): JsonResponse
    {
        $industry = Industry::with('clients')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $industry,
        ]);
    }

    /**
     * Update industry
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $industry = Industry::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('industries', 'slug')->ignore($industry->id)],
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $industry->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Industry updated successfully',
            'data' => $industry->fresh(),
        ]);
    }

    /**
     * Delete industry
     */
    public function destroy(int $id): JsonResponse
    {
        $industry = Industry::findOrFail($id);
        $industry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Industry deleted successfully',
        ]);
    }
}

