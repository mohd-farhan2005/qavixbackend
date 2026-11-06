<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Get active FAQs for React frontend with Google FAQPage Schema.org markup
     */
    public function index(Request $request): JsonResponse
    {
        $query = Faq::active()->ordered();

        // Optional category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Optional search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                  ->orWhere('answer', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('limit')) {
            $query->limit((int) $request->limit);
        }

        $faqs = $query->get();

        // Distinct categories with count of active FAQs
        $categories = Faq::active()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        // Grouped by category for convenient tabbed / grouped UI in React
        $grouped = $faqs->groupBy('category');

        // Google FAQPage Schema.org markup
        $schema = Faq::generateFaqSchema($faqs);

        return response()->json([
            'success' => true,
            'total' => $faqs->count(),
            'categories' => $categories,
            'data' => $faqs,
            'grouped' => $grouped,
            'schema' => $schema,
        ]);
    }
}

