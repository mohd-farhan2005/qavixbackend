<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * List all FAQs (including inactive) with sorting & filtering
     */
    public function index(Request $request): JsonResponse
    {
        $query = Faq::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                  ->orWhere('answer', 'LIKE', "%{$search}%");
            });
        }

        $faqs = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'total' => $faqs->count(),
            'data' => $faqs,
        ]);
    }

    /**
     * Create a new FAQ
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['category'] = !empty($validated['category']) ? trim($validated['category']) : 'General';
        $validated['sort_order'] = isset($validated['sort_order']) ? (int) $validated['sort_order'] : (Faq::max('sort_order') + 1);
        $validated['is_active'] = $request->boolean('is_active', true);

        $faq = Faq::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'FAQ created successfully',
            'data' => $faq,
        ], 201);
    }

    /**
     * Show single FAQ
     */
    public function show(int $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $faq,
        ]);
    }

    /**
     * Update an existing FAQ
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'sometimes|required|string|max:500',
            'answer' => 'sometimes|required|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (array_key_exists('category', $validated) && empty($validated['category'])) {
            $validated['category'] = 'General';
        }

        $faq->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'FAQ updated successfully',
            'data' => $faq,
        ]);
    }

    /**
     * Delete an FAQ
     */
    public function destroy(int $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully',
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();

        return response()->json([
            'success' => true,
            'message' => 'FAQ status updated to ' . ($faq->is_active ? 'Active' : 'Hidden'),
            'data' => $faq,
        ]);
    }
}

