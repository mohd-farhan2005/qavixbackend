<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    /**
     * Display listing of team members
     */
    public function index(Request $request): View
    {
        $query = TeamMember::query()->orderBy('order', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $members = $query->paginate(15);
        $totalMembers = TeamMember::count();
        $activeMembers = TeamMember::where('is_active', true)->count();

        return view('admin.team.index', compact('members', 'totalMembers', 'activeMembers'));
    }

    /**
     * Show form for creating a new team member
     */
    public function create(): View
    {
        return view('admin.team.create');
    }

    /**
     * Store a newly created team member
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|max:5120', // Up to 5MB
            'photo_url' => 'nullable|url|max:1000',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('team', 'public');
        } elseif ($request->filled('photo_url')) {
            $photoPath = $request->input('photo_url');
        }

        TeamMember::create([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'email' => $validated['email'] ?? null,
            'photo' => $photoPath,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member added successfully!');
    }

    /**
     * Show form for editing an existing team member
     */
    public function edit(int $id): View
    {
        $member = TeamMember::findOrFail($id);

        return view('admin.team.edit', compact('member'));
    }

    /**
     * Update existing team member
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $member = TeamMember::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|max:5120',
            'photo_url' => 'nullable|url|max:1000',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $photoPath = $member->photo;
        if ($request->hasFile('photo')) {
            if ($member->photo && !str_starts_with($member->photo, 'http') && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $photoPath = $request->file('photo')->store('team', 'public');
        } elseif ($request->filled('photo_url')) {
            $photoPath = $request->input('photo_url');
        }

        $member->update([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'email' => $validated['email'] ?? null,
            'photo' => $photoPath,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member updated successfully!');
    }

    /**
     * Delete team member
     */
    public function destroy(int $id): RedirectResponse
    {
        $member = TeamMember::findOrFail($id);

        if ($member->photo && !str_starts_with($member->photo, 'http') && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member deleted successfully!');
    }

    /**
     * Toggle active visibility status
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $member = TeamMember::findOrFail($id);
        $member->is_active = !$member->is_active;
        $member->save();

        $statusText = $member->is_active ? 'published' : 'hidden';

        return redirect()->route('admin.team.index')
            ->with('success', "Team member {$member->name} is now {$statusText}!");
    }
}
