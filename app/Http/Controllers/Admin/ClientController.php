<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Industry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable|image|max:5120',
            'logo_url' => 'nullable|url|max:1000',
            'website_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('clients', 'public');
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->input('logo_url');
        }

        Client::create([
            'name' => $validated['name'],
            'industry_id' => $validated['industry_id'] ?? null,
            'logo' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'featured' => $request->boolean('featured', false),
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', "Client '{$validated['name']}' added successfully!");
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
            'industry_id' => 'nullable|exists:industries,id',
            'logo' => 'nullable|image|max:5120',
            'logo_url' => 'nullable|url|max:1000',
            'website_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $logoPath = $client->logo;
        if ($request->hasFile('logo')) {
            if ($client->logo && !str_starts_with($client->logo, 'http') && Storage::disk('public')->exists($client->logo)) {
                Storage::disk('public')->delete($client->logo);
            }
            $logoPath = $request->file('logo')->store('clients', 'public');
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->input('logo_url');
        }

        $client->update([
            'name' => $validated['name'],
            'industry_id' => $validated['industry_id'] ?? null,
            'logo' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'description' => $validated['description'] ?? null,
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
}
