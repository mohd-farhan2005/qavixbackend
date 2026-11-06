<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaunchpadLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaunchpadController extends Controller
{
    /**
     * Display listing of Launchpad founder applications
     */
    public function index(Request $request): View
    {
        $query = LaunchpadLead::latest();

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Industry
        if ($request->filled('industry') && $request->industry !== 'all') {
            $query->where('industry', $request->industry);
        }

        // Search by Name, Email, Startup, or Phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('startup_name', 'LIKE', "%{$search}%")
                  ->orWhere('whatsapp_number', 'LIKE', "%{$search}%");
            });
        }

        $leads = $query->paginate(15)->withQueryString();

        // Statistics
        $totalLeads = LaunchpadLead::count();
        $newLeads = LaunchpadLead::where('status', 'new')->count();
        $unreadLeads = LaunchpadLead::where('is_read', false)->count();
        $contactedLeads = LaunchpadLead::where('status', 'contacted')->count();
        $qualifiedLeads = LaunchpadLead::whereIn('status', ['qualified', 'converted'])->count();
        $industriesList = LaunchpadLead::distinct()->pluck('industry')->filter()->values();

        return view('admin.launchpad.index', compact(
            'leads',
            'totalLeads',
            'newLeads',
            'unreadLeads',
            'contactedLeads',
            'qualifiedLeads',
            'industriesList'
        ));
    }

    /**
     * Show single application details and mark as read
     */
    public function show(int $id): View
    {
        $lead = LaunchpadLead::findOrFail($id);

        if (!$lead->is_read) {
            $lead->update(['is_read' => true]);
        }

        return view('admin.launchpad.show', compact('lead'));
    }

    /**
     * Update status and notes for a lead
     */
    public function updateStatus(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $lead = LaunchpadLead::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,archived',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $lead,
            ]);
        }

        return redirect()->back()->with('success', "Lead #{$lead->id} status updated to " . ucfirst($lead->status));
    }

    /**
     * Toggle read/unread status
     */
    public function toggleRead(int $id): RedirectResponse
    {
        $lead = LaunchpadLead::findOrFail($id);
        $lead->is_read = !$lead->is_read;
        $lead->save();

        return redirect()->back()->with('success', 'Marked as ' . ($lead->is_read ? 'Read' : 'Unread'));
    }

    /**
     * Delete an application
     */
    public function destroy(int $id): RedirectResponse
    {
        $lead = LaunchpadLead::findOrFail($id);
        $name = $lead->full_name;
        $lead->delete();

        return redirect()->route('admin.launchpad.index')
            ->with('success', "Application from {$name} deleted successfully.");
    }
}

