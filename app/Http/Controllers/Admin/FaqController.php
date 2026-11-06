<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs with create form and quick statistics
     */
    public function index(Request $request): View
    {
        $query = Faq::query();

        // Optional filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Optional search by question or answer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                  ->orWhere('answer', 'LIKE', "%{$search}%");
            });
        }

        $faqs = $query->ordered()->get();

        // Statistics
        $totalFaqs = Faq::count();
        $activeFaqs = Faq::active()->count();
        $hiddenFaqs = Faq::where('is_active', false)->count();
        $categoriesList = Faq::distinct()->pluck('category')->filter()->values();
        $totalCategories = $categoriesList->count();

        return view('admin.faqs.index', compact(
            'faqs',
            'totalFaqs',
            'activeFaqs',
            'hiddenFaqs',
            'totalCategories',
            'categoriesList'
        ));
    }

    /**
     * Store a newly created FAQ in storage
     */
    public function store(Request $request): RedirectResponse
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

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ "' . $faq->question . '" added successfully!');
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit(int $id): View
    {
        $faq = Faq::findOrFail($id);
        $categoriesList = Faq::distinct()->pluck('category')->filter()->values();

        return view('admin.faqs.edit', compact('faq', 'categoriesList'));
    }

    /**
     * Update the specified FAQ in storage
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['category'] = !empty($validated['category']) ? trim($validated['category']) : 'General';
        $validated['sort_order'] = isset($validated['sort_order']) ? (int) $validated['sort_order'] : 0;
        $validated['is_active'] = $request->boolean('is_active');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully!');
    }

    /**
     * Remove the specified FAQ from storage
     */
    public function destroy(int $id): RedirectResponse
    {
        $faq = Faq::findOrFail($id);
        $question = $faq->question;
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Toggle the active/published status of an FAQ
     */
    public function toggleActive(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $faq->is_active,
                'message' => 'FAQ status updated to ' . ($faq->is_active ? 'Active' : 'Hidden'),
            ]);
        }

        return redirect()->back()->with('success', 'FAQ status changed to ' . ($faq->is_active ? 'Active' : 'Hidden'));
    }
}

