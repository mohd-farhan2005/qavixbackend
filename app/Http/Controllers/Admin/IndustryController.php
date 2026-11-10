<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IndustryController extends Controller
{
    /**
     * List all industries
     */
    public function index(Request $request): View
    {
        $query = Industry::withCount('clients')->ordered();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $industries = $query->paginate(15);
        $totalIndustries = Industry::count();

        return view('admin.industries.index', compact('industries', 'totalIndustries'));
    }

    /**
     * Show form for creating a new industry
     */
    public function create(): View
    {
        return view('admin.industries.create');
    }

    /**
     * Store new industry
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:industries,slug',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        Industry::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.industries.index')
            ->with('success', "Industry '{$validated['name']}' added successfully! You can now select it in the Clients dropdown.");
    }

    /**
     * Show form for editing an existing industry
     */
    public function edit(int $id): View
    {
        $industry = Industry::findOrFail($id);

        return view('admin.industries.edit', compact('industry'));
    }

    /**
     * Update existing industry
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $industry = Industry::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('industries', 'slug')->ignore($industry->id)],
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $industry->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.industries.index')
            ->with('success', "Industry '{$industry->name}' updated successfully!");
    }

    /**
     * Delete industry
     */
    public function destroy(int $id): RedirectResponse
    {
        $industry = Industry::findOrFail($id);
        $industryName = $industry->name;
        $industry->delete();

        return redirect()->route('admin.industries.index')
            ->with('success', "Industry '{$industryName}' deleted successfully!");
    }
}

