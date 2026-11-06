<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaunchpadLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaunchpadController extends Controller
{
    /**
     * List all Launchpad applications
     */
    public function index(Request $request): JsonResponse
    {
        $query = LaunchpadLead::latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('industry') && $request->industry !== 'all') {
            $query->where('industry', $request->industry);
        }

        if ($request->has('is_read')) {
            $query->where('is_read', filter_var($request->is_read, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('startup_name', 'LIKE', "%{$search}%")
                  ->orWhere('whatsapp_number', 'LIKE', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        $leads = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'counts' => [
                'total' => LaunchpadLead::count(),
                'unread' => LaunchpadLead::where('is_read', false)->count(),
                'new' => LaunchpadLead::where('status', 'new')->count(),
                'contacted' => LaunchpadLead::where('status', 'contacted')->count(),
            ],
            'data' => $leads->items(),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'total' => $leads->total(),
            ],
        ]);
    }

    /**
     * Show single application and mark as read
     */
    public function show(int $id): JsonResponse
    {
        $lead = LaunchpadLead::findOrFail($id);

        if (!$lead->is_read) {
            $lead->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    /**
     * Update status & notes
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $lead = LaunchpadLead::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,archived',
            'notes' => 'nullable|string',
            'is_read' => 'nullable|boolean',
        ]);

        $lead->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Launchpad application updated successfully',
            'data' => $lead,
        ]);
    }

    /**
     * Delete application
     */
    public function destroy(int $id): JsonResponse
    {
        $lead = LaunchpadLead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Launchpad application deleted successfully',
        ]);
    }
}

